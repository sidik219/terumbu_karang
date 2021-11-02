<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata
                    LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                    LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                    ORDER BY id_fasilitas_wisata DESC';
$stmt = $pdo->prepare($sqlviewfasilitas);
$stmt->execute();
$rowfasilitas = $stmt->fetchAll();

function ageCalculator($dob)
{
    $birthdate = new DateTime($dob);
    $today   = new DateTime('today');
    $ag = $birthdate->diff($today)->y;
    $mn = $birthdate->diff($today)->m;
    $dy = $birthdate->diff($today)->d;
    if ($mn == 0) {
        return "$dy Hari";
    } elseif ($ag == 0) {
        return "$mn Bulan  $dy Hari";
    } else {
        return "$ag Tahun $mn Bulan $dy Hari";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Wisata - GoKarang</title>
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
    <link rel="stylesheet" type="text/css" href="css/style-card.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="js/trumbowyg/dist/ui/trumbowyg.min.css">
    <script src="js/trumbowyg/dist/trumbowyg.js"></script>
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
                    <a class="btn btn-outline-primary" href="kelola_wisata.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Data Fasilitas Wisata</span></h4>
                            <ul class="app-breadcrumb breadcrumb" style="margin-bottom: 20px;">
                                <li class="breadcrumb-item">
                                    <a href="kelola_wisata.php" class="non">Kelola Wisata</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="kelola_fasilitas_wisata.php" class="tanda">Data Fasilitas Wisata</a>
                                </li>
                            </ul>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                    if (!empty($_GET['status'])) {
                        if ($_GET['status'] == 'addsuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                        Input data fasilitas wisata berhasil ditambahkan!
                                        </div>';
                        }
                    }
                    ?>
                    <div align="right">
                        <!-- Cetak Laporan Fasilitas -->
                        <a class="btn btn-success" href="laporan_wisata.php?type=fasilitas">
                            <i class="fas fa-file-excel"></i> Laporan Data Fasilitas</a>

                        <a class="btn btn-outline-primary" href="input_fasilitas_wisata.php" style="margin-top: 5px; margin-bottom: 5px;">
                            Selanjutnya Input Fasilitas <i class="fas fa-angle-right"></i></a>
                    </div>

                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">ID Fasilitas</th>
                                <th scope="col">Nama Fasilitas</th>
                                <th scope="col">Biaya Fasilitas</th>
                                <th scope="col">Status Kerjasama</th>
                                <th scope="col">Status Pengadaan</th>
                                <th scope="col">Update Terakhir</th>
                                <!-- <th scope="col">Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rowfasilitas as $fasilitas) {
                                $truedate = strtotime($fasilitas->update_terakhir); ?>
                                <tr>
                                    <th scope="row"><?= $fasilitas->id_fasilitas_wisata ?></th>
                                    <td><?= $fasilitas->pengadaan_fasilitas ?></td>
                                    <td>Rp. <?= number_format($fasilitas->biaya_kerjasama, 0) ?></td>
                                    <td>
                                        <?php if ($fasilitas->status_kerjasama == "Melakukan Kerjasama") { ?>
                                            <span class="badge badge-pill badge-success"><?= $fasilitas->status_kerjasama ?></span>
                                        <?php } elseif ($fasilitas->status_kerjasama == "Tidak Melakukan Kerjasama") { ?>
                                            <span class="badge badge-pill badge-warning"><?= $fasilitas->status_kerjasama ?></span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($fasilitas->status_pengadaan == "Baik") { ?>
                                            <span class="badge badge-pill badge-success"><?= $fasilitas->status_pengadaan ?></span>
                                        <?php } elseif ($fasilitas->status_pengadaan == "Rusak") { ?>
                                            <span class="badge badge-pill badge-warning"><?= $fasilitas->status_pengadaan ?></span>
                                        <?php } elseif ($fasilitas->status_pengadaan == "Hilang") { ?>
                                            <span class="badge badge-pill badge-danger"><?= $fasilitas->status_pengadaan ?></span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <small class="text-muted"><b>Update Terakhir</b>
                                            <br><?= strftime('%A, %d %B %Y', $truedate) . '<br> (' . ageCalculator($fasilitas->update_terakhir) . ' yang lalu)'; ?></small>
                                    </td>
                                    <!-- <td>
                                    <a href="edit_fasilitas_wisata.php?id_fasilitas_wisata=<?= $fasilitas->id_fasilitas_wisata ?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                </td> -->
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
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.js"></script>
    </div>
    <!-- Import Trumbowyg font size JS at the end of <body>... -->
    <script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
</body>

</html>