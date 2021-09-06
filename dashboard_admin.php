<?php
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}

include 'build/config/connection.php';
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

if($level_user == 2){
  $id_wilayah = $_SESSION['id_wilayah_dikelola'];
  $extra_query = " AND t_wilayah.id_wilayah = $id_wilayah ";
  $extra_query_noand = " t_wilayah.id_wilayah = $id_wilayah ";

  $wilayah_join_donasi = " LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_donasi.id_lokasi
                            LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";

  $wilayah_join_reservasi = " LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_reservasi_wisata.id_lokasi
                            LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";

  $wilayah_join_batch = "   LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_batch.id_lokasi
                            LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";

}
else if($level_user == 3){
  $id_lokasi = $_SESSION['id_lokasi_dikelola'];
  $extra_query = " AND id_lokasi = $id_lokasi ";
  $extra_query_noand = " id_lokasi = $id_lokasi ";
  $wilayah_join_donasi = " ";
  $wilayah_join_reservasi = " ";
  $wilayah_join_batch = " ";
}
else if($level_user == 4){
  $extra_query = "  ";
  $extra_query_noand = "  ";
  $wilayah_join_donasi = " ";
  $wilayah_join_reservasi = " ";
  $wilayah_join_batch = " ";
}

$sqlviewdonasi = 'SELECT (SELECT COUNT(t_donasi.id_status_donasi)
                FROM t_donasi '.$wilayah_join_donasi.'
                WHERE t_donasi.id_status_donasi = 1 '.$extra_query.') AS donasi_baru,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi '.$wilayah_join_donasi.'
                WHERE t_donasi.id_status_donasi = 2 '.$extra_query.') AS donasi_verifikasi,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi '.$wilayah_join_donasi.'
                WHERE t_donasi.id_batch IS NULL AND t_donasi.id_status_donasi = 3  '.$extra_query.') AS donasi_tanpa_batch,
                (SELECT COUNT(t_donasi.id_status_donasi)
                                FROM t_donasi '.$wilayah_join_donasi.'
                WHERE t_donasi.id_status_donasi = 6 '.$extra_query.') AS donasi_bermasalah';
$stmt = $pdo->prepare($sqlviewdonasi);
$stmt->execute();
$rowdonasi = $stmt->fetch();


$sqlviewreservasi = 'SELECT (SELECT COUNT(t_reservasi_wisata.id_status_reservasi_wisata)
                FROM t_reservasi_wisata '.$wilayah_join_reservasi.'
                WHERE t_reservasi_wisata.id_status_reservasi_wisata = 1 '.$extra_query.') AS reservasi_baru,
                (SELECT COUNT(t_reservasi_wisata.id_status_reservasi_wisata)
                                FROM t_reservasi_wisata '.$wilayah_join_reservasi.'
                WHERE t_reservasi_wisata.id_status_reservasi_wisata = 3 '.$extra_query.') AS reservasi_bermasalah';
$stmt = $pdo->prepare($sqlviewreservasi);
$stmt->execute();
$rowreservasi = $stmt->fetch();




$sqlviewbatch = 'SELECT (SELECT COUNT(id_status_batch)
                FROM t_batch '.$wilayah_join_batch.'
                WHERE id_status_batch = 1 '.$extra_query.') AS batch_penyemaian,
                (SELECT COUNT(id_status_batch)
                FROM t_batch '.$wilayah_join_batch.'
                WHERE id_status_batch = 2 '.$extra_query.') AS batch_siap_tanam';

$stmt = $pdo->prepare($sqlviewbatch);
$stmt->execute();
$rowbatch = $stmt->fetch();

$sqlviewpemeliharaan = 'SELECT (SELECT COUNT(*) FROM (SELECT TIMESTAMPDIFF(MONTH, tanggal_pemeliharaan_terakhir, NOW()) AS lama_sejak_pemeliharaan FROM t_batch  '.$wilayah_join_batch.' WHERE  '.$extra_query_noand.' HAVING lama_sejak_pemeliharaan >= 3) AS jl_pml) AS perlu_pemeliharaan,
                        (SELECT COUNT(*) FROM (SELECT TIMESTAMPDIFF(MONTH, `tanggal_penanaman`, NOW()) AS lama_sejak_tanam FROM t_batch  '.$wilayah_join_batch.' WHERE status_cabut_label = 0 '.$extra_query.' HAVING lama_sejak_tanam >= 11) AS jl_pml) AS perlu_cabut_label';

$stmt = $pdo->prepare($sqlviewpemeliharaan);
$stmt->execute();
$rowperlupml = $stmt->fetch();

// ChartJS Wisata
$label = ["Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"];

for($bulan = 1; $bulan < 13; $bulan++) {
    // Donasi
    $sqldonasiSelect = 'SELECT COUNT(id_donasi) AS total_donasi FROM t_donasi
                            WHERE MONTH(tanggal_donasi) = :bulan';

    $stmt = $pdo->prepare($sqldonasiSelect);
    $stmt->execute(['bulan' => $bulan]);
    $totalDonasi = $stmt->fetch();

    $total_donasi[] = $totalDonasi->total_donasi;

    // Wisata
    $sqlreservasiSelect = 'SELECT COUNT(id_reservasi) AS total_reservasi FROM t_reservasi_wisata
                            WHERE MONTH(tgl_reservasi) = :bulan';

    $stmt = $pdo->prepare($sqlreservasiSelect);
    $stmt->execute(['bulan' => $bulan]);
    $totalReservasi = $stmt->fetch();

    $total_reservasi[] = $totalReservasi->total_reservasi;
    // var_dump($total_reservasi);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard Pengelola - GoKarang</title>
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
            <a href="dashboard_admin.php" class="brand-link">
                <?= $logo_website ?>
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
                        <h4>Selamat Datang, <?php //echo $_SESSION['username']; ?> !</h4>
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
                            <div class="col text-right"><a href="kelola_donasi.php?id_batch=isnull" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
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



                    <h5 class="mt-4"><span class="align-middle font-weight-bold"><i class="fas fa-suitcase-rolling text-info"></i> Reservasi Wisata</span></h5>

                    <div class="row rounded shadow-sm p-2">
                      <div class="col-sm">
                        <div class="alert dash-primary m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Reservasi Baru <span class="badge text-sm badge-pill badge-success"><?= $rowreservasi->reservasi_baru ?></span></div>
                            <div class="col text-right"><a href="kelola_reservasi_wisata.php?id_status_reservasi_wisata=1" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                    </div>


                      <div class="col">
                      <div class="alert dash-danger m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Reservasi Bermasalah <span class="badge text-sm badge-pill badge-danger"><?= $rowreservasi->reservasi_bermasalah?></span></div>
                            <div class="col text-right"><a href="kelola_reservasi_wisata.php?id_status_reservasi_wisata=3" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
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
                            <div class="col text-right"><a href="kelola_batch.php?id_status_batch=2" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                      </div>


                      <div class="col">
                        <div class="alert dash-success m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Batch Dalam Tahap Penyemaian <span class="badge text-sm badge-pill badge-info"><?= $rowbatch->batch_penyemaian?></span></div>
                            <div class="col text-right"><a href="kelola_batch.php?id_status_batch=1" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
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
                            <div class="col text-right"><a href="input_pemeliharaan.php" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                      </div>


                      <div class="col">
                        <div class="alert dash-warning m-1 border-0" role="alert">
                          <div class="row">
                            <div class="col-7">Batch Perlu Cabut Label <span class="badge text-sm badge-pill badge-warning"><?= $rowperlupml->perlu_cabut_label ?></span></div>
                            <div class="col text-right"><a href="input_pemeliharaan.php" class="btn btn-act text-dark text-decoration-none">Lihat</a></div>
                          </div>
                      </div>
                      </div>
                    </div>
                    
                    <!-- Grafik Donasi & Wisata -->
                    <h5 class="mt-4"><span class="align-middle font-weight-bold"><i class="nav-icon text-primary far fa-chart-bar"></i> Data Grafik</span></h5>

                    <div class="row rounded shadow-sm p-2">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-outline-info btn-sm" id="btn-donasi">
                                                <i class="far fa-file-image"></i> Export ke Image</button>
                                                Donasi
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="donasi" width="100%" height="100%"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-outline-info btn-sm" id="btn-wisata">
                                                <i class="far fa-file-image"></i> Export ke Image</button>
                                                Wisata
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="wisata" width="100%" height="100%"></canvas>
                                        </div>
                                    </div>
                                </div>
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
        <?= $footer ?>
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

    <!-- CharJs CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js" integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.js" integrity="sha512-XcsV/45eM/syxTudkE8AoKK1OfxTrlFpOltc9NmHXh3HF+0ZA917G9iG6Fm7B6AzP+UeEzV8pLwnbRNPxdUpfA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Export to Image -->
    <script src="plugins/canvas-toBlob.js-master/canvas-toBlob.js"></script>
    <!-- FileSaver -->
    <script src="plugins/FileSaver.js-master/dist/FileSaver.min.js"></script>
    <!-- Chartjs -->
    <script>
        // Donasi
        $('#btn-donasi').click(function () {
            $('#donasi').get(0).toBlob(function (blob) {
                saveAs(blob, 'data_donasi.png')
            });
        });

        // Any of the following formats may be used
        var ctx = document.getElementById('donasi');
        var donasi = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($label); ?>, //12 Bulan
                datasets: [{
                    label: 'Donatur',
                    data: <?php echo json_encode($total_donasi); ?>, //Total Donasi Berdasarkan Bulan
                    // data: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Data Donasi',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });

        // ==============================================================
        // Wisata
        $('#btn-wisata').click(function () {
            $('#wisata').get(0).toBlob(function (blob) {
                saveAs(blob, 'data_pengunjung.png')
            });
        });

        // Any of the following formats may be used
        var ctx = document.getElementById('wisata');
        var wisata = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($label); ?>, //12 Bulan
                datasets: [{
                    label: 'Pengunjung',
                    data: <?php echo json_encode($total_reservasi); ?>, //Total Pengunjung Berdasarkan Bulan
                    // data: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Data Wisatawan',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
    </script>
</div>
</body>
</html>
