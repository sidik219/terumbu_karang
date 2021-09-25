<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 3)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';
$id_konten = $_GET['id_titik'];
$sqlviewtitik = 'SELECT * from t_konten';
$stmt = $pdo->prepare($sqlviewtitik);
$stmt->execute();
$row = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    $fasilitas_masuk = $_POST['fasilitas_biaya'];
    $fasilitas_luar_biaya = $_POST['fasilitas_luar_biaya'];
    $syarat_ketentuan = $_POST['syarat_ketentuan'];
    $sqlkonten = " UPDATE t_konten SET sdh_biaya = ' $fasilitas_masuk ', blm_biaya=' $fasilitas_luar_biaya ',   sk=' $syarat_ketentuan ' where t_konten.id_konten = $id_konten ";
    $stmt = $pdo->prepare($sqlkonten);
    $stmt->execute();
    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: kelola_konten.php?status=nochange");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_konten.php?status=updatesuccess");
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
                    <a class="btn btn-outline-primary" href="kelola_konten.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Edit Kelola Konten</span></h4>
                            <p>Halaman Input Ini untuk pada halaman depan website</p>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">
                        <?php foreach ($row as $row) : ?>
                            <div class="form-group pb-3">
                                <label for="fasilitas_biaya">
                                    <h5><b>Fasilitas Sudah Termasuk Biaya:</b> </h5>
                                </label>

                                <textarea id="fasilitas_biaya" name="fasilitas_biaya" required><?= $row->sdh_biaya; ?></textarea>
                                <script>
                                    $('#fasilitas_biaya').trumbowyg();
                                </script>
                            </div>

                            <div class="form-group pb-3">
                                <label for="fasilitas_luar_biaya">
                                    <h5><b>Fasilitas Belum Termasuk Biaya:</b></h5>
                                </label>

                                <textarea id="fasilitas_luar_biaya" name="fasilitas_luar_biaya" required><?= $row->blm_biaya; ?></textarea>
                                <script>
                                    $('#fasilitas_luar_biaya').trumbowyg();
                                </script>
                            </div>

                            <div class="form-group pb-3">
                                <label for="syarat_ketentuan">
                                    <h5><b>Syarat & Ketentuan:</b></h5>
                                </label>

                                <textarea id="syarat_ketentuan" name="syarat_ketentuan" required><?= $row->sk; ?></textarea>
                                <script>
                                    $('#syarat_ketentuan').trumbowyg();
                                </script>
                            </div>
                        <?php endforeach ?>
                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                        </p>
                    </form>
                    <br><br>

                    <!-- copy of input fields group -->
                    <div class="form-group fieldGroupCopy" style="display: none;">
                        <div class="input-group">
                            <select class="form-control" name="nama_wisata[]" id="exampleFormControlSelect1">
                                <option selected disabled>Pilih Wisata:</option>
                                <?php
                                $sqlviewwisata = 'SELECT * FROM t_wisata
                                                    WHERE id_paket_wisata IS NULL
                                                    ORDER BY id_wisata';
                                $stmt = $pdo->prepare($sqlviewwisata);
                                $stmt->execute();
                                $rowwisata = $stmt->fetchAll();

                                foreach ($rowwisata as $wisata) { ?>
                                    <option value="<?= $wisata->id_wisata ?>">
                                        ID <?= $wisata->id_wisata ?> - <?= $wisata->judul_wisata ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="input-group-addon">
                                <a href="javascript:void(0)" class="btn btn-danger remove">
                                    <span class="fas fas fa-minus" aria-hidden="true"></span> Hapus Wisata
                                </a>
                            </div>
                        </div>
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

        <!-- jQuery library -->
        <!-- Pembatasan Date Pemesanan -->
        <script>
            var today = new Date().toISOString().split('T')[0];
            document.getElementsByName("tgl_pemesanan")[0].setAttribute('min', today);
        </script>
        <script>
            $(document).ready(function() {
                //group add limit
                var maxGroup = 3;

                //add more fields group
                $(".addMore").click(function() {
                    if ($('body').find('.fieldGroup').length < maxGroup) {
                        var fieldHTML = '<div class="form-group fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                        $('body').find('.fieldGroup:last').after(fieldHTML);
                    } else {
                        alert('Maksimal ' + maxGroup + ' paket wisata yang boleh dibuat.');
                    }
                });

                //remove fields group
                $("body").on("click", ".remove", function() {
                    $(this).parents(".fieldGroup").remove();
                });
            });
        </script>
    </div>
    <!-- Import Trumbowyg font size JS at the end of <body>... -->
    <script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
</body>

</html>