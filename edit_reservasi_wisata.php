<?php include 'build/config/connection.php';
session_start();

if (isset($_SESSION['level_user']) == 0) {
    header('location: login.php');
}

    $id_reservasi = $_GET['id_reservasi'];
    $status_reservasi_wisata = "Menunggu Konfirmasi Pembayaran";

    $sql = 'SELECT * FROM t_reservasi_wisata, t_user, t_lokasi, t_wisata
    WHERE id_reservasi = :id_reservasi
    AND t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
    AND t_reservasi_wisata.id_wisata = t_wisata.id_wisata';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_reservasi' => $id_reservasi]);
    $rowitem = $stmt->fetch();

    $sqlstatus = 'SELECT * FROM tb_status_reservasi_wisata';
    $stmt = $pdo->prepare($sqlstatus);
    $stmt->execute();
    $rowstatus = $stmt->fetchAll();

    if (isset($_POST['submit'])) {
        $keterangan                  = $_POST['keterangan'];
        $id_status_reservasi_wisata  = $_POST['radio_status'];
        $sqlreservasi = "UPDATE t_reservasi_wisata
                        SET keterangan = :keterangan,
                            id_status_reservasi_wisata = :id_status_reservasi_wisata
                        WHERE id_reservasi = :id_reservasi";

        $stmt = $pdo->prepare($sqlreservasi);
        $stmt->execute(['id_reservasi' => $id_reservasi, 'keterangan' => $keterangan,
                        'id_status_reservasi_wisata' => $id_status_reservasi_wisata]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            header("Location: kelola_reservasi_wisata.php?status=nochange");
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            header("Location: kelola_reservasi_wisata.php?status=updatesuccess");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Donasi - TKJB</title>
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
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_wisata.php" class="nav-link">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Kelola Wisata </p>
                            </a>
                        </li>
                        <li class="nav-item menu-open"> <!-- Lokasi -->
                            <a href="kelola_reservasi_wisata.php" class="nav-link active">
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
                    <a class="btn btn-outline-primary" href="kelola_reservasi_wisata.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Edit Data Reservasi</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php if($_SESSION['level_user'] == '3') { ?>
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
                        <div class="col-12 mb-2 border rounded bg-white p-3">
                        <h5 class="font-weight-bold">Status Reservasi Wisata</h5>

                        <?php foreach($rowstatus as $status){ ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="radio_status" id="radio_status<?=$status->id_status_reservasi_wisata?>" value="<?=$status->id_status_reservasi_wisata?>" <?php if($rowitem->id_status_reservasi_wisata == $status->id_status_reservasi_wisata) echo " checked"; ?>>
                                <label class="form-check-label <?php if($rowitem->id_status_reservasi_wisata == $status->id_status_reservasi_wisata) echo " font-weight-bold"; ?>" for="radio_status<?=$status->id_status_reservasi_wisata?>">
                                    <?=$status->nama_status_reservasi_wisata?>
                                </label>
                            </div>
                        <?php } ?>

                            <button type="submit" name="submit" value="Simpan" class="btn btn-primary mt-2">Update Status</button></p>
                        </div>


                        <div class="col-lg-9 border rounded bg-white mb-2">
                            <div class="" style="width:100%;">
                                <div class="">
                                    <h5 class="card-header mb-2 pl-0">Rincian Pembayaran</h5>
                                    <span class="">Lokasi : </span>  <span class="text-info font-weight-bolder"><?=$rowitem->nama_lokasi?></span>
                                    <div class="d-block my-3">
                                        <div class="custom-control custom-radio">
                                            <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                                            <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
                                        </div>
                                        <hr class="mb-2"/>

                                        <div class="row">
                                            <div class="col">
                                                <span class="font-weight-bold">ID User
                                            </div>
                                            <div class="col-lg-8 mb-2">
                                                <span class=""><?=$rowitem->id_reservasi?></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <span class="font-weight-bold">Nama User  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class=""><?=$rowitem->nama_user?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">Tanggal Reservasi  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class=""><?=$rowitem->tgl_reservasi?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">Wisata  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class="font-weight-bold"><?=$rowitem->judul_wisata?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">Jumlah Peserta  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class="font-weight-bold"><?=$rowitem->jumlah_peserta?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">Jumlah Donasi  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class="font-weight-bold">Rp. <?=number_format($rowitem->jumlah_donasi, 0)?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">Total  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class="font-weight-bold">Rp. <?=number_format($rowitem->total, 0)?></span>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">Keterangan  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <input type="text" name="keterangan" value="<?=$rowitem->keterangan?>" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col">
                                                <span class="font-weight-bold">No HP  </span>
                                            </div>
                                            <div class="col-lg-8  mb-2">
                                                <span class="font-weight-bold"><?=$rowitem->no_hp?></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>

                        <div class="col-lg-3  border rounded bg-white p-3 mb-2  text-center">
                            <div class="form-group">
                                <label for="file_bukti_reservasi_wisata">Bukti Reservasi Wisata</label><hr class="m-0">
                            <div class='form-group' id='buktireservasi'>
                            <!-- <div>
                                <input type='file'  class='form-control' id='image_uploads'
                                    name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                            </div> -->
                        </div>

                        <div class="form-group">
                            <img id="preview" src="#"  width="100px" alt="Preview Gambar"/>
                                <a href="<?=$rowitem->bukti_reservasi?>" data-toggle="lightbox"><img class="img-fluid" id="oldpic" src="<?=$rowitem->bukti_reservasi?>" width="50%" <?php if($rowitem->bukti_reservasi == NULL) echo " style='display:none;'"; ?>></a>
                            <br>

                            <small class="text-muted">
                                <?php if($rowitem->bukti_reservasi == NULL){
                                    echo "Bukti transfer belum diupload";
                                }else{
                                    echo "Klik gambar untuk memperbesar";
                                }

                                ?>
                            </small>

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



                    <p align="center">
                    <!-- <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p> -->
                    </form>


                    <?php } ?>
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
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</body>
</html>
