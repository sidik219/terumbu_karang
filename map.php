<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

//Reservasi Wisata
if (isset($_GET['aksi'])) {
    $wisata = $_GET['aksi'];
  } else {
    $wisata = null;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pilih Lokasi Penanaman - GoKarang</title>
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
                    <!-- Untuk User -->
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
                        <!-- Untuk User -->
                        <?php if($_SESSION['level_user'] == '1') { ?>
                            <?php if($wisata == null) { ?>
                            <h4><span class="align-middle font-weight-bold">Pilih Lokasi Penanaman</span></h4>
                            <?php } else {?>
                            <h4><span class="align-middle font-weight-bold">Pilih Lokasi Wisata</span></h4>
                            <?php } ?>
                        <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                <!-- Untuk User -->
                <?php if($_SESSION['level_user'] == '1') { ?>
                    <div>
                        <div>
                            <label>Keterangan Icon:</label>
                        </div>
                        <div>
                            <img src="images/foto_lokasi/icon_lokasi/icon_lokasi.png" style="width: 35px;">:
                            <label>Lokasi Pantai </label>
                            <img src="images/kumpulan_icon/cluster.png" style="width: 35px;">:
                            <label>Pengelompokan Pantai Berdekatan </label>
                            <img src="images/kumpulan_icon/geo_wilayah1.png" style="width: 35px;">:
                            <label>Wilayah tersedia donasi terumbu karang </label>
                            <img src="images/kumpulan_icon/geo_wilayah2.png" style="width: 35px;">:
                            <label>Wilayah belum tersedia donasi terumbu karang </label>
                        </div>
                        <div id="mapid" style="height: 640px; width: 100%; margin-top: 20px;"></div>
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
    <?php include 'dist/js/leaflet_map.php';?>

    <script>
      if(sessionStorage.getItem('keranjang_serialised')){
        sessionStorage.removeItem('keranjang_serialised')
      }
    </script>

</body>
</html>
