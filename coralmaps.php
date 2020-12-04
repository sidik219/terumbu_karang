<?php include 'build/config/connection.php';
session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}
?>

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

     <!-- summernote -->
     <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <!--Leaflet panel layer CSS-->
        <link rel="stylesheet" href="dist/css/leaflet-panel-layers.css" />
    <!-- Leaflet Marker Cluster CSS -->
        <link rel="stylesheet" href="dist/css/MarkerCluster.css" />
        <link rel="stylesheet" href="dist/css/MarkerCluster.Default.css" />
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
    
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
                <li class="nav-item active">
                    <a class="nav-link" href="coralmaps.php">Coralmaps <span class="sr-only">(current)</span></a>
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

 
  <div class="coralmaps-about-container">
        <div id="about" class="container" style="margin-top:50px;">
            
        <div class="starter-template">
                <h1 class="mb-5">PILIH LOKASI</h1><br>
        </div>

        <section class="content" style="margin-top:-50px;">
                <div class="container-fluid">
                <!-- Untuk User -->
                <?php //if($_SESSION['level_user'] == '2') { ?>
                    <div>
                        <div>
                            <label>Keterangan Icon:</label>
                        </div>
                        <div>
                            <img src="images/foto_lokasi/icon_lokasi/icon_lokasi.png" style="width: 3%;">:
                            <label>Lokasi Pantai, </label>
                            <img src="images/kumpulan_icon/cluster.png" style="width: 3%;">:
                            <label>Pengelompokan Jarak Terdekat, </label>
                            <img src="images/kumpulan_icon/geo_wilayah1.png" style="width: 3%;">:
                            <label>Geo Wilayah, </label>
                            <img src="images/kumpulan_icon/geo_wilayah2.png" style="width: 3%;">:
                            <label>Geo Wilayah, </label>
                        </div>
                        <div id="mapid" style="height: 560px; width: 100%; margin-top: 20px;"></div>
                    </div>
                <?php //} ?>
                </div>
        </section>

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
     <!-- Leaflet JS -->
     <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <!-- Leaflet Marker Cluster -->
    <script src="dist/js/leaflet.markercluster-src.js"></script>
    <!-- Leaflet panel layer JS-->
    <script src="dist/js/leaflet-panel-layers.js"></script>
    <!-- Leaflet Ajax, Plugin Untuk Mengloot GEOJson -->
    <script src="dist/js/leaflet.ajax.js"></script>
    <!-- Leaflet Map -->
    <?php include 'dist/js/leaflet_map.php'; ?>
   

</body>
</html>