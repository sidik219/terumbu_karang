<?php 
include '../../build/config/connection.php';
?>

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
                <h3 class="card-title" style="font-weight:bold; text-align: center;">Review Donasi</h3>
              </div>
              <!-- /.card-header -->

              <!-- form start -->
              <div class="card-body">
                <form action="#" method="POST">
                  <table align="center">
                    <tr>
                      <td style="color: #30A0E0; font-size: 18px; padding-right: 116px;">
                        Lokasi penanaman dipilih:
                      </td>
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
                    <?php } ?>
                    <!-- End -->  
                  </table>

                  <table align="center">
                    <thead>
                      <tr> 
                        <p>
                        <th style="color: #30A0E0;">
                          Pilihan Terumbu Karang:
                        </th>
                      </tr>
                    </thead>

                    <!-- Query Terumbu Karang -->
                    <?php
                    $get = $_GET['id_tk'];
                    $sql_view = "SELECT * FROM t_terumbu_karang
                    WHERE nama_terumbu_karang LIKE '%Acropora%'
                    AND id_terumbu_karang='$get'";

                    foreach ($pdo->query($sql_view) as $result) { ?>
                    <tbody>
                      <tr>
                        <td style="display:none;"><?php echo $result->id_terumbu_karang; ?></td>
                        <td>
                          <img src="../image/terumbu-karang/<?php echo $result->foto_terumbu_karang?>" width="200" height="110"/>
                        </td>
                        <td>
                          <span style="color: #30A0E0; margin-left: 5px;">
                            <b>
                              <input type="qty" name="qty" id="qty" value="2" onchange="myFunction()" style="color: #30A0E0; width: 25px; font-weight:bold; border: none;"> 
                            </b>
                            x 
                            <input type="hargaTK" name="hargaTK" id="hargaTK" value="<?php echo $result->harga_tk; ?>" style="color: #30A0E0; width:50px; border: none;"> 
                          </span> 
                        </td>
                      </tr>
                      <tr>
                        <td style="color: #30A0E0;"><?php echo $result->nama_terumbu_karang; ?></td>
                        <td style="display:none;"><?php echo $result->deskripsi_terumbu_karang; ?></td>
                      </tr>
                    </tbody>
                    <?php } ?>
                    <!-- End -->
                  </table>
                
                  <table align="center">
                    <tr>
                      <p>
                      <td colspan="2">
                        <label style="color: #30A0E0;">Pesan / Ekspresi:</label><br>
                        <input type="text" name="pesan" size="30" style=" color: #30A0E0; border: none; border: solid 1px; border-radius: 5px;">
                      </td>
                    </tr>
                    <tr>
                      <td style="color: #30A0E0; padding-left: 20px;">Subtotal:<br>
                        <span id="subtotal" style="font-weight:bold; font-size: 19px;">Rp. </span>
                      </td>
                      <td>
                        <button type="button" name="submit" class="btn btn-warning" style="color: white; background-color: #FF5733; margin-left: 90px; border: none; border-radius: 20px;">
                          <a href="h_metode_pembayaran.php" style="color: white;">
                            Bayar Donasi
                          </a>
                        </button>
                      </td>
                    </tr>
                  </table>
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
<script type="text/javascript">
  function myFunction()
  {
    var qty = document.getElementById("qty").value;
    var hargaTK = document.getElementById("hargaTK").value;
    var subtotal = qty * hargaTK;

    document.getElementById("subtotal").innerHTML = "Rp. " + subtotal;
  }
</script>
</body>
</html>

