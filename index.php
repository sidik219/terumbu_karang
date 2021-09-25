<?php include 'build/config/connection.php';
// session_start();
$url_sekarang = basename(__FILE__);
// include 'hak_akses.php';

$stmt = $pdo->prepare('SELECT * From t_konten_wilayah');
$stmt->execute();
// $stmt->execute(array(0));
$rowKonten = $stmt->fetchAll();
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
                            <li class="nav-item active  teks-biru">
                                <a class="nav-link current" href="index.php">Beranda</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link " href="konten-donasi.php">Donasi</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link " href="coralpedia.php">Coralpedia</a>
                            </li>  
                            <li class="nav-item ">
                                <a class="nav-link " href="coralmaps.php">Coralmaps</a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link " href="konten-wisata.php">Info Wisata</a>
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

            <!-- Header Container -->
            <div class="header-tkjb">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <!-- <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol> -->
                <div class="carousel-inner">
                    <?php foreach ($rowKonten as $wilayah) { ?>
                    <div class="carousel-item 
                        <?php 
                            if ($wilayah->status_konten_wilayah == "Donasi Sekarang") {
                              echo "active";  
                            } else {
                                echo " ";
                            }
                        ?>">
                        <img class="d-block w-100" src="<?=$wilayah->foto_konten_wilayah?>" alt="">
                        <div class="carousel-caption  d-md-block">
                            <h3><?= $wilayah->judul_konten_wilayah ?></h3><br>
                            <p><?= $wilayah->deskripsi_konten_wilayah ?></p><br>
                            <?php 
                            if ($wilayah->status_konten_wilayah == "Donasi Sekarang") { ?>
                            <a href="konten-donasi.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                                <?= $wilayah->status_konten_wilayah ?>
                            </a>
                            <?php } elseif ($wilayah->status_konten_wilayah == "Wisata Sekarang") { ?>
                            <a href="konten-wisata.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                                <?= $wilayah->status_konten_wilayah ?>
                            </a>
                            <?php } elseif ($wilayah->status_konten_wilayah == "Coralmaps") { ?>
                            <a href="coralmaps.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                                <?= $wilayah->status_konten_wilayah ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- Konten Statis -->
                    <!-- <div class="carousel-item active">
                        <img class="d-block w-100" src="images/home-slide1-darken.jpg" alt="First slide">
                        <div class="carousel-caption  d-md-block">
                            <h3>BANTU PULIHKAN RUMAH MEREKA</h3><br>
                            <p>Terumbu Karang merupakan rumah bagi ribuan ikan hias. Kerusakan yang
                            disebabkan manusia berdampak buruk pada kehidupan mereka.</p><br>
                            <a href="konten-donasi.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                            Donasi Sekarang
                            </a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="images/home-slide2-darken.jpg" alt="Second slide">
                        <div class="carousel-caption d-md-block">
                            <h3>NIKMATI HASIL
                            PELESTARIAN</h3><br>
                            <p>Terimakasih telah ikut melestarikan biota laut Jawa Barat. Jangan lupa
                            untuk menikmati keindahannya dengan berwisata.</p><br>
                            <a href="konten-wisata.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                                Wisata Sekarang
                            </a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="images/home-slide3-darken.jpg" alt="Third slide">
                        <div class="carousel-caption d-md-block">
                            <h3>AMATI KONDISINYA </h3><br>
                            <p>Beberapa titik terumbu karang berada dalam kondisi rusak parah dan
                            membutuhkan bantuan kita.</p><br>
                            <a href="coralmaps.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                                Coralmaps
                            </a>
                        </div>
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

            <!-- 1ST BODY CONTAINER -->
            <div class="home-about-container">
                <div id="about" class="container">
                    <!-- 1st PARAGRAPH -->
                    <div class="starter-template">
                        <h1 class="mb-5">Tentang Kami</h1><br>
                        <p><b>GoKarang</b> (Terumbu Karang Jawa Barat) merupakan bentuk kerjasama
                            antara Kementrian Kelautan dan
                            Perikanan (KKP) dengan Kelompok
                            Usaha Bersama (KUB) masyarakat
                            pesisir pantai di Provinsi Jawa Barat yang memiliki tujuan sebagai berikut :</p><br><br>
                    </div>
                    <!-- END 1st PARAGRAPH -->
                    <!-- ROW WITH ICON -->
                    <div class="row text-center pb-3 starter-template">
                        <div class="col-md-12 col-lg-4 p-1 ">
                            <img src="images/konservasi.jpg" class="rounded-circle p-3" alt="" width="250" height="250">
                            <h3>Konservasi <br>Terumbu Karang</h3><br>
                            <p>Melestarikan dan melakukan
                                pemulihan pada terumbu
                                karang sekitar pantai Jawa
                                Barat.</p>
                        </div>
                        <div class="col-md-12 col-lg-4 p-1-middle">
                            <img src="images/informasi.jpg" class="rounded-circle p-3" alt="" width="250" height="250">
                            <h3>Informasi <br> Terumbu Karang</h3><br>
                            <p>Menyajikan informasi pada
                                masyarakat umum tentang
                                kondisi terumbu karang
                                di Jawa Barat.
                                </p>
                        </div>
                        <div class="col-md-12 col-lg-4 p-1">
                            <img src="images/ekonomi.jpg" class="rounded-circle p-3" alt="" width="250" height="250">
                            <h3>Ekonomi <br>Terumbu Karang</h3><br>
                            <p>Mendukung wisata terumbu
                                karang untuk meningkatkan
                                ekonomi masyarakat pesisir.</p>
                        </div>
                    </div>
                    <!-- END OF ROW WITH ICON -->
                </div>
            </div>
            <!-- END OF BODY CONTAINER -->
        
  
            <!-- Donasi Banner -->
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="images/home-donasi.jpg" alt="First slide">
                        <div class="carousel-caption  d-md-block">
                        <center>
                            <h3>SISTEM DONASI MENARIK</h3><br>
                            <p>
                            Simak menariknya sistem donasi yang kami hadirkan
                            </p><br>
                             <a href="konten-donasi.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                               Info Donasi
                             </a>
                            </center>
                        </div>
                    </div>
                </div>
            <!-- END Donasi Banner -->
            
            <!-- Wisata Banner -->
            <div class="wisata-container">
                <div id="about" class="container">
                    <!-- 1st PARAGRAPH -->
                    <div class="starter-template">
                        <h1 class="mb-5">Wisata Terumbu
                            Karang Jabar</h1><br>
                        <p style="text-align: center;">Temukan spot favoritmu untuk
                            melihat indahnya terumbu
                            karang Jawa Barat !</p><br><br>
                            <a href="konten-wisata.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                                Info Wisata
                            </a>
                    </div>
                </div>
            </div>
            <!-- End of Wisata Banner -->

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
                    <p>© 2021 - Terumbu Karang Jawa Barat</p>                    
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