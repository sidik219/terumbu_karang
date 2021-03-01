<?php include 'build/config/connection.php';
session_start();

if (isset($_SESSION['level_user']) == 0) {
    header('location: login.php');
}

        $id_wisata = $_GET['id_wisata'];
        $defaultpic = "images/image_default.jpg";

        $sqlviewlokasi = 'SELECT * FROM t_lokasi
                            ORDER BY nama_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $rowlokasi = $stmt->fetchAll();

        $sqleditwisata = 'SELECT * FROM t_wisata
                            LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                            WHERE id_wisata = :id_wisata';
        $stmt = $pdo->prepare($sqleditwisata);
        $stmt->execute(['id_wisata' => $id_wisata]);
        $rowwisata = $stmt->fetch();

        if (isset($_POST['submit'])) {
            if (isset($_POST['persentase_paket_donasi'])) {
                $id_lokasi                  = $_POST['dd_id_lokasi'];
                $judul_wisata               = $_POST['tb_judul_wisata'];
                $deskripsi_wisata           = $_POST['tb_deskripsi_wisata'];
                $biaya_wisata               = $_POST['num_biaya_wisata'];
                $status_aktif               = $_POST['rb_status_wisata'];
                $deskripsi_panjang_wisata   = $_POST['deskripsi_panjang_wisata'];

                $randomstring = substr(md5(rand()), 0, 7);

                //Image upload
                if($_FILES["image_uploads"]["size"] == 0) {
                    $foto_wisata = $rowwisata->foto_wisata;
                    $pic = "&none=";
                }
                else if (isset($_FILES['image_uploads'])) {
                    if (($rowwisata->foto_wisata == $defaultpic) || (!$rowwisata->foto_wisata)){
                        $target_dir  = "images/foto_wisata/";
                        $foto_wisata = $target_dir .'WIL_'.$randomstring. '.jpg';
                        move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wisata);
                        $pic = "&new=";
                    }
                    else if (isset($rowwisata->foto_wisata)){
                        $foto_wisata = $rowwisata->foto_wisata;
                        unlink($rowwisata->foto_wisata);
                        move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowwisata->foto_wisata);
                        $pic = "&replace=";
                    }
                }
                //---image upload end

                $sqldeleteisipaket = "DELETE FROM tb_paket_donasi
                                        WHERE id_wisata = :id_wisata";

                $stmt = $pdo->prepare($sqldeleteisipaket);
                $stmt->execute(['id_wisata' => $id_wisata]);

                $sqlwisata = "UPDATE t_wisata
                                SET id_lokasi = :id_lokasi, judul_wisata = :judul_wisata, deskripsi_wisata = :deskripsi_wisata,
                                deskripsi_panjang_wisata = :deskripsi_panjang_wisata, biaya_wisata = :biaya_wisata, foto_wisata = :foto_wisata,
                                status_aktif = :status_aktif WHERE id_wisata = :id_wisata";

                $stmt = $pdo->prepare($sqlwisata);
                $stmt->execute(['id_lokasi' => $id_lokasi,
                                'judul_wisata' => $judul_wisata,
                                'deskripsi_wisata' => $deskripsi_wisata,
                                'biaya_wisata' => $biaya_wisata,
                                'foto_wisata' => $foto_wisata,
                                'status_aktif' => $status_aktif,
                                'id_wisata' => $id_wisata,
                                'deskripsi_panjang_wisata' => $deskripsi_panjang_wisata
                                ]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                    header("Location: kelola_wisata.php?status=nochange");
                } else {
                    //echo "HAHAHAAHA GREAT SUCCESSS !";
                    $last_wisata_id = $pdo->lastInsertId();
                }

                foreach ($_POST['persentase_paket_donasi'] as $persentase_paket_donasi) {
                    $persentase_paket_donasi    = $persentase_paket_donasi;
                    $id_wisata                  = $_GET['id_wisata'];

                    $sqlinsertpaketdonasi = "INSERT INTO tb_paket_donasi (persentase_paket_donasi, id_wisata)
                                        VALUES (:persentase_paket_donasi, :id_wisata)";

                    $stmt = $pdo->prepare($sqlinsertpaketdonasi);
                    $stmt->execute(['persentase_paket_donasi' => $persentase_paket_donasi,
                                    'id_wisata'               => $id_wisata
                                    ]);

                    $affectedrows = $stmt->rowCount();
                    if ($affectedrows == '0') {
                        header("Location: kelola_wisata.php?status=insertfailed");
                    } else {
                        //echo "HAHAHAAHA GREAT SUCCESSS !";
                        header("Location: kelola_wisata.php?status=addsuccess");
                    }
                } //End Foreach
            } else {
                echo '<script>alert("Harap pilih paket donasi yang akan ditambahkan")</script>';
            }
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Wisata - TKJB</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="js/trumbowyg/dist/ui/trumbowyg.min.css">
    <script src="js/trumbowyg/dist/trumbowyg.min.js"></script>

    <!-- Favicon -->
    <link rel="icon" href="dist/img/KKPlogo.png" type="image/x-icon" />
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
                    <!-- SESSION lvl Untuk Lokasi -->
                    <?php if($_SESSION['level_user'] == '3') { ?>
                        <li class="nav-item"> <!-- Wilayah & Lokasi -->
                           <a href="dashboard_admin.php" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_donasi.php" class="nav-link">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Kelola Donasi </p>
                            </a>
                        </li>
                        <li class="nav-item menu-open"> <!-- Lokasi -->
                            <a href="kelola_wisata.php" class="nav-link active">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Kelola Wisata </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_reservasi_wisata.php" class="nav-link">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p> Kelola Reservasi </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Wilayah & Lokasi -->
                            <a href="kelola_lokasi.php" class="nav-link">
                                <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                                <p> Kelola Lokasi </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_titik.php" class="nav-link">
                                 <i class="nav-icon fas fa-crosshairs"></i>
                                 <p> Kelola Titik </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_batch.php" class="nav-link">
                                  <i class="nav-icon fas fa-boxes"></i>
                                  <p> Kelola Batch </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_pemeliharaan.php" class="nav-link">
                                  <i class="nav-icon fas fa-heart"></i>
                                  <p> Kelola Pemeliharaan </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_user.php" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p> Kelola User </p>
                            </a>
                        </li>
                    <?php } ?>
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
                    <a class="btn btn-outline-primary" href="kelola_wisata.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Edit Data Wisata</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
        <?php if($_SESSION['level_user'] == '3') { ?>
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST" name="updateWisata">

                    <div class="form-group">
                    <label for="dd_id_lokasi">ID Lokasi</label>
                    <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control" required>
                            <option value="">Pilih Lokasi</option>
                        <?php foreach ($rowlokasi as $rowitem) {  ?>
                            <option <?php if($rowitem->id_lokasi == $rowwisata->id_lokasi) echo 'selected'; ?> value="<?=$rowitem->id_lokasi?>">ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></option>
                        <?php } ?>
                    </select>
                    </div>


                    <div class="form-group">
                        <label for="tb_judul_wisata">Judul Wisata</label>
                        <input type="text" id="tb_judul_wisata" name="tb_judul_wisata" value="<?=$rowwisata->judul_wisata?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="tb_deskripsi_wisata">Deskripsi Singkat Wisata</label>
                        <input type="text" id="tb_deskripsi_wisata" name="tb_deskripsi_wisata" value="<?=$rowwisata->deskripsi_wisata?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="isi_artikel">Deskripsi Lengkap Wisata:</label>
                        <textarea id="deskripsi_lengkap_wisata" name="deskripsi_panjang_wisata" required><?=$rowwisata->deskripsi_panjang_wisata?></textarea>
                    <script>
                            $('#deskripsi_lengkap_wisata').trumbowyg();
                    </script>
                    </div>

                    <div class="form-group">
                        <label for="num_biaya_wisata">Biaya Wisata</label>
                        <input type="number" id="num_biaya_wisata" name="num_biaya_wisata" value="<?=$rowwisata->biaya_wisata?>" class="form-control">
                    </div>

                    <div class="form-group field_wrapper">
                        <label for="persentase_paket_donasi">Paket donasi</label><br>
                        <div class="form-group fieldGroup">
                            <div class="input-group">

                                <?php
                                $sqlviewpaket = 'SELECT * FROM tb_paket_donasi
                                                    LEFT JOIN t_wisata ON tb_paket_donasi.id_wisata = t_wisata.id_wisata
                                                    WHERE t_wisata.id_wisata = :id_wisata
                                                    AND t_wisata.id_wisata = tb_paket_donasi.id_wisata';

                                $stmt = $pdo->prepare($sqlviewpaket);
                                $stmt->execute(['id_wisata' => $rowwisata->id_wisata]);
                                $rowpersentase = $stmt->fetchAll();

                                foreach ($rowpersentase as $rowpaket) {
                                ?>
                                <input type="text" name="persentase_paket_donasi[]" value="<?=$rowpaket->persentase_paket_donasi?>" class="form-control" placeholder="Paket Donasi"/>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class='form-group' id='fotowilayah'>
                        <div>
                            <label for='image_uploads'>Upload Foto Wisata</label>
                            <input type='file'  class='form-control' id='image_uploads'
                                name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                        </div>
                    </div>

                    <div class="form-group">
                        <img id="preview" src="#"  width="100px" alt="Preview Gambar"/>
                        <img id="oldpic" src="<?=$rowwisata->foto_wisata?>" width="100px">
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

                    <div class="form-group">
                        <label for="rb_status_wisata">Status</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_status_aktif" name="rb_status_wisata" value="Aktif" class="form-check-input" <?php if($rowwisata->status_aktif == 'Aktif') echo ' checked'; ?>>
                                <label class="form-check-label" for="rb_status_aktif" style="color: green">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_status_tidak_aktif" name="rb_status_wisata" value="Tidak Aktif" class="form-check-input" <?php if($rowwisata->status_aktif == 'Tidak Aktif') echo ' checked'; ?>>
                                <label class="form-check-label" for="rb_status_tidak_aktif" style="color: gray">
                                    Tidak Aktif
                                </label>
                            </div>
                    </div>

                    <br>
                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
                    <br><br>


            </section>
        <?php } ?>
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
<div>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</div>

</body>
</html>
