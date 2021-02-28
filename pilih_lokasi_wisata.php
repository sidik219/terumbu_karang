<?php include 'build/config/connection.php';
session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

  // else{
  //     $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
  // }

    if($_GET['id_lokasi']){
        $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
    }
    else if(!$_GET['id_lokasi' && !$_SESSION['id_lokasi']]){
        header("Location: map.php?aksi=wisata");
    }

    $sqlwisata = 'SELECT * FROM t_wisata
                LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE t_wisata.id_lokasi = :id_lokasi';

    $stmt = $pdo->prepare($sqlwisata);
    $stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
    $rowwisata = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pilih Wisata - TKJB</title>
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
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style-card.css">
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
            <a href="dashboard_user.php" class="brand-link">
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
                        <li class="nav-item  ">
                           <a href="dashboard_user.php" class="nav-link ">
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
                        <li class="nav-item menu-open">
                           <a href="reservasi_saya.php" class="nav-link active">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Reservasi Saya  </p>
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

                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
            <?php //if($_SESSION['level_user'] == '2') { ?>
                <div class="container-fluid">
                    <h3>Pilihan Wisata</h3>
                    <a href="map.php?aksi=wisata"><button class="btn btn-warning btn-back" type="button">
                        <i class="fas fa-angle-left"></i> Ganti Lokasi Wisata</button></a>
                    <div class="row">
                    <?php
                    foreach ($rowwisata as $rowitem) {
                        if ($rowitem->status_aktif == "Aktif") { ?>

                        <div class="col-md-4">
                            <div class="card card-pilihan mb-4 shadow-sm">
                            <a href="detail_lokasi_wisata.php?id_wisata=<?=$rowitem->id_wisata?>">
                                <img class="card-img-top" height="100%" src="<?=$rowitem->foto_wisata?>">
                            </a>
                                <div class="card-body card-body-costom">
                                    <p class="card-title"><h5 class="font-weight-bold"><?=$rowitem->nama_lokasi?></h5></p>
                                    <p class="card-text"><?=$rowitem->judul_wisata?></p>
                                    <a class="btn btn-primary btn-lg btn-block mb-4" href="detail_lokasi_wisata.php?id_wisata=<?=$rowitem->id_wisata?>" class="card-donasi__cta" style="color: white;">Pilih Wisata</a>
                                </div>
                            </div>
                        </div>


                        <?php } ?>
                    <?php } ?>
                    </div>
                </div>
            <?php //} ?>
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


    <script>


    </script>
</body>
</html>
