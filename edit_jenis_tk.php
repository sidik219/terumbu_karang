<?php
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
  header('location: login.php?status=restrictedaccess');
}
include 'build/config/connection.php';
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_jenis = $_GET['id_jenis'];
$defaultpic = "images/image_default.jpg";


$sql = 'SELECT * FROM t_jenis_terumbu_karang WHERE id_jenis = :id_jenis';

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_jenis' => $id_jenis]);
$rowitem = $stmt->fetch();

if (isset($_POST['submit'])) {
  $nama_jenis        = $_POST['tb_nama_jenis'];
  $deskripsi_jenis        = $_POST['tb_deskripsi_jenis'];
  $randomstring = substr(md5(rand()), 0, 7);


  //Image upload
  if ($_FILES["image_uploads"]["size"] == 0) {
    $foto_jenis = $rowitem->foto_jenis;
    $pic = "&none=";
  } else if (isset($_FILES['image_uploads'])) {
    if (($rowitem->foto_jenis == $defaultpic) || (!$rowitem->foto_jenis)) {
      $target_dir  = "images/foto_jenis_tk/";
      $foto_jenis = $target_dir . 'JNS_' . $randomstring . '.jpg';
      move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_jenis);
      $pic = "&new=";
    } else if (isset($rowitem->foto_jenis)) {
      $foto_jenis = $rowitem->foto_jenis;
      unlink($rowitem->foto_jenis);
      move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowitem->foto_jenis);
      $pic = "&replace=";
    }
  }

  //---image upload end

  $sqljenis = "UPDATE t_jenis_terumbu_karang
                        SET nama_jenis = :nama_jenis, deskripsi_jenis = :deskripsi_jenis, foto_jenis = :foto_jenis
                        WHERE id_jenis = :id_jenis";

  $stmt = $pdo->prepare($sqljenis);
  $stmt->execute(['id_jenis' => $id_jenis, 'nama_jenis' => $nama_jenis, 'deskripsi_jenis' => $deskripsi_jenis, 'foto_jenis' => $foto_jenis]);

  $affectedrows = $stmt->rowCount();
  if ($affectedrows == 0) {
    header("Location: kelola_jenis_tk.php?status=nochange&gambar=$pic");
  } else {
    //echo "HAHAHAAHA GREAT SUCCESSS !";
    header("Location: kelola_jenis_tk.php?status=updatesuccess");
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Kelola Jenis Terumbu Karang - GoKarang</title>
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
          <a class="btn btn-outline-primary" href="kelola_jenis_tk.php">
            < Kembali</a><br><br>
              <h4><span class="align-middle font-weight-bold">Edit Data Jenis Terumbu Karang</span></h4>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <form action="" enctype="multipart/form-data" method="POST">
            <div class="form-group">
              <label for="tb_nama_jenis">Nama Jenis</label>
              <input type="text" value="<?= $rowitem->nama_jenis ?>" id="tb_nama_jenis" name="tb_nama_jenis" class="form-control">
            </div>
            <div class="form-group">
              <label for="tb_deskripsi_jenis">Deskripsi Jenis</label>
              <input type="text" value="<?= $rowitem->deskripsi_jenis ?>" id="tb_deskripsi_jenis" name="tb_deskripsi_jenis" class="form-control">
            </div>
            <div class='form-group' id='fototitik'>
              <div>
                <label for='image_uploads'>Upload Foto Jenis</label>
                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
              </div>
            </div>

            <div class="form-group">
              <img id="preview" src="#" width="100px" alt="Preview Gambar" />
              <img id="oldpic" src="<?= $rowitem->foto_jenis ?>" width="100px">
              <script>
                window.onload = function() {
                  document.getElementById('preview').style.display = 'none';
                };

                function readURL(input) {
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

            <br>
            <p align="center">
              <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
            </p>
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
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>

</body>

</html>
