<?php include 'build/config/connection.php';
//session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

    $id_titik = $_GET['id_titik'];

    $sqlviewlokasi = 'SELECT * FROM t_lokasi
                        ORDER BY nama_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $rowlokasi = $stmt->fetchAll();

        $sqlviewwilayah = 'SELECT * FROM t_wilayah
                        ORDER BY nama_wilayah';
        $stmt = $pdo->prepare($sqlviewwilayah);
        $stmt->execute();
        $rowwilayah = $stmt->fetchAll();

        $sqlviewtitik = 'SELECT *, t_titik.latitude AS latitude_titik,
                          t_titik.longitude AS longitude_titik
                        FROM t_titik
                        LEFT JOIN t_lokasi ON t_titik.id_lokasi = t_lokasi.id_lokasi
                        LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah
                        WHERE id_titik = :id_titik';
        $stmt = $pdo->prepare($sqlviewtitik);
        $stmt->execute(['id_titik' => $id_titik]);
        $row = $stmt->fetch();

    if (isset($_POST['submit'])) {
            $id_lokasi        = $_POST['dd_id_lokasi'];
            $id_wilayah        = $_POST['dd_id_wilayah'];
            $luas_titik        = $_POST['tbluas_titik'];
            $longitude        = $_POST['tblongitude'];
            $latitude        = $_POST['tblatitude'];
            $kondisi_titik        = $_POST['rb_kondisi_titik'];
            $keterangan_titik = $_POST['tb_keterangan_titik'];

            $sqltitik = "UPDATE t_titik
                            SET id_wilayah = :id_wilayah, id_lokasi = :id_lokasi,
                            luas_titik = :luas_titik, longitude = :longitude,
                            latitude = :latitude, kondisi_titik = :kondisi_titik, keterangan_titik = :keterangan_titik
                            WHERE id_titik = :id_titik";

            $stmt = $pdo->prepare($sqltitik);
            $stmt->execute(['id_titik' => $id_titik, 'id_wilayah' => $id_wilayah, 'id_lokasi' => $id_lokasi,
            'luas_titik' => $luas_titik, 'longitude' => $longitude,
            'latitude' => $latitude, 'kondisi_titik' => $kondisi_titik, 'keterangan_titik' => $keterangan_titik]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            header("Location: kelola_titik.php?status=nochange");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_titik.php?status=addsuccess");
            }
        }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Titik - TKJB</title>
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
                        <li class="nav-item ">
                            <a href="kelola_lokasi.php" class="nav-link ">
                                <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                                <p> Kelola Lokasi </p>
                            </a>
                        </li>
                        <li class="nav-item menu-open">
                            <a href="kelola_titik.php" class="nav-link active">
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
                        <a class="btn btn-outline-primary" href="kelola_titik.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Edit Data Titik</span></h4>
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
                      <div class="form-group">
                        <label for="tb_keterangan_titik">Keterangan/Nama Titik</label>
                        <input type="text" value="<?=$row->keterangan_titik?>" name="tb_keterangan_titik" class="form-control" id="tb_keterangan_titik">
                    </div>
                        <label for="dd_id_wilayah">ID Wilayah</label>
                        <select id="dd_id_wilayah" name="dd_id_wilayah" class="form-control" onChange="loadLokasi(this.value);" required>
                            <?php foreach ($rowwilayah as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_wilayah?>" <?php if ($rowitem->id_wilayah == $row->id_wilayah) {echo " selected";} ?>>
                            ID <?=$rowitem->id_wilayah?> - <?=$rowitem->nama_wilayah?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dd_id_lokasi">ID Lokasi</label>
                        <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control" required>
                            <?php foreach ($rowlokasi as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_lokasi?>" <?php if ($rowitem->id_lokasi == $row->id_lokasi) {echo " selected";} ?>>
                            ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <label for="tblongitude">Koordinat Titik</label>
                    <div class="col-12 border rounded p-3 bg-light mb-2">
                              <div class="form-group">
                        <label for="tblongitude">Longitude</label>
                        <input type="text" name="tblongitude" value="<?=$row->longitude_titik?>" class="form-control" id="tblongitude" required>
                    </div>
                    <div class="form-group">
                        <label for="tblatitude">Latitude</label>
                        <input type="text" name="tblatitude" value="<?=$row->latitude_titik?>" class="form-control" id="tblatitude" required>
                    </div>
                    <button class="btn btn-act mb-1" onclick="getCoordinates()"><i class="nav-icon fas fa-map-marked-alt"></i> Deteksi Lokasi Anda</button><br>
                    <span class="" id="akurasi"></span><br>
                    <span class="text-muted small"> (Perlu izin browser)</span>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Luas Titik (m<sup>2</sup>)</label>
                        <input type="number" value="<?=$row->luas_titik?>" name="tbluas_titik" class="form-control" id="#">
                    </div>
                    <div class="form-group">
                        <label for="rb_status_wisata">Kondisi</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_kondisi_kurang" name="rb_kondisi_titik" value="Kurang" class="form-check-input"<?php if ($row->kondisi_titik == "Kurang"){echo " checked";} ?>>
                                <label class="form-check-label" for="rb_kondisi_kurang" style="color: #DE4C4F">
                                    Kurang (0-24%)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_kondisi_cukup" name="rb_kondisi_titik" value="Cukup" class="form-check-input"<?php if ($row->kondisi_titik == "Cukup"){echo " checked";} ?>>
                                <label class="form-check-label" for="rb_kondisi_cukup" style="color: #D8854F">
                                    Cukup (25-49%)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_kondisi_baik" name="rb_kondisi_titik" value="Baik" class="form-check-input"<?php if ($row->kondisi_titik == "Baik"){echo " checked";} ?>>
                                <label class="form-check-label" for="rb_kondisi_baik" style="color: #EEA637">
                                    Baik (50-74%)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_kondisi_sangat_baik" name="rb_kondisi_titik" value="Sangat Baik" class="form-check-input"<?php if ($row->kondisi_titik == "Sangat Baik"){echo " checked";} ?>>
                                <label class="form-check-label" for="rb_kondisi_sangat_baik" style="color: #A7A737">
                                    Sangat Baik (75-100%)
                                </label>
                            </div>
                    </div>
                        <br>
                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
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

    <script async>
    function loadLokasi(id_wilayah){
      $.ajax({
        type: "POST",
        url: "list_populate.php",
        data:{
            id_wilayah: id_wilayah,
            type: 'load_lokasi'
        },
        beforeSend: function() {
          $("#dd_id_lokasi").addClass("loader");
        },
        success: function(data){
          $("#dd_id_lokasi").html(data);
          $("#dd_id_lokasi").removeClass("loader");
        }
      });
    }

    function getCoordinates(){
      event.preventDefault()
      var options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0
};

function success(pos) {

  var crd = pos.coords;

  console.log('Your current position is:');
  document.getElementById('tblatitude').value = crd.latitude
  document.getElementById('tblongitude').value = crd.longitude
  document.getElementById('akurasi').innerHTML = `Akurasi: ${crd.accuracy} meter`
}

function error(err) {
  console.warn(`ERROR(${err.code}): ${err.message}`);
}

navigator.geolocation.getCurrentPosition(success, error, options);
    }
    </script>

</body>
</html>
