<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';
    $defaultpic = "images/image_default.jpg";

    $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN t_wisata ON t_reservasi_wisata.id_wisata = t_wisata.id_wisata
                    ORDER BY id_reservasi DESC';
    $stmt = $pdo->prepare($sqlviewreservasi);
    $stmt->execute();
    $row = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reservasi Saya - TKJB</title>
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
    <link rel="icon" href="dist/img/KKPlogo.png" type="image/x-icon" />
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
            <a href="index_admin.php" class="brand-link">
                <img src="dist/img/KKPlogo.png"  class="brand-image img-circle elevation-3" style="opacity: .8">
                <!-- BRAND TEXT (TOP) -->
                <span class="brand-text font-weight-bold">TKJB</span>
            </a>
            <!-- END OF TOP SIDEBAR -->

            <!-- SIDEBAR -->
            <div class="sidebar">
                <!-- SIDEBAR MENU -->
                <nav class="mt-2">
                   <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php print_sidebar(basename(__FILE__), $_SESSION['level_user'])?> <!-- Print sidebar -->
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
                    <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Reservasi Wisata Saya</span></h4>
                        </div>

                        <div class="col">

                        <a class="btn btn-primary float-right" href="map.php?aksi=wisata" role="button">Reservasi Wisata Sekarang (+)</a>

                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                <?php
                    if(!empty($_GET['status'])) {
                        if($_GET['status'] == 'updatesuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                    Update bukti pembayaran reservasi wisata berhasil!
                                    </div>'; }
                        else if($_GET['status'] == 'addsuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                    Reservasi wisata berhasil dibuat! Harap upload bukti pembayaran agar reservasi wisata diproses pengelola
                                    </div>'; }
                    }
                ?>

                <?php if($_SESSION['level_user'] == '1') { ?>
                    <div>
                        <?php foreach ($row as $rowitem) {
                            $truedate = strtotime($rowitem->update_terakhir);
                            $reservasidate = strtotime($rowitem->tgl_reservasi);
                        ?>
                        <div class="blue-container border rounded shadow-sm mb-4 p-4">
                                <!-- First row -->
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <span class="badge badge-pill badge-primary mr-2"> ID Reservasi <?=$rowitem->id_reservasi?> </span>
                                            <?php echo empty($rowitem->id_user) ? '' : '<span class="badge badge-pill badge-info mr-2"> Nama User - '.$rowitem->nama_user.'</span>';?>
                                            <?php echo empty($rowitem->id_wisata) ? '' : '<span class="badge badge-pill badge-success mr-2"> Wisata  - '.$rowitem->judul_wisata.'</span>';?>
                                        </span>
                                    </div>

                                    <div class="col-md mb-3">
                                        <div class="mb-2">
                                            <span class="font-weight-bold"><i class="nav-icon text-success fas fas fa-money-bill-wave"></i> Total</span>
                                            <br>
                                            <span class="mb-3">Rp. <?=number_format($rowitem->total, 0)?></span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-weight-bold"><i class="nav-icon text-success fas fas fa-donate"></i> Jumlah Donasi</span>
                                            <br>
                                            <span class="mb-3">Rp. <?=number_format($rowitem->jumlah_donasi, 0)?></span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-weight-bold"><i class="nav-icon text-secondary fas fas fa-calendar-alt"></i> Tanggal Reservasi</span>
                                            <br>
                                            <?=strftime('%A, %d %B %Y', $reservasidate);?>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-weight-bold"><i class="nav-icon text-info fas fas fa-comment-dots"></i> Keterangan Pengelola Lokasi</span>
                                            <br><?=$rowitem->keterangan?><br>
                                        </div>
                                    </div>


                                    <div class="col-md mb-3">
                                        <div class="mb-2">
                                            <span class="font-weight-bold"><i class="nav-icon text-info fas fas fa-users"></i> Jumlah Peserta</span>
                                            <br><?=$rowitem->jumlah_peserta?><br>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-weight-bold"><i class="nav-icon text-warning fas fas fa-list-alt"></i> Status Reservasi</span>
                                            <br><?=$rowitem->nama_status_reservasi_wisata?>

                                                <?php
                                                    if ($rowitem->id_status_reservasi_wisata == 2) {
                                                        //Pembayaran Telah di Konfirmasi
                                                        echo ($rowitem->id_status_reservasi_wisata <= 3) ? '<a href="edit_reservasi_saya.php?id_reservasi='.$rowitem->id_reservasi.'" class="btn btn-sm btn-primary userinfo" style="display: none;"><i class="fas fa-file-invoice-dollar"></i> Upload Bukti Reservasi Wisata</a>' : '';
                                                    } else if ($rowitem->id_status_reservasi_wisata == 3) {
                                                        //Pembayaran Tidak Sesuai
                                                        echo ($rowitem->id_status_reservasi_wisata <= 3) ? '<a href="edit_reservasi_saya.php?id_reservasi='.$rowitem->id_reservasi.'" class="btn btn-sm btn-primary userinfo"><i class="fas fa-file-invoice-dollar"></i> Upload Bukti Reservasi Wisata</a>' : '';
                                                    } else {
                                                        //Menunggu Konfirmasi Pembayaran
                                                        echo ($rowitem->id_status_reservasi_wisata <= 3) ? '<a href="edit_reservasi_saya.php?id_reservasi='.$rowitem->id_reservasi.'" class="btn btn-sm btn-primary userinfo"><i class="fas fa-file-invoice-dollar"></i> Upload Bukti Reservasi Wisata</a>' : '';
                                                    }
                                                ?>

                                            <br><small class="text-muted"><b>Update Terakhir</b>
                                            <br><?=strftime('%A, %d %B %Y', $truedate);?></small>
                                        </div>
                                        <div class="mb-3">
                                            <?php if ($rowitem->id_status_reservasi_wisata == 2) { ?>
                                                <!-- Invoice Reservasi Wisata -->
                                                <a href="invoice_wisata.php?id_reservasi=<?=$rowitem->id_reservasi?>" class="btn btn-sm btn-primary userinfo">
                                                    <i class="fas fa-file-invoice"></i> Download Inovice Reservasi Wisata</a>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-md mb-3">
                                        <div class="mb-2">
                                            <span class="font-weight-bold"><i class="nav-icon text-danger fas fas fa-map-marker-alt"></i> Lokasi Reservasi Wisata</span><br>
                                            <img height='75px' class="rounded" src=<?=$rowitem->foto_lokasi;?>><br><br>
                                            <span class=""><?="$rowitem->nama_lokasi (ID $rowitem->id_lokasi)";?></span>
                                            <br>
                                            <a target="_blank" href="http://maps.google.com/maps/search/?api=1&query=<?=$rowitem->latitude?>,<?=$rowitem->longitude?>&z=8"
                                                class="btn btn-act"><i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-weight-bold"><i class="nav-icon text-primary fas fas fa-phone"></i> No HP Pengelola Lokasi</span>
                                            <br><?=$rowitem->kontak_lokasi?><br>
                                        </div>
                                    </div>
                                </div>
                                <!-- First Row -->

                                <?php if ($rowitem->id_status_reservasi_wisata == 2) { ?>
                                    <p class=" btn btn-blue btn-primary" onclick="toggleDetail()">
                                        <i class="icon fas fa-chevron-down"></i>
                                        Bukti Pembayaran Reservasi Wisata
                                    </p>

                                    <div class="detail-toggle" id="main-toggle">
                                        <a href="<?=$rowitem->bukti_reservasi?>" data-toggle="lightbox">
                                        <img id="oldpic" src="<?=$rowitem->bukti_reservasi?>" width="100px">
                                        <script>
                                            window.onload = function() {
                                            document.getElementById('preview').style.display = 'none';
                                            };
                                            function readURL(input) {
                                                if (input.files && input.files[0]) {
                                                    var reader = new FileReader();
                                                    document.getElementById('oldpic').style.display = 'none';
                                                    reader.onload = function (e) {
                                                        $('#preview')
                                                            .attr('src', e.target.result)
                                                            .width(200);
                                                            document.getElementById('preview').style.display = 'block';
                                                    };

                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }
                                        </script>
                                        </a>
                                    </div>
                                <?php } ?>

                        </div>
                        <?php } ?>
                    </div>
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

    <footer class="main-footer">
        <strong>Copyright &copy; 2020 .</strong> Terumbu Karang Jawa Barat
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
    <!-- Hiden/Show Button Bukti Pembayaran -->
    <script>
        $(document).ready(function() {
            $('.preview-images').hide()
            $('.detail-toggle').hide()
        });

        function toggleDetail(e){
            var e = event.target
            $(e).siblings('.detail-toggle').fadeToggle()
        }
    </script>
    <!-- Menampilkan Bukti Pembayaran -->
    <script src="js/ekko-lightbox.min.js"></script>
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>

</body>
</html>
