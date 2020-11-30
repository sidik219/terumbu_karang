<?php 
    include 'build/config/connection.php';

    $sqlviewdonasi = 'SELECT * FROM t_donasi 
                    WHERE id_user = 1';
    $stmt = $pdo->prepare($sqlviewdonasi);
    $stmt->execute();
    $row = $stmt->fetchAll();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Donasi Saya - TKJB</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
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
            <a href="dashboard_user.php" class="brand-link">
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
                        <li class="nav-item  ">
                           <a href="dashboard_user.php" class="nav-link ">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item menu-open">
                           <a href="donasi_saya.php" class="nav-link active">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Donasi Saya </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="reservasi_saya.php" class="nav-link">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Reservasi Saya  </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="profil_saya.php" class="nav-link">
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
                            <h4><span class="align-middle font-weight-bold">Donasi Saya</span></h4>
                        </div>

                        <div class="col">
                           
                        <a class="btn btn-primary float-right" href="map.php" role="button">Donasi Sekarang (+)</a>
                   
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div>
                        <table class="table table-striped">
                     <thead>
                            <tr>
                                <th scope="col">ID Donasi</th>
                                <!-- <th scope="col">ID User</th> -->
                                <th scope="col">Nominal</th>
                                <!-- <th scope="col">Bukti Donasi</th> -->
                                <th scope="col">Tanggal Donasi</th>
                                <th scope="col">Status Donasi</th>
                                <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                    <tbody>
                        <?php
                         //$index = 0;
                          foreach ($row as $rowitem) {    
                            $deskripsi = json_decode($rowitem->deskripsi_donasi);                    
                          ?>
                          <tr>
                              <th scope="row"><?=$rowitem->id_donasi?></th>
                              <!-- <td>10918004</td> -->
                              <td>Rp. <?=$rowitem->nominal?></td>
                              <!-- <td>-</td> -->
                              <td><?=$rowitem->tanggal_donasi?></td>
                              <td><?=$rowitem->status_donasi?></td>
                              <td>
                                <button type="button" class="btn btn-act">
                                <a href="edit_donasi.php" class="fas fa-edit"></a>
                            	</button>
                                <button type="button" class="btn btn-act"><i class="far fa-trash-alt"></i></button>
                              </td>
                              
                          </tr>

                          <tr>
                                <td colspan="3">
                                    <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$rowitem->id_donasi?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$rowitem->id_donasi?>, .contentall<?=$rowitem->id_donasi?>">
                                <p
                                    class="fielddetail<?=$rowitem->id_donasi?>">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Donasi</p>
                            </div>
                            <div class="col-12 cell<?=$rowitem->id_donasi?> collapse contentall<?=$rowitem->id_donasi?>">                               
                                <div class="row mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Pesan/Ekspresi 
                                    </div>
                                    <div class="col isi">
                                        <?php 
                                            echo $deskripsi->pesan;
                                        ?>
                                    </div>
                                </div>
                                

                                <div class="row mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Pilihan 
                                    </div>
                                    <div class="col isi">
                                        <?php 
                                           foreach ($deskripsi->keranjang as $isi){?>
                                              <span><?= $isi->nama_tk?> x<?= $isi->jumlah_tk?></span><br/>
                                           
                                        <?php   }
                                        ?>
                                    </div>
                                </div>

                                <!-- <div class="row  mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Foto Wilayah 
                                    </div>
                                    <div class="col isi">
                                        <img src="<?=$rowitem->foto_wilayah?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        ID Lokasi
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->id_lokasi?>
                                    </div>
                                </div>
                                    
                            </div>
                        </div>

                        <!--collapse end -->
                                </td>
                            </tr>
                            <?php //$index++; 
                            } ?>
                    </tbody>
                  </table> 
                    </div>
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
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>

</body>
</html>