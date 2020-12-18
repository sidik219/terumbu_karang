<?php include 'build/config/connection.php';
//session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

    $id_donasi = $_GET['id_donasi'];
    $defaultpic = "images/image_default.jpg";
    $status_donasi = "Menunggu Konfirmasi oleh Pengelola Lokasi";

    $sql = 'SELECT * FROM t_donasi, t_lokasi
    WHERE id_donasi = :id_donasi
    AND t_donasi.id_lokasi = t_lokasi.id_lokasi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_donasi' => $id_donasi]);
    $rowitem = $stmt->fetch();

    if (isset($_POST['submit'])) {
        $randomstring = substr(md5(rand()), 0, 7);

        //Image upload
            if($_FILES["image_uploads"]["size"] == 0) {
                $bukti_donasi = $rowitem->bukti_donasi;
                $pic = "&none=";
            }
            else if (isset($_FILES['image_uploads'])) {
                if (($rowitem->bukti_donasi == $defaultpic) || (!$rowitem->bukti_donasi)){
                    $target_dir  = "images/bukti_donasi/";
                    $bukti_donasi = $target_dir .'BKTDNS_'.$randomstring. '.jpg';
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $bukti_donasi);
                    $pic = "&new=";
                }
                else if (isset($rowitem->bukti_donasi)){
                    $bukti_donasi = $rowitem->bukti_donasi;
                    unlink($rowitem->bukti_donasi);
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowitem->bukti_donasi);
                    $pic = "&replace=";
                }
            }

            //---image upload end

        $tanggal_upload_bukti = date ('Y-m-d H:i:s', time());
        $sqldonasi = "UPDATE t_donasi
                        SET bukti_donasi = :bukti_donasi, status_donasi = :status_donasi, update_terakhir = :update_terakhir
                        WHERE id_donasi = :id_donasi";

        $stmt = $pdo->prepare($sqldonasi);
        $stmt->execute(['id_donasi' => $id_donasi, 'bukti_donasi' => $bukti_donasi, 'status_donasi' => $status_donasi, 'update_terakhir' => $tanggal_upload_bukti]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
        header("Location: donasi_saya.php?status=nochange.$pic");
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            header("Location: donasi_saya.php?status=updatesuccess.$pic");
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
                        <a href="donasi_saya.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Kirim Bukti Donasi</span></h4>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
        <?php //if($_SESSION['level_user'] == '1') { ?>
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">

                    <div class="form-group">
                        <label for="file_bukti_donasi">Bukti Donasi</label>
                        <div class='form-group' id='buktidonasi'>
                        <div>
                            <input type='file'  class='form-control' id='image_uploads'
                                name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                        </div>
                    </div>
                    <div class="form-group">
                        <img id="preview" src="#"  width="100px" alt="Preview Gambar"/>
                        <img id="oldpic" src="<?=$rowitem->bukti_donasi?>" width="100px" <?php if($rowitem->bukti_donasi == NULL) echo " style='display:none;'"; ?>>
                        <script>
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

                    <br>
                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
                     <div class="" style="width:100%;">
                <div class="">
                    <h4 class="card-header mb-2 pl-0">Metode Pembayaran</h4>
            <span class="">Pilihan untuk lokasi</span>  <span class="text-info font-weight-bolder"><?=$rowitem->nama_lokasi?> : </span>
            <div class="d-block my-3">
              <div class="custom-control custom-radio">
                <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
                <p class="text-muted">Harap upload bukti transfer agar donasi segera diproses pengelola lokasi.</p>
              </div>
<hr class="mb-2"/>

            <div class="row">
                <div class="col">
                     <span class="font-weight-bold">Nama Rekening Pengelola
                </div>
                <div class="col-lg-8 mb-2">
                     <span class=""><?=$rowitem->nama_rekening?></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <span class="font-weight-bold">Nomor Rekening Pengelola  </span>
                </div>
                <div class="col-lg-8  mb-2">
                    <span class=""><?=$rowitem->nomor_rekening?></span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <span class="font-weight-bold">Bank Pengelola  </span>
                </div>
                <div class="col-lg-8  mb-2">
                    <span class=""><?=$rowitem->nama_bank?></span>
                </div>
            </div>
                </div>
            </div>
            <br><br>

            </section>
        <?php //} ?>
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
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</body>
</html>
