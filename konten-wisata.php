<?php include 'build/config/connection.php';
// session_start();
$url_sekarang = basename(__FILE__);

$stmt = $pdo->prepare('SELECT * From tb_paket_wisata LEFT JOIN t_lokasi ON tb_paket_wisata.id_lokasi = t_lokasi.id_lokasi and tb_paket_wisata.id_lokasi > 0');
$stmt->execute();
$rowpaket = $stmt->fetchAll();



// Tabel apa ini????
// $stmt = $pdo->prepare('SELECT * FROM t_penjelasan');
// $stmt->execute();
// $rowkontet = $stmt->fetchAll();

$sqlpaketSelect = 'SELECT * FROM t_wisata
LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
WHERE tb_paket_wisata.status_aktif = "Aktif"';

$stmt = $pdo->prepare($sqlpaketSelect);
$stmt->execute();
$rowWisata = $stmt->fetchAll();

// var_dump($rowWisata);
// die;
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icon Title -->
    <link rel="icon" href="images/coral.svg">
    <title>Konservasi dan Wisata Terumbu Karang Jawa Barat</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/tkjb.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b41ecad032.js" crossorigin="anonymous"></script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <!--Leaflet panel layer CSS-->
    <link rel="stylesheet" href="dist/css/leaflet-panel-layers.css" />
    <!-- Leaflet Marker Cluster CSS -->
    <link rel="stylesheet" href="dist/css/MarkerCluster.css" />
    <link rel="stylesheet" href="dist/css/MarkerCluster.Default.css" />
    <style>
        .carousel-item img {
            position: absolute;
            object-fit: cover;
            top: 0;
            left: 0;
            min-height: 250px;
        }

        .carousel-item {
            height: 250px;
        }
    </style>
</head>

<body>
    <div class="informational">
        <div class="informational-container">
            <!-- Navbar Container-->
            <div class="navbar-tkjb fixed-top">
                <!-- Navbar -->
                <nav class="flex-wrap navpadd navbar navbar-expand-lg navbar-light ">
                    <!-- Navbar First Layer -->
                    <!-- Logo Holder -->
                    <a class="navbar-brand" href="index.php">
                        <img id="logo-tkjb-navbar" src="images/gokarang.png">
                    </a>
                    <!-- Menu Toogler -->
                    <button class="navbar-toggler custom-toggler hamburger-menu" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon "></span>
                    </button>
                    <!-- Button & Link Action -->
                    <ul class="ml-auto d-none d-lg-block navbar-nav">
                        <button class="btn radius-50 py-1.5 px-5 ml-3 btn-donasi " onclick="window.location.href='konten-donasi.php'">Donasi</button>
                        <button class="btn radius-50 py-1.5 px-5 ml-3 btn-login " onclick="window.location.href='login.php'">Login</button>
                    </ul>
                    <!-- END Navbar First Layer -->
                    <!-- Navbar Second Layer -->
                    <div class="navbar-tkjb-navigation col px-0 collapse navbar-collapse" id="navbarTogglerDemo02">
                        <!-- Navbar Menu -->
                        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Beranda</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link " href="konten-donasi.php">Donasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="coralpedia.php">Coralpedia</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="coralmaps.php">Coralmaps</a>
                            </li>
                            <li class="nav-item active  teks-biru">
                                <a class="nav-link current" href="konten-wisata.php?aksi=wisata">Info Wisata</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Pantai Jawa Barat
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="tangkolak/index.php" target="_blank">Pantai Tangkolak</a>
                                    <a class="dropdown-item" href="#">Pulau Biawak</a>
                                </div>
                            </li>
                        </ul>
                        <!-- END Navbar Menu -->
                        <!-- Navbar Button & Link Action Mobile Version-->
                        <div class="d-flex d-lg-none p-3 mobile-act-button">
                            <div class="row-mid">
                                <button class="btn radius-50 py-1.5 px-5  btn-donasi " onclick="window.location.href='konten-donasi.php'">Donasi</button>
                            </div>
                            <div class="row-mid d-none d-md-block">
                                <p>

                                </p>
                            </div>
                            <div class="row-mid">
                                <button class="btn radius-50 py-1.5 px-5 btn-login " onclick="window.location.href='login.php'">Login</button>
                            </div>
                        </div>
                        <!-- END Navbar Button & Link Action Mobile Version-->
                    </div>
                    <!-- END Navbar Second Layer -->
                </nav>
                <!-- END Navbar -->
            </div>
            <!-- END Navbar Container -->

            <div class="tkjb-card">

                <div class="container-fluid pt-4 ">
                    <span>
                        <h2 class="border-bottom">Penjelasan Wisata</h2>
                    </span>
                    <div class="row card-body">
                        <?php foreach ($rowWisata as $wisata) : ?>

                            <div class="col-md-4">
                                <div class="card card-pilihan mb-4 shadow-sm">
                                    <a href="">
                                        <img class="card-img-top berita-img" width="100%" src="<?= $wisata->image_wisata ?>">
                                    </a>
                                    <div class="card-body">
                                        <p>
                                        <h5 class="max-length">Wisata <?= $wisata->judul_wisata ?></h5>
                                        </p>
                                        <p class="max-length2"></p>
                                        <div class="collapse" id="collapseExample<?= $wisata->id_wisata ?>">
                                            <div class="card card-body">
                                                <?= $wisata->deskripsi_wisata ?>
                                            </div>
                                        </div>
                                        <p>
                                            <a class="btn btn-primary btn-sm btn-block mb-4 btn-kata-media2" data-toggle="collapse" href="#collapseExample<?= $wisata->id_wisata ?>" role="button" aria-expanded="false" aria-controls="collapseExample"> Lihat Detail</a>
                                            <!-- <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" href="paket_wisata.php" target="_blank">Lihat Paket Wisata</a> -->
                                    </div>
                                </div>
                            </div>

                        <?php endforeach ?>
                    </div>
                </div>

                <div class="container-fluid pt-4">
                    <h2> Pilih Wisata Terumbu Karang Di Jawa Barat </h2>
                    <!-- <div id="mapid" style="height: 640px; width: 100%; margin-top: 20px;"></div> -->
                    <div class="row">
                        <?php
                        foreach ($rowpaket as $rowitem) {
                            // tanggal sekarang
                            $tgl_sekarang = date("Y-m-d");
                            // tanggal pembuatan batas pemesanan paket wisata
                            $tgl_awal = $rowitem->tgl_pemesanan;
                            // tanggal berakhir pembuatan batas pemesanan paket wisata
                            $tgl_akhir = $rowitem->tgl_akhir_pemesanan;
                            // jangka waktu + 365 hari
                            $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                            //tanggal expired
                            $tgl_exp = date("Y-m-d", $jangka_waktu);
                            if ($rowitem->status_aktif == "Aktif" && $tgl_sekarang <= $tgl_exp) { ?>

                                <div class="col-md-4" style="text-align: left;">
                                    <div class="card card-pilihan mb-4 shadow-sm">
                                        <a href="detail_lokasi_wisata.php?id_paket_wisata=<?= $rowitem->id_paket_wisata ?>">
                                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <img class="card-img-top d-block w-60" src="<?= $rowitem->foto_wisata ?>" height="300px" alt="">
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
                                                            <img class="card-img-top d-block w-60" src="<?= $wisata->image_wisata ?>" height="300px" alt="">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-body" style="font-weight: bold;">
                                            <p>
                                            <h5 class="max-length" style="font-weight: bold;"><?= $rowitem->nama_paket_wisata ?></h5>
                                            </p>
                                            <p class="max-length2">
                                                <i class="fas fa-map-marked-alt"></i> <?= $rowitem->nama_lokasi ?>
                                            </p>
                                            <div>
                                                <!-- Select Wisata -->
                                                <div class="card card-body" style="text-align: left;">
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
                                                                        <?= $wisata->jadwal_wisata ?>
                                                                    </span>
                                                                </div>
                                                            </h5>
                                                            <hr class="mr-4">

                                                            <!-- Judul Wisata -->
                                                            <li>Wisata:
                                                                <span style="font-weight:normal;">
                                                                    <?= $wisata->judul_wisata ?>
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
                                                            $stmt->execute([
                                                                'id_wisata' => $wisata->id_wisata,
                                                                'id_paket_wisata' => $rowitem->id_paket_wisata
                                                            ]);
                                                            $rowfasilitas = $stmt->fetchAll();

                                                            foreach ($rowfasilitas as $allfasilitas) { ?>
                                                                <i class="text-info fas fa-arrow-circle-right"></i>
                                                                <span style="font-weight:normal;">
                                                                    <?= $allfasilitas->pengadaan_fasilitas ?>
                                                                </span><br>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </ol>
                                                </div>

                                                <!-- Biaya Paket Kalkulasi Dari Biaya Fasilitas -->
                                                <div class="card card-body">
                                                    <?php
                                                    $sqlviewfasilitas = 'SELECT SUM(biaya_kerjasama) AS total_biaya_fasilitas, pengadaan_fasilitas, biaya_kerjasama, biaya_asuransi
                                                            FROM tb_fasilitas_wisata 
                                                            LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                                            LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                            LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                            LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                            LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                                                            WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                            AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                                    $stmt = $pdo->prepare($sqlviewfasilitas);
                                                    $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                                    $rowfasilitas = $stmt->fetchAll();

                                                    foreach ($rowfasilitas as $fasilitas) {

                                                        // Menjumlahkan biaya asuransi dan biaya paket wisata
                                                        $asuransi       = $fasilitas->biaya_asuransi;
                                                        $wisata         = $fasilitas->total_biaya_fasilitas;
                                                        $total_paket    = $asuransi + $wisata;

                                                    ?>
                                                        Rp. <?= number_format($total_paket, 0) ?>
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
                                            $tgl_exp = date("Y-m-d", $jangka_waktu);

                                            if ($tgl_sekarang >= $tgl_exp) { ?>
                                                <p class="btn btn-primary-paket btn-lg-paket btn-paket btn-block mb-4">
                                                    Reservasi Ditutup</p>
                                            <?php } else { ?>
                                                <a class="btn btn-primary-paket btn-lg-paket btn-paket btn-block mb-4" href="detail_lokasi_wisata.php?id_paket_wisata=<?= $rowitem->id_paket_wisata ?>">
                                                    Reservasi</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Footer -->
        <section id="footer">
            <div class="row">
                <div class="blogo col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <a href="#"><img src="images/gokarang.png" id="footer-logo" alt="Tangkolak Footer Logo"></a>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 cr-tkjb">
                    <div class="cpt text-light text-center">
                        <p><a href="about_us.php" style="text-decoration: none !important; color:white; ">© 2021 - Terumbu Karang Jawa Barat</a></p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <div class="ftaw text-light text-center">
                        <a href="#" target="_blank"><i class="fa fa-phone-square-alt"></i></a>
                        <a href="#" target="_blank"><i class="fas fa-envelope-square"></i></a>
                        <a href="#" target="_blank"><i class="fa fa-facebook-square"></i></a>
                        <a href="#" target="_blank"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Footer -->
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
        <!-- Leaflet Marker Cluster -->
        <script src="dist/js/leaflet.markercluster-src.js"></script>
        <!-- Leaflet panel layer JS-->
        <script src="dist/js/leaflet-panel-layers.js"></script>
        <!-- Leaflet Ajax, Plugin Untuk Mengloot GEOJson -->
        <script src="dist/js/leaflet.ajax.js"></script>
        <!-- Leaflet Map -->
        <?php include 'dist/js/leaflet_map.php'; ?>
        <!-- Bootstrap JS & JQuery -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>