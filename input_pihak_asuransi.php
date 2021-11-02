<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if (isset($_POST['submit'])) {
    $nama_perusahaan_asuransi      = $_POST['nama_perusahaan_asuransi'];
    $alamat_asuransi     = $_POST['alamat_asuransi'];
    $notlp_asuransi     = $_POST['notlp_asuransi'];
    // var_dump($_POST);
    // die;
    //Insert t_asuransi
    // INSERT INTO `t_perusahaan_asuransi` (`id_perusahaan`, `nama_perusahaan_asuransi`, `alamat_asuransi`, `notlp_asuransi`) VALUES (NULL, 'asd', 'asd', '4321');
    $sqlasuransi = "INSERT INTO `t_perusahaan_asuransi` (`id_perusahaan`, `nama_perusahaan_asuransi`, `alamat_asuransi`, `notlp_asuransi`)
     VALUES (NULL,'$nama_perusahaan_asuransi','$alamat_asuransi','$notlp_asuransi')";
    $stmt = $pdo->prepare($sqlasuransi);
    // var_dump(
    $stmt->execute(
        //     [
        //     'nama_perusahaan_asuransi' => $nama_perusahaan_asuransi,
        //     'alamat_asuransi'  => $alamat_asuransi,
        //     'notlp_asuransi' => $notlp_asuransi
        // ]
    );
    // );
    // die;

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: input_asuransi.php?status=insertfailed");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: input_asuransi.php?status=addsuccess");
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
                    <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Input Data Perusahaan Asuransi</span></h4>
                        </div>
                        <div class="col">
                            <a class="btn btn-success float-right" href="input_asuransi.php" style="margin-bottom: 10px;">
                                Selanjutnya <i class="fas fa-arrow-right"></i></a>
                            <a class="btn btn-success float-right mr-1" href="kelola_asuransi.php" style="margin-bottom: 10px;">
                                <i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <form action="" enctype="multipart/form-data" method="POST">
                        <h3 class="my-4">Detail Asuransi</h3>

                        <div class="form-group">
                            <label for="nama_perusahaan_asuransi">Perusahaan Asuransi</label>
                            <input type="text" id="nama_perusahaan_asuransi" name="nama_perusahaan_asuransi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat_asuransi">Alamat Asuransi</label>
                            <input type="text" id="alamat_asuransi" name="alamat_asuransi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="notlp_asuransi">No Telp Asuransi</label>
                            <input type="tel" id="notlp_asuransi" name="notlp_asuransi" class="form-control" pattern="^[0-9-+\s()]*$" required>
                        </div>
                        <p class="small">Lewati Jika Dengan Perusahaan Asuransi Yang Sama</p>
                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                        </p>
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