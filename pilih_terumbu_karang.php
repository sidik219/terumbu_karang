<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if($_GET['id_lokasi']){
      $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
  }
else if(!$_GET['id_lokasi' && !$_SESSION['id_lokasi']]){
      header("Location: map.php");
  }

  if(isset($_GET['id_jenis']) && ((!$_GET['id_jenis']) == "")){
    $id_jenis = $_GET['id_jenis'];

    $sqlviewtk = 'SELECT * FROM t_detail_lokasi
                LEFT JOIN t_terumbu_karang ON t_terumbu_karang.id_terumbu_karang = t_detail_lokasi.id_terumbu_karang
                LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                WHERE t_terumbu_karang.id_jenis = :id_jenis
                AND id_lokasi = :id_lokasi AND stok_terumbu > 0';

    $stmt = $pdo->prepare($sqlviewtk);
    $stmt->execute(['id_jenis' => $_GET['id_jenis'], 'id_lokasi' => $_SESSION['id_lokasi']]);
    $row = $stmt->fetchAll();
  }elseif(isset($_GET['id_jenis']) && (($_GET['id_jenis']) == "")){
    $sqlviewtk = 'SELECT * FROM t_detail_lokasi
                LEFT JOIN t_terumbu_karang ON t_terumbu_karang.id_terumbu_karang = t_detail_lokasi.id_terumbu_karang
                LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                WHERE id_lokasi = :id_lokasi AND stok_terumbu > 0';

    $stmt = $pdo->prepare($sqlviewtk);
    $stmt->execute(['id_lokasi' => $_SESSION['id_lokasi']]);
    $row = $stmt->fetchAll();
  }
  else{
    $sqlviewtk = 'SELECT * FROM t_detail_lokasi
                LEFT JOIN t_terumbu_karang ON t_terumbu_karang.id_terumbu_karang = t_detail_lokasi.id_terumbu_karang
                LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                WHERE id_lokasi = :id_lokasi AND stok_terumbu > 0';

    $stmt = $pdo->prepare($sqlviewtk);
    $stmt->execute(['id_lokasi' => $_SESSION['id_lokasi']]);
    $row = $stmt->fetchAll();
  }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pilih Jenis Terumbu Karang - TKJB</title>
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
    <link rel="icon" href="dist/img/KKPlogo.png" type="image/x-icon" />

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
                    <?php print_sidebar(basename(__FILE__), $_SESSION['level_user'])?> <!-- Print sidebar -->
                    </ul>
                </nav>
                <!-- END OF SIDEBAR MENU -->
            </div>
            <!-- SIDEBAR -->
        </aside>

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper bg-light">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
        <?php if($_SESSION['level_user'] == '1') { ?>
            <div class="row">
                    <div class="col">

          <a href="map.php" class="btn btn-primary btn-sm btn-blue"><i class="fas fa-angle-left"></i> Ganti Lokasi Penanaman</button></a>
        <h4 class="font-weight-bold mt-2">Pilih Terumbu Karang</h4>
        <!-- <button class="btn btn-warning btn-back" type="button"><i class="fas fa-angle-left"></i> Jenis Lainnya</button> -->

        <div class="row">
                      <div class="col">
                        <div class="dropdown show">
                          <a class="btn btn-warning mb-2 dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pilih Jenis
                          </a>

                          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="pilih_terumbu_karang.php?id_lokasi=<?=$_SESSION['id_lokasi'];?>">Tampilkan Semua</a>

                            <?php

                            $sqlviewjenis = 'SELECT * FROM t_detail_lokasi
                                              LEFT JOIN t_terumbu_karang ON t_terumbu_karang.id_terumbu_karang = t_detail_lokasi.id_terumbu_karang
                                              LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                                              AND id_lokasi = :id_lokasi AND stok_terumbu > 0 AND t_jenis_terumbu_karang.id_jenis IS NOT NULL
                                              GROUP BY t_jenis_terumbu_karang.id_jenis';

                            $stmt = $pdo->prepare($sqlviewjenis);
                            $stmt->execute(['id_lokasi' => $_SESSION['id_lokasi']]);
                            $rowjenis = $stmt->fetchAll();

                            foreach($rowjenis as $jenis){
                            ?>
                            <a class="dropdown-item" href="pilih_terumbu_karang.php?id_lokasi=<?=$_SESSION['id_lokasi']?>&id_jenis=<?= $jenis->id_jenis?>"><?=$jenis->nama_jenis?></a>
                            <?php } ?>
                        </div>
                    </div>
                      </div>
        </div>

        <div class="row shop-items">
            <div class="card-columns">
        <?php
            foreach ($row as $rowitem) {
        ?>

            <div class="card  card-pilihan rounded mb-4 shadow-sm shop-item text-sm">
                <a href="#">
                    <img class="card-img-top rounded shop-item-image" height="150px" width="150px" src="<?=$rowitem->foto_terumbu_karang?>"
                ></a>
                <div class="card-body pt-2">
                <h5 class="shop-item-title mb-0 card-title"><?=$rowitem->nama_terumbu_karang?></h5>
                <p class="card-text text-muted deskripsi_pilih_tk text-sm"><?php echo $rowitem->deskripsi_terumbu_karang;?></p>
                <?php //echo strlen($rowitem->deskripsi_terumbu_karang) > 50 ? substr($rowitem->deskripsi_terumbu_karang,0,40)."..." :$rowitem->deskripsi_terumbu_karang;?>
                <span class="font-weight-bold" id="harga<?=$rowitem->id_terumbu_karang?>"><script>
                                                    var hargaformat = formatter.format(<?=$rowitem->harga_patokan_lokasi?>);
                                                    var hargap =  document.createElement('p')
                                                    hargap.classList.add("mb-0", "mt-0")
                                                    hargap.textContent = hargaformat
                                                    document.getElementById("harga<?=$rowitem->id_terumbu_karang?>").appendChild(hargap)
                                            </script></span>
                <span class="shop-item-price d-none">Rp. <?=$rowitem->harga_patokan_lokasi?></span>
                <input type="hidden" class="shop-item-id" value="<?=$rowitem->id_terumbu_karang?>">
                <div class="row">
                    <!-- <div class="col-2">
                        <input type="number" min="1" id="tbqty" style="width: 100%; height:100%;">
                    </div> -->
                    <div class="col">
                            <a data-nama_tk="<?=$rowitem->id_terumbu_karang?>" data-harga_tk="<?=$rowitem->harga_patokan_lokasi?>"
                            data-id_tk="<?=$rowitem->id_terumbu_karang?>" data-stok_tk="<?=$rowitem->stok_terumbu?>"
                            class="add-to-cart btn btn-warning shop-item-button"><i class="nav-icon fas fa-cart-plus"></i> Tambahkan Keranjang</a>
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
    <?php } ?>
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

 <footer class="main-footer bg-light p-0 border-top-0">
        <section class="container bg-white content-section p-3 shadow-lg pb-0 rounded">
            <h4 class="section-header font-weight-bold" id="keranjang"><i class="fas fa-cart-arrow-down"></i> Keranjang Anda</h4>
            <div class="cart-items">
            </div>
            <div class="cart-total">
                <strong class="cart-total-title">Subtotal</strong>
                <span class="cart-total-price font-weight-bold">Rp0</span>
            </div>
            <div class="mb-3 text-center mt-2">
              <h5 class="font-weight-bold">Pesan / Ekspresi</h5><label for="pesan" class="font-weight-normal">
              (Opsional. Pesan akan disertakan dalam label khusus pada terumbu karang )</label>
              <input type="text" maxlength="64" class="form-control success" id="pesan" placeholder="Isi pesan anda di sini...">
            </div>
            <!-- <button class="btn btn-warning btn-back" type="button"><i class="fas fa-angle-left"></i> Jenis Lainnya</button> -->
            <button class="btn btn-primary btn-purchase btn-blue" onclick="updateCartTotal()" type="button">Selesai Pilih <i class="fas fa-angle-double-right"></i></button>
        </section>
    </footer>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- Shopping Cart -->
    <script src="js\shopping_cart.js" async></script>
    <script src="js\numberformat.js"></script>

    <script>
      //pre fill pesan text
      var keranjang_deserialised = JSON.parse(sessionStorage.getItem('keranjang_serialised'))
      var isipesan = keranjang_deserialised["pesan"]

      document.getElementById('pesan').value = isipesan



    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function() {
        // $("html, body").animate({
        //     scrollTop: 0
        // }, 600);
        // return false;
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#keranjang").offset().top
        }, 1000);
    });

    </script>

</body>
</html>
