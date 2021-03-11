<?php session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=unrestrictedaccess');
}


include 'build/config/connection.php';
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

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

        $sqlviewpengelola = 'SELECT * FROM t_user
                    WHERE level_user = 3
                    ORDER BY nama_user';
        $stmt = $pdo->prepare($sqlviewpengelola);
        $stmt->execute();
        $rowpengelola = $stmt->fetchAll();

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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Akun Saya</a>
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
                    <?php print_sidebar(basename(__FILE__), $_SESSION['level_user'])?> <!-- Print sidebar -->
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
                        <label for="dd_id_wilayah">User Pengelola Lokasi</label>
                        <select id="dd_id_wilayah" name="tb_id_pengelola" class="form-control" required>
                            <?php foreach ($rowpengelola as $rowitem) {
                            ?>
                            <option <?php if($row->id_user_pengelola == $rowitem->id_user) echo ' selected ' ?> value="<?=$rowitem->id_user?>">ID <?=$rowitem->id_user?> - <?=$rowitem->nama_user?> - <?=$rowitem->organisasi_user?></option>

                            <?php } ?>
                        </select>
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



                    <br>
                    <p align="center">
                        <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
            <br><br>

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

        <script>




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


    </script>

</body>
</html>
