<?php include '../build/config/connection.php';
// session_start();
$url_sekarang = basename(__FILE__);
// include 'hak_akses.php';

$stmt = $pdo->prepare('SELECT * From t_konten_lokasi');
$stmt->execute();
// $stmt->execute(array(0));
$rowKonten = $stmt->fetchAll();
// var_dump($rowKonten);
// die;
$stmt = $pdo->prepare('SELECT * From t_konten_tangkolak_section');
$stmt->execute();
// $stmt->execute(array(0));
$rowKonteninfo = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT * From t_berita_kegiatan ORDER BY `t_berita_kegiatan`.`tgl_upload` DESC');
$stmt->execute();
// $stmt->execute(array(0));
$rowKontenkegiatan = $stmt->fetchAll();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icon Title -->
    <link rel="icon" href="img/KKPlogo.png">
    <title>Wisata Bahari Tangkolak</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/pantai.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b41ecad032.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- Navbar Container-->
    <div class="navbar-tkjb fixed-top">
        <!-- Navbar -->
        <nav class="flex-wrap navpadd navbar navbar-expand-lg navbar-light ">
            <!-- Navbar First Layer -->
            <!-- Logo Holder -->
            <a class="navbar-brand" href="index.php">
                <img id="logo-tkjb-navbar" src="img/TANGKOLAK3.png">
            </a>
            <!-- Menu Toogler -->
            <button class="navbar-toggler custom-toggler hamburger-menu" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon "></span>
            </button>
            <!-- Button & Link Action -->
            <ul class="ml-auto d-none d-lg-block navbar-nav">
                <button class="btn radius-50 py-1.5 px-4 ml-3 btn-wisata " onclick="window.location.href='wisata_tangkolak.php'">Reservasi Wisata</button>
                <button class="btn radius-50 py-1.5 px-5 ml-3 btn-login " onclick="window.location.href='login.php'">Login</button>
            </ul>
            <!-- END Navbar First Layer -->
            <!-- Navbar Second Layer -->
            <div class="navbar-tkjb-navigation col px-0 collapse navbar-collapse" id="navbarTogglerDemo02">
                <!-- Navbar Menu -->
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link current" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="wisata_tangkolak.php">Wisata Bahari</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="paket_wisata.php">Paket Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="terumbu_karang.php">Terumbu Karang Tangkolak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://tkjb.or.id/" target="_blank">Website GoKarang</a>
                    </li>
                </ul>
                <!-- END Navbar Menu -->
                <!-- Navbar Button & Link Action Mobile Version-->
                <div class="d-flex d-lg-none p-3 mobile-act-button">
                    <div class="row-mid">
                        <button class="btn radius-50 py-1.5 px-4  btn-wisata " onclick="window.location.href='wisata_tangkolak.php'">Reservasi Wisata</button>
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

    <!-- Konten -->
    <div class="informational">
        <div class="informational-container">
            <!-- Header Container -->
            <div class="header-tkjb">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <!-- <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol> -->
                    <div class="carousel-inner">
                        <?php foreach ($rowKonten as $key => $lokasi) { ?>
                            <div class="carousel-item 

                                <?php
                                if ($lokasi->status_konten_lokasi == "Wisata Bahari" && $key == 0) {
                                    echo "active";
                                } else if ($lokasi->status_konten_lokasi == "Terumbu Karang" && $key == 0) {
                                    echo "active";
                                } else if ($lokasi->status_konten_lokasi == "Paket Wisata" && $key == 0) {
                                    echo "active";
                                } else {
                                    echo " ";
                                }
                                ?>">
                                <img class="d-block w-100" src="../<?= $lokasi->foto_konten_lokasi ?>" alt="" height="600px">
                                <div class="carousel-caption  d-md-block">
                                    <h3><?= $lokasi->judul_konten_lokasi ?></h3><br>
                                    <p><?= $lokasi->deskripsi_konten_lokasi ?></p><br>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- <div class="carousel-item active">
                    <img class="d-block w-100" src="img/banner1.jpg" alt="First slide">
                    </div>
                    <div class="carousel-item">
                    <img class="d-block w-100" src="img/banner1.jpg" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                    <img class="d-block w-100" src="img/banner1.jpg" alt="Third slide">
                    </div> -->
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <!-- End Header Container -->

            <!-- Konten Mid Container -->
            <div class="mid-tkjb">
                <!-- foreach sama if -->
                <?php foreach ($rowKonteninfo as $key => $info) : ?>
                    <?php if ($key == 1) : ?>
                        <div class="paragraf-awal">
                            <div class="row justify-content-between">
                                <div class="col-md-12 col-lg-5 p-5 text-center paragraf-awal-img">
                                    <img src="../<?= $info->gambar; ?>" class="img-fluid shadow-sm rounded" alt="pantai tangkolak">
                                </div>
                                <div class="col-md-12 col-lg-7 p-5 text-light paragraf-awal-caption">
                                    <h2><?= $info->judul; ?></h2>
                                    <p>
                                        <?= $info->isi_konten; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($key == 0 || $key == 2) : ?>
                        <div class="paragraf-awal">
                            <div class="row justify-content-between">
                                <div class="col-md-12 col-lg-7 p-5 text-light paragraf-awal-caption">
                                    <h2><?= $info->judul; ?></h2>
                                    <p>
                                        <?= $info->isi_konten; ?>
                                    </p>
                                </div>
                                <div class="col-md-12 col-lg-5 p-5 text-center paragraf-awal-img">
                                    <img src="../<?= $info->gambar; ?>" class="img-fluid shadow-sm rounded" alt="pantai tangkolak">
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
                <!-- endforeach sama endif -->
            </div>

            <div class="kata-media">
                <h2> Berita Kegiatan Di Tangkolak </h2>
                <div class="row">
                    <!-- foreach dari t_berita_kegiatan -->
                    <?php foreach ($rowKontenkegiatan as $kegiatan) : ?>
                        <div class="col-md-4">
                            <div class="card card-pilihan mb-4 shadow-sm">
                                <a href="">
                                    <img class="card-img-top berita-img" width="100%" src="../<?= $kegiatan->foto_kegiatan; ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="max-length"><?= $kegiatan->judul_kegiatan; ?></h5>
                                    <div class="max-length"><?= $kegiatan->deskripsi_kegiatan; ?>
                                    </div>
                                    <!-- link buat lihat pake id -->
                                    <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" href="lihat_kegiatan.php?id_kegiatan=<?= $kegiatan->id_kegiatan; ?>" target="_blank">Baca Kegiatan</a>
                                    <div class="small">
                                        <i class="far fa-calendar-alt"></i> Tanggal Kegiatan: <?= date('d F Y', strtotime($kegiatan->tgl_kegiatan)); ?>
                                    </div>
                                </div>
                                <!-- <div class="card-footer"></div> -->
                            </div>
                        </div>
                    <?php endforeach ?>
                    <!-- endforeach -->
                </div>
            </div>
        </div>
        <!-- End Konten Mid Container -->



    </div>
    </div>
    <!-- End Konten -->

    <!-- Pre Footer -->
    <div class="pre-footer-tkjb">
        <div>
            <section>
                <div class="map-tangkolak">

                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.6200792239033!2d107.55975721436018!3d-6.181576562289898!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x8209ca60143b4b83!2sPusat%20Informasi%20Bahari%20TANGKOLAK!5e0!3m2!1sid!2sid!4v1614278046421!5m2!1sid!2sid" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

                </div>
            </section>
        </div>
    </div>
    <!-- End Pre Footer -->

    <!-- Footer -->
    <section id="footer">
        <div class="row">
            <div class="blogo col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <a href="#"><img src="img/footer-logo.png" id="footer-logo" alt="Tangkolak Footer Logo"></a>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 cr-tkjb">
                <div class="cpt text-light text-center">
                    <p>© 2021 - Wisata Bahari Tangkolak</p>
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

    <!-- Bootstrap JS & JQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>