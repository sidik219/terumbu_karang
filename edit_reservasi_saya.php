<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_reservasi = $_GET['id_reservasi'];
$defaultpic = "images/image_default.jpg";
$id_status_reservasi_wisata = 1;

$sql = 'SELECT * FROM t_reservasi_wisata
        LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
        LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
        LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
        LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
        LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
        WHERE id_reservasi = :id_reservasi
        AND t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi';

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_reservasi' => $id_reservasi]);
$rowitem = $stmt->fetch();

// Rekening Bersama
$id_rekening_bersama = $rowitem->id_rekening_bersama;

$sqlviewrekeningbersama = 'SELECT * FROM t_rekening_bank WHERE id_rekening_bank = :id_rekening_bersama';

$stmt = $pdo->prepare($sqlviewrekeningbersama);
$stmt->execute(['id_rekening_bersama' => $id_rekening_bersama]);
$rowrekening = $stmt->fetch();

if (isset($_POST['submit'])) {
    $randomstring = substr(md5(rand()), 0, 7);

    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
        $bukti_reservasi = $rowitem->bukti_reservasi;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($rowitem->bukti_reservasi == $defaultpic) || (!$rowitem->bukti_reservasi)) {
            $target_dir  = "images/bukti_reservasi/";
            $bukti_reservasi = $target_dir . 'BKTDNS_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $bukti_reservasi);
            $pic = "&new=";
        } else if (isset($rowitem->bukti_reservasi)) {
            $bukti_reservasi = $rowitem->bukti_reservasi;
            unlink($rowitem->bukti_reservasi);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowitem->bukti_reservasi);
            $pic = "&replace=";
        }
    }
    //---image upload end

    $tanggal_upload_bukti = date('Y-m-d H:i:s', time());

    $sqlreservasi = "UPDATE t_reservasi_wisata
                        SET bukti_reservasi = :bukti_reservasi, 
                            id_status_reservasi_wisata = :id_status_reservasi_wisata, 
                            update_terakhir = :update_terakhir
                        WHERE id_reservasi = :id_reservasi";

    $stmt = $pdo->prepare($sqlreservasi);
    $stmt->execute([
        'id_reservasi' => $id_reservasi,
        'bukti_reservasi' => $bukti_reservasi,
        'id_status_reservasi_wisata' => $id_status_reservasi_wisata,
        'update_terakhir' => $tanggal_upload_bukti
    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: reservasi_saya.php?status=nochange");
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

            $subjek = 'Bukti Reservasi Wisata Perlu Verifikasi (ID Reservasi : ' . $id_reservasi . ' ) - GoKarang';
            $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                    <br>Yth. ' . $nama_user . '
                    <br>Harap verifikasi bukti reservasi wisata baru pada lokasi ' . $pengelola->nama_lokasi . '
                    <br>Berikut rincian reservasi wisata baru tersebut:
                    <br>Bank Wisatawan: ' . $rowitem->bank_donatur . '
                    <br>Nomor Rekening Wisatawan: ' . $rowitem->nomor_rekening_donatur . '
                    <br>Nama Rekening Wisatawan: ' . $rowitem->nama_donatur . '
                    <br>          
                    <br>Bank Tujuan Pembayaran: ' . $rowrekening->nama_bank . '
                    <br>Nomor Rekening Tujuan: ' . $rowrekening->nomor_rekening . '
                    <br>Nama Rekening Tujuan: ' . $rowrekening->nama_pemilik_rekening . '
                    <br>Nominal pembayaran: Rp. ' . number_format($rowitem->total, 0) . '
                    <br>
                    <br>Harap segera verifikasi bukti reservasi wisata di link berikut:
                    <br><a href="https://tkjb.or.id/edit_reservasi_wisata.php?id_reservasi=' . $id_reservasi . '">Verifikasi Bukti Pembayaran</a>
                ';

            smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
        }

        header("Location: reservasi_saya.php?status=updatesuccess");
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
    <!-- Theme style -->
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
                    <a class="btn btn-outline-primary" href="reservasi_saya.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Kirim Bukti Reservasi Wisata</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php if ($_SESSION['level_user'] == '1') { ?>
                        <!--
                        <form action="" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label for="dd_id_lokasi_wisata">ID Lokasi</label>
                                <select id="dd_id_lokasi_wisata" name="dd_id_lokasi_wisata" class="form-control">
                                    <option value="">41051 - Pantai Tangkolak</option>
                                    <option value="">45211 - Pulau Biawak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date_reservasi_wisata">Tanggal Reservasi</label>
                                <div class="file-form">
                                <input type="date" id="date_reservasi_wisata" name="date_reservasi_wisata" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="num_jumlah_peserta">Jumlah Peserta</label>
                                <input type="number" id="num_jumlah_peserta" name="num_jumlah_peserta" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="num_total_reservasi">Total (Rp.)</label>
                                <input type="number" id="num_total_reservasi" name="num_total_reservasi" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="rb_status_reservasi">Status</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="rb_status_reservasi_selesai" name="rb_status_reservasi" value="diterima" class="form-check-input">
                                    <label class="form-check-label" for="rb_status_reservasi_selesai">Selesai</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="rb_status_reservasi_pending" name="rb_status_reservasi" value="belum_diterima" class="form-check-input">
                                    <label class="form-check-label" for="rb_status_reservasi_pending">Pending</label>
                                </div>
                            </div>
                            <br>
                            <p align="center">
                            <button type="submit" class="btn btn-submit">Kirim</button></p>
                        </form> -->


                        <form action="" enctype="multipart/form-data" method="POST">
                            <div class="row">
                                <div class="col-lg-9 border rounded bg-white mb-2">
                                    <div class="" style="width:100%;">
                                        <div class="">
                                            <h5 class="card-header mb-2 pl-0">Rincian Pembayaran</h5>
                                            <span class="">Pilihan Reservasi Wisata : </span> <span class="text-info font-weight-bolder"><?= $rowitem->nama_lokasi ?></span>
                                            <div class="d-block my-3">
                                                <div class="custom-control custom-radio">
                                                    <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                                                    <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
                                                    <p class="text-muted">Harap upload bukti transfer agar reservasi wisata segera diproses pengelola lokasi.</p>
                                                </div>
                                                <hr class="mb-2" />

                                                <div class="row">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-danger fas fa-id-card-alt"></i> ID Reservasi
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8 mb-2">
                                                        <span class=""><?= $rowitem->id_reservasi ?></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-primary fas fa-user"></i> Nama User
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowitem->nama_user ?></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-secondary fas fa-calendar-alt"></i> Tanggal Reservasi
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowitem->tgl_reservasi ?></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-info fas fa-suitcase"></i> Paket Wisata
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowitem->nama_paket_wisata ?></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-info fas fa-users"></i> Jumlah Peserta
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowitem->jumlah_peserta ?></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-danger fas fa-heartbeat"></i> Asuransi
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class="">Rp. <?= number_format($rowitem->biaya_asuransi, 0) ?></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-success fas fa-donate"></i> Donasi
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class="">Rp. <?= number_format($rowitem->harga_donasi, 0) ?></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-success fas fa-money-bill-wave"></i> Total
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class="">Rp. <?= number_format($rowitem->total, 0) ?></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold">
                                                            <i class="text-info fas fa-comment-dots"></i> Keterangan
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class="font-weight-bold"><?= $rowitem->keterangan ?></span>
                                                    </div>
                                                </div>

                                                <!-- Info Pengelola -->
                                                <hr class="mb-2" />

                                                <div class="row">
                                                    <div class="col">
                                                        <span class="font-weight-bold"><i class="fas fa-user-tie"></i> Nama Rekening Pengelola</span>
                                                    </div>
                                                    <div class="col-lg-8 mb-2">
                                                        <span class=""><?= $rowrekening->nama_pemilik_rekening ?></span>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <span class="font-weight-bold"><i class="text-warning fas fa-university"></i> Nama Bank Pengelola</span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowrekening->nama_bank ?></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <span class="font-weight-bold"><i class="text-info fas fa-hashtag"></i> Nomor Rekening Pengelola</span>
                                                    </div>
                                                    <div class="col-lg-8  mb-2">
                                                        <span class=""><?= $rowrekening->nomor_rekening ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br><br>

                                <div class="col-lg-3  border rounded bg-white p-3 mb-2  text-center">
                                    <div class="form-group">
                                        <label for="file_bukti_reservasi_wisata">Bukti Reservasi Wisata</label>
                                        <div class='form-group' id='buktireservasi'>
                                            <label class="btn btn btn-primary btn-blue" for='image_uploads'>
                                                <i class="fas fa-camera"></i> Upload File
                                            </label>
                                            <div>
                                                <input type='file' class='form-control d-none' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png, .pdf' onchange="readURL(this);" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <img id="preview" src="#" width="100px" alt="Preview Gambar" />
                                            <a href="<?= $rowitem->bukti_reservasi ?>" data-toggle="lightbox"><img class="img-fluid" id="oldpic" src="<?= $rowitem->bukti_reservasi ?>" width="50%" <?php if ($rowitem->bukti_reservasi == NULL) echo " style='display:none;'"; ?>></a>
                                            <br>

                                            <small class="text-muted">
                                                <?php if ($rowitem->bukti_reservasi == NULL) {
                                                    echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                                                } else {
                                                    echo "Klik gambar untuk memperbesar";
                                                }

                                                ?>
                                            </small>

                                            <script>
                                                window.onload = function() {
                                                    document.getElementById('preview').style.display = 'none';
                                                };

                                                function readURL(input) {
                                                    //Validasi Size Upload Image
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
                                    </div>
                                    <p align="center">
                                        <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                                    </p>
                        </form>

                    <?php } ?>
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

</body>

</html>