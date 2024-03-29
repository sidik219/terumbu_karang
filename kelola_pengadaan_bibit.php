<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';
$tanggal_sekarang = date('Y-m-d H:i:s', time());
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}

$id_donasi = $_GET['id_donasi'];
$defaultpic = "images/image_default.jpg";
$status_donasi = "Menunggu Konfirmasi oleh Pengelola Lokasi";

$sql = 'SELECT * FROM t_donasi
    LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
    LEFT JOIN t_user ON t_donasi.id_user = t_user.id_user
    LEFT JOIN t_rekening_bank ON t_donasi.id_rekening_bersama = t_rekening_bank.id_rekening_bank
    LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
    WHERE id_donasi = :id_donasi';

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_donasi' => $id_donasi]);
$rowitem = $stmt->fetch();

$sqlstatus = 'SELECT * FROM t_status_pengadaan_bibit';
$stmt = $pdo->prepare($sqlstatus);
$stmt->execute();
$rowstatus = $stmt->fetchAll();




if (isset($_POST['submit'])) {
    // $randomstring = substr(md5(rand()), 0, 7);
    $tgl_pembelian_bibit = $_POST['tgl_pembelian_bibit'];

    // Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
        $bukti_pengadaan_bibit = $rowitem->bukti_pengadaan_bibit;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($rowitem->bukti_pengadaan_bibit == $defaultpic) || (!$rowitem->bukti_pengadaan_bibit)) {
            $target_dir  = "images/bukti_pengadaan_bibit/";
            $bukti_pengadaan_bibit = $target_dir . 'BKTBIBIT_id' . $rowitem->id_donasi . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $bukti_pengadaan_bibit);
            $pic = "&new=";
        } else if (isset($rowitem->bukti_pengadaan_bibit)) {
            $bukti_pengadaan_bibit = $rowitem->bukti_pengadaan_bibit;
            unlink($rowitem->bukti_pengadaan_bibit);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowitem->bukti_pengadaan_bibit);
            $pic = "&replace=";
        }
    }

    // ---image upload end

    $tanggal_update_status = date('Y-m-d H:i:s', time());
    $id_status_pengadaan_bibit = $_POST['radio_status'];
    $sqldonasi = "UPDATE t_donasi
                        SET `id_status_pengadaan_bibit` = :id_status_pengadaan_bibit, `update_terakhir` = :update_terakhir, `bukti_pengadaan_bibit` = :bukti_pengadaan_bibit, `tgl_pembelian_bibit`= :tgl_pembelian_bibit
                        WHERE id_donasi = :id_donasi";

    $stmt = $pdo->prepare($sqldonasi);

    $stmt->execute(['id_donasi' => $id_donasi, 'id_status_pengadaan_bibit' => 3, 'update_terakhir' => $tanggal_update_status, 'bukti_pengadaan_bibit' => $bukti_pengadaan_bibit, 'tgl_pembelian_bibit' => $tgl_pembelian_bibit]);


    // Kirim email ke pengelola wilayah
    include 'includes/email_handler.php'; //PHPMailer
        $sqlpengelolawilayah = "SELECT * FROM t_pengelola_wilayah
                                WHERE id_wilayah = {$rowitem->id_wilayah} ";

    $stmt = $pdo->prepare($sqlpengelolawilayah);
    $stmt->execute();
    $rowpengelola = $stmt->fetchAll();


    foreach($rowpengelola as $pengelola){
          $sqlviewdatauser = 'SELECT * FROM t_user 
                              WHERE id_user = :id_user';
          $stmt = $pdo->prepare($sqlviewdatauser);
          $stmt->execute(['id_user' => $pengelola->id_user]);
          $datauser = $stmt->fetch();

          $email = $datauser->email;
          $nama_user = $datauser->nama_user;

          $subjek = 'Verifikasi Bukti Pembelian Bibit (ID Donasi: '.$id_donasi.') - Terumbu Karang GoKarang';
          $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
          <br>Yth. '.$nama_user.'
          <br>Pengelola lokasi '.$rowitem->nama_lokasi.' telah mengupload bukti pembelian bibit untuk donasi dengan ID Donasi '.$id_donasi.'
          <br>
          <br>Harap verifikasi bukti pembelian bibit tersebut di link berikut:
          <br><a href="https://tkjb.or.id/kelola_pengadaan_bibit.php?id_donasi='.$id_donasi.'">Verifikasi Bukti Pembelian Bibit</a>
      ';
      
      smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
      } 

    $affectedrows = $stmt->rowCount();
    header("Location: kelola_donasi.php?status=updatesuccess");
    // if ($affectedrows == '0') {
    // header("Location: kelola_donasi.php?status=nochange");
    // } else {
    //     //echo "HAHAHAAHA GREAT SUCCESSS !";
    //     header("Location: kelola_donasi.php?status=updatesuccess");
    //     }
}

if (isset($_POST['submit_terima_bibit'])) {
    $tanggal_update_status = date('Y-m-d H:i:s', time());
    $sqldonasi = "UPDATE t_donasi
                        SET id_status_donasi = :id_status_donasi, update_terakhir = :update_terakhir, id_status_pengadaan_bibit = :id_status_pengadaan_bibit
                        WHERE id_donasi = :id_donasi";

    $stmt = $pdo->prepare($sqldonasi);
    $stmt->execute(['id_donasi' => $id_donasi, 'id_status_donasi' => 3, 'update_terakhir' => $tanggal_update_status, 'id_status_pengadaan_bibit' => 4]);

    $affectedrows = $stmt->rowCount();

    // Kirim email terima ke pengelola Lokasi
    include 'includes/email_handler.php'; //PHPMailer
        $sqlpengelolalokasi = "SELECT * FROM t_pengelola_lokasi
                                WHERE id_lokasi = {$rowitem->id_lokasi} ";

    $stmt = $pdo->prepare($sqlpengelolalokasi);
    $stmt->execute();
    $rowpengelola = $stmt->fetchAll();


    foreach($rowpengelola as $pengelola){
          $sqlviewdatauser = 'SELECT * FROM t_user 
                              WHERE id_user = :id_user';
          $stmt = $pdo->prepare($sqlviewdatauser);
          $stmt->execute(['id_user' => $pengelola->id_user]);
          $datauser = $stmt->fetch();

          $email = $datauser->email;
          $nama_user = $datauser->nama_user;

          $subjek = 'Bukti Pembelian Bibit Telah Diterima (ID Donasi: '.$id_donasi.') - Terumbu Karang GoKarang';
          $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
          <br>Yth. '.$nama_user.'
          <br>Pengelola Wilayah telah memverifikasi bukti pembelian bibit untuk donasi dengan ID Donasi '.$id_donasi.'
          <br>
          <br>Harap masukkan donasi tersebut ke dalam batch penanaman jika jumlah donasi yang terkumpul sudah mencapai jumlah penanaman bibit minimal yang ditentukan.
          <br><a href="https://tkjb.or.id/kelola_pengadaan_bibit.php?id_donasi='.$id_donasi.'">Kelola Batch Penanaman</a>
      ';
      
      smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
      } 


    header("Refresh: 0");

    if ($affectedrows == '0') {
        header("Location: kelola_donasi.php?status=nochange");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_donasi.php?status=updatesuccess");
    }
}

if (isset($_POST['submit_tolak_bibit'])) {
    $sqldonasi = "UPDATE t_donasi
                        SET id_status_pengadaan_bibit = :id_status_pengadaan_bibit, update_terakhir = :update_terakhir
                        WHERE id_donasi = :id_donasi";

    $stmt = $pdo->prepare($sqldonasi);
    $stmt->execute(['id_donasi' => $id_donasi, 'id_status_pengadaan_bibit' => 5, 'update_terakhir' => $tanggal_update_status]);

    $affectedrows = $stmt->rowCount();

    // Kirim email tolak ke pengelola Lokasi
    include 'includes/email_handler.php'; //PHPMailer
        $sqlpengelolalokasi = "SELECT * FROM t_pengelola_lokasi
                                WHERE id_lokasi = {$rowitem->id_lokasi} ";

    $stmt = $pdo->prepare($sqlpengelolalokasi);
    $stmt->execute();
    $rowpengelola = $stmt->fetchAll();


    foreach($rowpengelola as $pengelola){
          $sqlviewdatauser = 'SELECT * FROM t_user 
                              WHERE id_user = :id_user';
          $stmt = $pdo->prepare($sqlviewdatauser);
          $stmt->execute(['id_user' => $pengelola->id_user]);
          $datauser = $stmt->fetch();

          $email = $datauser->email;
          $nama_user = $datauser->nama_user;

          $subjek = 'Bukti Pembelian Bibit Ditolak (ID Donasi: '.$id_donasi.') - Terumbu Karang GoKarang';
          $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
          <br>Yth. '.$nama_user.'
          <br>Pengelola Wilayah telah menolak bukti pembelian bibit untuk donasi dengan ID Donasi '.$id_donasi.'
          <br>
          <br>Harap upload kembali bukti pembelian bibit yang sesuai.
          <br><a href="https://tkjb.or.id/kelola_pengadaan_bibit.php?id_donasi='.$id_donasi.'">Upload Ulang Bukti Pembelian bibit</a>
      ';
      
      smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
      } 


    if ($affectedrows == '0') {
        header("Location: kelola_donasi.php?status=nochange");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_donasi.php?status=updatesuccess");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Pengadaan Bibit - GoKarang</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- Favicon -->
    <?= $favicon ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- NAVBAR -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Navbar Toogle -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Akun Saya</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">Edit Profil</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>
        <!-- END OF NAVBAR -->

        <!-- TOP SIDEBAR -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- BRAND LOGO (TOP)-->
            <a href="dashboard_admin.php" class="brand-link">
                <?= $logo_website ?>
            </a>
            <!-- END OF TOP SIDEBAR -->

            <!-- SIDEBAR -->
            <div class="sidebar">
                <!-- SIDEBAR MENU -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <?php print_sidebar(basename(__FILE__), $_SESSION['level_user']) ?>
                        <!-- Print sidebar -->
                    </ul>
                </nav>
                <!-- END OF SIDEBAR MENU -->
            </div>
            <!-- SIDEBAR -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <a href="kelola_donasi.php"><button class="btn btn-outline-primary">
                            < Kembali</button></a><br><br>
                    <h4><span class="align-middle font-weight-bold">Kelola Pengadaan Bibit</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->

            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">

                        <div class="row">
                            <div class="col-12 mb-2 border rounded bg-white p-3">
                                <h5 class="font-weight-bold">Status Pengadaan Bibit</h5>

                                <?php
                                foreach ($rowstatus as $status) {
                                ?>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" <?= ($_SESSION['level_user'] == 3) ? 'disabled' : ''; ?> name="radio_status" id="radio_status<?= $status->id_status_pengadaan_bibit ?>" value="<?= $status->id_status_pengadaan_bibit ?>" <?php if ($rowitem->id_status_pengadaan_bibit == $status->id_status_pengadaan_bibit) echo " checked"; ?>>
                                        <label class="form-check-label <?php if ($rowitem->id_status_pengadaan_bibit == $status->id_status_pengadaan_bibit) echo " font-weight-bold"; ?>" for="radio_status<?= $status->id_status_pengadaan_bibit ?>">
                                            <?= $status->nama_status_pengadaan_bibit ?>
                                        </label>
                                    </div>

                                <?php } ?>

                                <button type="submit" <?= ($_SESSION['level_user'] == 3) ? 'disabled class="d-none" ' : ''; ?> name="submit" value="Simpan" class="btn btn-primary btn-blue mt-2">Update Status</button></p>

                            </div>


                            <div class="col-lg-9 border rounded bg-white mb-2">
                                <div class="" style="width:100%;">
                                    <div class="">
                                        <h5 class="card-header mb-2 pl-0">Rincian Pembayaran</h5>
                                        <span class="">Lokasi : </span> <span class="text-info font-weight-bolder"><?= $rowitem->nama_lokasi ?></span>
                                        <div class="d-block my-3">
                                            <div class="custom-control custom-radio">
                                                <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                                                <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
                                                <br><label class="font-weight-bold"> Rekening Pembayaran : </label>
                                                <br><?= $rowitem->nama_bank ?> A.N. <?= $rowitem->nama_pemilik_rekening ?> - <?= $rowitem->nomor_rekening ?>
                                            </div>
                                            <hr class="mb-2" />

                                            <div class="row">
                                                <div class="col">
                                                    <span class="font-weight-bold"><i class="fas fa-user-tie"></i> Nama Donatur
                                                </div>
                                                <div class="col-lg-8 mb-2">
                                                    <span class=""><?= $rowitem->nama_donatur ?></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <span class="font-weight-bold"><i class="text-primary fas fa-phone"></i> Kontak Donatur </span>
                                                </div>
                                                <div class="col-lg-8  mb-2">
                                                    <span class=""><?= $rowitem->no_hp ?></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <span class="font-weight-bold"><i class="text-info fas fa-hashtag"></i> Nomor Rekening Donatur </span>
                                                </div>
                                                <div class="col-lg-8  mb-2">
                                                    <span class=""><?= $rowitem->nomor_rekening_donatur ?></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <span class="font-weight-bold"><i class="text-warning fas fa-university"></i> Bank Donatur </span>
                                                </div>
                                                <div class="col-lg-8  mb-2">
                                                    <span class=""><?= $rowitem->bank_donatur ?></span>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <span class="font-weight-bold"><i class="text-success fas fa-money-bill-wave"></i> Nominal </span>
                                                </div>
                                                <div class="col-lg-8  mb-2">
                                                    <span class="font-weight-bold">Rp. <?= number_format($rowitem->nominal, 0) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <!-- BUKTI DONASI BUKTI DONASI BUKTI DONASI -->
                            <div class="col-lg-3  border rounded bg-white p-3 mb-2  text-center">
                                <div class="form-group">
                                    <label for="file_bukti_donasi">Bukti Donasi</label>
                                    <hr class="m-0">
                                    <div class='form-group' id='buktidonasi'>
                                    </div>
                                    <div class="form-group">
                                        <a href="<?= $rowitem->bukti_donasi ?>" data-toggle="lightbox"><img class="img-fluid" id="oldpic" src="<?= $rowitem->bukti_donasi ?>" width="50%" <?php if ($rowitem->bukti_donasi == NULL) echo " style='display:none;'"; ?>></a>
                                        <br>
                                        <small class="text-muted">
                                            <?php if ($rowitem->bukti_donasi == NULL) {
                                                echo "Bukti transfer belum diupload";
                                            } else {
                                                echo "Klik gambar untuk memperbesar";
                                            }

                                            ?>
                                        </small>
                                    </div>

                                    <?php
                                    if ($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4) {
                                        if (($rowitem->id_status_donasi == 2) || (($rowitem->id_status_donasi) == 6)) { ?>
                                            <form name="submit_terima" method="POST">
                                                <button type="submit" name="submit_terima" value="terima" class="btn btn-success rounded-pill mt-2"><i class="fas fa-check-circle"></i> Terima</button></p>
                                            </form>
                                            <form name="submit_tolak" method="POST">
                                                <button type="submit" name="submit_tolak" value="tolak" class="btn btn-danger rounded-pill mt-2"><i class="fas fa-times-circle"></i> Tolak</button></p>
                                            </form>
                                    <?php }
                                    } ?>
                                </div>
                                <p align="center">
                                    <!-- <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p> -->
                    </form>
                </div>
                <!-- END BUKTI DONASI BUKTI DONASI BUKTI DONASI -->

                <div class="col-lg-9 border rounded bg-white p-0">
                    <h5 class="card-header mb-1 font-weight-bold"><i class="text-danger fas fa-disease"></i> Terumbu Karang Pilihan</h5><br />
                    <?php
                    $sqlviewisi = 'SELECT jumlah_terumbu, nama_terumbu_karang, foto_terumbu_karang FROM t_detail_donasi LEFT JOIN t_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi LEFT JOIN t_terumbu_karang ON t_detail_donasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang WHERE t_detail_donasi.id_donasi = :id_donasi';
                    $stmt = $pdo->prepare($sqlviewisi);
                    $stmt->execute(['id_donasi' => $rowitem->id_donasi]);
                    $rowisi = $stmt->fetchAll();
                    foreach ($rowisi as $isi) {
                    ?>
                        <div class="row  mb-3 pl-3">
                            <div class="col">
                                <img class="rounded" height="60px" src="<?= $isi->foto_terumbu_karang ?>?<?php if ($status = 'nochange') {
                                                                                                                echo time();
                                                                                                            } ?>">
                            </div>
                            <div class="col">
                                <span><?= $isi->nama_terumbu_karang ?>
                            </div>
                            <div class="col">
                                x<?= $isi->jumlah_terumbu ?></span><br />
                            </div>

                            <hr class="mb-2" />
                        </div>

                    <?php   }
                    ?>
                </div>

                <div class="col-lg-3  border rounded bg-white p-3 text-center">
                    <div class="form-group">
                        <label for="file_bukti_donasi">Bukti Pembelian Bibit</label>
                        <?php if (($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4) && $rowitem->id_status_pengadaan_bibit != 4) {      ?>
                            <div class='form-group' id='buktidonasi'>
                                <label class="btn btn btn-primary btn-blue" for='image_uploads'>
                                    <i class="fas fa-camera"></i> Upload File</label>
                                <div>
                                    <input type='file' class='form-control d-none' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png, .pdf' onchange="readURL(this);">
                                    <img id="preview_pembelian" class="<?= $rowitem->bukti_pengadaan_bibit == null ? ' style="display:none;" ' : '' ?>" width="100px" alt="Preview Gambar" />
                                </div>
                            </div>
                            <script>
                                const actualBtn = document.getElementById('image_uploads');

                                const fileChosen = document.getElementById('file-input-label');

                                actualBtn.addEventListener('change', function() {
                                    fileChosen.innerHTML = '<b>File dipilih :</b> ' + this.files[0].name
                                })
                                window.onload = function() {
                                    document.getElementById('preview_pembelian').style.display = 'none';
                                };

                                function readURL(input) {
                                    if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                        alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                        input.value = "";
                                    };
                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();
                                        document.getElementById('oldpicpembelian').style.display = 'none';
                                        reader.onload = function(e) {
                                            $('#preview_pembelian')
                                                .attr('src', e.target.result)
                                                .addClass('text-center')
                                                .width(200);
                                            document.getElementById('preview_pembelian').style.display = 'block';
                                        };

                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                        <?php } ?>
                        <div class="form-group">
                            <img id="preview_pembelian" class="" <?= $rowitem->bukti_pengadaan_bibit != null ? ' style="display:none;" ' : '' ?> width="100px" alt="Preview Gambar2" />
                            <a href="<?= $rowitem->bukti_pengadaan_bibit ?>" data-toggle="lightbox"><img class="img-fluid" id="oldpicpembelian" src="<?= $rowitem->bukti_pengadaan_bibit ?>" width="50%" <?php if ($rowitem->bukti_pengadaan_bibit == NULL) echo " style='display:none;'"; ?>></a>
                            <br>
                            <small class="text-muted">
                                <?php if ($rowitem->bukti_pengadaan_bibit == NULL) {
                                    echo "Gambar bukti pembelian bibit belum diupload<br>Format .jpg .jpeg .png";
                                } else {
                                    echo "Klik gambar untuk memperbesar";
                                }

                                ?>
                            </small>
                            <p class="mb-1 font-weight-bold"> Tanggal Pembelian</p>
                            <?php if ($rowitem->tgl_pembelian_bibit == null) : ?>
                                <input type="date" name="tgl_pembelian_bibit" id="tgl_pembelian_bibit" <?= $_SESSION['level_user'] == 2 ? ' disabled ' : '';?> required></input>
                            <?php else : ?>
                                <p><?= $rowitem->tgl_pembelian_bibit; ?></p>
                            <?php endif ?>

                        </div>

                    </div>

                    <?php
                    if (($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4) && $rowitem->bukti_pengadaan_bibit != NULL) {
                        if (($rowitem->id_status_pengadaan_bibit == 3) || (($rowitem->id_status_pengadaan_bibit) == 5)) { ?>
                            <form name="submit_terima" method="POST">
                                <button type="submit" name="submit_terima_bibit" value="terima" class="btn btn-success rounded-pill mt-2"><i class="fas fa-check-circle"></i> Terima</button></p>
                            </form>

                            <form name="submit_tolak" method="POST">
                                <button type="submit" name="submit_tolak_bibit" value="tolak" class="btn btn-danger rounded-pill mt-2"><i class="fas fa-times-circle"></i> Tolak</button></p>
                            </form>
                    <?php }
                    } ?>

                    <br>
                    <?php
                    if (($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4) && $rowitem->id_status_pengadaan_bibit != 4) {      ?>
                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                        </p>
                        </form>
                    <?php } ?>
                </div>

                <div class="col-9 border rounded bg-white p-0 mb-2">
                    <h5 class="card-header mb-1 font-weight-bold"><i class="text-info fas fa-comment-dots"></i> Pesan/Ekspresi</h5>
                    <span class="font-weight-bold mb-3 pl-3 pt-4 pb-4"><?= $rowitem->pesan ?></span>
                </div>
                <div class="col-3 border rounded bg-white p-0 mb-2">

                </div>




            </section>

            <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->
    </div>
    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <br><br>
    <footer class="main-footer">
        <?= $footer ?>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <script src="js/ekko-lightbox.min.js"></script>
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("tgl_pembelian_bibit")[0].setAttribute('max', today);
    </script>



</body>

</html>