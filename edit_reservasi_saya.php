<?php include 'build/config/connection.php';
//session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}
    
    $id_reservasi = $_GET['id_reservasi'];
    $defaultpic = "images/image_default.jpg";
    $id_status_reservasi_wisata = 1;
    
    $sql = 'SELECT * FROM t_reservasi_wisata, t_user, t_lokasi
    WHERE id_reservasi = :id_reservasi
    AND t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_reservasi' => $id_reservasi]);
    $rowitem = $stmt->fetch();
    
    if (isset($_POST['submit'])) {
        $randomstring = substr(md5(rand()), 0, 7);

        //Image upload
        if($_FILES["image_uploads"]["size"] == 0) {
            $bukti_reservasi = $rowitem->bukti_reservasi;
            $pic = "&none=";
        }
        else if (isset($_FILES['image_uploads'])) {
            if (($rowitem->bukti_reservasi == $defaultpic) || (!$rowitem->bukti_reservasi)){
                $target_dir  = "images/bukti_reservasi/";
                $bukti_reservasi = $target_dir .'BKTDNS_'.$randomstring. '.jpg';
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $bukti_reservasi);
                $pic = "&new=";
            }
            else if (isset($rowitem->bukti_reservasi)){
                $bukti_reservasi = $rowitem->bukti_reservasi;
                unlink($rowitem->bukti_reservasi);
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowitem->bukti_reservasi);
                $pic = "&replace=";
            }
        }
        //---image upload end

        $tanggal_upload_bukti = date ('Y-m-d H:i:s', time());

        $sqlreservasi = "UPDATE t_reservasi_wisata
                        SET bukti_reservasi = :bukti_reservasi, id_status_reservasi_wisata = :id_status_reservasi_wisata, update_terakhir = :update_terakhir
                        WHERE id_reservasi = :id_reservasi";

        $stmt = $pdo->prepare($sqlreservasi);
        $stmt->execute(['id_reservasi' => $id_reservasi, 'bukti_reservasi' => $bukti_reservasi, 'id_status_reservasi_wisata' => $id_status_reservasi_wisata, 'update_terakhir' => $tanggal_upload_bukti]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            header("Location: reservasi_saya.php?status=nochange");
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            header("Location: reservasi_saya.php?status=updatesuccess");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Donasi - TKJB</title>
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
                   <?php //if($_SESSION['level_user'] == '2') { ?>    
                        <li class="nav-item ">
                           <a href="dashboard_user.php" class="nav-link  ">
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
                        <li class="nav-item  menu-open">
                           <a href="reservasi_saya.php" class="nav-link active">
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
                    <a class="btn btn-outline-primary" href="reservasi_saya.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Kirim Bukti Reservasi Wisata</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php //if($_SESSION['level_user'] == '1') { ?>
                        <!--
                        <form action="" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label for="dd_id_lokasi_wisata">ID Lokasi</label>
                                <select id="dd_id_lokasi_wisata" name="dd_id_lokasi_wisata" class="form-control">
                                    <option value="">41051 - Pantai Tangkolak</option>
                                    <option value="">45211 - Pulau Biawak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date_reservasi_wisata">Tanggal Reservasi</label>
                                <div class="file-form">
                                <input type="date" id="date_reservasi_wisata" name="date_reservasi_wisata" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="num_jumlah_peserta">Jumlah Peserta</label>
                                <input type="number" id="num_jumlah_peserta" name="num_jumlah_peserta" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="num_total_reservasi">Total (Rp.)</label>
                                <input type="number" id="num_total_reservasi" name="num_total_reservasi" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="rb_status_reservasi">Status</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="rb_status_reservasi_selesai" name="rb_status_reservasi" value="diterima" class="form-check-input">
                                    <label class="form-check-label" for="rb_status_reservasi_selesai">Selesai</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="rb_status_reservasi_pending" name="rb_status_reservasi" value="belum_diterima" class="form-check-input">
                                    <label class="form-check-label" for="rb_status_reservasi_pending">Pending</label>
                                </div>
                            </div>
                            <br>
                            <p align="center">
                            <button type="submit" class="btn btn-submit">Kirim</button></p>
                        </form> -->


                <form action="" enctype="multipart/form-data" method="POST">
                    <div class="row">
                        <div class="col-lg-9 border rounded bg-white mb-2">
                            <div class="" style="width:100%;">
                                <div class="">
                                    <h5 class="card-header mb-2 pl-0">Rincian Pembayaran</h5>
                                    <span class="">Pilihan Reservasi Wisata : </span>  <span class="text-info font-weight-bolder"><?=$rowitem->nama_lokasi?></span>
                                    <div class="d-block my-3">
                                        <div class="custom-control custom-radio">
                                            <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                                            <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
                                            <p class="text-muted">Harap upload bukti transfer agar reservasi wisata segera diproses pengelola lokasi.</p>
                                        </div>
                                        <hr class="mb-2"/>

                                        <div class="row">
                                            <div class="col">
                                                <span class="font-weight-bold">
                                                <i class="text-danger fas fa-id-card-alt"></i> ID User
                                                </span>
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <span class=""><?=$rowitem->id_reservasi?></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <span class="font-weight-bold">
                                                    <i class="text-primary fas fa-user"></i> Nama User
                                                </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class=""><?=$rowitem->nama_user?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">
                                                    <i class="text-secondary fas fa-calendar-alt"></i> Tanggal Reservasi
                                                </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class=""><?=$rowitem->tgl_reservasi?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">
                                                    <i class="text-info fas fa-users"></i> Jumlah Peserta
                                                </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class="font-weight-bold"><?=$rowitem->jumlah_peserta?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">
                                                    <i class="text-success fas fa-money-bill-wave"></i> Total
                                                </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class="font-weight-bold">Rp. <?=number_format($rowitem->total, 0)?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">
                                                <i class="text-info fas fa-comment-dots"></i> Keterangan
                                                </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class="font-weight-bold"><?=$rowitem->keterangan?></span>
                                            </div>
                                        </div>

                                        <!-- Info Pengelola -->
                                        <hr class="mb-2"/>
                                        
                                        <div class="row">
                                            <div class="col">
                                                <span class="font-weight-bold"><i class="fas fa-user-tie"></i> Nama Rekening Pengelola
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <span class=""><?=$rowitem->nama_rekening?></span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold"><i class="text-warning fas fa-university"></i> Bank Pengelola  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class=""><?=$rowitem->nama_bank?></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <span class="font-weight-bold"><i class="text-info fas fa-hashtag"></i> Nomor Rekening Pengelola  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class=""><?=$rowitem->nomor_rekening?></span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>

                        <div class="col-lg-3  border rounded bg-white p-3 mb-2  text-center">
                            <div class="form-group">
                                <label for="file_bukti_reservasi_wisata">Bukti Reservasi Wisata</label>
                                <div class='form-group' id='buktireservasi'>
                                    <label class="btn btn btn-primary btn-blue" for='image_uploads'>
                                        <i class="fas fa-camera"></i> Upload File
                                    </label>
                                <div>
                                    <input type='file'  class='form-control d-none' id='image_uploads'
                                        name='image_uploads' accept='.jpg, .jpeg, .png, .pdf' onchange="readURL(this);">
                            </div>
                        </div>

                        <div class="form-group">
                            <img id="preview" src="#"  width="100px" alt="Preview Gambar"/>
                                <a href="<?=$rowitem->bukti_reservasi?>" data-toggle="lightbox"><img class="img-fluid" id="oldpic" src="<?=$rowitem->bukti_reservasi?>" width="50%" <?php if($rowitem->bukti_reservasi == NULL) echo " style='display:none;'"; ?>></a>
                            <br>

                            <small class="text-muted">
                                <?php if($rowitem->bukti_reservasi == NULL){
                                    echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                                }else{
                                    echo "Klik gambar untuk memperbesar";
                                }

                                ?>
                            </small>
                            
                            <script>
                                const actualBtn = document.getElementById('image_uploads');
                                
                                const fileChosen = document.getElementById('file-input-label');

                                actualBtn.addEventListener('change', function(){
                                fileChosen.innerHTML = '<b>File dipilih :</b> '+this.files[0].name
                                })
                                window.onload = function() {
                                document.getElementById('preview').style.display = 'none';
                                };
                                function readURL(input) {
                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();
                                        document.getElementById('oldpic').style.display = 'none';
                                        reader.onload = function (e) {
                                            $('#preview')
                                                .attr('src', e.target.result)
                                                .width(200);
                                                document.getElementById('preview').style.display = 'block';
                                        };

                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                        </div>
                    </div>
                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>

                    <?php //} ?>
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
    <br><br>
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
