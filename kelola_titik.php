<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

if ($level_user == 2) {
    $id_wilayah = $_SESSION['id_wilayah_dikelola'];
    $extra_query = " AND t_wilayah.id_wilayah = $id_wilayah ";
    $extra_query_noand = " t_wilayah.id_wilayah = $id_wilayah ";
    $wilayah_join = " LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_donasi.id_lokasi
                    LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";
    $extra_query_k_lok = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand_where = " WHERE id_wilayah = $id_wilayah ";
    $extra_query_k_titik = " WHERE t_lokasi.id_wilayah = $id_wilayah ";
} else if ($level_user == 3) {
    $id_lokasi = $_SESSION['id_lokasi_dikelola'];
    $extra_query = " AND id_lokasi = $id_lokasi ";
    $extra_query_k_lok = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand = " id_lokasi = $id_lokasi ";
    $extra_query_noand_where = " WHERE id_lokasi = $id_lokasi ";
    $wilayah_join = " ";
    $extra_query_k_titik = " WHERE t_lokasi.id_lokasi = $id_lokasi ";
} else if ($level_user == 4) {
    $extra_query = "  ";
    $extra_query_noand = "  ";
    $wilayah_join = " ";
    $extra_query_k_lok = " ";
    $extra_query_noand_where = " ";
}

$sqlviewtitik = 'SELECT *, t_titik.latitude AS latitude_titik,
                          t_titik.longitude AS longitude_titik
            FROM t_titik
            LEFT JOIN t_lokasi ON t_titik.id_lokasi = t_lokasi.id_lokasi
            LEFT JOIN t_zona_titik ON t_titik.id_zona_titik = t_zona_titik.id_zona_titik ' . $extra_query_k_titik;
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
                          Update data berhasil
                      </div>';
                        } else if ($_GET['status'] == 'addsuccess') {
                            echo '<div class="alert alert-success" role="alert">
                          Data baru berhasil ditambahkan
                      </div>';
                        } else if ($_GET['status'] == 'deletesuccess') {
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
                            <?php //if($_SESSION['level_user'] == '3') { 
                            ?>
                            <a class="btn btn-primary float-right" href="input_titik.php" role="button">Input Data Baru (+)</a>
                            <?php //} 
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
                                <th scope="col">ID Titik</th>
                                <th scope="col">ID Lokasi</th>
                                <th class="text-right" scope="col">Koordinat</th>
                                <th class="text-right" scope="col">Luas Titik (ha)</th>
                                <th scope="col">Zona</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($row as $rowitem) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $rowitem->id_titik ?><br><?= $rowitem->keterangan_titik ?></th>
                                    <td>ID <?= $rowitem->id_lokasi ?> - <?= $rowitem->nama_lokasi ?></td>
                                    <td class="text-right">Lat: <?= $rowitem->latitude_titik ?><br> Long: <?= $rowitem->longitude_titik ?><br><a target="_blank" href="http://maps.google.com/maps/search/?api=1&query=<?= $rowitem->latitude_titik ?>,<?= $rowitem->longitude_titik ?>&zoom=8" class="btn btn-act"><i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a></td>
                                    <td class="text-right"><?= number_format($rowitem->luas_titik) ?></td>
                                    <td><?= $rowitem->nama_zona_titik ?></td>
                                    <td>
                                        <a href="edit_titik.php?id_titik=<?= $rowitem->id_titik ?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                        <a onclick="return konfirmasiHapusPengadaan(event)" href="hapus.php?type=titik&id_titik=<?= $rowitem->id_titik ?>" class="far fa-trash-alt btn btn-act"></a>
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

    <script>
        function konfirmasiHapusPengadaan(event) {
            jawab = true
            jawab = confirm('Yakin ingin menghapus? Data Titik akan hilang permanen!')

            if (jawab) {
                // alert('Lanjut.')
                return true
            } else {
                event.preventDefault()
                return false

            }
        }
    </script>
</body>

</html>