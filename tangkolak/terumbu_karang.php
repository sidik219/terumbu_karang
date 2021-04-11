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
                        <li class="nav-item ">
                            <a class="nav-link " href="index.php">Beranda</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link " href="wisata_tangkolak.php">Wisata Bahari</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link current" href="terumbu_karang.php">Terumbu Karang</a>
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
            <!-- Head -->
            <div class="header-tkjb">

            <div class="terumbu-karang-tangkolak">
                <h2>Kondisi Terumbu Karang Tangkolak</h2>
                    <div class="row ">
                            <div class="col-md-12 col-lg-6 p-5 text-center  tabel-status-tangkolak">
                                <div class="row justify-content-center ">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                    <b>Persentase Sebaran</b>
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1" style="color:green;">
                                        <b>76.7%</b>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                    <b> Estimasi Total Luas Titik</b>
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1">
                                        3,000 m2
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                    <b>Total Luas Titik Terdata</b>
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1">
                                        2,320 m2
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                    <b>Jumlah Titik Terdata</b>
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1 ">
                                        7 Titik
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                    <b>Status</b>
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1" style="color:green;">
                                        Sangat Baik
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12 col-lg-6 p-5 text-center tabel-kondisi-tangkolak ">
                                <div class="row justify-content-center">
                                    <div class="col-sm-10 border border-dark py-1 top-col-bg">
                                    <b>     Kondisi Titik</b>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                        Sangat Baik
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1">
                                        1
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                        Baik
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1">
                                        2
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                        Cukup
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1">
                                        2
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 border border-dark py-1 top-col-bg">
                                        Kurang
                                    </div>
                                    <div class="col-sm-4 border border-dark py-1">
                                        2
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>


                </div>
            </div>
            <!-- End Head -->

            <!-- Konten Mid Container -->
            <div class="mid-tkjb">
            <div class="tkjb-banner">
                    <div>
                    <picture>
                         <source srcset="img/donasi1.jpg" media="(min-width: 604px)">
                         <source srcset="img/donasi2.jpg" media="(max-width: 604px)">
                         <img src="img/jembatan.jpg" alt="Slide 1 Image" class="d-block img-fluid">
                    </picture>
                    <div class="carousel-caption donasi-caption">
                                    <div>
                                        <h2>Bersama GoKarang Bantu lestarikan terumbu karang di pantai Tangkolak</h2>
                                    </div>
                                    <div class="row-mid py-4">
                                    <button class="btn radius-50 py-1.5 px-4  btn-donasi " onclick="window.location.href='#'">Donasi</button>
                                    </div>
                                </div>
                    </div>
                </div>




            </div>
            <!-- End Konten Mid Container -->



        </div>
        </div>
        <!-- End Konten -->



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
