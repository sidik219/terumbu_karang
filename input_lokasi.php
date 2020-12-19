<?php include 'build/config/connection.php';
//session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

    $sqlviewwilayah = 'SELECT * FROM t_wilayah
                        ORDER BY nama_wilayah';
        $stmt = $pdo->prepare($sqlviewwilayah);
        $stmt->execute();
        $row = $stmt->fetchAll();

    if (isset($_POST['submit'])) {
        if($_POST['submit'] == 'Simpan'){
            $id_wilayah = $_POST['dd_id_wilayah'];
            $nama_lokasi        = $_POST['tb_nama_lokasi'];
            $luas_lokasi        = $_POST['num_luas_lokasi'];
            $deskripsi_lokasi     = $_POST['tb_deskripsi_lokasi'];
            $id_user_pengelola     = $_POST['tb_id_pengelola'];
            $kontak_lokasi     = $_POST['num_kontak_lokasi'];
            $nama_bank     = $_POST['tb_nama_bank'];
            $nama_rekening     = $_POST['tb_nama_rekening'];
            $nomor_rekening     = $_POST['num_nomor_rekening'];
            $longitude        = $_POST['tb_longitude'];
            $latitude        = $_POST['tb_latitude'];
            $randomstring = substr(md5(rand()), 0, 7);

            //Image upload
            if($_FILES["image_uploads"]["size"] == 0) {
                $foto_lokasi = "images/image_default.jpg";
            }
            else if (isset($_FILES['image_uploads'])) {
                $target_dir  = "images/foto_lokasi/";
                $foto_lokasi = $target_dir .'LOK_'.$randomstring. '.jpg';
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_lokasi);
            }

            //---image upload end

            $sqllokasi = "INSERT INTO t_lokasi
                            (id_wilayah, nama_lokasi, deskripsi_lokasi, foto_lokasi, luas_lokasi, id_user_pengelola,
                            kontak_lokasi, nama_bank, nama_rekening, nomor_rekening, longitude, latitude)
                            VALUES (:id_wilayah, :nama_lokasi, :deskripsi_lokasi, :foto_lokasi, :luas_lokasi,
                            :id_user_pengelola, :kontak_lokasi, :nama_bank, :nama_rekening, :nomor_rekening, :longitude, :latitude)";

            $stmt = $pdo->prepare($sqllokasi);
            $stmt->execute(['id_wilayah' => $id_wilayah, 'nama_lokasi' => $nama_lokasi,
            'deskripsi_lokasi' => $deskripsi_lokasi, 'foto_lokasi' => $foto_lokasi,
            'luas_lokasi' => $luas_lokasi, 'id_user_pengelola' => $id_user_pengelola,
            'kontak_lokasi' => $kontak_lokasi,'nama_bank' => $nama_bank,
            'nama_rekening' => $nama_rekening,'nomor_rekening' => $nomor_rekening, 'longitude' => $longitude, 'latitude' => $latitude]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_lokasi.php?status=addsuccess");
                }
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Lokasi - Terumbu Karang</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
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
                    <?php //if($_SESSION['level_user'] == '1') { ?>
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
                            <a href="kelola_wisata.php" class="nav-link">
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
                        <a href="kelola_lokasi.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Input Data Lokasi</span></h4>
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
                        <label for="dd_id_wilayah">ID Wilayah</label>
                        <select id="dd_id_wilayah" name="dd_id_wilayah" class="form-control">
                            <?php foreach ($row as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_wilayah?>">ID <?=$rowitem->id_wilayah?> - <?=$rowitem->nama_wilayah?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tb_nama_lokasi">Nama Lokasi</label>
                        <input type="text" id="tb_nama_lokasi" name="tb_nama_lokasi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="num_luas_lokasi">Estimasi Luas Titik Total (m<sup>2</sup>)</label>
                        <input type="number" id="num_luas_lokasi" name="num_luas_lokasi" class="form-control">
                    </div>
                    <div class='form-group' id='fotowilayah'>
                        <div>
                            <label for='image_uploads'>Upload Foto Lokasi</label>
                            <input type='file'  class='form-control' id='image_uploads'
                                name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                        </div>
                    </div>
                    <div class="form-group">
                        <img id="preview"  width="100px" src="#" alt="Preview Gambar"/>

                        <script>
                            window.onload = function() {
                            document.getElementById('preview').style.display = 'none';
                            };
                            function readURL(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();

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
                    <div class="form-group">
                        <label for="tb_deskripsi_lokasi">Deskripsi</label>
                        <input type="text" id="tb_deskripsi_lokasi" name="tb_deskripsi_lokasi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tb_id_pengelola">ID User Pengelola</label>
                        <input type="text" id="tb_id_pengelola" name="tb_id_pengelola" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="num_kontak_lokasi">Kontak Lokasi</label>
                        <input type="number" id="num_kontak_lokasi" name="num_kontak_lokasi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tb_nama_bank">Nama Bank</label>
                        <input type="text" id="tb_nama_bank" name="tb_nama_bank" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tb_nama_rekening">Nama Rekening</label>
                        <input type="text" id="tb_nama_rekening" name="tb_nama_rekening" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="num_nomor_rekening">Nomor Rekening</label>
                        <input type="number" id="num_nomor_rekening" name="num_nomor_rekening" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tb_longitude">Longitude (Koordinat diperlukan agar lokasi tampil di peta)</label>
                        <input type="text" id="tb_longitude" name="tb_longitude" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tb_latitude">Latitude</label>
                        <input type="text" id="tb_latitude" name="tb_latitude" class="form-control">
                    </div>
                    <br>
                    <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
            <br><br>
                    <a href="input_titik.php">Lanjut isi data titik ></a>
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
