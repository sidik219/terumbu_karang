<?php include 'build/config/connection.php';
session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

  // else{
  //     $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
  // }

    $sqlwisata = 'SELECT * FROM t_wisata
                LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi';

    $stmt = $pdo->prepare($sqlwisata);
    $stmt->execute();
    $rowwisata = $stmt->fetchAll();
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

    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style-card.css">

    <!-- Favicon -->
    <link rel="icon" href="dist/img/KKPlogo.png" type="image/x-icon" />


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
                <?php print_sidebar(basename(__FILE__), $_SESSION['level_user'])?> <!-- Print sidebar -->
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
            <h1 class="mb-5">PILIH LOKASI RESERVASI WISATA</h1><br>
        </div>

        <section class="content" style="margin-top:-50px;">
                <div class="container-fluid">
                <!-- Untuk User -->
                    <?php //if($_SESSION['level_user'] == '2') { ?>
                        <div class="container-fluid">
                            <div class="row">
                            <?php
                            foreach ($rowwisata as $rowitem) {
                                if ($rowitem->status_aktif == "Aktif") { ?>

                                <div class="col-md-4">
                                    <div class="card card-pilihan mb-4 shadow-sm">
                                    <a href="detail_lokasi_wisata.php?id_lokasi=<?=$rowitem->id_lokasi?>">
                                        <img class="card-img-top" width="100%" src="<?=$rowitem->foto_wisata?>">
                                    </a>
                                        <div class="card-body card-body-costom">
                                            <p class="card-title"><h5 class="font-weight-bold"><?=$rowitem->nama_lokasi?></h5></p>
                                            <p class="card-text"><?=$rowitem->judul_wisata?></p>
                                            <a class="btn btn-primary btn-lg btn-block mb-4" href="detail_lokasi_wisata.php?id_wisata=<?=$rowitem->id_wisata?>" class="card-donasi__cta" style="color: white;">Pilih Lokasi</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Hidden Input :V -->
                                <div class="row">
                                    <div class="col-lg-8 mb-2">
                                        <input type="hidden" id="" name="" value="<?=$rowitem->nama_rekening?>">
                                        <input type="hidden" id="" name="" value="<?=$rowitem->nomor_rekening?>">
                                        <input type="hidden" id="" name="" value="<?=$rowitem->nama_bank?>">
                                    </div>
                                </div>

                                <?php } ?>
                            <?php } ?>
                            </div>
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


</body>
</html>
