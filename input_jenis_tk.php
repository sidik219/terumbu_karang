<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if (isset($_POST['submit'])) {
  $nama_jenis        = $_POST['tb_nama_jenis'];
  $deskripsi_jenis        = $_POST['tb_deskripsi_jenis'];
  $randomstring = substr(md5(rand()), 0, 7);

  // Cek data existing
  $result = mysqli_query($conn, "SELECT nama_jenis FROM t_jenis_terumbu_karang WHERE nama_jenis = '$nama_jenis'");
  if (mysqli_fetch_assoc($result)) {
    header('location: input_jenis_tk.php?pesan=jenis_terumbu_karang_sudah_terdaftar');
  } else {

    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
      $foto_jenis = "images/image_default.jpg";
    } else if (isset($_FILES['image_uploads'])) {
      $target_dir  = "images/foto_jenis_tk/";
      $foto_jenis = $target_dir . 'JNS_' . $randomstring . '.jpg';
      move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_jenis);
    }

    //---image upload end

    $sqljenis = "INSERT INTO t_jenis_terumbu_karang
                        (nama_jenis, deskripsi_jenis, foto_jenis)
                        VALUES (:nama_jenis, :deskripsi_jenis, :foto_jenis)";

    $stmt = $pdo->prepare($sqljenis);
    $stmt->execute(['nama_jenis' => $nama_jenis, 'deskripsi_jenis' => $deskripsi_jenis, 'foto_jenis' => $foto_jenis]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
      //echo "HAHAHAAHA INSERT FAILED !";
    } else {
      //echo "HAHAHAAHA GREAT SUCCESSS !";
      header("Location: kelola_jenis_tk.php?status=addsuccess");
    }
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
          <?php
          if (!empty($_GET['pesan'])) {
            if ($_GET['pesan'] == 'jenis_terumbu_karang_sudah_terdaftar') {
              echo '<div class="alert alert-danger" role="alert">
                          Jenis terumbu karang sudah terdaftar sebelumnya, harap input data yang baru.
                      </div>';
            }
          }
          ?>
          <a class="btn btn-outline-primary" href="kelola_jenis_tk.php">
            < Kembali</a><br><br>
              <h4><span class="align-middle font-weight-bold">Input Data Jenis Terumbu Karang</span></h4>
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
              <input type="text" id="tb_nama_jenis" name="tb_nama_jenis" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="tb_deskripsi_jenis">Deskripsi Jenis</label>
              <input type="text" id="tb_deskripsi_jenis" name="tb_deskripsi_jenis" class="form-control" required>
            </div>
            <div class='form-group' id='fotojenistk'>
              <div>
                <label for='image_uploads'>Upload Foto Jenis Terumbu</label>
                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                <p class="small">Maksimal Ukuran Foto 2MB</p>
              </div>
            </div>
            <!-- <script>
                            var uploadField = document.getElementById("image_uploads");

                            uploadField.onchange = function() {
                                if (this.files[0].size > 2097152) {
                                    alert("Ukuran File Terlalu besar");
                                    this.value = "";
                                };
                            };
                        </script> -->
            <div class="form-group">
              <img id="preview" width="100px" src="#" alt="Preview Gambar" />

              <script>
                window.onload = function() {
                  document.getElementById('preview').style.display = 'none';
                };

                function readURL(input) {
                  if (input.files[0].size > 2097152) {
                    alert("Ukuran File Terlalu besar");
                    input.value = "";
                  };
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();

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
