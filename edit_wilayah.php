<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

    $id_wilayah = $_GET['id_wilayah'];
    $defaultpic = "images/image_default.jpg";

    $sqlviewwilayah = 'SELECT * FROM t_user
                    WHERE level_user = 2
                    ORDER BY nama_user';
        $stmt = $pdo->prepare($sqlviewwilayah);
        $stmt->execute();
        $row = $stmt->fetchAll();


    $sql = 'SELECT * FROM t_wilayah WHERE id_wilayah = :id_wilayah';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_wilayah' => $id_wilayah]);
    $rowitem = $stmt->fetch();

    if (isset($_POST['submit'])) {
            if($_POST['submit'] == 'Simpan'){
                $nama_wilayah        = $_POST['tbnama_wilayah'];
                $deskripsi_wilayah     = $_POST['txtdeskripsi_wilayah'];
                $id_user_pengelola     = 1;

                //Image upload
            if($_FILES["image_uploads"]["size"] == 0) {
                $foto_wilayah = $rowitem->foto_wilayah;
                $pic = "&none=";
            }
            else if (isset($_FILES['image_uploads'])) {
                if (($rowitem->foto_wilayah == $defaultpic) || (!$rowitem->foto_wilayah)){
                    $randomstring = substr(md5(rand()), 0, 7);
                    $target_dir  = "images/foto_wilayah/";
                    $foto_wilayah = $target_dir .'WIL_'.$randomstring. '.jpg';
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wilayah);
                    $pic = "&new=";
                }
                else if (isset($rowitem->foto_wilayah)){
                    $foto_wilayah = $rowitem->foto_wilayah;
                    unlink($rowitem->foto_wilayah);
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowitem->foto_wilayah);
                    $pic = "&replace=";
                }
            }

            //---image upload end

                $sqleditwilayah = "UPDATE t_wilayah
                            SET nama_wilayah = :nama_wilayah,
                            deskripsi_wilayah = :deskripsi_wilayah, foto_wilayah = :foto_wilayah,
                            id_user_pengelola = :id_user_pengelola
                            WHERE id_wilayah = :id_wilayah";

                $stmt = $pdo->prepare($sqleditwilayah);
                $stmt->execute(['nama_wilayah' => $nama_wilayah,
                'deskripsi_wilayah' => $deskripsi_wilayah,
                'foto_wilayah' => $foto_wilayah, 'id_wilayah' => $id_wilayah,
                'id_user_pengelola' => $id_user_pengelola]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                header("Location: kelola_wilayah.php?status=nochange".$pic);
                } else {
                header("Location: kelola_wilayah.php?status=updatesuccess".$pic);
                }
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Wilayah - GoKarang</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
        <link rel="stylesheet" href= "plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href= "dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->"
        <link rel="stylesheet" href= "plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href= "css/style.css">

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
            <a href= "dashboard_admin.php" class="brand-link">
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
                        <a class="btn btn-outline-primary" href="kelola_wilayah.php">< Kembali</a><br><br>
                         <h3>Edit Data Wilayah</h3>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST" name="updateWilayah">

                          <div class="form-group">
                                <label for="nama_wilayah">Nama Wilayah</label>
                                <input type="text" value="<?=$rowitem->nama_wilayah?>" class="form-control" name="tbnama_wilayah" id="#" placeholder="Nama Kota/Kabupaten">
                          </div>
                          <div class="form-group">
                                <label for="nama_wilayah">Deskripsi Wilayah</label>
                                <input type="#" value="<?=$rowitem->deskripsi_wilayah?>" class="form-control" name="txtdeskripsi_wilayah" id="#" placeholder="Deskripsi singkat">
                          </div>

                                        <div class='form-group' id='fotowilayah'>
                                            <div>
                                                <label for='image_uploads'>Upload Foto Wilayah</label>
                                                <input type='file'  class='form-control' id='image_uploads'
                                                    name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <img id="preview" src="#"  width="100px" alt="Preview Gambar"/>
                                            <img id="oldpic" src="<?=$rowitem->foto_wilayah?>" width="100px">
                                            <script>
                                                window.onload = function() {
                                                document.getElementById('preview').style.display = 'none';
                                                };
                                                function readURL(input) {
                                                    if (input.files && input.files[0]) {
                                                        var reader = new FileReader();
                                                        document.getElementById('oldpic').style.display = 'none';
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
                    </form>
            <br><br>

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
    <br><br>
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
    <script src= "plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <!-- Bootstrap 4 -->
    <script src= "plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src= "plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src= "dist/js/adminlte.js"></script>

</body>
</html>
