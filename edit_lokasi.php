<?php include 'build/config/connection.php';
//session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}

    $id_lokasi = $_GET['id_lokasi'];
    $defaultpic = "images/image_default.jpg";

    $sqlviewlokasi = 'SELECT * FROM t_lokasi
    LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah WHERE id_lokasi = :id_lokasi';
    $stmt = $pdo->prepare($sqlviewlokasi);
    $stmt->execute(['id_lokasi' => $id_lokasi]);
    $row = $stmt->fetch();

    $sqlviewwilayah = 'SELECT * FROM t_wilayah
                        ORDER BY nama_wilayah';
        $stmt = $pdo->prepare($sqlviewwilayah);
        $stmt->execute();
        $row2 = $stmt->fetchAll();

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
                $foto_lokasi = $row->foto_lokasi;
            }
            else if (isset($_FILES['image_uploads'])) {
                if (($row->foto_lokasi == $defaultpic)  || (!$rowitem->foto_lokasi)){
                    $target_dir  = "images/foto_lokasi/";
                    $foto_lokasi = $target_dir .'LOK_'.$randomstring. '.jpg';
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_lokasi);
                }
                else{
                    $foto_lokasi = $row->foto_lokasi;
                    unlink($row->foto_lokasi);
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $row->foto_lokasi);
                }
            }

            //---image upload end

            $sqllokasi = "UPDATE t_lokasi
                        SET id_wilayah = :id_wilayah, nama_lokasi=:nama_lokasi, deskripsi_lokasi=:deskripsi_lokasi, foto_lokasi = :foto_lokasi,
                        luas_lokasi=:luas_lokasi, id_user_pengelola=:id_user_pengelola,
                        kontak_lokasi=:kontak_lokasi, nama_bank=:nama_bank, nama_rekening=:nama_rekening, nomor_rekening=:nomor_rekening, longitude=:longitude, latitude=:latitude

                        WHERE id_lokasi = :id_lokasi";

            $stmt = $pdo->prepare($sqllokasi);
            $stmt->execute(['id_wilayah' => $id_wilayah, 'nama_lokasi' => $nama_lokasi,
            'deskripsi_lokasi' => $deskripsi_lokasi,
            'luas_lokasi' => $luas_lokasi, 'id_user_pengelola' => $id_user_pengelola,
            'kontak_lokasi' => $kontak_lokasi,'nama_bank' => $nama_bank,
            'nama_rekening' => $nama_rekening,'nomor_rekening' => $nomor_rekening,'id_lokasi' => $id_lokasi,
            'foto_lokasi' => $foto_lokasi, 'longitude' => $longitude, 'latitude' => $latitude]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: kelola_lokasi.php?status=nochange");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_lokasi.php?status=updatesuccess");
                }
            }
        }
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
                        <li class="nav-item menu-open">
                            <a href="kelola_lokasi.php" class="nav-link active">
                                <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                                <p> Kelola Lokasi </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_titik.php" class="nav-link ">
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
                        <a class="btn btn-outline-primary" href="kelola_lokasi.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Edit Data Lokasi</span></h4>
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
                            <?php foreach ($row2 as $rowitem2) {
                            ?>
                            <option value="<?=$rowitem2->id_wilayah?>" <?php if ($rowitem2->id_wilayah == $row->id_wilayah) {echo " selected";} ?>>
                            ID <?=$rowitem2->id_wilayah?> - <?=$rowitem2->nama_wilayah?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tb_nama_lokasi">Nama Lokasi</label>
                        <input type="text" value="<?=$row->nama_lokasi?>" id="tb_nama_lokasi" name="tb_nama_lokasi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="num_luas_lokasi">Estimasi Luas Titik Total (m<sup>2</sup>)</label>
                        <input type="number" value="<?=$row->luas_lokasi?>"  id="num_luas_lokasi" name="num_luas_lokasi" class="form-control">
                    </div>
                    <div class='form-group' id='fotowilayah'>
                        <div>
                            <label for='image_uploads'>Upload Foto Lokasi</label>
                            <input type='file'  class='form-control' id='image_uploads'
                                name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                        </div>
                    </div>
                    <div class="form-group">
                        <img id="preview" src="#"  width="100px" alt="Preview Gambar"/>
                        <img id="oldpic" src="<?=$row->foto_lokasi?>" width="100px">
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
                        <label for="tb_deskripsi_lokasi">Deskripsi</label>
                        <input type="text" value="<?=$row->deskripsi_lokasi?>"  id="tb_deskripsi_lokasi" name="tb_deskripsi_lokasi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tb_id_pengelola">ID User Pengelola</label>
                        <input type="text" value="<?=$row->id_user_pengelola?>"  id="tb_id_pengelola" name="tb_id_pengelola" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="num_kontak_lokasi">Kontak Lokasi</label>
                        <input type="number" value="<?=$row->kontak_lokasi?>"  id="num_kontak_lokasi" name="num_kontak_lokasi" class="form-control number-input">
                    </div>
                    <div class="form-group">
                        <label for="tb_nama_bank">Nama Bank</label>
                        <input type="text" value="<?=$row->nama_bank?>"  id="tb_nama_bank" name="tb_nama_bank" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tb_nama_rekening">Nama Rekening</label>
                        <input type="text" value="<?=$row->nama_rekening?>"  id="tb_nama_rekening" name="tb_nama_rekening" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="num_nomor_rekening">Nomor Rekening</label>
                        <input type="number" value="<?=$row->nomor_rekening?>"  id="num_nomor_rekening" name="num_nomor_rekening" class="form-control number-input">
                    </div>
                    <label for="tblongitude">Koordinat Lokasi (Diperlukan agar lokasi muncul di peta)</label>
                    <div class="col-12 border rounded p-3 bg-light mb-2">
                              <div class="form-group">
                                <label for="tblatitude">Latitude</label>
                        <input type="text" value="<?=$row->latitude?>" name="tb_latitude" class="form-control number-input" id="tblatitude" required>

                    </div>
                    <div class="form-group">
                        <label for="tblongitude">Longitude</label>
                        <input type="text" value="<?=$row->longitude?>" name="tb_longitude" class="form-control number-input" id="tblongitude" required>
                    </div>
                    <button class="btn btn-act mb-1" onclick="getCoordinates()"><i class="nav-icon fas fa-map-marked-alt"></i> Deteksi Lokasi Anda</button><br>
                    <span class="" id="akurasi"></span><br>
                    <span class="text-muted small"> (Perlu izin browser)</span>
                    </div>

                    <div class="terumbu-karang mt-3 form-group">
                      <label for="num_nomor_rekening">Terumbu yang Dapat Ditanam</label>
                      <div class="col border rounded p-2 bg-light">
                            <div class="row">
                              <div class="col-sm">
                                <label>Jenis</label>
                                <select id="dd_id_wilayah" name="dd_id_jenis" class="form-control" onchange="loadTk(this.value);">
                                <option value="">-Pilih Jenis-</option>
                            <?php
                            $sqlviewjenis = 'SELECT * FROM t_jenis_terumbu_karang';
                            $stmt = $pdo->prepare($sqlviewjenis);
                            $stmt->execute();
                            $rowjenis = $stmt->fetchAll();
                            foreach ($rowjenis as $jenis) {
                            ?>
                            <option value="<?=$jenis->id_jenis?>">
                            ID <?=$jenis->id_jenis?> - <?=$jenis->nama_jenis?></option>

                            <?php } ?>
                        </select>
                              </div>
                              <div class="col-sm">
                                <label>Sub-jenis</label>
                                <select id="dd_id_jenis" name="dd_id_tk" class="form-control">
                                  <option value="">-Pilih Sub-jenis-</option>

                              </select>
                              </div>
                              <div class="col">

                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                               <label for="num_biaya_pergantian">Harga Patokan</label>
                        <input type="hidden" id="biaya_pergantian_number" name="biaya_pergantian_number" value="">
                        <div class="row">
                          <div class="col-auto text-center p-2">
                            Rp.
                          </div>
                          <div class="col">
                            <input onkeyup="formatNumber(this)" type="text" id="num_biaya_pergantian" min="1" name="num_biaya_pergantian" class="form-control number-input" required>
                          </div>
                              </div>
                              <div class="col">
                                <span onclick="addDocInput()" class="btn btn-blue btn-sm btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambahkan</span>
                              </div>
                            </div>
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

        <script>
function loadTk(id_jenis){
      $.ajax({
        type: "POST",
        url: "list_populate.php",
        data:{
            id_jenis: id_jenis,
            type: 'load_tk'
        },
        beforeSend: function() {
          $("#dd_id_jenis").addClass("loader");
        },
        success: function(data){
          $("#dd_id_jenis").html(data);
          $("#dd_id_jenis").removeClass("loader");
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
  console.log(`Latitude : ${crd.latitude}`);
  document.getElementById('tblatitude').value = crd.latitude
  console.log(`Longitude: ${crd.longitude}`);
  document.getElementById('tblongitude').value = crd.longitude
  console.log();
  document.getElementById('akurasi').innerHTML = `Akurasi: ${crd.accuracy} meter`
}

function error(err) {
  console.warn(`ERROR(${err.code}): ${err.message}`);
}

navigator.geolocation.getCurrentPosition(success, error, options);
    }

    var formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0

    // These options are needed to round to whole numbers if that's what you want.
    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});

function formatNumber(e){
  var formattedNumber = parseInt(e.value.replace(/\,/g,''))
  $('#biaya_pergantian_number').val(formattedNumber)
  $('#num_biaya_pergantian').val(formatter.format(formattedNumber))
}
    </script>

</body>
</html>
