<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Homepage - GoKarang</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <!--Leaflet panel layer CSS-->
    <link rel="stylesheet" href="dist/css/leaflet-panel-layers.css" />
    <!-- Leaflet Marker Cluster CSS -->
    <link rel="stylesheet" href="dist/css/MarkerCluster.css" />
    <link rel="stylesheet" href="dist/css/MarkerCluster.Default.css" />
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
            <a href="dashboard_user.php" class="brand-link">
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

                    <!-- card menu -->
                    <section id="menu">
                        <div class="container">
                            <!-- <div class="card-group"> -->
                            <div class="text-center">
                                <h2>Kelola Halaman Depan Website</h2>
                            </div>
                            <div class="row d-flex justify-content-center text-center p-5 ">
                                <?php if ($_SESSION['level_user'] == '2' || $_SESSION['level_user'] == '3') { ?>
                                    <div class="col-md-12 col-lg-4">
                                        <div class="card m-3 dashboard-home">
                                            <!-- <img class="card-img-top mb-3" src="dist/img/konservasi.jpg" alt="Card image cap" id="img-cap"> -->

                                            <i class="fas fa-images fa-7x py-4 " style="color: #244276;"></i>
                                            <div class="card-block">
                                                <button class="btn btn-info mb-3 btn-card" onclick="window.location.href='kelola_konten_tangkolak.php';">Kelola Banner Website</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-4">
                                        <div class="card m-3 dashboard-home">
                                            <!-- <img class="card-img-top mb-3" src="dist/img/briefcase.jpg" alt="Card image cap" id="img-cap"> -->
                                            <i class="fas fa-handshake fa-7x py-4" style="color: #244276;"></i>
                                            <div class="card-block">
                                                <button class="btn btn-info mb-3 btn-card" onclick="window.location.href='kelola_konten_ketentuan.php';">Kelola Ketentuan Wisata</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12 col-lg-4">
                                        <div class="card m-3 dashboard-home">
                                            <img class="card-img-top mb-3" src="dist/img/briefcase.jpg" alt="Card image cap" id="img-cap">
                                            <i class="fas fa-book-open fa-7x py-4" style="color: #244276;"></i>
                                            <div class="card-block">
                                                <button class="btn btn-info mb-3 btn-card" onclick="window.location.href='kelola_konten_penjelasan.php';">Kelola Penjelasan Wisata</button>
                                            </div>
                                        </div>
                                    </div> -->
                                <?php } ?>
                            </div>
                            <!-- </div> -->
                        </div>
                    </section>
                    <!-- end of card menu -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

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
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <!-- Leaflet Marker Cluster -->
    <script src="dist/js/leaflet.markercluster-src.js"></script>
    <!-- Leaflet panel layer JS-->
    <script src="dist/js/leaflet-panel-layers.js"></script>
    <!-- Leaflet Ajax, Plugin Untuk Mengloot GEOJson -->
    <script src="dist/js/leaflet.ajax.js"></script>
    <!-- Leaflet Map -->
    <script src="dist/js/leaflet-map.js"></script>

</body>

</html>