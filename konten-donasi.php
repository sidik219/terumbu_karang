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
                                <img id="logo-tkjb-navbar" src="images/tkjb.png">
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
                            <li class="nav-item active teks-biru">
                                <a class="nav-link current" href="konten-donasi.php">Donasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="coralpedia.php">Coralpedia</a>
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
                        <h1 class="mb-5">Manfaat Donasi Terumbu Karang</h1><br>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-6 p-5 text-center">
                            <img src="images/nelayan.jpg" class="img-fluid" alt="team">
                        </div>
                        <div class="col-md-12 col-lg-6 p-5 text-light">
                            <h2>Bagi Masyarakat Pesisir</h2>
                            <p>
                            Terumbu karang yang anda donasikan bermanfaat untuk perbaikan dan perluasan titik
                            terumbu karang di tempat tinggal mereka.
                            <br>
                            <ol>
                                Manfaat yang didapat antara lain:
                                <li>Terumbu karang melindungi pantai dan daerah pesisir dari ombak besar.</li>
                                <li>Terumbu karang dapat mencegah abrasi.</li>
                                <li>Terumbu karang dapat dijadikan tempat wisata yang mendorong ekonomi masyarakat pesisir.</li>
                            </ol>
                            <a href="coralpedia.php"> <p>Pelajari manfaat terumbu karang lebih lanjut ></p></a>
                        </div>
                    </div>
                </div>
            </section>

           <!-- Donasi About -->
            <div class="donasi-about-container">
                <div id="about" class="container ">
                    <!-- 1st PARAGRAPH -->
                    <div class="starter-template">
                        <h1 class="mb-5">SISTEM DONASI</h1><br>
                    </div>
                    <!-- END 1st PARAGRAPH -->
                    <!-- ROW WITH ICON -->
                    <div class="row text-center pb-4 starter-template">
                        <div class="col-md-12 col-lg-4 p-1">
                            <img src="images/choose.jpg" class="rounded-circle p-3" alt="" width="250" height="250">
                            <h3>Pilih</h3><br>
                            <p>Pilih sendiri lokasi penanaman dan jenis terumbu karang yang tersedia pada lokasi pilihan.</p>
                        </div>
                        <div class="col-md-12 col-lg-4 p-1-middle">
                            <img src="images/pesan.jpg" class="rounded-circle p-3" alt="" width="250" height="250">
                            <h3>Pesan</h3><br>
                            <p>Tulis pesan untuk dipasang pada terumbu karang, yang nantinya bisa kita lihat pada saat menyelam nanti.
                                </p>
                        </div>
                        <div class="col-md-12 col-lg-4 p-1">
                            <img src="images/coral-status.jpg" class="rounded-circle p-3" alt="" width="250" height="250">
                            <h3>Pantau</h3><br>
                            <p>Pantau status perkembangan dan kondisi terumbu karang yang telah kita donasikan.</p>
                        </div>
                    </div>
                <div id="about" class="container donate-now">
                    <!-- 1st PARAGRAPH -->
                    <div class="starter-template">
                        <a href="coralmaps.php" class="btn btn-link-slide" role="button" aria-pressed="true">
                        Donasi Sekarang
                        </a>
                    </div>
                </div>
            </div>
            <!-- End of Donasi About -->
            
        </div>
    </div>

    <!-- Footer -->
    <section id="footer">
        <div class="row">
            <div class="blogo col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <a href="#"><img src="images/tkjb.png" id="footer-logo" alt="Tangkolak Footer Logo"></a>
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