<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 3)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewtitik = 'SELECT * from t_konten';
$stmt = $pdo->prepare($sqlviewtitik);
$stmt->execute();
$row = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Detail Titik - GoKarang</title>
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
    <?= $favicon ?>
    <!-- tooltips -->
    <link rel="stylesheet" type="text/css" href="css/tooltips.css">
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

                    <?php
                    if (!empty($_GET['status'])) {
                        if ($_GET['status'] == 'updatesuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                        Update Kelola Konten wisata berhasil!
                                        </div>';
                        } else if ($_GET['status'] == 'addsuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                        Kelola Konten berhasil ditambahkan!
                                        </div>';
                        }
                    }
                    ?>


                    <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Ketentuan Wisata</span></h4>
                            <p>
                                Beri Ketentuan Berwisata Untuk Para Calon Wisatawan Di Wisata Anda
                            </p>
                            <p class="small"><b>Ketentuan Ini Akan Berada Di Halaman Info Wisata</b>
                                <span class="mytooltip tooltip-effect-1">
                                    <span class="fas fa-info-circle"></span>
                                    <span class="tooltip-content clearfix">
                                        <img src="./images/tooltips/ketentuan.png">
                                        <span class="tooltip-text">Contoh seperti gambar disamping</span>
                                    </span>
                                </span>
                            </p>
                        </div>
                        <div class="col">
                            <?php if ($row == null) {
                            ?>
                                <a class="btn btn-primary float-right" href="input_konten_ketentuan.php" role="button">Input Data Baru (+)</a>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <!-- <th scope="col">ID Konten</th> -->
                                <th scope="col">Termasuk Biaya</th>
                                <th scope="col">Diluar Biaya</th>
                                <th scope="col" colspan="2">Syarat & Ketentuan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($row as $rowitem) {
                            ?>
                                <tr>
                                    <!-- <th scope="row"><?= $rowitem->id_konten ?></th> -->
                                    <td class="max-length2"><?= $rowitem->sdh_biaya ?></td>
                                    <td class="max-length2"><?= $rowitem->blm_biaya ?></td>
                                    <td class="max-length2" colspan="2"><?= $rowitem->sk ?></td>
                                    <td>
                                        <a href="edit_konten_ketentuan.php?id_titik=<?= $rowitem->id_konten ?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <!-- <p class="small"><b>*Ketentuan Ini Akan Berada Di Halaman Info Wisata</b></p> -->
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


</body>

</html>