<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

  // else{
  //     $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
  // }

    if($_GET['id_lokasi']){
        $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
    }
    else if(!$_GET['id_lokasi' && !$_SESSION['id_lokasi']]){
        header("Location: map.php?aksi=wisata");
    }

    $sqlpaket = 'SELECT * FROM tb_paket_wisata
                LEFT JOIN t_lokasi ON tb_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                WHERE tb_paket_wisata.id_lokasi = :id_lokasi';

    $stmt = $pdo->prepare($sqlpaket);
    $stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
    $rowpaket = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pilih Wisata - TKJB</title>
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
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style-card.css">
    <!-- Favicon -->
    <link rel="icon" href="dist/img/KKPlogo.png" type="image/x-icon" />
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
            <a href="index_admin.php" class="brand-link">
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
                <div class="row">

                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
            <?php if($_SESSION['level_user'] == '1') { ?>
                <div class="container-fluid">
                    <h3>Pilihan Wisata</h3>
                    <a href="map.php?aksi=wisata"><button class="btn btn-warning btn-back" type="button">
                        <i class="fas fa-angle-left"></i> Ganti Lokasi Wisata</button></a><p>
                    <div class="row">
                    <?php
                    foreach ($rowpaket as $rowitem) {
                        $reservasidate = strtotime($rowitem->tgl_akhir_pemesanan);
                        if ($rowitem->status_aktif == "Aktif") { ?>

                        <div class="col-md-4" style="text-align: left;">
                            <div class="card card-pilihan mb-4 shadow-sm">
                                <a href="detail_lokasi_wisata.php?id_paket_wisata=<?=$rowitem->id_paket_wisata?>">
                                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img class="card-img-top d-block w-60" src="<?=$rowitem->foto_wisata?>" height="300px" alt="">
                                            </div>
                                            <!-- Select Wisata -->
                                            <?php
                                            $sqlpaketSelect = 'SELECT * FROM t_wisata
                                                            LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                            WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlpaketSelect);
                                            $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                            $rowWisata = $stmt->fetchAll();

                                            foreach ($rowWisata as $wisata) { ?>
                                            <div class="carousel-item">
                                                <img class="card-img-top d-block w-60" src="<?=$wisata->image_wisata?>" height="300px" alt="">
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-body" style="font-weight: bold;">
                                    <p><h5 class="max-length" style="font-weight: bold;"><?=$rowitem->nama_paket_wisata?></h5></p>
                                    <p class="max-length2">
                                    <i class="fas fa-map-marked-alt"></i> <?=$rowitem->nama_lokasi?></p>
                                    <div>
                                        <!-- Select Wisata -->
                                        <div class="card card-body" style="text-align: left;">
                                            
                                            <!-- Batas Pemesanan -->
                                            <div>
                                                <div class="d-flex flex-sm-row flex-column mb-2">
                                                    <div class="mr-auto p-2">
                                                        <small style="font-weight:bold;">
                                                            Batas Pemesanan:
                                                        </small>
                                                    </div>
                                                    <div class="p-2">
                                                        <small style="font-weight:normal;">
                                                            <?= strftime('%A, %d %B %Y', $reservasidate); ?>
                                                        </small>
                                                    </div>
                                                </div>
                                                <h5>
                                                    <?php
                                                    // tanggal sekarang
                                                    $tgl_sekarang = date("Y-m-d");
                                                    // tanggal pembuatan batas pemesanan paket wisata
                                                    $tgl_awal = $rowitem->tgl_pemesanan;
                                                    // tanggal berakhir pembuatan batas pemesanan paket wisata
                                                    $tgl_akhir = $rowitem->tgl_akhir_pemesanan;
                                                    // jangka waktu + 365 hari
                                                    $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                                                    //tanggal expired
                                                    $tgl_exp = date("Y-m-d",$jangka_waktu);

                                                    if ($tgl_sekarang >= $tgl_exp) { ?>
                                                        <i class="text-danger fas fa-tag"></i>
                                                        Sudah Tidak Berlaku.
                                                    <?php } else { ?>
                                                        <i class="text-success fas fa-tag"></i>
                                                        Masih dalam jangka waktu.
                                                    <?php }?>
                                                </h5>
                                            </div>

                                            <ol style="margin-left: 1rem;">
                                            <?php
                                            $sqlpaketSelect = 'SELECT * FROM t_wisata
                                                            LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                            WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlpaketSelect);
                                            $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                            $rowWisata = $stmt->fetchAll();

                                            foreach ($rowWisata as $wisata) { ?>
                                                <!-- Deskripsi Wisata -->
                                                <hr class="mt-4 mr-4">
                                                <h5 class="mb-4">
                                                    <div class="deskripsi-paket">
                                                        <span class="badge badge-pill badge-warning">
                                                            <?=$wisata->jadwal_wisata?>
                                                        </span>
                                                    </div>
                                                </h5>
                                                <hr class="mr-4">

                                                <!-- Judul Wisata -->
                                                <label></label>
                                                <li>Wisata: 
                                                    <span style="font-weight:normal;">
                                                        <?=$wisata->judul_wisata?>
                                                    </span>
                                                </li>

                                                <!-- Select Fasilitas -->
                                                <?php
                                                $sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata
                                                                    LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                                                    LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                                    LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                                    LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                                    WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                                    AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata
                                                                    AND t_wisata.id_wisata = :id_wisata';

                                                $stmt = $pdo->prepare($sqlviewfasilitas);
                                                $stmt->execute(['id_wisata' => $wisata->id_wisata,
                                                                'id_paket_wisata' => $rowitem->id_paket_wisata]);
                                                $rowfasilitas = $stmt->fetchAll();

                                                foreach ($rowfasilitas as $allfasilitas) { ?> 
                                                <i class="text-info fas fa-arrow-circle-right"></i>   
                                                <span style="font-weight:normal;">              
                                                    <?=$allfasilitas->pengadaan_fasilitas?>
                                                </span><br>
                                                <?php } ?>
                                            <?php } ?>
                                            </ol>
                                        </div>

                                        <!-- Biaya Paket Kalkulasi Dari Biaya Fasilitas -->
                                        <div class="card card-body">
                                        <?php
                                        $sqlviewfasilitas = 'SELECT SUM(biaya_kerjasama) AS total_biaya_fasilitas, pengadaan_fasilitas, biaya_kerjasama, biaya_asuransi, harga_donasi
                                                            FROM tb_fasilitas_wisata 
                                                            LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                                            LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                            LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                            LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                            LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                                                            LEFT JOIN t_lokasi ON tb_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                                                            WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                            AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                        $stmt = $pdo->prepare($sqlviewfasilitas);
                                        $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                        $rowfasilitas = $stmt->fetchAll();

                                        foreach ($rowfasilitas as $fasilitas) { 
                                            
                                        // Menjumlahkan biaya asuransi dan biaya paket wisata
                                        $donasi         = $fasilitas->harga_donasi;
                                        $asuransi       = $fasilitas->biaya_asuransi;
                                        $wisata         = $fasilitas->total_biaya_fasilitas;
                                        $total_paket    = $asuransi + $wisata;
                                        $hasil          = $donasi + $total_paket;
                                        
                                        ?>
                                        Rp. <?=number_format($hasil, 0)?>
                                        <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                    // tanggal sekarang
                                    $tgl_sekarang = date("Y-m-d");
                                    // tanggal pembuatan batas pemesanan paket wisata
                                    $tgl_awal = $rowitem->tgl_pemesanan;
                                    // tanggal berakhir pembuatan batas pemesanan paket wisata
                                    $tgl_akhir = $rowitem->tgl_akhir_pemesanan;
                                    // jangka waktu + 365 hari
                                    $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                                    //tanggal expired
                                    $tgl_exp = date("Y-m-d",$jangka_waktu);

                                    if ($tgl_sekarang >= $tgl_exp) { ?>
                                        Rincian Reservasi Ditutup
                                    <?php } else { ?>
                                        <a class="btn btn-primary-paket btn-lg-paket btn-paket btn-block mb-4" href="detail_lokasi_wisata.php?id_paket_wisata=<?=$rowitem->id_paket_wisata?>">
                                        Rincian Reservasi</a>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    <?php } ?>
                    </div>
                </div>
            <?php } ?>
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

    <footer class="main-footer">
        <strong>Copyright &copy; 2020 .</strong> Terumbu Karang Jawa Barat
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
    <script>
        $('.carousel').carousel({
            interval: 2000
        })
</script>
    
</body>
</html>
