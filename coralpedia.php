<?php
include 'build/config/connection.php';
$url_sekarang = basename(__FILE__);
$sqlviewjenis = 'SELECT * FROM t_jenis_terumbu_karang
                ORDER BY id_jenis';
$stmt = $pdo->prepare($sqlviewjenis);
$stmt->execute();
$row = $stmt->fetchAll();
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
                            <li class="nav-item active teks-biru">
                                <a class="nav-link current" href="coralpedia.php">Coralpedia</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="coralmaps.php">Coralmaps</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="konten-wisata.php">Info Wisata</a>
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

            <section id="benefit">
                <div class="container">
                    <div class="starter-title">
                        <h1 class="mb-5">Coralpedia</h1><br>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-6 p-5 text-center">
                            <img src="images/terumbu-karang.jpg" class="img-fluid" alt="team">
                        </div>
                        <div class="col-md-12 col-lg-6 p-5 text-light">
                            <h2>Apa itu Terumbu Karang ?</h2>
                            <p>
                                Terumbu karang adalah sekumpulan hewan karang yang bersimbiosis dengan sejenis tumbuhan alga yang disebut zooxanthellae. Terumbu karang merupakan batuan sedimen kapur yang terbentuk dari kalsium karbonat yang dihasilkan oleh biota laut penghasil kalsium karbonat yang kemudian melalui proses sedimentasi. Sedimentasi yang terjadi pada terumbu dapat berasal dari karang maupun dari alga.
                                <br>
                            </p></a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Donasi About -->
            <div class="donasi-about-container">
                <div id="about" class="container ">
                    <!-- 1st PARAGRAPH -->
                    <div class="coralpedia-title">
                        <h2>Manfaat Terumbu Karang</h2><br>
                    </div>
                    <!-- END 1st PARAGRAPH -->
                    <div class="coralpedia-paragraph">
                        <ol>
                            <li>Terumbu karang bermanfaat sebagai habitat dan sumber makanan bagi berbagai jenis makhluk hidup di laut. Di sini banyak berbagai jenis makhluk hidup yang tinggal, mencari makan, berlindung, dan berkembang biak.</li><br>
                            <li>Terumbu karang merupakan sumber keanekaragaman hayati yang tinggi. Dengan tingginya keanekaragaman hayati yang ada di dalamnya, terumbu karang ini menjadi sumber keanekaragaman genetik dan spesies yang ditemukan memiliki ketahanan hidup yang lebih tinggi.</li><br>
                            <li>Terumbu karang dapat bermanfaat sebagai pelindung bagi ekosistem yang ada disekitarnya, misalnya pada ekosistem fungsi hutan bakau, dan juga melindungi pantai dan daerah pesisir dari ombak besar. Terumbu karang dapat memperkecil energi ombak yang menuju ke daratan yang dapat menyebabkan abrasi pantai dan kerusakan sekitarnya.</li><br>
                            <li>Masyarakat sekitarnya dapat memanfaatkan biota yang hidup di terumbu karang, seperti rumput laut, udang, dan ikan untuk dijadikan sumber makanan yang nantinya dapat dijual sehingga menjadi sumber pendapatan bagi masyarakat.</li><br>
                            <li>Karena keindahan yang dihasilkan oleh ekosistem terumbu karang, ekosistem ini dapat dijadikan objek wisata yang menarik sehingga dapat meningkatkan pendapatan masyarakat yang tinggal di sekitarnya.</li><br>
                        </ol>
                    </div>

                </div>
                <!-- End of Donasi About -->
                <div class="coralpedia-card coralpedia-title">
                    <h2> Eksplor Terumbu Karang Jawa Barat </h2>
                    <div class="coralpedia-paragraph2">
                        Berikut merupakan jenis terumbu karang yang terdapat di seluruh wilayah pantai Jawa Barat :
                    </div>
                    <div class="row">
                        <?php foreach ($row as $rowitem) : ?>
                            <div class="col-md-4">
                                <div class="card card-pilihan mb-4 shadow-sm">
                                    <a href="">
                                        <img class="card-img-top berita-img" width="100%" src="<?= $rowitem->foto_jenis ?>?<?php if ($status = 'nochange') {
                                                                                                                                echo time();
                                                                                                                            } ?>" width="150px">
                                    </a>
                                    <div class="card-body">
                                        <p>
                                        <h5 class="max-length"><?= $rowitem->nama_jenis ?></h5>
                                        </p>
                                        <p class="max-length2"><?= $rowitem->deskripsi_jenis ?></p>
                                        <div class="collapse" id="collapseExample<?= $rowitem->id_jenis ?>">
                                            <div class="card card-body">
                                                <?= $rowitem->deskripsi_jenis ?>
                                            </div>
                                            <!-- <div class="card card-body">
                                                Tumbuh di daerah : Karawang, Indramayu, Cirebon
                                            </div> -->
                                        </div>
                                        <p>
                                            <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" data-toggle="collapse" href="#collapseExample<?= $rowitem->id_jenis ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                Lihat Detail
                                            </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                        <!-- <div class="col-md-4">
                            <div class="card card-pilihan mb-4 shadow-sm">
                                <a href="">
                                    <img class="card-img-top berita-img" width="100%" src="images/Acropora-microphthalma.jpg">
                                </a>
                                <div class="card-body card-body-costom">
                                    <p>
                                    <h5 class="max-length">Acropoda Micropthalma</h5>
                                    </p>
                                    <p class="max-length2">Berbentuk bantalan dengan cabang yang pendek dan gemuk serta dengan ukuran yang sama. terumbu karang ini ada kemiripan dengan Acropora Aspera. </p>
                                    <div class="collapse" id="collapseExample2">
                                        <div class="card card-body">
                                            Berbentuk bantalan dengan cabang yang pendek dan gemuk serta dengan ukuran yang sama. terumbu karang ini ada kemiripan dengan Acropora Aspera.
                                        </div>
                                        <div class="card card-body">
                                            Tumbuh di daerah : Karawang, Cirebon
                                        </div>
                                    </div>
                                    <p>
                                        <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" data-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Lihat Detail
                                        </a>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div class="col-md-4">
                            <div class="card card-pilihan mb-4 shadow-sm">
                                <a href="">
                                    <img class="card-img-top berita-img" width="100%" src="images/Acropora_humilis.jpg">
                                </a>
                                <div class="card-body card-body-costom">
                                    <p>
                                    <h5 class="max-length">Acropora Humilis</h5>
                                    </p>
                                    <p class="max-length2">Berbetuk seperti piring dengan cabang yang tipis. Terumbu karang ini termasuk terumbu karang yang mudah rapuh. </p>
                                    <div class="collapse" id="collapseExample">
                                        <div class="card card-body">
                                            Berbetuk seperti piring dengan cabang yang tipis. Terumbu karang ini termasuk terumbu karang yang mudah rapuh.
                                        </div>
                                        <div class="card card-body">
                                            Tumbuh di daerah : Karawang, Indramayu
                                        </div>
                                    </div>
                                    <p>
                                        <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Lihat Detail
                                        </a>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <section id="footer">
            <div class="row">
                <div class="blogo col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <a href="#"><img src="images/gokarang.png" id="footer-logo"></a>
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

        <!-- Bootstrap JS & JQuery -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>