<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewdonasi = 'SELECT (SELECT COUNT(t_donasi.id_status_donasi)
                FROM t_donasi
                WHERE t_donasi.id_status_donasi = 1) AS donasi_baru,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi
                WHERE t_donasi.id_status_donasi = 2) AS donasi_verifikasi,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi
                WHERE t_donasi.id_batch IS NULL AND t_donasi.id_status_donasi = 3 ) AS donasi_tanpa_batch,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi
                WHERE t_donasi.id_status_donasi = 6) AS donasi_bermasalah';
$stmt = $pdo->prepare($sqlviewdonasi);
$stmt->execute();
$rowdonasi = $stmt->fetch();


$sqlviewbatch = 'SELECT (SELECT COUNT(id_status_batch)
                FROM t_batch
                WHERE id_status_batch = 1) AS batch_penyemaian,
                (SELECT COUNT(id_status_batch)
                FROM t_batch
                WHERE id_status_batch = 2) AS batch_siap_tanam';

$stmt = $pdo->prepare($sqlviewbatch);
$stmt->execute();
$rowbatch = $stmt->fetch();

$sqlviewpemeliharaan = 'SELECT (SELECT COUNT(*) FROM (SELECT TIMESTAMPDIFF(MONTH, tanggal_pemeliharaan_terakhir, NOW()) AS lama_sejak_pemeliharaan FROM t_batch HAVING lama_sejak_pemeliharaan >= 3) AS jl_pml) AS perlu_pemeliharaan,
                        (SELECT COUNT(*) FROM (SELECT TIMESTAMPDIFF(MONTH, `tanggal_penanaman`, NOW()) AS lama_sejak_tanam FROM t_batch WHERE status_cabut_label = 0 HAVING lama_sejak_tanam >= 11) AS jl_pml) AS perlu_cabut_label';

$stmt = $pdo->prepare($sqlviewpemeliharaan);
$stmt->execute();
$rowperlupml = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard Pengelola - TKJB</title>
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
            <a href="dashboard_admin.php" class="brand-link">
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
            <!-- <div class="content-header">
                <div class="container-fluid">
                   <div class="jumbotron jumbotron-fluid-profil">
                        <h4>Selamat Datang, <?php echo $_SESSION['username']; ?> !</h4>
                    </div>
                    <div class="row">
                        <div class="col"> -->
                            <!-- Untuk Admin -->
                            <?php //if($_SESSION['level_user'] == '1') { ?>
                                <!-- <h4><span class="align-middle font-weight-bold">Lokasi Penanaman</span></h4>
                            <?php //} ?>
                        </div>
                    </div> -->
                    <!-- end jumbotro profil -->
                    <!-- profil pic holder -->
                    <!-- <div class="row justify-content-center">
                         <div class ="profile-pic-div" >
                            <img src="dist/img/profil-example.jpg" id="photo">
                         </div>
                    </div>
                </div> -->
                <!-- /.container-fluid -->
            <!-- </div> -->
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Untuk Admin -->
                    <!-- <?php if($_SESSION['level_user'] == '2' || $_SESSION['level_user'] == '3') { ?>
                        <div>
                            <div>
                                <label>Keterangan Icon:</label>
                            </div>
                            <div>
                                <img src="images/kumpulan_icon/cluster.png" style="width: 3%;">:
                                <label>Pengelompokan Jarak Terdekat, </label>
                                <img src="images/kumpulan_icon/geo_wilayah1.png" style="width: 3%;">:
                                <label>Wilayah yang ada terumbu karang, </label>
                                <img src="images/kumpulan_icon/geo_wilayah2.png" style="width: 3%;">:
                                <label>Wilayah yang tidak ada terumbu karang, </label>
                            </div>
                            <div>
                                <img src="images/foto_kondisi_titik/Sangat Baik.png" style="width: 3%;">:
                                <label>Sangat Baik, </label>
                                <img src="images/foto_kondisi_titik/Baik.png" style="width: 3%;">:
                                <label>Baik, </label>
                                <img src="images/foto_kondisi_titik/Cukup.png" style="width: 3%;">:
                                <label>Cukup, </label>
                                <img src="images/foto_kondisi_titik/Kurang.png" style="width: 3%;">:
                                <label>Kurang</label>
                            </div>
                            <div id="mapid" style="height: 560px; width: 100%; margin-top: 20px;"></div>
                        </div>
                    <?php } ?> -->

                    <div class="content-header"><h4 class=""><span class="mt-2 align-middle font-weight-bold">Dashboard Pengelola</span></h4></div>

                    <h5 class=""><span class="align-middle font-weight-bold"><i class="fas text-success fa-hand-holding-usd"></i> Donasi</span></h5>

                    <div class="row rounded shadow-sm p-2">
                      <div class="col-sm">
                        <div class="alert dash-primary m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Donasi Baru <span class="badge text-sm badge-pill badge-success"><?= $rowdonasi->donasi_baru ?></span></div>
                            <div class="col text-right"><a href="kelola_donasi.php?id_status_donasi=1" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>

                      <div class="alert dash-success m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Donasi Perlu Verifikasi <span class="badge text-sm badge-pill badge-info"><?= $rowdonasi->donasi_verifikasi ?></span></div>
                            <div class="col text-right"><a href="kelola_donasi.php?id_status_donasi=2" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>


                      </div>
                      <div class="col">
                        <div class="alert dash-warning m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Donasi Belum Masuk Batch <span class="badge text-sm badge-pill badge-warning"><?= $rowdonasi->donasi_tanpa_batch ?></span></div>
                            <div class="col text-right"><a href="kelola_donasi.php?id_status_donasi=isnull" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>

                      <div class="alert dash-danger m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Donasi Bermasalah <span class="badge text-sm badge-pill badge-danger"><?= $rowdonasi->donasi_bermasalah?></span></div>
                            <div class="col text-right"><a href="kelola_donasi.php?id_status_donasi=6" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                      </div>
                    </div>




                  <h5 class="mt-4"><span class="align-middle font-weight-bold"><i class="fas text-warning fa-boxes"></i> Batch</span></h5>

                    <div class="row rounded shadow-sm p-2">
                      <div class="col-sm">
                        <div class="alert dash-primary m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Batch Siap Ditanam <span class="badge text-sm badge-pill badge-success"><?= $rowbatch->batch_siap_tanam?></span></div>
                            <div class="col text-right"><a href="kelola_donasi.php?id_status_donasi=1" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                      </div>


                      <div class="col">
                        <div class="alert dash-success m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Batch Dalam Tahap Penyemaian <span class="badge text-sm badge-pill badge-info"><?= $rowbatch->batch_penyemaian?></span></div>
                            <div class="col text-right"><a href="kelola_donasi.php?id_status_donasi=3" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                    </div>
                    </div>



                    <h5 class="mt-4"><span class="align-middle font-weight-bold"><i class="nav-icon text-danger fas fa-heart"></i> Pemeliharaan</span></h5>

                    <div class="row rounded shadow-sm p-2">
                      <div class="col-sm">
                        <div class="alert dash-primary m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Batch Perlu Pemeliharaan <span class="badge text-sm badge-pill badge-success"><?= $rowperlupml->perlu_pemeliharaan ?></span></div>
                            <div class="col text-right"><a href="kelola_donasi.php?id_status_donasi=1" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                      </div>


                      <div class="col">
                        <div class="alert dash-warning m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Batch Perlu Cabut Label <span class="badge text-sm badge-pill badge-warning"><?= $rowperlupml->perlu_cabut_label ?></span></div>
                            <div class="col text-right"><a href="kelola_donasi.php?id_status_donasi=3" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                    </div>

















                <!-- /.container-fluid -->
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

<div>
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
    <?php include 'dist/js/leaflet_map.php'; ?>
</div>
</body>
</html>
