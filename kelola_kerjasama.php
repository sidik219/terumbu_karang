<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

$sqlkerjasama = 'SELECT * FROM t_kerjasama
                LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                ORDER BY id_kerjasama DESC';
$stmt = $pdo->prepare($sqlkerjasama);
$stmt->execute();
$rowkerjasama = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Kerjasama Fasilitas - GoKarang</title>
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
    <style>
        td {
            text-align: center;
        }

        th {
            text-align: center;
        }
    </style>
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
                    <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Kelola Kerjasama Fasilitas</span></h4>

                            <!-- Fitur Next -->
                            <a class="btn btn-success" href="kelola_pengadaan_fasilitas.php" style="margin-bottom: 10px;">
                                <i class="fas fa-arrow-left"></i> Kembali</a>
                            <a class="btn btn-success" href="kelola_asuransi.php" style="margin-bottom: 10px;">
                                Selanjutnya <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="col">
                            <a class="btn btn-primary float-right" href="input_kerjasama.php" role="button">Input Data Baru (+)</a>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <?php
                        if (!empty($_GET['status'])) {
                            if ($_GET['status'] == 'updatesuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Data Kerjasama Fasilitas berhasil diupdate!
                                        </div>';
                            } else if ($_GET['status'] == 'addsuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Data Kerjasama Fasilitas berhasil ditambahkan!
                                        </div>';
                            } else if ($_GET['status'] == 'deletesuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Data Kerjasama Fasilitas berhasil dihapus!
                                        </div>';
                            }
                        }
                        ?>

                        <table class="table table-striped table-responsive-sm">
                            <thead>
                                <tr>
                                    <th scope="col">ID Kerjasama</th>
                                    <th scope="col">Pihak Ketiga</th>
                                    <th scope="col">Nama Fasilitas</th>
                                    <th scope="col">Status Kerjasama</th>
                                    <th scope="col">Pembagian Kerjasama</th>
                                    <th scope="col">Biaya Fasilitas</th>
                                    <th scope="col">Pembagian Hasil</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rowkerjasama as $kerjasama) {
                                    $persentasi = round($kerjasama->pembagian_kerjasama * 100, 2);
                                ?>
                                    <tr>
                                        <th scope="row"><?= $kerjasama->id_kerjasama ?></th>
                                        <?php if ($kerjasama->status_kerjasama == "Tidak Melakukan Kerjasama") : ?>
                                            <td>Tidak Ada Pihak Ketiga</td>
                                        <?php elseif ($kerjasama->pihak_ketiga != null) : ?>
                                            <td><?= $kerjasama->pihak_ketiga ?></td>
                                        <?php else : ?>
                                            <td>Data Kosong</td>
                                        <?php endif ?>
                                        <td><?= $kerjasama->pengadaan_fasilitas ?></td>
                                        <td>
                                            <?php if ($kerjasama->status_kerjasama == "Melakukan Kerjasama") { ?>
                                                <span class="badge badge-pill badge-success">
                                                    <?= $kerjasama->status_kerjasama ?>
                                                </span>
                                            <?php } elseif ($kerjasama->status_kerjasama == "Tidak Melakukan Kerjasama") { ?>
                                                <p style="font-size: 14px;">
                                                    <span class="badge badge-pill badge-warning">
                                                        <?= $kerjasama->status_kerjasama ?>
                                                    </span>
                                                </p>
                                            <?php } ?>
                                        </td>
                                        <td><?= $persentasi ?>%</td>
                                        <td>Rp. <?= number_format($kerjasama->biaya_kerjasama, 0) ?></td>
                                        <td>Rp. <?= number_format($kerjasama->pembagian_hasil, 0) ?></td>
                                        <td>
                                            <a href="edit_kerjasama.php?id_kerjasama=<?= $kerjasama->id_kerjasama ?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                            <a onclick="return konfirmasiHapusKerjasama(event)" href="hapus.php?type=kerjasama&id_kerjasama=<?= $kerjasama->id_kerjasama ?>" class="far fa-trash-alt btn btn-act"></a>
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
    <div>
        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.js"></script>
        <!-- Konfirmasi Hapus -->
        <script>
            function konfirmasiHapusKerjasama(event) {
                jawab = true
                jawab = confirm('Yakin ingin menghapus? Data Kerjasama akan hilang permanen!')

                if (jawab) {
                    // alert('Lanjut.')
                    return true
                } else {
                    event.preventDefault()
                    return false

                }
            }
        </script>

    </div>

</body>

</html>