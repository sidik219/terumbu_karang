<?php include 'build/config/connection.php';
session_start();

if (isset($_SESSION['level_user']) == 0) {
    header('location: login.php');
}

$sqlviewwisata = 'SELECT * FROM t_wisata
                  LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                  ORDER BY id_wisata DESC';
$stmt = $pdo->prepare($sqlviewwisata);
$stmt->execute();
$row = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Wisata - TKJB</title>
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
                    <!-- SESSION lvl Untuk Lokasi -->
                    <?php if($_SESSION['level_user'] == '3') { ?>
                        <li class="nav-item"> <!-- Wilayah & Lokasi -->
                           <a href="dashboard_admin.php" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_donasi.php" class="nav-link">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Kelola Donasi </p>
                            </a>
                        </li>
                        <li class="nav-item menu-open"> <!-- Lokasi -->
                            <a href="kelola_wisata.php" class="nav-link active">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Kelola Wisata </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_reservasi_wisata.php" class="nav-link">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p> Kelola Reservasi </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Wilayah & Lokasi -->
                            <a href="kelola_lokasi.php" class="nav-link">
                                <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                                <p> Kelola Lokasi </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_titik.php" class="nav-link">
                                 <i class="nav-icon fas fa-crosshairs"></i>
                                 <p> Kelola Titik </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_batch.php" class="nav-link">
                                  <i class="nav-icon fas fa-boxes"></i>
                                  <p> Kelola Batch </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_pemeliharaan.php" class="nav-link">
                                  <i class="nav-icon fas fa-heart"></i>
                                  <p> Kelola Pemeliharaan </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_user.php" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p> Kelola User </p>
                            </a>
                        </li>
                    <?php } ?>
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
                            <h4><span class="align-middle font-weight-bold">Kelola Wisata</span></h4>
                        </div>
                        <div class="col">

                        <a class="btn btn-primary float-right" href="input_wisata.php" role="button">Input Data Baru (+)</a>

                        </div>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                <?php if($_SESSION['level_user'] == '3') { ?>
                     <table class="table table-striped">
                     <thead>
                            <tr>
                            <th scope="col">ID Wisata</th>
                            <th scope="col">ID Lokasi</th>
                            <th scope="col">Judul Wisata</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php foreach ($row as $rowitem) { ?>
                            <tr>
                              <th scope="row"><?=$rowitem->id_wisata?></th>
                              <td><?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></td>
                              <td><?=$rowitem->judul_wisata?></td>
                              <td><?=$rowitem->status_aktif?></td>
                              <td>
                                <a href="edit_wisata.php?id_wisata=<?=$rowitem->id_wisata?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                <a href="hapus.php?type=wisata&id_wisata=<?=$rowitem->id_wisata?>" class="far fa-trash-alt btn btn-act"></a>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                <!--collapse start -->
                                <div class="row  m-0">
                                    <div class="col-12 cell detailcollapser<?=$rowitem->id_wisata?>"
                                        data-toggle="collapse"
                                        data-target=".cell<?=$rowitem->id_wisata?>, .contentall<?=$rowitem->id_wisata?>">
                                        <p class="fielddetail<?=$rowitem->id_wisata?> btn btn-act">
                                            <i class="icon fas fa-chevron-down"></i>
                                            Rincian Wisata</p>
                                    </div>
                                    <div class="col-12 cell<?=$rowitem->id_wisata?> collapse contentall<?=$rowitem->id_wisata?> border rounded shadow-sm p-3">

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Deskripsi Wisata
                                        </div>
                                        <div class="col isi">
                                            <?=$rowitem->deskripsi_wisata?>
                                        </div>
                                    </div>

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Biaya Wisata
                                        </div>
                                        <div class="col isi">
                                            Rp. <?=number_format($rowitem->biaya_wisata, 0)?>
                                        </div>
                                    </div>

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Paket donasi
                                        </div>

                                        <?php
                                        $sqlviewpaket = 'SELECT * FROM tb_paket_donasi
                                                            LEFT JOIN t_wisata ON tb_paket_donasi.id_wisata = t_wisata.id_wisata
                                                            WHERE t_wisata.id_wisata = :id_wisata
                                                            AND t_wisata.id_wisata = tb_paket_donasi.id_wisata';

                                        $stmt = $pdo->prepare($sqlviewpaket);
                                        $stmt->execute(['id_wisata' => $rowitem->id_wisata]);
                                        $rowpersentase = $stmt->fetchAll();

                                        foreach ($rowpersentase as $rowpaket) { ?>
                                        <div class="col isi">
                                            <span class="badge badge-pill badge-success mr-2">
                                                <?=number_format($rowpaket->persentase_paket_donasi, 0)?>%
                                            </span>
                                        </div>
                                        <?php } ?>

                                    </div>

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Foto Wisata
                                        </div>
                                        <div class="col isi">
                                            <img src="<?=$rowitem->foto_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
                                        </div>
                                    </div>

                                </div>
                                <!--collapse end -->
                                </td>
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
<div>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>                                        
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</div>

</body>
</html>
