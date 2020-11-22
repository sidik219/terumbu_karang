<?php
    include 'build\config\connection.php';

    if (isset($_GET['status'])){
        $status = $_GET['status'];
    }
    
    $sqlviewlokasi = 'SELECT *, nama_wilayah, SUM(luas_titik) AS luas_total, 
    COUNT(id_titik) AS jumlah_titik, COUNT(case when kondisi_titik = "Kurang" then 1 else null end) as jumlah_kurang, 
    COUNT(case when kondisi_titik = "Cukup" then 1 else null end) as jumlah_cukup, 
    COUNT(case when kondisi_titik = "Baik" then 1 else null end) as jumlah_baik, 
    COUNT(case when kondisi_titik = "Sangat Baik" then 1 else null end) as jumlah_sangat_baik
    FROM t_lokasi LEFT JOIN t_titik ON t_lokasi.id_lokasi = t_titik.id_lokasi 
    LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah 
    GROUP BY nama_lokasi
';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $row = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Lokasi - TKJB</title>
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
                            <a class="dropdown-item" href="#">Logout</a>              
                </li>  
            </ul>
        </nav>
        <!-- END OF NAVBAR -->

        <!-- TOP SIDEBAR -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- BRAND LOGO (TOP)-->
            <a href="dashboard_admin.php" class="brand-link">
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
                        <li class="nav-item ">
                           <a href="dashboard_admin.php" class="nav-link ">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_donasi.php" class="nav-link ">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Kelola Donasi </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_wisata.php" class="nav-link ">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Kelola Wisata </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_reservasi_wisata.php" class="nav-link">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p> Kelola Reservasi </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_wilayah.php" class="nav-link ">
                                <i class="nav-icon fas fa-globe-asia"></i>
                                <p> Kelola Wilayah </p>
                            </a>
                        </li>
                        <li class="nav-item menu-open">
                            <a href="kelola_lokasi.php" class="nav-link active">
                                <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                                <p> Kelola Lokasi </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_titik.php" class="nav-link">
                                 <i class="nav-icon fas fa-crosshairs"></i>
                                 <p> Kelola Titik </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_detail_titik.php" class="nav-link">
                                 <i class="nav-icon fas fa-podcast"></i>
                                 <p> Kelola Detail Titik </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_batch.php" class="nav-link">
                                  <i class="nav-icon fas fa-boxes"></i>
                                  <p> Kelola Batch </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_pemeliharaan.php" class="nav-link">
                                  <i class="nav-icon fas fa-heart"></i>
                                  <p> Kelola Pemeliharaan </p>
                            </a>
                        </li>
                        <li class="nav-item">
                             <a href="kelola_jenis_tk.php" class="nav-link">
                                   <i class="nav-icon fas fa-certificate"></i>
                                   <p> Kelola Jenis Terumbu </p>
                             </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_tk.php" class="nav-link">
                                  <i class="nav-icon fas fa-disease"></i>
                                  <p> Kelola Terumbu Karang </p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                             <a href="kelola_perizinan.php" class="nav-link">
                                    <i class="nav-icon fas fa-scroll"></i>
                                    <p> Kelola Perizinan </p>
                             </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_laporan.php" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p> Kelola Laporan </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_user.php" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p> Kelola User </p>
                            </a>
                        </li>
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
                <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Kelola Lokasi</span></h4>
                        </div>
                        <div class="col">
                           
                        <a class="btn btn-primary float-right" href="input_lokasi.php" role="button">Input Data Baru (+)</a>
                   
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <table class="table table-striped">
                    <thead>
                            <tr>
                            <th scope="col">ID Lokasi</th>
                            <th scope="col">ID Wilayah</th>
                            <th scope="col">Nama Lokasi</th>
                            <th scope="col">Luas Titik Terdata</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php foreach ($row as $rowitem) {                            
                        ?>
                            <tr>
                            <th scope="row"><?=$rowitem->id_lokasi?></th>
                            <td><?=$rowitem->id_wilayah?> - <?=$rowitem->nama_wilayah?></td>
                            <td><?=$rowitem->nama_lokasi?></td>
                            <td><?=$rowitem->luas_total?> m<sup>2</sup></td>
                            <td>
                                <a href="edit_lokasi.php?id_lokasi=<?=$rowitem->id_lokasi?>" class="fas fa-edit mr-3"></a>
                                <a href="hapus.php?type=lokasi&id_lokasi=<?=$rowitem->id_lokasi?>" class="far fa-trash-alt"></a>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$rowitem->id_lokasi?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$rowitem->id_lokasi?>, .contentall<?=$rowitem->id_lokasi?>">
                                <p
                                    class="fielddetail<?=$rowitem->id_lokasi?>">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Lokasi</p>
                            </div>
                            <div class="col-12 cell<?=$rowitem->id_lokasi?> collapse contentall<?=$rowitem->id_lokasi?>">                               
                                 <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Estimasi Total Luas Titik
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->luas_lokasi. ' m<sup>2</sup>'?> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Jumlah Titik Terdata
                                    </div>
                                    <div class="col isi mb-3">
                                        <?=$rowitem->jumlah_titik?>
                                    </div>
                                </div>
                                <h5>Kondisi Titik</h5>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Sangat Baik 
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->jumlah_sangat_baik?>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Baik
                                    </div>
                                     <div class="col isi">
                                        <?=$rowitem->jumlah_baik?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Cukup
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->jumlah_cukup?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Kurang
                                    </div>
                                    <div class="col isi mb-3">
                                        <?=$rowitem->jumlah_kurang?>
                                    </div>
                                </div>                                
                                <div class="row mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Deskripsi Lokasi 
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->deskripsi_lokasi?>
                                    </div>
                                </div>
                                <div class="row  mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Foto Lokasi 
                                    </div>
                                    <div class="col isi">
                                        <img src="<?=$rowitem->foto_lokasi?>?<?php if ($status='nochange'){echo time();}?>" width="150px">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        ID Pengelola
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->id_user_pengelola?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Kontak Lokasi
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->kontak_lokasi?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Nama Bank
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->nama_bank?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Nama Rekening
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->nama_rekening?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Nomor Rekening
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->nomor_rekening?>
                                    </div>
                                </div>                                
                            </div>
                        </div>

                        <!--collapse end -->
                                </td>
                            </tr>
                           <?php } ?>
                    </tbody>
                  </table>
            
            
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