<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_asuransi = $_GET['id_asuransi'];

// Asuransi
$sqleditasuransi = 'SELECT * FROM t_asuransi
                    LEFT JOIN t_perusahaan_asuransi ON t_asuransi.id_perusahaan = t_perusahaan_asuransi.id_perusahaan
                    WHERE id_asuransi = :id_asuransi';

$stmt = $pdo->prepare($sqleditasuransi);
$stmt->execute(['id_asuransi' => $id_asuransi]);
$asuransi = $stmt->fetch();

if (isset($_POST['submit'])) {
    $id_perusahaan  = $_POST['nama_pihak'];
    $notlp_asuransi = $_POST['notlp_asuransi'];

    // Asuransi
    $id_asuransi = $_POST["id_asuransi"];
    $nama_asuransi = $_POST['nama_asuransi'];
    $biaya_asuransi = $_POST['biaya_asuransi'];

    $hitung = count($id_asuransi);
    for ($x = 0; $x < $hitung; $x++) {
        $sqlasuransi = "UPDATE t_asuransi
        SET id_perusahaan = :id_perusahaan,
            nama_asuransi = :nama_asuransi,
            biaya_asuransi = :biaya_asuransi
        WHERE id_asuransi = :id_asuransi";

        $stmt = $pdo->prepare($sqlasuransi);
        $stmt->execute([
            'id_asuransi' => $id_asuransi[$x],
            'nama_asuransi' => $nama_asuransi[$x],
            'biaya_asuransi' => $biaya_asuransi[$x],
            'id_perusahaan' => $id_perusahaan
        ]);
    }

    //Insert t_perusahaan_asuransi
    $sqlperusahaan = "UPDATE t_perusahaan_asuransi
                    SET notlp_asuransi = :notlp_asuransi
                    WHERE id_perusahaan = :id_perusahaan";

    $stmt = $pdo->prepare($sqlperusahaan);
    $stmt->execute([
        'notlp_asuransi' => $notlp_asuransi,
        'id_perusahaan' => $id_perusahaan
    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: edit_asuransi.php?status=updatefailed&id_asuransi=$id_asuransi");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_asuransi.php?status=updatesuccess");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Asuransi - GoKarang</title>
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
                    <a class="btn btn-outline-primary" href="kelola_asuransi.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Edit Data Asuransi</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                    if (!empty($_GET['status'])) {
                        if ($_GET['status'] == 'updatefailed') {
                            echo '<div class="alert alert-success" role="alert">
                                        Data Asuransi Tidak Ada yang Berubah
                                        </div>';
                        }
                    }
                    ?>
                    <form action="" enctype="multipart/form-data" method="POST">
                        <!-- Hidden Id Asuransi -->
                        <input type="hidden" name="id_asuransi" value="<?= $asuransi->id_asuransi ?> - <?= $asuransi->nama_asuransi ?>">

                        <div class="form-group">
                            <label for="nama_asuransi">Nama Asuransi</label><br>
                            <small style="color: red;">*Jika nama asuransi tidak tahu bisa dikosongkan dengan (-)</small>
                            <input type="text" name="nama_asuransi[]" value="<?= $asuransi->nama_asuransi ?>" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="biaya_asuransi">Biaya Asuransi</label>
                            <input type="number" name="biaya_asuransi[]" value="<?= $asuransi->biaya_asuransi ?>" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nama_pihak">Pihak Asuransi</label>
                            <select class="form-control" name="nama_pihak" id="nama_pihak" required>
                                <option selected disabled>Pilih Pihak Asuransi:</option>
                                <?php
                                $sqlpihak = 'SELECT * FROM t_perusahaan_asuransi
                                                ORDER BY id_perusahaan DESC';
                                $stmt = $pdo->prepare($sqlpihak);
                                $stmt->execute();
                                $rowpihak = $stmt->fetchAll();

                                foreach ($rowpihak as $perusahaan) { ?>
                                    <option <?php if ($perusahaan->id_perusahaan == $asuransi->id_perusahaan) echo 'selected'; ?> value="<?= $perusahaan->id_perusahaan ?>">ID <?= $perusahaan->id_perusahaan ?> - <?= $perusahaan->nama_perusahaan_asuransi ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notlp_asuransi">No Telp Asuransi</label>
                            <input type="tel" name="notlp_asuransi" value="<?= $asuransi->notlp_asuransi ?>" class="form-control" pattern="^[0-9-+\s()]*$" required>
                        </div <p align="center">
                        <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form><br><br>

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