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
                <li class="nav-item">
                    <a class="nav-link" href="wisata.php">Wisata</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="coralmaps.php">Coralmaps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="coralpedia.php">Coralpedia</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="login.php">Login <span class="sr-only">(current)</span></a>
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


    <div id="about" class="container">
        
        <div class="starter-template">
            <h1 class="mb-5"><br>REGISTRASI AKUN</h1><br>
        </div>
       
        <form action="" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                    <div class="form-group">
                        <label for="tb_nama_user">Nama User</label>
                        <input type="text" id="tb_nama_user" name="tb_nama_user" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="rb_jenis_kelamin">Jenis Kelamin</label><br>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="rb_jenis_kelamin_pria" name="rb_jenis_kelamin" value="pria" class="form-check-input">
                            <label class="form-check-label" for="rb_jenis_kelamin_pria">Pria</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" id="rb_jenis_kelamin_wanita" name="rb_jenis_kelamin" value="wanita" class="form-check-input">
                            <label class="form-check-label" for="rb_jenis_kelamin_wanita">Wanita</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tb_email">Email</label>
                        <input type="text" id="tb_email" name="tb_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="num_nomer_hp">No. HP</label>
                        <input type="number" id="num_nomer_hp" name="num_nomer_hp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tb_alamat_user">Alamat</label>
                        <input type="text" id="tb_email" name="tb_alamat_user" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="num_ktp_user">No. KTP</label>
                        <input type="number" id="num_ktp_user" name="num_ktp_user" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="file_fc_ktp">Fotokopi KTP</label>
                        <div class="file-form">
                        <input type="file" id="file_fc_ktp" name="file_fc_ktp" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tb_tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tb_tempat_lahir" name="tb_tempat_lahir" class="form-control">
                    </div>
                    <div class="form-group">
                         <label for="date_tanggal_lahir">Tanggal Lahir</label>
                         <div class="file-form">
                         <input type="date" id="date_tanggal_lahir" name="date_tanggal_lahir" class="form-control" >
                         </div>
                     </div>
                     <div class="form-group">
                        <label for="file_foto_user">Foto Diri</label>
                        <div class="file-form">
                        <input type="file" id="file_foto_user" name="file_foto_user" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tb_username">Username</label>
                        <input type="text" id="tb_username" name="tb_username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Password</label>
                        <input type="password" id="pwd" name="pwd" class="form-control">
                    </div>
                    <br>
                    <p align="center">
                         <button type="submit" class="btn btn-submit">Kirim</button></p>
                    </form>

</div>
        
    </div>
    <!-- END OF BODY CONTAINER -->
   
    <br><br><br><br><br><br>
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