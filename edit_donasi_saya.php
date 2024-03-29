<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);

include 'hak_akses.php';


$id_donasi = $_GET['id_donasi'];
$defaultpic = "images/image_default.jpg";
$id_status_donasi = 2;

$sql = 'SELECT * FROM t_donasi, t_lokasi
    WHERE id_donasi = :id_donasi
    AND t_donasi.id_lokasi = t_lokasi.id_lokasi';

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_donasi' => $id_donasi]);
$rowitem = $stmt->fetch();

if ($rowitem->id_user != $_SESSION['id_user']) {
    header("Location: donasi_saya.php");
}

$id_rekening_bersama = $rowitem->id_rekening_bersama;

$sqlviewrekeningbersama = 'SELECT * FROM t_rekening_bank WHERE id_rekening_bank = :id_rekening_bersama';
$stmt = $pdo->prepare($sqlviewrekeningbersama);
$stmt->execute(['id_rekening_bersama' => $id_rekening_bersama]);
$rowrekening = $stmt->fetch();

if (isset($_POST['submit'])) {
    // $randomstring = substr(md5(rand()), 0, 7);

    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
        $bukti_donasi = $rowitem->bukti_donasi;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($rowitem->bukti_donasi == $defaultpic) || (!$rowitem->bukti_donasi)) {
            $target_dir  = "images/bukti_reservasi/";
            $bukti_donasi = $target_dir . 'BKTDNS_id' . $id_donasi . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $bukti_donasi);
            $pic = "&new=";
        } else if (isset($rowitem->bukti_donasi)) {
            $bukti_donasi = $rowitem->bukti_donasi;
            unlink($rowitem->bukti_donasi);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowitem->bukti_donasi);
            $pic = "&replace=";
        }
    }

    //---image upload end

    $tanggal_upload_bukti = date('Y-m-d H:i:s', time());
    $sqldonasi = "UPDATE t_donasi
                        SET bukti_donasi = :bukti_donasi, id_status_donasi = :id_status_donasi, update_terakhir = :update_terakhir
                        WHERE id_donasi = :id_donasi";

    $stmt = $pdo->prepare($sqldonasi);
    $stmt->execute(['id_donasi' => $id_donasi, 'bukti_donasi' => $bukti_donasi, 'id_status_donasi' => $id_status_donasi, 'update_terakhir' => $tanggal_upload_bukti]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: donasi_saya.php?status=nochange");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";

        //Kirim email untuk Pengelola Wilayah
        include 'includes/email_handler.php'; //PHPMailer
        $sqlviewpengelolawilayah = 'SELECT * FROM t_lokasi 
                                            LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah
                                            LEFT JOIN t_pengelola_wilayah ON t_pengelola_wilayah.id_wilayah = t_lokasi.id_wilayah
                                            WHERE id_lokasi = :id_lokasi';
        $stmt = $pdo->prepare($sqlviewpengelolawilayah);
        $stmt->execute(['id_lokasi' => $rowitem->id_lokasi]);
        $rowpengelola = $stmt->fetchAll();

        foreach ($rowpengelola as $pengelola) {
            $sqlviewdatauser = 'SELECT * FROM t_user 
                                        WHERE id_user = :id_user';
            $stmt = $pdo->prepare($sqlviewdatauser);
            $stmt->execute(['id_user' => $pengelola->id_user]);
            $datauser = $stmt->fetch();

            $email = $datauser->email;
            $username = $datauser->username;
            $nama_user = $datauser->nama_user;

            $subjek = 'Bukti Donasi Perlu Verifikasi (ID Donasi : ' . $id_donasi . ' ) - GoKarang';
            $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                    <br>Yth. ' . $nama_user . '
                    <br>Harap verifikasi bukti donasi baru pada lokasi ' . $pengelola->nama_lokasi . '
                    <br>Berikut rincian donasi baru tersebut:
                    <br>Bank Donatur: ' . $rowitem->bank_donatur . '
                    <br>Nomor Rekening Donatur: ' . $rowitem->nomor_rekening_donatur . '
                    <br>Nama Rekening Donatur: ' . $rowitem->nama_donatur . '
                    <br>          
                    <br>Bank Tujuan Pembayaran: ' . $rowrekening->nama_bank . '
                    <br>Nomor Rekening Tujuan: ' . $rowrekening->nomor_rekening . '
                    <br>Nama Rekening Tujuan: ' . $rowrekening->nama_pemilik_rekening . '
                    <br>Nominal pembayaran: Rp. ' . number_format($rowitem->nominal, 0) . '
                    <br>
                    <br>Harap segera verifikasi bukti donasi di link berikut:
                    <br><a href="https://tkjb.or.id/edit_donasi.php?id_donasi=' . $id_donasi . '">Verifikasi Bukti Pembayaran</a>
                ';

            smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
        }
        header("Location: donasi_saya.php?status=updatesuccess");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Donasi - GoKarang</title>
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
                    <a class="btn btn-outline-primary" href="donasi_saya.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Kirim Bukti Donasi</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <?php if ($_SESSION['level_user'] == '1') { ?>
                <section class="content">
                    <div class="container-fluid">
                        <form action="" enctype="multipart/form-data" method="POST">

                            <div class="row">
                                <div class="col-lg-9 border rounded bg-white mb-2">
                                    <div class="" style="width:100%;">
                                        <div class="">
                                            <h4 class="card-header mb-2 pl-0">Rekening Pengelola Lokasi</h4>
                                            <span class="">Pilihan untuk lokasi</span> <span class="text-info font-weight-bolder"><?= $rowitem->nama_lokasi ?> : </span>
                                            <div class="d-block my-3">
                                                <div class="custom-control custom-radio">
                                                    <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                                                    <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
                                                    <p class="text-muted">Harap upload bukti transfer agar donasi segera diproses pengelola lokasi.</p>
                                                </div>
                                                <hr class="mb-2" />

                                                <div class="row">
                                                    <div class="col">
                                                        <span class="font-weight-bold"><i class="fas fa-user-tie"></i> Nama Rekening Pengelola
                                                    </div>
                                                    <div class="col-lg-8 mb-2">
                                                        <span class=""><?= $rowrekening->nama_pemilik_rekening ?></span>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold"><i class="text-primary fas fa-phone"></i> Kontak Pengelola </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowitem->kontak_lokasi ?></span>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold"><i class="text-warning fas fa-university"></i> Bank Pengelola </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowrekening->nama_bank ?></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <span class="font-weight-bold"><i class="text-info fas fa-hashtag"></i> Nomor Rekening Pengelola </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowrekening->nomor_rekening ?></span>
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

                                <div class="col-lg-3  border rounded bg-white p-3 mb-2 text-center">
                                    <div class="form-group">
                                        <label for="file_bukti_donasi">Bukti Donasi</label>
                                        <div class='form-group' id='buktidonasi'>
                                            <label class="btn btn btn-primary btn-blue" for='image_uploads'>
                                                <i class="fas fa-camera"></i> Upload File</label>
                                            <div>
                                                <input type='file' class='form-control d-none' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png, .pdf' onchange="readURL(this)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <img id="preview" src="#" width="100px" alt="Preview Gambar" />

                                            <a href="<?= $rowitem->bukti_donasi ?>" data-toggle="lightbox"><img class="img-fluid" id="oldpic" src="<?= $rowitem->bukti_donasi ?>" width="50%" <?php if ($rowitem->bukti_donasi == NULL) echo " style='display:none;'"; ?>></a>
                                            <br>
                                            <small class="text-muted">
                                                <?php if ($rowitem->bukti_donasi == NULL) {
                                                    echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                                                } else {
                                                    echo "Klik gambar untuk memperbesar";
                                                }

                                                ?>
                                            </small>
                                        </div>
                                        <script>
                                            //Validasi Size Upload Image

                                            window.onload = function() {
                                                document.getElementById('preview').style.display = 'none';
                                            };

                                            function readURL(input) {
                                                if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                                    alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                                    input.value = "";
                                                };
                                                if (input.files && input.files[0]) {
                                                    var reader = new FileReader();

                                                    reader.onload = function(e) {
                                                        $('#preview')
                                                            .attr('src', e.target.result)
                                                            .width(200);
                                                        document.getElementById('preview').style.display = 'block';
                                                    };

                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }
                                        </script>
                                    </div>

                                    <br>
                                    <p align="center">
                                        <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                                    </p>
                        </form>
                    </div>

                    <div class="col-12 border rounded bg-white p-0 mb-2">
                        <h5 class="card-header mb-1 font-weight-bold"><i class="text-info fas fa-comment-dots"></i> Pesan/Ekspresi</h5>
                        <span class="font-weight-bold mb-3 pl-3 pt-4 pb-4"><?= $rowitem->pesan ?></span>
                    </div>

                    <div class="col-12 border rounded bg-white p-0">
                        <h5 class="card-header mb-1 font-weight-bold"><i class="text-danger fas fa-disease"></i> Terumbu Karang Pilihan</h5><br />
                        <?php
                        $sqlviewisi = 'SELECT jumlah_terumbu, nama_terumbu_karang, foto_terumbu_karang FROM t_detail_donasi
                                              LEFT JOIN t_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
                                              LEFT JOIN t_terumbu_karang ON t_detail_donasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                                              WHERE t_detail_donasi.id_donasi = :id_donasi';
                        $stmt = $pdo->prepare($sqlviewisi);
                        $stmt->execute(['id_donasi' => $rowitem->id_donasi]);
                        $rowisi = $stmt->fetchAll();
                        foreach ($rowisi as $isi) {
                        ?>
                            <div class="row  mb-3 pl-3">
                                <div class="col">
                                    <img class="rounded" height="60px" src="<?= $isi->foto_terumbu_karang ?>">
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
        </div>





        </section>
    <?php } ?>
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

</body>

</html>