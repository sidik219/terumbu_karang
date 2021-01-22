<?php include 'build/config/connection.php';
session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

    $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata';
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
    <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
        <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
        <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
        <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</a>
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
                    <?php //if($_SESSION['level_user'] == '2') { ?>    
                        <li class="nav-item ">
                           <a href="dashboard_user.php" class="nav-link  ">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="donasi_saya.php" class="nav-link">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Donasi Saya </p>
                           </a>
                        </li>
                        <li class="nav-item  menu-open">
                           <a href="reservasi_saya.php" class="nav-link active">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Reservasi Saya  </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="#" class="nav-link">
                                <i class="nav-icon fas fas fa-disease"></i>
                                <p> Terumbu Karang  </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="profil_saya.php" class="nav-link">
                                <i class="nav-icon fas fas fa-user"></i>
                                <p> Profil Saya  </p>
                           </a>
                        </li>
                    <?php //} ?>
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

                        <a class="btn btn-primary float-right" href="pilih_lokasi_wisata.php" role="button">Reservasi Wisata Sekarang (+)</a>

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
                                            <?php echo empty($rowitem->id_user) ? '' : '<span class="badge badge-pill badge-info mr-2"> ID User '.$rowitem->id_user.' - '.$rowitem->nama_user.'</span>';?>
                                        </span>
                                    </div>

                                    <div class="col-md mb-3">
                                        <div class="mb-2">
                                            <span class="font-weight-bold"><i class="nav-icon text-success fas fas fa-money-bill-wave"></i> Total</span>
                                            <br>
                                            <span class="mb-3">Rp. <?=number_format($rowitem->total, 0)?></span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="font-weight-bold"><i class="nav-icon text-secondary fas fas fa-calendar-alt"></i> Tanggal Reservasi</span>
                                            <br>
                                            <?=strftime('%A, %d %B %Y', $reservasidate);?>
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

                                                <?php echo ($rowitem->id_status_reservasi_wisata <= 2) ? '<a href="edit_reservasi_saya.php?id_reservasi='.$rowitem->id_reservasi.'" class="btn btn-sm btn-primary userinfo"><i class="fas fa-file-invoice-dollar"></i> Upload Bukti Reservasi Wisata</a>' : ''; ?>

                                            <br><small class="text-muted"><b>Update Terakhir</b>
                                            <br><?=strftime('%A, %d %B %Y', $truedate);?></small>
                                        </div>                                                             
                                    </div>

                                    <div class="col-md mb-3">
                                        <div class="mb-2">
                                            <span class="font-weight-bold"><i class="nav-icon text-danger fas fas fa-map-marker-alt"></i> Lokasi Reservasi Wisata</span><br>
                                            <span class=""><?="$rowitem->nama_lokasi (ID $rowitem->id_lokasi)";?></span>
                                        </div>
                                        <div class="mb-2">
                                            <span class="font-weight-bold"><i class="nav-icon text-info fas fas fa-comment-dots"></i> Keterangan</span>
                                            <br><?=$rowitem->keterangan?><br>
                                        </div>
                                    </div>
                            </div><!-- First Row -->
                        </div>
                        <?php } ?>
                    </div>
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
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>

</body>
</html>