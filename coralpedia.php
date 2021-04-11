<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Styles Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer felis neque, suscipit eget dolor quis, accumsan imperdiet elit. Praesent quis mauris eu quam malesuada auctor. Etiam vitae ante sapien. Sed mauris dui, varius non tempor in, semper fringilla ipsum. Phasellus nec purus enim. Nulla eget fringilla mi, id iaculis ante.">
    <meta name="author" content="">
    <link rel="icon" href="dist/img/KKPlogo.png">

    <title>GoKarang | Terumbu Karang Jawa Barat</title>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&family=Roboto&display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="css/konten.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Favicon -->
    <?= $favicon ?>


</head>


<body >

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light  main-navigation fixed-top">
        <!-- LOGO HOLDER -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php"><img id=logo src="dist/img/logo.png"></a>
        </nav>
        <!-- END OF LOGO HOLDER -->

        <!-- MENU NAVBAR -->
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto ">
                <li class="nav-item ">
                    <a class="nav-link" href="index.php">Beranda </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="donasi.php">Donasi</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="wisata.php">Wisata </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="coralmaps.php">Coralmaps </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="coralpedia.php">Coralpedia <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <!-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Akun Saya</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Edit Profil</a>
                    <a class="dropdown-item" href="#">Logout</a>
                </li>  -->
            </ul>
        </div>
        <!-- END OF MENU NAVBAR -->
    </nav>
    <!-- END OF NAVBAR -->


    <section id="coralpedia-title">
        <div id="about" class="container">
            <div class="starter-title">
                <h1 class="mb-5">CORALPEDIA</h1><br>
            </div>
            <div class="coralpedia-template">
                <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Terumbu Karang
                        </button>
                    </h5>
                    </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                        <p>Terumbu karang merupakan batuan sedimen kapur yang terbentuk dari kalsium karbonat yang dihasilkan oleh biota laut penghasil kalsium karbonat yang kemudian melalui proses sedimentasi.
                        Sedimentasi yang terjadi pada terumbu dapat berasal dari karang maupun dari alga.</p>
                        <br>
                        <p>Di dalam dan sekitar terumbu karang, hidup beraneka ragam biota yang umumnya merupakan hewan avertebrata. Hewan–hewan tersebut adalah seperti crustacea, siput dan kerang-kerangan, bulu babi,
                        anemon laut, teripang, bintang laut dan leli laut, ikan–ikan kecil, ular laut, penyu laut, ganggang dan juga alga.</p>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Manfaat Terumbu Karang
                            </button>
                        </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                         <ol>
                            <li>Terumbu karang bermanfaat sebagai habitat dan sumber makanan bagi berbagai jenis makhluk hidup di laut. Di sini banyak berbagai jenis makhluk hidup yang tinggal, mencari makan, berlindung, dan berkembang biak.</li><br>
                            <li>Terumbu karang merupakan sumber keanekaragaman hayati yang tinggi. Dengan tingginya keanekaragaman hayati yang ada di dalamnya, terumbu karang ini menjadi sumber keanekaragaman genetik dan spesies yang ditemukan memiliki ketahanan hidup yang lebih tinggi.</li><br>
                            <li>Terumbu karang dapat bermanfaat sebagai pelindung bagi ekosistem yang ada disekitarnya, misalnya pada ekosistem fungsi hutan bakau, dan juga melindungi pantai dan daerah pesisir dari ombak besar. Terumbu karang dapat memperkecil energi ombak yang menuju ke daratan yang dapat menyebabkan abrasi pantai dan kerusakan sekitarnya.</li><br>
                            <li>Masyarakat sekitarnya dapat memanfaatkan biota yang hidup di terumbu karang, seperti rumput laut, udang, dan ikan untuk dijadikan sumber makanan yang nantinya dapat dijual sehingga menjadi sumber pendapatan bagi masyarakat.</li>
                            <li>Karena keindahan yang dihasilkan oleh ekosistem terumbu karang, ekosistem ini dapat dijadikan objek wisata yang menarik sehingga dapat meningkatkan pendapatan masyarakat yang tinggal di sekitarnya.</li>
                        </ol>
                        </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Terumbu Karang Jawa Barat
                            </button>
                        </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <p>Jawa Barat memiliki 11 Wilayah (Kota/Kabupaten) yang didalamnya terdapat ribuan titik terumbu karang, wilayah tersebut antara lain :</p>
                            <ol>
                            <b>Pantai Utara :</b>
                                <li>Bekasi</li>
                                <li>Karawang</li>
                                <li>Subang</li>
                                <li>Indramayu</li>
                                <li>Kabupaten Cirebon</li>
                                <li>Kota Cirebon</li>
                            </ol>
                            <ol>
                            <b>Pantai Selatan :</b>
                                <li>Sukabumi</li>
                                <li>Cianjur</li>
                                <li>Garut</li>
                                <li>Tasik</li>
                                <li>Pangandaran</li>
                            </ol>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



  <div class="container">
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
  </div>

    <section id="footer">
        <div class="container">
            <div class="row">
                <div class="blogo col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <!-- <a href="#"><img src="dist/img/logo.png" alt="Styles logo"></a> -->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <div class="cpt text-light text-center">
                        <p>© 2020-Terumbu Karang Jawa Barat.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>




    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Scrollspy -->
    <script>$('body').scrollspy({ target: '#navbarsExampleDefault', offset: 108 })</script>
    <!-- Smooth Scroll -->
    <script src="js/smooth-scroll.js"></script>

    <!-- Number Counter -->
    <script src="js/nsc.js"></script>


</body>
</html>coralpedia.php
