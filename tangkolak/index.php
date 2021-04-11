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
                            <a class="nav-link" href="terumbu_karang.php">Terumbu Karang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://tkjb.or.id/"  target="_blank">Website GoKarang</a>
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
                <!-- Carousel -->
                <div id="carousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="6000">
                    <ol class="carousel-indicators">
                        <li data-target="#carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel" data-slide-to="1"></li>
                        <li data-target="#carousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <!-- Slide 1 -->
                        <div class="carousel-item active">
                                <!-- Image -->
                                <picture>
                                    <source srcset="img/banner1.jpg" media="(min-width: 604px)">
                                    <source srcset="img/banner2.jpg" media="(max-width: 604px)">
                                    <img src="img/jembatan.jpg" alt="Slide 1 Image" class="d-block img-fluid">
                                </picture>
                                <!-- Caption -->
                                <div class="carousel-caption">
                                    <div>
                                        <h2>Selamat Datang di Wisata Bahari Tangkolak</h2>
                                        <!-- <p>Paragraph</p> -->
                                    </div>
                                </div>
                        </div>
                        <!-- Slide 2 -->
                        <div class="carousel-item">
                                <!-- Image -->
                                <picture>
                                    <source srcset="img/santai1.jpg" media="(min-width: 604px)">
                                    <source srcset="img/santai2.jpg" media="(max-width: 604px)">
                                    <img src="img/mob1.jpg" alt="Slide 2 Image" class="d-block img-fluid">
                                </picture>
                                <!-- Caption -->
                                <div class="carousel-caption justify-content-center align-items-center">
                                    <div>
                                        <h2>Tempat wisata bahari terbaik di Karawang</h2>
                                        <!-- <p>Paragraph</p> -->
                                    </div>
                                </div>
                        </div>
                        <!-- Slide 3 -->
                        <div class="carousel-item">
                                <!-- Image -->
                                <picture>
                                    <source srcset="img/diving1.jpg" media="(min-width: 604px)">
                                    <source srcset="img/diving2.jpg" media="(max-width: 604px)">
                                    <img src="img/vol1.jpg" alt="Slide 3 Image" class="d-block img-fluid">
                                </picture>
                                <!-- Caption -->
                                <div class="carousel-caption justify-content-center align-items-center">
                                    <div>
                                        <h2>Dengan ratusan titik snorkling dan hutan mangrove</h2>
                                        <!-- <p>Paragraph</p> -->
                                    </div>
                                </div>
                        </div>
                        <!-- /.carousel-item -->
                    </div>
                    <!-- /.carousel-inner -->
                    <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <!-- / END Carousel -->
            </div>
            <!-- End Header Container -->

            <!-- Konten Mid Container -->
            <div class="mid-tkjb">

                <div class="paragraf-awal">

                        <div class="row justify-content-between">
                            <div class="col-md-12 col-lg-7 p-5 text-light paragraf-awal-caption">
                                <h2>Pantai Tangkolak</h2>
                                <p>
                                Kampung Tangkolak bersama Pemkab Karawang, dan komunitas pegiat pariwisata terus berinovasi dalam mengembangkan kampung Tangkolak sebagai wisata bahari.
                                Sehingga pantai tangkolak terus berbenah menjadi obyek wisata terintegrasi, mulai dari wisata snorkling, mangrove, museum bawah laut BMKT, Pusat Informasi Bahari Tangkolak, hingga arena memancing.
                                </p>
                            </div>
                            <div class="col-md-12 col-lg-5 p-5 text-center paragraf-awal-img">
                                <img src="img/pantai-tangkolak.jpg" class="img-fluid shadow-sm rounded" alt="pantai tangkolak">
                            </div>
                        </div>

                </div>

                <div class="tkjb-banner-caption">
                <h2>Bagian dari GoKarang</h2>
                <p>Pantai Tangkolak merupakan bagian dari Program Terumbu Karang Jawa Barat (<a href="https://tkjb.or.id/">tkjb.or.id</a>) yang merupakan bentuk kerjasama antara
                Dinas Kelautan dan Perikanan Jawa Barat (DKP) dengan Kelompok Usaha Bersama (KUB) masyarakat pesisir pantai di Provinsi Jawa Barat yang memiliki tujuan sebagai berikut :</p>
                    <div id="accordion " class="list-tujuan-tkjb">
                        <div class="card accordion-bg">
                            <div class="card-header accordion-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
                                1. Konservasi Terumbu Karang
                                <i class="fas fa-chevron-circle-down icon-list-pad"></i>
                                </button>
                            </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                <p class="accordion-body-p">
                                Melestarikan dan melakukan pemulihan pada terumbu karang sekitar pantai Jawa Barat.<br>
                                <a href="terumbu_karang.php">Ikut konservasi ></a>
                                </p>
                                </div>
                            </div>
                        </div>
                        <div class="card accordion-bg">
                            <div class="card-header accordion-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                2. Informasi Terumbu Karang
                                <i class="fas fa-chevron-circle-down icon-list-pad"></i>
                                </button>
                            </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <p class="accordion-body-p">
                                Menyajikan informasi pada masyarakat umum tentang kondisi terumbu karang di Jawa Barat.<br>
                                <a href="https://tkjb.or.id/coralmaps.php" target="_blank">Lihat informasi ></a>
                                </p>
                            </div>
                            </div>
                        </div>
                        <div class="card accordion-bg">
                            <div class="card-header accordion-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                3. Ekonomi Terumbu Karang
                                <i class="fas fa-chevron-circle-down icon-list-pad"></i>
                                </button>
                            </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                                <p class="accordion-body-p">
                                Mendukung wisata terumbu karang untuk meningkatkan ekonomi masyarakat pesisir.<br>
                                <a href="wisata_tangkolak.php">Wisata Tangkolak ></a>
                                </p>

                            </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="kata-media">
                 <h2> Berita Tangkolak </h2>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card card-pilihan mb-4 shadow-sm">
                                    <a href="">
                                        <img class="card-img-top berita-img" width="100%" src="img/news1.jpg">
                                    </a>
                                        <div class="card-body">
                                            <p > <h5 class="max-length">Tahun 2023 Nanti Pantai Tangkolak Jadi Wisata Bahari Terintegritas</h5></p>
                                            <p class="max-length1">onlinemetro.id</p>
                                            <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" href="https://onlinemetro.id/berita-metro-karawang/tahun-2023-nanti-pantai-tangkolak-jadi-wisata-bahari-terintegritas/" target="_blank" >Baca Berita</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-pilihan mb-4 shadow-sm">
                                    <a href="">
                                        <img class="card-img-top berita-img" width="100%" src="img/news2.jpg">
                                    </a>
                                        <div class="card-body card-body-costom">
                                            <p><h5 class="max-length">Pantai Tangkolak,<br>Wajah Baru Wisata Bahari</h5></p>
                                            <p class="max-length1">radarkarawang.id</p>
                                            <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" href="https://radarkarawang.id/cilamaya/tangkolak-wajah-baru-wisata-bahari/" target="_blank">Baca Berita</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card card-pilihan mb-4 shadow-sm">
                                    <a href="">
                                        <img class="card-img-top berita-img" width="100%" src="img/news3.jpg">
                                    </a>
                                        <div class="card-body card-body-costom">
                                            <p><h5 class="max-length">Cerita Harta Karun Belanda Membuat Kampung Tangkolak Populer</h5></p>
                                            <p class="max-length1">daerah.sindonews.com</p>
                                            <a class="btn btn-primary btn-lg btn-block mb-4 btn-kata-media" href="https://daerah.sindonews.com/artikel/jabar/8612/cerita-tentang-harta-karun-belanda-membuat-kampung-tangkolak-populer" target="_blank" >Baca Berita</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>





                <div class="member-tkjb"></div>

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
