<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if (isset($_GET['status'])) {
    $status = $_GET['status'];
}
$sqlviewwilayah = 'SELECT * FROM t_wilayah
                    LEFT JOIN t_user ON t_wilayah.id_user_pengelola = t_user.id_user
                    ORDER BY nama_wilayah';
$stmt = $pdo->prepare($sqlviewwilayah);
$stmt->execute();
$row = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Wilayah - GoKarang</title>
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
                            <h4><span class="align-middle font-weight-bold">Kelola Wilayah</span></h4>
                        </div>
                        <div class="col">

                            <a class="btn btn-primary float-right" href="input_wilayah.php" role="button">Input Data Baru (+)</a>

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
                                <th scope="col">ID Wilayah</th>
                                <th scope="col">Nama Wilayah</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            foreach ($row as $rowitem) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $rowitem->id_wilayah ?></th>
                                    <td><?= $rowitem->nama_wilayah ?></td>
                                    <td>
                                        <a href="atur_pengelola_wilayah.php?id_wilayah=<?= $rowitem->id_wilayah ?>" class="mr-3 btn btn-act"><i class="fas fa-id-badge"></i> Atur Pengelola</a>
                                        <a href="edit_wilayah.php?id_wilayah=<?= $rowitem->id_wilayah ?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                        <a onclick="return konfirmasiHapusPengadaan(event)" href="hapus.php?type=wilayah&id_wilayah=<?= $rowitem->id_wilayah ?>" class="far fa-trash-alt btn btn-act"></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3">
                                        <!--collapse start -->
                                        <div class="row  m-0">
                                            <div class="col-12 cell detailcollapser<?= $rowitem->id_wilayah ?>" data-toggle="collapse" data-target=".cell<?= $rowitem->id_wilayah ?>, .contentall<?= $rowitem->id_wilayah ?>">
                                                <p class="fielddetail<?= $rowitem->id_wilayah ?> btn btn-act">
                                                    <i class="icon fas fa-chevron-down"></i>
                                                    Rincian Wilayah
                                                </p>
                                            </div>
                                            <div class="col-12 cell<?= $rowitem->id_wilayah ?> collapse contentall<?= $rowitem->id_wilayah ?> border rounded shadow-sm p-3">
                                                <div class="row">

                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-3 kolom font-weight-bold">
                                                        Deskripsi Wilayah
                                                    </div>
                                                    <div class="col isi">
                                                        <?= $rowitem->deskripsi_wilayah ?>
                                                    </div>
                                                </div>
                                                <div class="row  mb-3">
                                                    <div class="col-md-3 kolom font-weight-bold">
                                                        Foto Wilayah
                                                    </div>
                                                    <div class="col isi">
                                                        <img src="<?= $rowitem->foto_wilayah ?>?<?php if ($status = 'nochange') {
                                                                                                    echo time();
                                                                                                } ?>" width="100px">
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <!--collapse end -->
                                    </td>
                                </tr>

                            <?php } ?>
                        </tbody>

                        <script>
                            function konfirmasiHapusPengadaan(event) {
                                jawab = true
                                jawab = confirm('Yakin ingin menghapus? Data pengadaan akan hilang permanen!')

                                if (jawab) {
                                    // alert('Lanjut.')
                                    return true
                                } else {
                                    event.preventDefault()
                                    return false

                                }
                            }
                        </script>
                    </table>
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
    </div>

    <script>
        $(document).ready(function() {
            $("button").click(function() {
                $("p").slideToggle();
            });
        });
    </script>

</body>

</html>