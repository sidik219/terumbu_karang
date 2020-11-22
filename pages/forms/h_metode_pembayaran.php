<?php include '../../build/config/connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Terumbu Karang</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- Theme style Costume -->
  <link rel="stylesheet" href="../../dist/css/css-main-costume.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index.php" class="brand-link">
      <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="../../index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Home
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
              <li class="breadcrumb-item active">Review Donasi</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title" style="font-weight:bold; text-align: center;">Metode Pembayaran</h3>
              </div>
              <!-- /.card-header -->

              <!-- form start -->
              <div class="card-body">
                <form action="#" method="POST">
                  <table align="center">
                    <tr>
                      <td class="ukuran-table">Lokasi penanaman dipilih:</td>
                      <td rowspan="2"></td>
                    </tr>
                    <!-- Query Lokasi -->
                    <?php
                    $sql_view = "SELECT * FROM t_lokasi";

                    foreach ($pdo->query($sql_view) as $result) { ?>
                    <tr>
                      <span style="display:none;"><?php echo $result->id_lokasi; ?></span>
                      <td style="color: #30A0E0; font-weight:bold;"><?php echo $result->nama_lokasi; ?></td>
                    </tr>
                  </table>
                  <!-- Halaman Data Metode Pembayaran -->
                  <table align="center" style="color: #30A0E0;">
                    <tr>
                      <p>
                      <td>
                        <span>
                          Untuk lokasi ini, metode pembayaran<br>yang diterima adalah:
                        </span>
                        <p>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <b>
                          <span>Bank Transfer (<?php echo $result->nama_bank; ?>)</span><br>
                          <span><?php echo $result->nomor_rekening; ?></span><br>
                          <span>A.N <?php echo $result->nama_rekening; ?></span>
                        </b>
                        <p>
                      </td>
                    </tr>
                    <?php } ?>
                  <!-- End -->
                    <tr>
                      <td>
                        <span>
                          Nominal: <b>Rp. 100000</b>
                        </span>
                        <p>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span>
                          Kunjungi halaman <b>"Donasi Saya"</b><br>
                          untuk <b>mengupload bukti transfer</b> atau<br>
                          melihat <b>status donasi anda.</b>
                        </span>
                      </td>
                    </tr>
                  </table>

                  <!-- Halaman Input Metode Pembayaran -->
                  <table align="center" style="color: #30A0E0;">
                    <tr>
                      <p>
                      <td class="ukuran-table">Nama Pemilik Rekening</td>
                      <tr>
                        <td>
                          <input type="text" name="#" class="css-input-metode-pembayaran">
                        </td>
                      </tr>
                    </tr>
                    <tr>
                      <td class="ukuran-table">Bank Pengirim</td>
                      <tr>
                        <td>
                          <input type="text" name="#" class="css-input-metode-pembayaran">
                        </td>
                      </tr>
                    </tr>
                    <tr>
                      <td class="ukuran-table">No. Rekening Pengirim</td>
                      <tr>
                        <td>
                          <input type="text" name="#" class="css-input-metode-pembayaran">
                          <p>
                        </td>
                      </tr>
                    </tr>
                    <tr>
                      <td>
                        <input type="submit" name="submit" class="css-button-metode-pembayaran" value="Buat Pesanan Donasi"><a href="../../index.php"></a>
                      </td>
                    </tr>
                  </table>
                  <!-- End -->
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2020 .</strong>
    Terumbu Karang Jawa Barat.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>

