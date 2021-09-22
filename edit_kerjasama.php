<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_kerjasama = $_GET['id_kerjasama'];

// Kerjasama
$sqlkerjasama = 'SELECT * FROM t_kerjasama
                    WHERE id_kerjasama = :id_kerjasama';

$stmt = $pdo->prepare($sqlkerjasama);
$stmt->execute(['id_kerjasama' => $id_kerjasama]);
$kerjasama = $stmt->fetch();

if (isset($_POST['submit'])) {
    $id_pengadaan           = $_POST['nama_fasilitas'];
    $status_kerjasama       = $_POST['status_kerjasama'];
    $pembagian_kerjasama    = $_POST['pembagian_kerjasama'];
    $biaya_kerjasama        = $_POST['biaya_kerjasama'];
    $pembagian_hasil        = $_POST['pembagian_hasil'];
    $Pihak_Ketiga           = $_POST['Pihak_Ketiga'];

    //Insert t_kerjasama
    $sqlpengadaan = "UPDATE t_kerjasama
                    SET id_pengadaan = :id_pengadaan, 
                        status_kerjasama = :status_kerjasama, 
                        pembagian_kerjasama = :pembagian_kerjasama,
                        biaya_kerjasama = :biaya_kerjasama,
                        pembagian_hasil = :pembagian_hasil,
                        Pihak_Ketiga = :Pihak_Ketiga
                    WHERE id_kerjasama = :id_kerjasama";

    $stmt = $pdo->prepare($sqlpengadaan);
    $stmt->execute([
        'id_pengadaan'   => $id_pengadaan,
        'status_kerjasama'   => $status_kerjasama,
        'pembagian_kerjasama'  => $pembagian_kerjasama,
        'biaya_kerjasama'  => $biaya_kerjasama,
        'pembagian_hasil'  => $pembagian_hasil,
        'id_kerjasama'  => $id_kerjasama,
        'Pihak_Ketiga' => $Pihak_Ketiga
    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: edit_kerjasama.php?status=updatefailed");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_kerjasama.php?status=updatesuccess");
    }
}
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
                    <a class="btn btn-outline-primary" href="kelola_kerjasama.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Edit Data Kerjasama Fasilitas</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <form action="" enctype="multipart/form-data" method="POST">
                        <div class="form-group field_wrapper">
                            <div class="form-group">
                                <label for="nama_fasilitas">Fasilitas Wisata</label>
                                <select class="form-control" name="nama_fasilitas" id="exampleFormControlSelect1">
                                    <option selected disabled>Pilih Fasilitas Wisata:</option>
                                    <?php
                                    $sqlpengadaan = 'SELECT * FROM t_pengadaan_fasilitas
                                                    ORDER BY id_pengadaan DESC';
                                    $stmt = $pdo->prepare($sqlpengadaan);
                                    $stmt->execute();
                                    $rowpengadaan = $stmt->fetchAll();

                                    foreach ($rowpengadaan as $pengadaan) { ?>
                                        <option value="<?= $pengadaan->id_pengadaan ?>" <?php if ($pengadaan->id_pengadaan == $kerjasama->id_pengadaan) {
                                                                                            echo " selected";
                                                                                        } ?>>
                                            <?= $pengadaan->pengadaan_fasilitas ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status_kerjasama">Status Kerjasama</label>
                                <select class="form-control" name="status_kerjasama" id="exampleFormControlSelect1">
                                    <option selected disabled>Pilih Status Kerjasama:</option>
                                    <option value="Tidak Melakukan Kerjasama">Tidak Melakukan Kerjasama</option>
                                    <option value="Melakukan Kerjasama">Melakukan Kerjasama</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Pihak_Ketiga">Nama Pihak Ketiga</label>
                                <input type="text" id="Pihak_Ketiga" name="Pihak_Ketiga" class="form-control" required value="<?= $kerjasama->Pihak_Ketiga ?>">
                            </div>
                            <div class="form-group">
                                <label for="pembagian_kerjasama">Pembagian Kerjasama</label>
                                <select class="form-control" name="pembagian_kerjasama" id="persentase" onchange="myPersentase();">
                                    <option selected disabled>Pilih Pembagian Kerjasama:</option>
                                    <option value="0">0%</option>
                                    <option value="0.1">10%</option>
                                    <option value="0.2">20%</option>
                                    <option value="0.3">30%</option>
                                    <option value="0.4">40%</option>
                                    <option value="0.5">50%</option>
                                    <option value="0.6">60%</option>
                                    <option value="0.7">70%</option>
                                    <option value="0.8">80%</option>
                                    <option value="0.9">90%</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="biaya_kerjasama">Biaya Fasilitas</label>
                                <input type="number" id="biaya_kerjasama" name="biaya_kerjasama" value="<?= $kerjasama->biaya_kerjasama ?>" class="form-control" onchange="myPersentase();" required>
                            </div>
                            <div class="form-group">
                                <label for="pembagian_hasil">Pembagian Hasil</label>
                                <!-- Output for display in form -->
                                <input type="text" id="hasil" class="form-control" required readonly>
                                <!-- Hidden Output insert to DB -->
                                <input type="hidden" id="pembagian_hasil" name="pembagian_hasil" value="" class="form-control" required>
                            </div>
                        </div>

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
        <script>
            function myPersentase() {
                var persentase = document.getElementById("persentase").value;
                var biaya_kerjasama = document.getElementById("biaya_kerjasama").value;

                var pembagian = parseFloat(persentase) * biaya_kerjasama;
                var hasil = pembagian;
                console.log(hasil);

                // Format untuk number.
                var formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                });

                document.getElementById("hasil").value = formatter.format(hasil); //Untuk Ditampilkan
                document.getElementById("pembagian_hasil").value = hasil; //Untuk insert ke DB
            }
        </script>

    </div>
    <!-- Import Trumbowyg font size JS at the end of <body>... -->
    <script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
</body>

</html>