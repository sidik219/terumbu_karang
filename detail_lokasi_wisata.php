<?php include 'build/config/connection.php';
session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

  // else{
  //     $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
  // }

    $id_wisata = $_GET['id_wisata'];

    $sqllokasi = 'SELECT * FROM t_wisata
                    LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                    WHERE id_wisata = :id_wisata';

    $stmt = $pdo->prepare($sqllokasi);
    $stmt->execute(['id_wisata' => $id_wisata]);
    $row = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rincian Wisata - TKJB</title>
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
                        <a class="btn btn-primary float-right" href="pilih_lokasi_wisata.php" role="button">< Wisata Lainnya</a>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
            <?php //if($_SESSION['level_user'] == '2') { ?>
                <div class="container-fluid">
                    <?php
                     //$index = 0;
                        foreach ($row as $rowitem) { ?>

                          <div class="row card-donasi p-2 m-0">
                            <div class="col-6 text-center">
                              <img class="w-50" src="<?=$rowitem->foto_wisata?>">
                            </div>
                            <div class="col">
                              <div class="row p-2"><span><b>Nama Lokasi</b></span>
                              <div class="col-12 p-0 border-bottom"><span class="text-xl text-warning"><?=$rowitem->nama_lokasi?></span></div></div>

                              <div class="row p-2 border-bottom"><p class="">
                                    <b>Alamat:</b> <?=$rowitem->deskripsi_lokasi?>
                                </p></div>
                              <div class="row p-2 border-bottom"><p class="">
                                    <b>Daftar Wisata:</b> <?=$rowitem->judul_wisata?>
                                </p></div>
                              <div class="row p-2 border-bottom"><p class="">
                                    <b>Harga:</b> Rp. <?=number_format($rowitem->biaya_wisata, 0)?>
                                </p></div>
                              <div class="row p-2 border-bottom"><p class="">
                                    <b>Deskripsi:</b> <?=$rowitem->deskripsi_wisata?>
                                </p></div>
                              <div class="row"><a class="btn btn-primary btn-lg btn-block mb-1"
                                href="reservasi_wisata.php?id_wisata=<?=$rowitem->id_wisata?>_&status=review_reservasi" style="color: white;">Wisata Sekarang</a></div>

                            </div>
                          </div>



                        <div class="row mt-0">
                          <div class="col p-3 shadow rounded"><b class="text-lg"><i class="text-primary nav-icon fas fa-info-circle"></i> Tentang Paket Wisata ini</b><br>
                          <?php
                            if($rowitem->deskripsi_panjang_wisata == NULL){
                              echo "<span class='text-muted mt-3'>Informasi tidak tersedia</span>";
                            }
                            else{
                               echo $rowitem->deskripsi_panjang_wisata;
                            }
                          ?>
                          </div>
                        </div>


                    <?php  } ?>
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
    <!-- jQuery UI 1.11.4 -->
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</body>
</html>
