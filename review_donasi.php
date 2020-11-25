<?php 
include 'build/config/connection.php'; 
session_start();

$sqlviewlokasi = 'SELECT * FROM t_lokasi
                WHERE id_lokasi = :id_lokasi
                    ';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute(['id_lokasi' => $_SESSION['id_lokasi']]);
        $rowlokasi = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Review Donasi - TKJB</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
        <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
        <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<script>
if (sessionStorage.getItem('keranjang_serialised') == undefined){
  document.location.href = 'map.php';
}
  var keranjang = JSON.parse(sessionStorage.getItem('keranjang_serialised'))  
</script>


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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">Edit Profil</a>
                            <a class="dropdown-item" href="#">Logout</a>              
                </li>  
            </ul>
        </nav>
        <!-- END OF NAVBAR -->

        <!-- TOP SIDEBAR -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- BRAND LOGO (TOP)-->
            <a href="dashboard_user.php" class="brand-link">
                <img src="dist/img/KKPlogo.png"  class="brand-image img-circle elevation-3" style="opacity: .8">
                <!-- BRAND TEXT (TOP) -->
                <span class="brand-text font-weight-bold">TKJB</span>
            </a>
            <!-- END OF TOP SIDEBAR -->

            <!-- SIDEBAR -->
            <div class="sidebar">
                <!-- SIDEBAR MENU -->
                <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                      <li class="nav-item  ">
                          <a href="dashboard_user.php" class="nav-link ">
                              <i class="nav-icon fas fa-home"></i>
                              <p> Home </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="donasi_saya.php" class="nav-link">
                              <i class="nav-icon fas fa-hand-holding-usd"></i>
                              <p> Donasi Saya </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="reservasi_saya.php" class="nav-link">
                              <i class="nav-icon fas fa-suitcase"></i>
                              <p> Reservasi Saya  </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="profil_saya.php" class="nav-link">
                              <i class="nav-icon fas fas fa-user"></i>
                              <p> Profil Saya  </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="map.php" class="nav-link">
                              <i class="nav-icon fas fas fa-user"></i>
                              <p> Map  </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="pilih_jenis_tk.php" class="nav-link">
                              <i class="nav-icon fas fas fa-user"></i>
                              <p> Jenis Terumbu Karang  </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="pilih_terumbu_karang.php" class="nav-link">
                              <i class="nav-icon fas fas fa-user"></i>
                              <p> Terumbu Karang  </p>
                          </a>
                      </li>
                      <li class="nav-item menu-open">
                          <a href="review_donasi.php" class="nav-link active">
                              <i class="nav-icon fas fas fa-user"></i>
                              <p> Review Donasi  </p>
                          </a>
                        </li>
                    </ul>      
                </nav>
                <!-- END OF SIDEBAR MENU -->
            </div>
            <!-- SIDEBAR -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
   
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container">
                            <h4 class="mt-3 mb-3"><span class="font-weight-bold">Review Donasi</span></h4>
            <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Keranjang Anda</span>
            <span id="badge-jumlah" class="badge badge-secondary badge-pill"></span>
          </h4>

          <ul class="list-group mb-3" id="keranjangancestor">
            <!-- listcontentrow cetak di sini -->
          </ul>

          
        </div>
        <div class="col-md-8 order-md-1 card">
            <h4 class="mb-3 card-header">Data Rekening Donatur</h4>
            <form action="" method="POST">
            <div class="mb-3">
              <label for="email">Nama Pemilik Rekening</label>
              <input type="email" class="form-control" id="nama_donatur">
            </div>
            <div class="mb-3">
              <label for="email">Nomor Rekening</label>
              <input type="email" class="form-control" id="no_rekening_donatur">
            </div>
            <div class="mb-3">
              <label for="email">Nama Bank</label>
              <input type="email" class="form-control" id="nama_bank_donatur">
            </div>


            <div class="" style="width:100%;">
                <div class="">
                    <h4 class="card-header mb-2">Metode Pembayaran</h4>
            <span class="">Pilihan untuk lokasi</span>  <span class="text-info font-weight-bolder"><?=$rowlokasi->nama_lokasi?> : </span>
            <div class="d-block my-3">
              <div class="custom-control custom-radio">
                <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
                <p class="text-muted">Harap upload bukti transfer di halaman "Donasi Saya" setelah menekan tombol konfirmasi donasi.</p>
              </div>
<hr class="mb-2"/>

            <div class="row">                
                <div class="col">
                     <span class="font-weight-bold">Nama Rekening Pengelola  
                </div>
                <div class="col-lg-8 mb-2">
                     <span class=""><?=$rowlokasi->nama_rekening?></span>
                </div>
            </div>
            <div class="row">
                <div class="col">                
                    <span class="font-weight-bold">Nomor Rekening Pengelola  </span>
                </div>
                <div class="col-lg-8  mb-2">                
                    <span class=""><?=$rowlokasi->nomor_rekening?></span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">                
                    <span class="font-weight-bold">Bank Pengelola  </span> 
                </div>
                <div class="col-lg-8  mb-2">                
                    <span class=""><?=$rowlokasi->nama_bank?></span>
                </div>
            </div>
                </div>
            </div>
             
            <button class="btn btn-primary btn-lg btn-block mb-4" type="submit">Konfirmasi Donasi</button>
          </form>
        </div>
      </div>
        <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2020 .</strong> Terumbu Karang Jawa Barat
    </footer>
    <!-- /.content-wrapper -->    

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>

    <script>
      var keranjangancestor = document.getElementById("keranjangancestor")
      var listcontentrow = document.createElement('li')
      listcontentrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed")
      // for (i = 0; i < keranjang.keranjang.length; i++){
      //   var listcontentrow = document.createElement('li')
      //   listcontentrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed")
      //   var listcontent = 
      //   `<div>
      //       <h6 class="my-0">${keranjang.keranjang[i].nama_tk}</h6>
      //     </div>
      //     <span class="text-muted">x${keranjang.keranjang[i].jumlah_tk}</span>`
      //   listcontentrow.innerHTML = listcontent
      //   keranjangancestor.prepend(listcontentrow)
      // }


      var jumlahitem = 0;
      for (item in keranjang.keranjang){
        
        var listcontentrow = document.createElement('li')
        listcontentrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed")
        var listcontent = 
        `<div>
            <h6 class="my-0">${keranjang.keranjang[item].nama_tk}</h6>
          </div>
          <span class="font-weight-bold">x${keranjang.keranjang[item].jumlah_tk}</span>`
        listcontentrow.innerHTML = listcontent
        keranjangancestor.prepend(listcontentrow)

        jumlahitem += parseInt(keranjang.keranjang[item].jumlah_tk)
      }

        var listpesanrow = document.createElement('li')
        listpesanrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed", "text-break")
        var listpesan = 
        `<div class="row">
        <div class="col-12">
            <h6 class="my-0">Pesan/Ekspresi</h6>
          </div>
          <div class="col">
          <span><i>${keranjang.pesan}</i></span>
          </div>
        </div>`
        listpesanrow.innerHTML = listpesan
        keranjangancestor.append(listpesanrow)


      var listtotalrow = document.createElement('li')
        listtotalrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed")
      var listtotal = 
        `<div>
            <h6 class="my-0 font-weight-bold">Total</h6>
          </div>
          <span class="font-weight-bold">Rp. ${keranjang.nominal}</span>`
        listtotalrow.innerHTML = listtotal
        keranjangancestor.append(listtotalrow)       

      var badgejumlah = document.getElementById("badge-jumlah")
      badgejumlah.innerText = jumlahitem


    </script>

</body>
</html>