<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewjenis = 'SELECT * FROM t_jenis_terumbu_karang
                        ORDER BY id_jenis';
$stmt = $pdo->prepare($sqlviewjenis);
$stmt->execute();
$row = $stmt->fetchAll();

if (isset($_POST['submit'])) {
  $id_jenis      = $_POST['dd_id_jenis'];
  $nama_terumbu_karang        = $_POST['tb_nama_terumbu'];
  $deskripsi_terumbu_karang        = $_POST['tb_deskripsi_terumbu'];
  $randomstring = substr(md5(rand()), 0, 7);
  $harga_terumbu_karang = $_POST['num_harga_terumbu_karang'];
  $deskripsi_panjang_tk = $_POST['deskripsi_panjang_tk'];

  // Cek data existing
  $result = mysqli_query($conn, "SELECT nama_terumbu_karang FROM t_terumbu_karang WHERE nama_terumbu_karang = '$nama_terumbu_karang'");
  if (mysqli_fetch_assoc($result)) {
    header('location: input_tk.php?pesan=jenis_terumbu_karang_sudah_terdaftar');
  } else {


    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
      $foto_terumbu_karang = "images/image_default.jpg";
    } else if (isset($_FILES['image_uploads'])) {
      $target_dir  = "images/foto_terumbu_karang/";
      $foto_terumbu_karang = $target_dir . 'TK_' . $randomstring . '.jpg';
      move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_terumbu_karang);
    }

    //---image upload end

    $sqlterumbu_karang = "INSERT INTO t_terumbu_karang
                            (id_jenis, nama_terumbu_karang, deskripsi_terumbu_karang, foto_terumbu_karang, harga_terumbu_karang, deskripsi_panjang_tk)
                            VALUES (:id_jenis, :nama_terumbu_karang,
                            :deskripsi_terumbu_karang, :foto_terumbu_karang, :harga_terumbu_karang, :deskripsi_panjang_tk)";

    $stmt = $pdo->prepare($sqlterumbu_karang);
    $stmt->execute([
      'id_jenis' => $id_jenis, 'nama_terumbu_karang' => $nama_terumbu_karang,
      'deskripsi_terumbu_karang' => $deskripsi_terumbu_karang,
      'foto_terumbu_karang' => $foto_terumbu_karang, 'harga_terumbu_karang' => $harga_terumbu_karang, 'deskripsi_panjang_tk' => $deskripsi_panjang_tk
    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
      //echo "HAHAHAAHA INSERT FAILED !";
    } else {
      //echo "HAHAHAAHA GREAT SUCCESSS !";
      header("Location: kelola_tk.php?status=addsuccess");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Kelola Terumbu Karang - GoKarang</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link rel="stylesheet" href="js/trumbowyg/dist/ui/trumbowyg.min.css">
  <script src="js/trumbowyg/dist/trumbowyg.js"></script>
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
                          Terumbu karang sudah terdaftar sebelumnya, harap input data yang baru.
                      </div>';
            }
          }
          ?>
          <a class="btn btn-outline-primary" href="kelola_tk.php">
            < Kembali</a><br><br>
              <h4><span class="align-middle font-weight-bold">Input Data Terumbu Karang</h4></span>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <form action="" enctype="multipart/form-data" method="POST">
            <div class="form-group">
              <label for="dd_id_jenis">ID Jenis</label>
              <select id="dd_id_jenis" name="dd_id_jenis" class="form-control">
                <?php foreach ($row as $rowitem) {
                ?>
                  <option value="<?= $rowitem->id_jenis ?>">ID <?= $rowitem->id_jenis ?> - <?= $rowitem->nama_jenis ?></option>

                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="tb_nama_terumbu">Nama Terumbu Karang</label>
              <input type="text" id="tb_nama_terumbu" name="tb_nama_terumbu" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="tb_deskripsi_terumbu">Deskripsi Terumbu Karang</label>
              <input type="text" id="tb_deskripsi_terumbu" name="tb_deskripsi_terumbu" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="isi_artikel">Deskripsi Lengkap Terumbu Karang</label>
              <textarea id="deskripsi_lengkap_tk" name="deskripsi_panjang_tk" required></textarea>
              <script>
                $('#deskripsi_lengkap_tk').trumbowyg();
              </script>
            </div>

            <div class='form-group' id='fototk'>
              <div>
                <label for='image_uploads'>Upload Foto Terumbu Karang</label>
                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);" required>
              </div>
            </div>
            <div class="form-group">
              <img id="preview" width="100px" src="#" alt="Preview Gambar" />

              <script>
                window.onload = function() {
                  document.getElementById('preview').style.display = 'none';
                };

                function readURL(input) {
                  if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                    alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
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
            <div class="form-group d-none">
              <label for="num_harga_terumbu_karang">Harga Terumbu Karang</label>
              <input type="number" id="num_harga_terumbu_karang" name="num_harga_terumbu_karang" class="form-control" value="1">
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
  <!-- Import Trumbowyg font size JS at the end of <body>... -->
  <script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>

</body>

</html>
