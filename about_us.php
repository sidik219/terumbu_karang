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
    <style>
        .social-link {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            border-radius: 50%;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .social-link:hover,
        .social-link:focus {
            background: #ddd;
            text-decoration: none;
            color: #555;
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
                <div class="bg-light">
                    <div class="container py-5 pt-5">
                        <div class="row mb-4">
                            <div class="col-lg-5">
                                <h2 class="display-4 font-weight-light">Tim Pengembang</h2>
                                <p class="font-italic text-muted">Nama-Nama Tim Pengembang Aplikasi GoKarang</p>
                            </div>
                        </div>

                        <div class="row text-center justify-content-md-center">
                            <!-- Team item-->
                            <div class="col-xl-3 col-sm-6 mb-5">
                                <div class="bg-white rounded shadow-sm py-5 px-4"><img src="" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                                    <h5 class="mb-0">Nama</h5><span class="small text-uppercase text-muted">Pengembang</span>
                                    <ul class="social mb-0 list-inline mt-3">
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-facebook-f"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-twitter"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-instagram"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End-->
                            <div class="col-xl-3 col-sm-6 mb-5">
                                <div class="bg-white rounded shadow-sm py-5 px-4"><img src="" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                                    <h5 class="mb-0">Nama</h5><span class="small text-uppercase text-muted">Pengembang</span>
                                    <ul class="social mb-0 list-inline mt-3">
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-facebook-f"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-twitter"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-instagram"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End-->

                            <!-- Team item-->
                            <div class="col-xl-3 col-sm-6 mb-5">
                                <div class="bg-white rounded shadow-sm py-5 px-4"><img src="" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                                    <h5 class="mb-0">Nama</h5><span class="small text-uppercase text-muted">Pengembang</span>
                                    <ul class="social mb-0 list-inline mt-3">
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-facebook-f"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-twitter"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-instagram"></i></a></li>
                                        <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End-->

                            <!-- Team item-->
                            <div class="col-xl-3 col-sm-6 mb-5">
                                <div class="bg-white rounded shadow-sm py-5 px-4"><img src="images/about_us/IMG_SDK2190.jpg" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                                    <h5 class="mb-0">Sidik Mulyana</h5><span class="small text-uppercase text-muted">Pengembang</span>
                                    <ul class="social mb-0 list-inline mt-3">
                                        <li class="list-inline-item"><a href="https://web.facebook.com/C.Ber.MG/" class="social-link" target="_blank"><i class="fa fa-facebook-f"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End-->

                            <!-- Team item-->
                            <!-- <div class="row"> -->

                            <div class="col-xl-3 col-sm-6 mb-5">
                                <div class="bg-white rounded shadow-sm py-5 px-4"><img src="images/about_us/IMG_0660.jpg" alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
                                    <h5 class="mb-0">Nabil Fadhiilah</h5><span class="small text-uppercase text-muted">Pengembang</span>
                                    <ul class="social mb-0 list-inline mt-3">

                                        <li class="list-inline-item"><a href="https://www.instagram.com/bilnbl13/" class="social-link"><i class="fa fa-instagram"></i></a></li>

                                    </ul>
                                </div>
                            </div>
                            <!-- </div> -->
                            <!-- End-->

                        </div>
                    </div>
                </div>
            </div>
            <!-- END OF BODY CONTAINER -->
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