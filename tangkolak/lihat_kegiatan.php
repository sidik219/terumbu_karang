<?php include '../build/config/connection.php';
// session_start();
$url_sekarang = basename(__FILE__);
// include 'hak_akses.php';

$id_kegiatan = $_GET['id_kegiatan'];

$sqleditkegiatan = 'SELECT * FROM t_berita_kegiatan
                    WHERE id_kegiatan = :id_kegiatan';

$stmt = $pdo->prepare($sqleditkegiatan);
$stmt->execute(['id_kegiatan' => $id_kegiatan]);
$kegiatan = $stmt->fetch();
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
    <!-- <link rel="stylesheet" href="style.css" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />
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

    <main class="container">

        <div class="header-tkjb">

            <div class="p-4 text-white rounded d-flex justify-content-center">
                <div class="col-md-8 px-0">
                    <!-- <img src="../<?= $kegiatan->foto_kegiatan; ?>" class="img-fluid max-width: 100%;" alt=""> -->
                    <img src="../<?= $kegiatan->foto_kegiatan; ?>" class="img-fluid rounded mx-auto d-block foto" alt="">
                    <p class="small text-secondary"><i class="far fa-calendar-alt "></i> Tanggal Kegiatan : <?= date('d F Y', strtotime($kegiatan->tgl_kegiatan)); ?></p>
                </div>
            </div>


            <div class="row g-5">
                <div class="col-md-8 ">

                    <article class="blog-post pb-5">
                        <h2 class="blog-post-title"><?= $kegiatan->judul_kegiatan; ?></h2>
                        <div class="d-flex align-items-center share-btn">
                            <p class="blog-post-meta" style="margin-bottom: 0px; padding-bottom:1px;">Bagikan :</p>
                            <a href="#" class="facebook-btn px-1"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="twitter-btn px-1"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="whatsapp-btn px-1"><i class="fab fa-whatsapp"></i></a>
                        </div>
                        <p style="margin: 0;" class="blog-post-meta"><i class="far fa-calendar-alt "></i> Tanggal Upload : <?= date('d F Y', strtotime($kegiatan->tgl_upload)); ?></p>
                        <hr>
                        <?= $kegiatan->deskripsi_kegiatan; ?>
                    </article>

                </div>
            </div>
        </div>
    </main>


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
    <script src="../tangkolak/js/share.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>