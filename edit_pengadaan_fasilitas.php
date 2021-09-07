<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_pengadaan = $_GET['id_pengadaan'];

$sqlpengadaan= 'SELECT * FROM t_pengadaan_fasilitas
                    WHERE id_pengadaan = :id_pengadaan';

$stmt = $pdo->prepare($sqlpengadaan);
$stmt->execute(['id_pengadaan' => $id_pengadaan]);
$pengadaan = $stmt->fetch();

if (isset($_POST['submit'])) {
    $pengadaan_fasilitas    = $_POST['pengadaan_fasilitas'];
    $status_pengadaan       = $_POST['status_pengadaan'];
    
    //Insert t_pengadaan_fasilitas
    $sqlpengadaan = "UPDATE t_pengadaan_fasilitas
                    SET pengadaan_fasilitas = :pengadaan_fasilitas, 
                        status_pengadaan = :status_pengadaan
                    WHERE id_pengadaan = :id_pengadaan";

    $stmt = $pdo->prepare($sqlpengadaan);
    $stmt->execute(['pengadaan_fasilitas'   => $pengadaan_fasilitas,
                    'status_pengadaan'  => $status_pengadaan,
                    'id_pengadaan'  => $id_pengadaan
                    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: edit_pengadaan_fasilitas.php?status=updatefailed");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_pengadaan_fasilitas.php?status=updatesuccess");
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
                    <a class="btn btn-outline-primary" href="kelola_pengadaan_fasilitas.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Edit Data Pengadaan Fasilitas</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <form action="" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <label for="pengadaan_fasilitas">Pengadaan Fasilitas</label><br>
                        <input type="text" name="pengadaan_fasilitas" value="<?=$pengadaan->pengadaan_fasilitas?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="status_pengadaan">Status Pengadaan</label>
                        <select class="form-control" name="status_pengadaan" id="exampleFormControlSelect1">
                            <option selected disabled>Status Pengadaan:</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Hilang">Hilang</option>
                        </select>
                    </div>

                    <p align="center">
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
