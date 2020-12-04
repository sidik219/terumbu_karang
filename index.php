<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Styles Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer felis neque, suscipit eget dolor quis, accumsan imperdiet elit. Praesent quis mauris eu quam malesuada auctor. Etiam vitae ante sapien. Sed mauris dui, varius non tempor in, semper fringilla ipsum. Phasellus nec purus enim. Nulla eget fringilla mi, id iaculis ante.">
    <meta name="author" content="">
    <link rel="icon" href="dist/img/KKPlogo.png">

    <title>TKJB | Terumbu Karang Jawa Barat</title>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&family=Roboto&display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="css/konten.css" rel="stylesheet">
    
    <!-- Font Awesome CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    
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
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Beranda <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="donasi.php">Donasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="wisata.php">Wisata</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="coralmaps.php">Coralmaps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="coralpedia.php">Coralpedia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <!-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Edit Profil</a>
                    <a class="dropdown-item" href="#">Logout</a>              
                </li>  -->
            </ul> 
        </div>
        <!-- END OF MENU NAVBAR -->
</nav> 
<!-- END OF NAVBAR -->

<!-- CAROUSEL SLIDE -->
    
    <div id="carouselFull" class="carousel slide" data-ride="carousel">
       <ol class="carousel-indicators">
           <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
           <li data-target="#carouselIndicators" data-slide-to="1"></li>
           <li data-target="#carouselIndicators" data-slide-to="2"></li>
           <li data-target="#carouselIndicators" data-slide-to="3"></li>
       </ol>
       <div class="carousel-inner">
           <div class="carousel-item active">
               <img class="d-block" src="dist/img/home-slide1-darken.jpg" alt="First slide">
               <div class="carousel-caption  d-md-block">
                   <h3>BANTU PULIHKAN RUMAH MEREKA</h3><br>
                   <p>Terumbu Karang merupakan rumah bagi ribuan ikan hias. Kerusakan yang
                    disebabkan manusia berdampak buruk pada kehidupan mereka.</p><br>
                    <a href="donasi.php" class="btn btn-link-slide" role="button" aria-pressed="true">        
                      Donasi Sekarang
                    </a>
               </div>
           </div>
           <div class="carousel-item">
               <img class="d-block" src="dist/img/home-slide2-darken.jpg" alt="Second slide">
               <div class="carousel-caption d-md-block">
                   <h3>MARI NIKMATI HASIL
                    PELESTARIAN</h3><br>
                   <p>Terimakasih telah ikut melestarikan biota laut Jawa Barat. Jangan lupa
                    untuk menikmati keindahannya dengan berwisata.</p><br>
                    <a href="wisata.php" class="btn btn-link-slide" role="button" aria-pressed="true">        
                        Wisata Sekarang
                      </a>
               </div>
           </div>
           <div class="carousel-item">
               <img class="d-block" src="dist/img/home-slide3-darken.jpg" alt="Third slide">
               <div class="carousel-caption d-md-block">
                   <h3>AMATI KONDISINYA </h3><br>
                   <p>Beberapa titik terumbu karang berada dalam kondisi rusak parah dan 
                    membutuhkan bantuan kita.</p><br>
                    <a href="coralmaps.php" class="btn btn-link-slide" role="button" aria-pressed="true">        
                        Coralmaps
                      </a>
               </div>
           </div>
           <div class="carousel-item">
            <img class="d-block" src="dist/img/home-slide4-darken.jpg" alt="Third slide">
            <div class="carousel-caption d-md-block">
                <h3>TAK KENAL MAKA
                    TAK SAYANG</h3><br>
                <p>Mari kenali beragam jenis terumbu karang cantik yang terdapat di Jawa Barat.</p><br>
                    <a href="coralpedia.php" class="btn btn-link-slide" role="button" aria-pressed="true">        
                       Coralpedia
                      </a>
            </div>
        </div>
       </div>
       <a class="carousel-control-prev" href="#carouselFull" role="button" data-slide="prev">
           <span class="carousel-control-prev-icon" aria-hidden="true"></span>
           <span class="sr-only">Previous</span>
       </a>
       <a class="carousel-control-next" href="#carouselFull" role="button" data-slide="next">
           <span class="carousel-control-next-icon" aria-hidden="true"></span>
           <span class="sr-only">Next</span>
       </a>
   </div>

   <!-- END CAROUSEL SLIDE -->
    
    <!-- BODY CONTAINER -->
    
    <div class="home-about-container">
        <div id="about" class="container">
            <!-- 1st PARAGRAPH -->
            <div class="starter-template">
                <h1 class="mb-5">TENTANG KAMI</h1><br>
                <p><b>TKJB</b> (Terumbu Karang Jawa Barat) merupakan bentuk kerjasama
                    antara Kementrian Kelautan dan
                    Perikanan (KKP) dengan Kelompok
                    Usaha Bersama (KUB) masyarakat
                    pesisir pantai di Provinsi Jawa Barat yang memiliki tujuan sebagai berikut :</p><br><br>
            </div>
            <!-- END 1st PARAGRAPH -->
            <!-- ROW WITH ICON -->
            <div class="row text-center pb-4">
                <div class="col-md-12 col-lg-4 p-1">
                    <img src="dist/img/konservasi.jpg" class="rounded-circle p-3" alt="Prime Meat Image" width="250" height="250">
                    <h3>Konservasi <br>Terumbu Karang</h3><br>
                    <p>Melestarikan dan melakukan
                        pemulihan pada terumbu
                        karang sekitar pantai Jawa
                        Barat.</p>
                </div>
                <div class="col-md-12 col-lg-4 p-1-middle">
                    <img src="dist/img/informasi.jpg" class="rounded-circle p-3" alt="Fish Image" width="250" height="250">
                    <h3>Informasi <br> Terumbu Karang</h3><br>
                    <p>Menyajikan informasi pada
                        masyarakat umum tentang
                        kondisi terumbu karang
                        di Jawa Barat.
                        </p>
                </div>
                <div class="col-md-12 col-lg-4 p-1">
                    <img src="dist/img/ekonomi.jpg" class="rounded-circle p-3" alt="Vegetables Image" width="250" height="250">
                    <h3>Ekonomi <br>Terumbu Karang</h3><br>
                    <p>Mendukung wisata terumbu
                        karang untuk meningkatkan
                        ekonomi masyarakat pesisir.</p>
                </div>
            </div>
            <!-- END OF ROW WITH ICON -->

        </div>
    <!-- END OF BODY CONTAINER -->
    </div>

    <div id="carouselFull" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block" src="dist/img/home-donasi.jpg" alt="First slide">
                <div class="carousel-caption  d-md-block">
                <center>
                    <h3>SISTEM DONASI MENARIK</h3><br>
                    <p>
                    Simak menariknya sistem donasi yang kami hadirkan
                    </p><br>
                     <a href="donasi.php" class="btn btn-link-slide" role="button" aria-pressed="true">        
                       Info Donasi
                     </a>
                    </center>  
                </div>
            </div>
        </div>
    </div>

    <div class="wisata-container">
        <div id="about" class="container">
            <!-- 1st PARAGRAPH -->
            <div class="starter-template">
                <h3 class="mb-5">WISATA TERUMBU
                    KARANG JABAR</h3><br>
                <p style="text-align: center;">Temukan spot favoritmu untuk
                    melihat indahnya terumbu
                    karang Jawa Barat !</p><br><br>
                    <a href="wisata.php" class="btn btn-link-slide" role="button" aria-pressed="true">        
                        Info Wisata
                      </a>
            </div>
        </div>
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

    <div id="bttp" class="btt">
        <a href="#"><i class="fa fa-arrow-circle-up"></i></a>
    </div>
 



    <!-- <div class="prologue" style="background-color: white; width: 100%; margin-top: -40px; padding-top: 30px;">
        <div id="about" class="container" style="background-color: white;">
            <div class="starter-template">
                <h1 class="mb-5">TERUMBU KARANG</h1>
                <p>
                    Terumbu karang merupakan salah satu komponen utama sumber daya alam laut dan pesisir, 
                    disamping hutan bakau (mangrove) dan padang lamun. Berbagai manfaat yang dihasilkan terumbu karang antara lain :</p><br><br>
            </div>
        </div>
    </div>
     -->
    
    
   
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
    <!-- Video Full Width -->
    <script src="js/jquery.vide.js"></script> 
    <script>
        var scroll = new SmoothScroll('a[href*="#"]');
    </script>
    <!-- Number Counter -->
    <script src="js/nsc.js"></script>
    <!-- Video -->
    <script async src="https://www.youtube.com/iframe_api"></script>
    <script>
     function onYouTubeIframeAPIReady() {
      var player;
      player = new YT.Player('muteYouTubeVideoPlayer', {
        videoId: 'iLs5c2Y1BOM', // YouTube Video ID
        width: 560,               // Player width (in px)
        height: 316,              // Player height (in px)
        playerVars: {
          autoplay: 1,        // Auto-play the video on load
          controls: 1,        // Show pause/play buttons in player
          showinfo: 0,        // Hide the video title
          modestbranding: 1,  // Hide the Youtube Logo
          loop: 1,            // Run the video in a loop
          fs: 0,              // Hide the full screen button
          cc_load_policy: 0, // Hide closed captions
          iv_load_policy: 3,  // Hide the Video Annotations
          autohide: 0         // Hide video controls when playing
        },
        events: {
          onReady: function(e) {
            e.target.mute();
          }
        }
      });
     }

     // Written by @labnol 
    </script>
    <script>
        myID = document.getElementById("bttp");

        var myScrollFunc = function() {
          var y = window.scrollY;
          if (y >= 1200) {
            myID.className = "btt show"
          } else {
            myID.className = "btt hide"
          }
        };

        window.addEventListener("scroll", myScrollFunc);
    </script>
</body>
</html>