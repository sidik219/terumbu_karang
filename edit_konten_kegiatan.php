<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_kegiatan = $_GET['id_kegiatan'];
$defaultpic = "images/image_default.jpg";

// Kegiatan
$sqleditkegiatan = 'SELECT * FROM t_berita_kegiatan
                    WHERE id_kegiatan = :id_kegiatan';

$stmt = $pdo->prepare($sqleditkegiatan);
$stmt->execute(['id_kegiatan' => $id_kegiatan]);
$kegiatan = $stmt->fetch();
// var_dump($kegiatan);die;

if (isset($_POST['submit'])) {
    // var_dump($_POST, $_FILES);
    // die;
    $judul_kegiatan     = $_POST['judul_kegiatan'];
    $deskripsi_kegiatan = $_POST['deskripsi_kegiatan'];
    $tgl_kegiatan       = $_POST['tgl_kegiatan'];
    $randomstring = substr(md5(rand()), 0, 7);
    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
        $foto_kegiatan = $kegiatan->foto_kegiatan;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($kegiatan->foto_kegiatan == $defaultpic) || (!$kegiatan->foto_kegiatan)) {
            $target_dir  = "images/foto_konten/kegiatan/";
            $foto_kegiatan = $target_dir . 'KEG_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_kegiatan);
            $pic = "&new=";
        } else if (isset($kegiatan->foto_kegiatan)) {
            $foto_kegiatan = $kegiatan->foto_kegiatan;
            unlink($kegiatan->foto_kegiatan);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $kegiatan->foto_kegiatan);
            $pic = "&replace=";
        }
    }
    //---image upload end

    $sqleditkegiatan = 'UPDATE t_berita_kegiatan
                        SET judul_kegiatan = :judul_kegiatan,
                            deskripsi_kegiatan = :deskripsi_kegiatan,
                            tgl_kegiatan = :tgl_kegiatan,
                            foto_kegiatan = :foto_kegiatan
                        WHERE id_kegiatan = :id_kegiatan';

    $stmt = $pdo->prepare($sqleditkegiatan);
    $stmt->execute([
        'judul_kegiatan' => $judul_kegiatan,
        'deskripsi_kegiatan' => $deskripsi_kegiatan,
        'tgl_kegiatan' => $tgl_kegiatan,
        'foto_kegiatan' => $foto_kegiatan,
        'id_kegiatan' => $id_kegiatan
    ]);

    $affectedrows = $stmt->rowCount();
    // var_dump($affectedrows);
    // die;
    if ($affectedrows == '0') {
        // header("Location: edit_konten_kegiatan.php?status=insertfailed&id_kegiatan=$id_kegiatan");
        header("Location: kelola_konten_kegiatan.php?status=updatesuccess");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_konten_kegiatan.php?status=updatesuccess");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Konten - GoKarang</title>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="js/trumbowyg/dist/ui/trumbowyg.min.css">
    <script src="js/trumbowyg/dist/trumbowyg.min.js"></script>

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
                    <a class="btn btn-outline-primary" href="kelola_konten_kegiatan.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Edit Data Kegiatan</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">
                        <?php
                        if (!empty($_GET['status'])) {
                            if ($_GET['status'] == 'insertfailed') {
                                echo '<div class="alert alert-success" role="alert">
                                    Data Kegiatan Tidak Ada Yang Berubah!
                                    </div>';
                            }
                        }
                        ?>
                        <div class="form-group">
                            <label for="judul_kegiatan">Judul Kegiatan</label>
                            <input type="text" id="judul_kegiatan" name="judul_kegiatan" value="<?= $kegiatan->judul_kegiatan ?>" class="form-control" placeholder="Judul Konten" required>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi_kegiatan">Deskripsi Kegiatan</label>
                            <!-- <input type="text" id="deskripsi_kegiatan" value="<?= $kegiatan->deskripsi_kegiatan ?>" name="deskripsi_kegiatan" class="form-control" placeholder="Deskripsi Konten" required> -->

                            <textarea id="deskripsi_kegiatan" name="deskripsi_kegiatan" class="form-control" placeholder="Deskripsi Konten" required><?= $kegiatan->deskripsi_kegiatan; ?></textarea>
                            <script>
                                $('#deskripsi_kegiatan').trumbowyg();
                            </script>
                        </div>

                        <div class="form-group">
                            <label for="tgl_kegiatan">Tanggal Kegiatan</label>
                            <input type="date" id="tgl_kegiatan" name="tgl_kegiatan" value="<?= $kegiatan->tgl_kegiatan ?>" class="form-control" placeholder="Status Konten" required>
                        </div>

                        <div class='form-group'>
                            <div>
                                <label for='image_uploads'>Upload Foto Kegiatan</label>
                                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                            </div>
                        </div>


                        <div class="form-group">
                            <img id="preview" src="#" width="100px" alt="Preview Gambar" />
                            <a href="<?= $kegiatan->foto_kegiatan ?>" data-toggle="lightbox">
                                <img class="img-fluid" id="oldpic" src="<?= $kegiatan->foto_kegiatan ?>" width="20%" <?php if ($kegiatan->foto_kegiatan == NULL) echo "style='display: none;'"; ?>></a>
                            <br>

                            <small class="text-muted">
                                <?php if ($kegiatan->foto_kegiatan == NULL) {
                                    echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                                } else {
                                    echo "Klik gambar untuk memperbesar";
                                }

                                ?>
                            </small>

                            <script>
                                const actualBtn = document.getElementById('image_uploads');
                                const fileChosen = document.getElementById('file-input-label');

                                actualBtn.addEventListener('change', function() {
                                    fileChosen.innerHTML = '<b>File dipilih :</b> ' + this.files[0].name
                                })
                                window.onload = function() {
                                    document.getElementById('preview').style.display = 'none';
                                };

                                function readURL(input) {
                                    //Validasi Size Upload Image
                                    var uploadField = document.getElementById("image_uploads");

                                    uploadField.onchange = function() {
                                        if (this.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                            alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                            this.value = "";
                                        };
                                    };

                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();
                                        document.getElementById('oldpic').style.display = 'none';
                                        reader.onload = function(e) {
                                            $('#preview')
                                                .attr('src', e.target.result)
                                                .width(200);
                                            document.getElementById('preview').style.display = 'block';
                                        };

                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
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

</body>

</html>