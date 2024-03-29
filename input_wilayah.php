<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
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

$stmt = $pdo->prepare('SELECT * FROM t_kode_wilayah');
$stmt->execute();
$rowkodewilayah = $stmt->fetchAll();

if (isset($_POST['submit'])) {
  if ($_POST['submit'] == 'Simpan') {
    $nama_wilayah        = $_POST['tbnama_wilayah'];
    $deskripsi_wilayah     = $_POST['txtdeskripsi_wilayah'];
    $alamat_kantor_wilayah = $_POST['alamat_kantor_wilayah'];
    $kontak_wilayah = $_POST['kontak_wilayah'];
    $sisi_pantai = $_POST['sisi_pantai'];
    $id_user_pengelola     = 1;
    $randomstring = substr(md5(rand()), 0, 7);
    $kode_wilayah = $_POST['kode_wilayah'];

    // Cek data existing
    $result = mysqli_query($conn, "SELECT nama_wilayah FROM t_wilayah WHERE nama_wilayah = '$nama_wilayah'");
    if (mysqli_fetch_assoc($result)) {
      header('location: input_wilayah.php?pesan=wilayah_sudah_terdaftar');
    } else {

      //Image upload
      if ($_FILES["image_uploads"]["size"] == 0) {
        $foto_wilayah = "images/image_default.jpg";
      } else if (isset($_FILES['image_uploads'])) {
        $target_dir  = "images/foto_wilayah/";
        $foto_wilayah = $target_dir . 'WIL_' . $randomstring . '.jpg';
        move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wilayah);
      }

      //---image upload end

      $sqlwilayah = "INSERT INTO t_wilayah
                        (nama_wilayah, deskripsi_wilayah, foto_wilayah, id_user_pengelola, sisi_pantai, alamat_kantor_wilayah, kontak_wilayah, kode_wilayah)
                        VALUES (:nama_wilayah, :deskripsi_wilayah, :foto_wilayah, :id_user_pengelola, :sisi_pantai, :alamat_kantor_wilayah, :kontak_wilayah, :kode_wilayah)";

      $stmt = $pdo->prepare($sqlwilayah);
      $stmt->execute([
        'nama_wilayah' => $nama_wilayah, 'deskripsi_wilayah' => $deskripsi_wilayah, 'foto_wilayah' => $foto_wilayah, 'id_user_pengelola' => $id_user_pengelola, 'sisi_pantai' => $sisi_pantai,
        'alamat_kantor_wilayah' => $alamat_kantor_wilayah, 'kontak_wilayah' => $kontak_wilayah, 'kode_wilayah' => $kode_wilayah
      ]);

      $affectedrows = $stmt->rowCount();
      if ($affectedrows == '0') {
        //echo "HAHAHAAHA INSERT FAILED !";
      } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_wilayah.php?status=addsuccess");
      }
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
          <a class="btn btn-outline-primary" href="kelola_wilayah.php">
            < Kembali</a><br><br>
              <h3>Input Data Wilayah</h3>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <?php
          if (!empty($_GET['pesan'])) {
            if ($_GET['pesan'] == 'wilayah_sudah_terdaftar') {
              echo '<div class="alert alert-danger" role="alert">
                          Wilayah sudah terdaftar sebelumnya, harap input data yang baru.
                      </div>';
            }
          }
          ?>
          <form action="" enctype="multipart/form-data" method="POST" name="addWilayah">

            <div class="form-group">
              <label for="nama_wilayah">Nama Wilayah</label>
              <input type="text" class="form-control" name="tbnama_wilayah" id="#" required>
            </div>

            <div class="form-group">
              <label for="dd_kode_wilayah">Kode Wilayah</label>
              <select id="dd_kode_wilayah" name="kode_wilayah" class="form-control" required>
                <?php foreach ($rowkodewilayah as $kodewilayah) {
                ?>
                  <option value="<?= $kodewilayah->kode_wilayah ?>"> <?= $kodewilayah->kode_wilayah ?> - <?= $kodewilayah->nama_wilayah ?></option>

                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label for="alamat_kantor_wilayah">Alamat Kantor Wilayah</label>
              <input type="text" class="form-control" name="alamat_kantor_wilayah" id="alamat_kantor_wilayah" required>
            </div>

            <div class="form-group">
              <label for="kontak_wilayah">Kontak Wilayah</label>
              <input type="text" class="form-control" name="kontak_wilayah" id="kontak_wilayah" placeholder="Nomor yang bisa dihubungi" required>
            </div>

            <div class="form-group">
              <label for="nama_wilayah">Deskripsi Wilayah</label>
              <input type="text" class="form-control" name="txtdeskripsi_wilayah" id="#" placeholder="Deskripsi singkat">
            </div>

            <div class='form-group' id='fotowilayah'>
              <div>
                <label for='image_uploads'>Upload Foto Wilayah</label>
                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
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

            <div class="form-group">
              <label for="dd_id_jenis">Sisi Pantai</label>
              <select id="dd_id_jenis" name="sisi_pantai" class="form-control" required>
                <option value="">-- Pilih Sisi Pantai --</option>
                <option value="Pantai Utara">Pantai Utara</option>
                <option value="Pantai Selatan">Pantai Selatan</option>
              </select>
            </div>




            <p align="center">
              <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
            </p>
          </form>
          <!-- <br><a href="input_lokasi.php">Lanjut isi data lokasi ></a> -->
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
