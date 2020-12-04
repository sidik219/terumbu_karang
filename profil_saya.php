<?php include 'build/config/connection.php';
//session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profil Saya - TKJB</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
        <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
        <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
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

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- NAVBAR -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Navbar Toogle -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">  
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">Edit Profil</a>
                            <a class="dropdown-item" href="logout.php">Logout</a>              
                </li>  
            </ul>
        </nav>
        <!-- END OF NAVBAR -->

        <!-- TOP SIDEBAR -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- BRAND LOGO (TOP)-->
            <a href="index_admin.php" class="brand-link">
                <img src="dist/img/KKPlogo.png"  class="brand-image img-circle elevation-3" style="opacity: .8">
                <!-- BRAND TEXT (TOP) -->
                <span class="brand-text font-weight-bold">TKJB</span>
            </a>
            <!-- END OF TOP SIDEBAR -->

            <!-- SIDEBAR -->
            <div class="sidebar">
                <!-- SIDEBAR MENU -->
                <nav class="mt-2">
                   <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php //if($_SESSION['level_user'] == '2') { ?>
                        <li class="nav-item  ">
                           <a href="dashboard_user.php" class="nav-link ">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="donasi_saya.php" class="nav-link">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Donasi Saya </p>
                           </a>
                        </li>
                        <li class="nav-item ">
                           <a href="reservasi_saya.php" class="nav-link ">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Reservasi Saya  </p>
                           </a>
                        </li>
                        <li class="nav-item menu-open">
                           <a href="profil_saya.php" class="nav-link active">
                                <i class="nav-icon fas fas fa-user"></i>
                                <p> Profil Saya  </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="map.php" class="nav-link">
                                <i class="nav-icon fas fas fa-user"></i>
                                <p> Map  </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="review_donasi.php" class="nav-link">
                                <i class="nav-icon fas fas fa-user"></i>
                                <p> Review Donasi  </p>
                           </a>
                        </li>
                    <?php //} ?>
                    </ul>      
                </nav>
                <!-- END OF SIDEBAR MENU -->
            </div>
            <!-- SIDEBAR -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                <?php //if($_SESSION['level_user'] == '2') { ?>
                    <!-- Data profil saya -->
                    <div class="container-profil-saya">
                        <div class="container-profil-header">
                        <h4><span class="align-middle font-weight-bold">Profil Saya</span></h4> 
                        Kelola informasi profil anda       
                        </div>
                        <div class="container-profil-flex">
                            <div class="container-profil-kiri">
                              <form action="" enctype="multipart/form-data" method="POST">
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
                                </div><br>
                                <p align="center">
                         <button type="submit" class="btn btn-submit">Simpan Perubahan</button></p>
                         <br>
                            </form>
 
                        </div>
                        

                    </div>
                    <!-- end of data profil saya -->
                <?php //} ?>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                   
            </div>
            
            </section>
            <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->
        </div>
        <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; 2020 .</strong> Terumbu Karang Jawa Barat
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <!-- Leaflet Marker Cluster -->
    <script src="dist/js/leaflet.markercluster-src.js"></script>
    <!-- Leaflet panel layer JS-->
    <script src="dist/js/leaflet-panel-layers.js"></script>
    <!-- Leaflet Ajax, Plugin Untuk Mengloot GEOJson -->
    <script src="dist/js/leaflet.ajax.js"></script>
    <!-- Leaflet Map -->
    <script src="dist/js/leaflet-map.js"></script>

</body>
</html>