<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if (isset($_POST['submit'])) {
    $judul_kegiatan     = $_POST['judul_kegiatan'];
    $deskripsi_kegiatan = $_POST['deskripsi_kegiatan'];
    $tgl_kegiatan       = $_POST['tgl_kegiatan'];
    $randomstring       = substr(md5(rand()), 0, 7);

    //Image upload
    if($_FILES["image_uploads"]["size"] == 0) {
        $foto_kegiatan = "images/image_default.jpg";
    }
    else if (isset($_FILES['image_uploads'])) {
        $target_dir  = "images/foto_konten/kegiatan/";
        $foto_kegiatan = $target_dir .'KEG_'.$randomstring. '.jpg';
        move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_kegiatan);
    }
    //---image upload end

    $sqlinsertkegiatan = "INSERT INTO t_berita_kegiatan (judul_kegiatan, 
                                                        deskripsi_kegiatan, 
                                                        foto_kegiatan, 
                                                        tgl_kegiatan)
                                        VALUES (:judul_kegiatan, 
                                                :deskripsi_kegiatan, 
                                                :foto_kegiatan,
                                                :tgl_kegiatan)";

    $stmt = $pdo->prepare($sqlinsertkegiatan);
    $stmt->execute(['judul_kegiatan' => $judul_kegiatan,
                    'deskripsi_kegiatan' => $deskripsi_kegiatan,
                    'foto_kegiatan' => $foto_kegiatan,
                    'tgl_kegiatan' => $tgl_kegiatan
                    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: input_konten_kegiatan.php?status=insertfailed");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_konten_kegiatan.php?status=addsuccess");
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
                    <a class="btn btn-outline-primary" href="kelola_konten_kegiatan.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Input Data Kegiatan</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">

                    <div class="form-group">
                        <label for="judul_kegiatan">Judul Kegiatan</label>
                        <input type="text" id="judul_kegiatan" name="judul_kegiatan" class="form-control" placeholder="Judul Konten" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_kegiatan">Deskripsi Kegiatan</label>
                        <input type="text" id="deskripsi_kegiatan" name="deskripsi_kegiatan" class="form-control" placeholder="Deskripsi Konten" required>
                    </div>

                    <div class="form-group">
                        <label for="tgl_kegiatan">Tanggal Kegiatan</label>
                        <input type="date" id="tgl_kegiatan" name="tgl_kegiatan" class="form-control" placeholder="Status Konten" required>
                    </div>

                    <div class='form-group' id='fotowilayah'>
                        <div>
                            <label for='image_uploads'>Upload Foto Kegiatan</label>
                            <input type='file'  class='form-control' id='image_uploads'
                                name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                        </div>
                    </div>

                    <div class="form-group">
                        <img id="preview"  width="100px" src="#" alt="Preview Gambar"/>

                        <script>
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

                                    reader.onload = function (e) {
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