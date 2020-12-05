<?php include 'build/config/connection.php';
session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

if(!$_GET['id_jenis']){
    header("Location: pilih_jenis_tk.php");
}

$sqlviewtk = 'SELECT * FROM t_terumbu_karang
                LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                WHERE t_terumbu_karang.id_jenis = :id_jenis';
$stmt = $pdo->prepare($sqlviewtk);
$stmt->execute(['id_jenis' => $_GET['id_jenis']]);
$row = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pilih Jneis Terumbu Karang - TKJB</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>
 <script src="js\numberformat.js"></script>
<body class="hold-transition sidebar-mini layout-fixed">
    <a href="#" class="scrollup"><img class="scrollup" src="images/cart.png"></a>
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
                            <a class="dropdown-item" href="logout.php">Logout</a>
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
                    <?php //if($_SESSION['level_user'] == '2') { ?>
                        <li class="nav-item  ">
                           <a href="dashboard_user.php" class="nav-link ">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item menu-open">
                           <a href="donasi_saya.php" class="nav-link active">
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
                           <a href="#" class="nav-link">
                                <i class="nav-icon fas fas fa-disease"></i>
                                <p> Terumbu Karang  </p>
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
                                <i class="nav-icon fas fas fa-globe-asia"></i>
                                <p> Peta Lokasi</p>
                           </a>
                        </li>

                    <?php //} ?>
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
        <?php //if($_SESSION['level_user'] == '2') { ?>
            <div class="row">
                    <div class="col">


        <h4 class="font-weight-bold">Pilih Terumbu Karang</h4>
        <button class="btn btn-warning btn-back" type="button">< Jenis Lainnya</button>
        <div class="row shop-items">
            <div class="card-columns">
        <?php
            foreach ($row as $rowitem) {
        ?>

            <div class="card  card-pilihan mb-4 shadow-sm shop-item">
                <a href="#">
                    <img class="card-img-top shop-item-image" height="150px" width="150px" src="<?=$rowitem->foto_terumbu_karang?>"
                ></a>
                <div class="card-body pt-2">
                <h5 class="shop-item-title mb-0 card-title"><?=$rowitem->nama_terumbu_karang?></h5>
                <p class="card-text text-muted deskripsi_pilih_tk"><?php echo strlen($rowitem->deskripsi_terumbu_karang) > 50 ? substr($rowitem->deskripsi_terumbu_karang,0,40)."..." :$rowitem->deskripsi_terumbu_karang;?></p>
                <span class="font-weight-bold" id="harga<?=$rowitem->id_terumbu_karang?>"><script>
                                                    var hargaformat = formatter.format(<?=$rowitem->harga_terumbu_karang?>);
                                                    var hargap =  document.createElement('p')
                                                    hargap.classList.add("mb-0", "mt-0")
                                                    hargap.textContent = hargaformat
                                                    document.getElementById("harga<?=$rowitem->id_terumbu_karang?>").appendChild(hargap)
                                            </script></span>
                <span class="shop-item-price d-none">Rp. <?=$rowitem->harga_terumbu_karang?></span>
                <input type="hidden" class="shop-item-id" value="<?=$rowitem->id_terumbu_karang?>">
                <div class="row">
                    <!-- <div class="col-2">
                        <input type="number" min="1" id="tbqty" style="width: 100%; height:100%;">
                    </div> -->
                    <div class="col">
                            <a data-nama_tk="<?=$rowitem->id_terumbu_karang?>" data-harga_tk="<?=$rowitem->harga_terumbu_karang?>"
                            data-id_tk="<?=$rowitem->id_terumbu_karang?>"
                            class="add-to-cart btn btn-warning shop-item-button"><i class="nav-icon fas fa-cart-plus"></i> Tambah ke Keranjang</a>
                    </div>
                </div>

        </div>
    </div>
        <?php } ?>
        <input type="text" class="d-none" id="id-lokasi" value="<?=$_SESSION['id_lokasi']?>">
            </div>
            </div>
        </div>
            </section>
            <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->
        </div>
    <?php //} ?>
        <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

 <footer class="main-footer">
        <section class="container content-section">
            <h4 class="section-header font-weight-bold" id="keranjang">Keranjang Anda</h4>
            <div class="cart-row row">
                <div class="col"><span class="cart-item cart-header cart-column">Nama</span></div>
                <div class="col"><span class="cart-price cart-header cart-column">Harga</span></div>
                <div class="col"><span class="cart-quantity cart-header cart-column">Jumlah</span></div>
            </div>
            <div class="cart-items">
            </div>
            <div class="cart-total">
                <strong class="cart-total-title">Total</strong>
                <span class="cart-total-price font-weight-bold">Rp0</span>
            </div>
            <div class="mb-3 text-center mt-2">
              <h5 class="font-weight-bold">Pesan / Ekspresi</h5><label for="pesan" class="font-weight-normal">
              (Opsional. Pesan akan disertakan dalam label khusus pada terumbu karang )</label>
              <input type="text" maxlength="64" class="form-control success" id="pesan" placeholder="Isi pesan anda di sini...">
            </div>
            <button class="btn btn-warning btn-back" type="button">< Jenis Lainnya</button>
            <button class="btn btn-primary btn-purchase" type="button">Selesai Pilih ></button>
        </section>
    </footer>
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
    <!-- Shopping Cart -->
    <script src="js\shopping_cart.js" async></script>
    <script src="js\numberformat.js"></script>

    <script>
    </script>

</body>
</html>
