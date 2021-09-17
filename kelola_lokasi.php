<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=unrestrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if (isset($_GET['status'])){
    $status = $_GET['status'];
}

$level_user = $_SESSION['level_user'];

if($level_user == 2){
  $id_wilayah = $_SESSION['id_wilayah_dikelola'];
  $extra_query_k_lok = " WHERE t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level_user == 3){
  $id_lokasi = $_SESSION['id_lokasi_dikelola'];
  $extra_query = " AND id_lokasi = $id_lokasi ";
  $extra_query_k_lok = " WHERE t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level_user == 4){
  $extra_query = " 1 ";
  $extra_query_noand = " 1 ";
  $wilayah_join = " ";
  $extra_query_k_lok = " ";
}

$sqlviewlokasi = 'SELECT *, SUM(luas_titik) AS total_titik,
                                  COUNT(DISTINCT id_titik) AS jumlah_titik,
                                  SUM(DISTINCT luas_lokasi) AS total_lokasi,
                                  SUM(DISTINCT luas_titik) / SUM(DISTINCT luas_lokasi) * 100 AS persentase_sebaran,
                                  COUNT(id_titik) AS jumlah_titik, COUNT(case when kondisi_titik = "Kurang" then 1 else null end) as jumlah_kurang,
                                  COUNT(case when kondisi_titik = "Cukup" then 1 else null end) as jumlah_cukup,
                                  COUNT(case when kondisi_titik = "Baik" then 1 else null end) as jumlah_baik,
                                  COUNT(case when kondisi_titik = "Sangat Baik" then 1 else null end) as jumlah_sangat_baik,
                                  t_lokasi.latitude AS latitude_lokasi,
                                  t_lokasi.longitude AS longitude_lokasi

                                  FROM t_lokasi
                                  LEFT JOIN t_titik ON t_titik.id_lokasi = t_lokasi.id_lokasi
                                  LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah  '.$extra_query_k_lok.'
                                  GROUP BY t_lokasi.id_lokasi';





// $sqlviewlokasi222 = 'SELECT *, SUM(luas_titik) AS total_titik,
// COUNT(DISTINCT id_titik) AS jumlah_titik,
// SUM(DISTINCT luas_lokasi) AS total_lokasi,
// SUM(DISTINCT luas_titik) / SUM(DISTINCT luas_lokasi) * 100 AS persentase_sebaran,
// COUNT(id_titik) AS jumlah_titik, COUNT(case when kondisi_titik = "Kurang" then 1 else null end) as jumlah_kurang,
// COUNT(case when kondisi_titik = "Cukup" then 1 else null end) as jumlah_cukup,
// COUNT(case when kondisi_titik = "Baik" then 1 else null end) as jumlah_baik,
// COUNT(case when kondisi_titik = "Sangat Baik" then 1 else null end) as jumlah_sangat_baik

// FROM `t_titik`, t_lokasi, t_wilayah
// WHERE t_titik.id_lokasi = t_lokasi.id_lokasi
// AND t_lokasi.id_wilayah = t_wilayah.id_wilayah  '.$extra_query_k_lok.'
// GROUP BY t_lokasi.id_lokasi';

$stmt = $pdo->prepare($sqlviewlokasi);
$stmt->execute();
$row = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Lokasi - GoKarang</title>
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
            <div class="content-header">
                <div class="container-fluid">
                <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Kelola Lokasi</span></h4>
                        </div>
                        <div class="col">

                        <?php if(($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){ ?>
                        <a class="btn btn-primary float-right" href="input_lokasi.php" role="button">Input Data Baru (+)</a>
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

                      <?php
                if(!empty($_GET['status'])){
                  if($_GET['status'] == 'updatesuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Update data berhasil
                      </div>';}
                      else if($_GET['status'] == 'addsuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Data baru berhasil ditambahkan
                      </div>';}
                      else if($_GET['status'] == 'deletesuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Data berhasil dihapus
                      </div>';
                    }
                  }
                ?>

                    <table class="table table-striped table-responsive-sm">
                    <thead>
                            <tr>
                            <th scope="col">ID Lokasi</th>
                            <th scope="col">ID Wilayah</th>
                            <th scope="col">Nama Lokasi</th>
                            <th scope="col">Persentase Sebaran</th>
                            <th class="text-right" scope="col">Aksi</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php foreach ($row as $rowitem) {
                          $ps = $rowitem->persentase_sebaran;
                      if($ps >= 0 && $ps < 25){
                        $kondisi_wilayah = 'Kurang';
                      }
                      else if($ps >= 25 && $ps < 50){
                        $kondisi_wilayah = 'Cukup';
                      }
                      else if($ps >= 50 && $ps < 75){
                        $kondisi_wilayah = 'Baik';
                      }
                      else{
                        $kondisi_wilayah = 'Sangat Baik';
                      }

                        if($_SESSION['level_user'] == 4){
                          $sqlplokasi = 'SELECT * FROM t_lokasi';
                        }else{
                          $sqlplokasi = 'SELECT * FROM t_lokasi
                        
                        WHERE id_lokasi = :id_lokasi';
                        }
                        $sqlplokasi = 'SELECT * FROM t_lokasi
                        
                        WHERE id_lokasi = :id_lokasi';
                        $stmt = $pdo->prepare($sqlplokasi);
                        $stmt->execute(['id_lokasi' => $rowitem->id_lokasi]);
                        $rowpengelola = $stmt->fetch();
                        ?>
                            <tr>
                            <th scope="row"><?=$rowitem->id_lokasi?></th>
                            <td><?=$rowitem->id_wilayah?> - <?=$rowitem->nama_wilayah?></td>
                            <td><?=$rowitem->nama_lokasi?><br><a target="_blank" href="http://maps.google.com/maps/search/?api=1&query=<?=$rowitem->latitude_lokasi?>,<?=$rowitem->longitude_lokasi?>&z=8"
                                                                                                                                      class="btn btn-act"><i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a></td>
                            <td><?=number_format($rowitem->total_titik).' / '.number_format($rowitem->total_lokasi).' ha<br>'.number_format($rowitem->persentase_sebaran, 1).'% ( '.$kondisi_wilayah.' )'?></td>
                            <td class="text-right">
                                <?php if(($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){ ?>
                                <a href="edit_lokasi.php?id_lokasi=<?=$rowitem->id_lokasi?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                <a href="hapus.php?type=lokasi&id_lokasi=<?=$rowitem->id_lokasi?>" class="far fa-trash-alt btn btn-act"></a>
                                <?php } ?>
                                <a href="kelola_harga_terumbu.php?id_lokasi=<?=$rowitem->id_lokasi?>" class="btn btn-act text-dark mt-3"><i class="fas fa-money-bill-alt text-success"></i> Kelola Harga Patokan Terumbu</a>
                                <br><a href="kelola_biaya_operasional.php?id_lokasi=<?=$rowitem->id_lokasi?>" class="btn btn-act text-dark mt-3"><i class="fas fa-money-bill-alt text-info"></i> Kelola Biaya Operasional</a>
                                <?php if(($_SESSION['level_user'] == 4)){ ?>
                                <a href="atur_pengelola_lokasi.php?id_lokasi=<?=$rowitem->id_lokasi?>" class="mr-3 btn btn-act"><i class="fas fa-id-badge"></i> Atur Pengelola</a>
                                <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$rowitem->id_lokasi?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$rowitem->id_lokasi?>, .contentall<?=$rowitem->id_lokasi?>">
                                <p
                                    class="fielddetail<?=$rowitem->id_lokasi?> btn btn-act">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Lokasi</p>
                            </div>
                            <div class="col-12 cell<?=$rowitem->id_lokasi?> collapse contentall<?=$rowitem->id_lokasi?> border rounded shadow-sm p-3">

                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Estimasi Total Luas Titik
                                    </div>
                                    <div class="col isi">
                                        <?=number_format($rowitem->luas_lokasi). ' ha'?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Total Luas Titik Terdata
                                    </div>
                                    <div class="col isi">
                                        <?=number_format($rowitem->total_titik). ' ha'?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Jumlah Titik Terdata
                                    </div>
                                    <div class="col isi mb-3">
                                        <?=$rowitem->jumlah_titik?>
                                    </div>
                                </div>
                                <h5>Kondisi Titik</h5>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Sangat Baik
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->jumlah_sangat_baik?>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Baik
                                    </div>
                                     <div class="col isi">
                                        <?=$rowitem->jumlah_baik?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Cukup
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->jumlah_cukup?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Kurang
                                    </div>
                                    <div class="col isi mb-3">
                                        <?=$rowitem->jumlah_kurang?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Deskripsi Lokasi
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->deskripsi_lokasi?>
                                    </div>
                                </div>
                                <div class="row  mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Foto Lokasi
                                    </div>
                                    <div class="col isi">
                                        <img src="<?=$rowitem->foto_lokasi?>?<?php if ($status='nochange'){echo time();}?>" width="150px">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Kontak Lokasi
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->kontak_lokasi?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Nama Bank
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->nama_bank?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Nama Rekening
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->nama_rekening?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Nomor Rekening
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->nomor_rekening?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--collapse end -->
                                </td>
                            </tr>
                           <?php } ?>
                    </tbody>
                  </table>

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
