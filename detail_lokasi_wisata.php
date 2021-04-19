<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

  // else{
  //     $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
  // }

    $id_paket_wisata = $_GET['id_paket_wisata'];

    $sqldetailpaket = 'SELECT * FROM t_wisata
                LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata';

    $stmt = $pdo->prepare($sqldetailpaket);
    $stmt->execute(['id_paket_wisata' => $_GET['id_paket_wisata']]);
    $rowwisata = $stmt->fetchAll();
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
            <a href="index_admin.php" class="brand-link">
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
                        <a class="btn btn-warning btn-back" href="#" onclick="history.back()"><i class="fas fa-angle-left"></i>Wisata Lainnya</a>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
            <?php if($_SESSION['level_user'] == '1') { ?>
                <div class="container-fluid">
                    <?php
                     //$index = 0;
                        foreach ($rowwisata as $rowitem) { ?>
                        <div class="row card-donasi p-2 m-0">
                            <div class="col-6 text-center">
                                <img class="w-50" src="<?=$rowitem->foto_wisata?>">
                            </div>
                            <div class="col">
                                <div class="row p-2">
                                <div class="col-12 p-0 border-bottom"><span class="text-xl text-warning"><?=$rowitem->nama_paket_wisata?></span></div></div>

                                <div class="row p-2 border-bottom"><p class="">
                                    <i class="text-danger fas fa-map-marker-alt"></i>
                                    <b>Alamat:</b> <?=$rowitem->deskripsi_lokasi?>
                                </p></div>
                                <div class="row p-2 border-bottom"><p class="">
                                    <i class="text-primary fas fa-umbrella-beach"></i>
                                    <b>Nama Lokasi:</b> <?=$rowitem->nama_lokasi?>
                                </p></div>

                                <div class="row p-2 border-bottom"><p class="">
                                    <i class="text-info fas fa-luggage-cart"></i>
                                    <label>Paket Wisata:</label>
                                    <div class="divTable">
                                        <div class="divTableBody">
                                            <div class="divTableRow">
                                                <div class="divTableCell-1">
                                                    <i class="text-info fas fa-arrow-circle-right"></i>                             
                                                    <?=$rowitem->judul_wisata?>
                                                </div>
                                                <div class="divTableCell-2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </p></div>

                                <?php
                                $sqlviewfasilitas = 'SELECT SUM(biaya_fasilitas) AS total_biaya_fasilitas, nama_fasilitas, biaya_fasilitas 
                                                    FROM tb_fasilitas_wisata 
                                                    LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                    LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                    WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                    AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                $stmt = $pdo->prepare($sqlviewfasilitas);
                                $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                $rowfasilitas = $stmt->fetchAll();

                                foreach ($rowfasilitas as $fasilitas) { ?>
                                <div class="row p-2 border-bottom"><p class="">
                                    <i class="text-info fas fa-cubes"></i>
                                    <label>Fasilitas Wisata:</label>
                                    <div class="divTable">
                                        <div class="divTableBody">
                                            <div class="divTableRow">
                                                <div class="divTableCell-1">
                                                    <?php
                                                    $sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata 
                                                                        LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                                        LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                                        WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                                        AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                                    $stmt = $pdo->prepare($sqlviewfasilitas);
                                                    $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                                    $rowfasilitas = $stmt->fetchAll();

                                                    foreach ($rowfasilitas as $allfasilitas) { ?> 
                                                    <i class="text-info fas fa-arrow-circle-right"></i>                 
                                                    <?=$allfasilitas->nama_fasilitas?><br>
                                                    <?php } ?>
                                                </div>
                                                <div class="divTableCell-2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </p></div>

                                <div class="row p-2 border-bottom"><p class="">
                                    <i class="text-success fas fa-money-bill-wave"></i>
                                    <b>Total Paket Wisata:</b><br> Rp. <?=number_format($fasilitas->total_biaya_fasilitas, 0)?>
                                </p></div>
                                <?php } ?>

                                <div class="row p-2 border-bottom"><p class="">
                                    <i class="text-warning far fa-bookmark"></i>
                                    <b>Deskripsi:</b> <?=$rowitem->deskripsi_wisata?>
                                </p></div>
                                <div class="row"><a class="btn btn-primary btn-lg btn-block mb-1"
                                href="reservasi_wisata.php?id_paket_wisata=<?=$rowitem->id_paket_wisata?>_&status=review_reservasi" style="color: white;">Wisata Sekarang</a></div>

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
    <!-- jQuery UI 1.11.4 -->
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</body>
</html>
