<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewtitik = 'SELECT *, t_titik.latitude AS latitude_titik,
                          t_titik.longitude AS longitude_titik
            FROM t_titik
            LEFT JOIN t_lokasi ON t_titik.id_lokasi = t_lokasi.id_lokasi
            LEFT JOIN t_zona_titik ON t_titik.id_zona_titik = t_zona_titik.id_zona_titik';
$stmt = $pdo->prepare($sqlviewtitik);
$stmt->execute();
$row = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Detail Titik - TKJB</title>
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
            <div class="content-header">
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


                <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Kelola Titik</span></h4>
                        </div>
                        <div class="col">
                        <?php if($_SESSION['level_user'] == '3') { ?>
                        <a class="btn btn-primary float-right" href="input_titik.php" role="button">Input Data Baru (+)</a>
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
                <?php if($_SESSION['level_user'] == '2' || $_SESSION['level_user'] == '3') { ?>
                    <table class="table table-striped">
                     <thead>
                            <tr>
                            <th scope="col">ID Titik</th>
                            <th scope="col">ID Lokasi</th>
                            <th class="text-right" scope="col">Koordinat</th>
                            <th class="text-right" scope="col">Luas Titik (m<sup>2</sup>)</th>
                            <th scope="col">Zona</th>
                            <?php if($_SESSION['level_user'] == '3') { ?>
                            <th scope="col">Aksi</th>
                            <?php } ?>
                            </tr>
                          </thead>
                    <tbody>
                            <?php foreach ($row as $rowitem) {
                            ?>
                          <tr>
                              <th scope="row"><?=$rowitem->id_titik?><br><?=$rowitem->keterangan_titik?></th>
                              <td>ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></td>
                              <td class="text-right">Lat: <?=$rowitem->latitude_titik?><br> Long: <?=$rowitem->longitude_titik?><br><a target="_blank" href="http://maps.google.com/maps/search/?api=1&query=<?=$rowitem->latitude_titik?>,<?=$rowitem->longitude_titik?>&zoom=8"
                                                                                                                                      class="btn btn-act"><i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a></td>
                              <td class="text-right"><?=number_format($rowitem->luas_titik)?></td>
                              <td><?=$rowitem->nama_zona_titik?></td>
                              <?php if($_SESSION['level_user'] == '3') { ?>
                              <td>
                                <a href="edit_titik.php?id_titik=<?=$rowitem->id_titik?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                <a href="hapus.php?type=titik&id_titik=<?=$rowitem->id_titik?>" class="far fa-trash-alt btn btn-act"></a>
                              </td>
                              <?php } ?>
                          </tr>
                          <?php } ?>
                    </tbody>
                  </table>
                <?php } ?>
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


</body>
</html>
