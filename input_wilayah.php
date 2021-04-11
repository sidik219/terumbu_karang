<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewwilayah = 'SELECT * FROM t_user
                    WHERE level_user = 2
                    ORDER BY nama_user';
        $stmt = $pdo->prepare($sqlviewwilayah);
        $stmt->execute();
        $row = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if($_POST['submit'] == 'Simpan'){
        $nama_wilayah        = $_POST['tbnama_wilayah'];
        $deskripsi_wilayah     = $_POST['txtdeskripsi_wilayah'];
        $id_user_pengelola     = $_POST['tb_id_user_pengelola'];
        $randomstring = substr(md5(rand()), 0, 7);

        //Image upload
        if($_FILES["image_uploads"]["size"] == 0) {
            $foto_wilayah = "images/image_default.jpg";
        }
        else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_wilayah/";
            $foto_wilayah = $target_dir .'WIL_'.$randomstring. '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wilayah);
        }

        //---image upload end

        $sqlwilayah = "INSERT INTO t_wilayah
                        (nama_wilayah, deskripsi_wilayah, foto_wilayah, id_user_pengelola)
                        VALUES (:nama_wilayah, :deskripsi_wilayah, :foto_wilayah, :id_user_pengelola)";

        $stmt = $pdo->prepare($sqlwilayah);
        $stmt->execute(['nama_wilayah' => $nama_wilayah, 'deskripsi_wilayah' => $deskripsi_wilayah, 'foto_wilayah' => $foto_wilayah, 'id_user_pengelola' => $id_user_pengelola]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            header("Location: kelola_wilayah.php?status=addsuccess");
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
                  <h3>Input Data Wilayah</h3>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST" name="addWilayah">

                          <div class="form-group">
                                <label for="nama_wilayah">Nama Wilayah</label>
                                <input type="text" class="form-control" name="tbnama_wilayah" id="#" placeholder="Nama Kota/Kabupaten">
                          </div>
                          <div class="form-group">
                                <label for="nama_wilayah">Deskripsi Wilayah</label>
                                <input type="#" class="form-control" name="txtdeskripsi_wilayah" id="#" placeholder="Deskripsi singkat">
                          </div>

                                        <div class='form-group' id='fotowilayah'>
                                            <div>
                                                <label for='image_uploads'>Upload Foto Wilayah</label>
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

                          <div class="form-group">
                        <label for="dd_id_wilayah">User Pengelola Wilayah</label>
                        <select id="dd_id_wilayah" name="tb_id_user_pengelola" class="form-control" required>
                            <?php foreach ($row as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_user?>">ID <?=$rowitem->id_user?> - <?=$rowitem->nama_user?> - <?=$rowitem->organisasi_user?></option>

                            <?php } ?>
                        </select>
                    </div>


                          <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
            <br><a href="input_lokasi.php">Lanjut isi data lokasi ></a>
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
    <br>
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
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</body>
</html>
