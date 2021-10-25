<?php
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
include 'build/config/connection.php';

$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

if ($level_user == 2) {
    $id_wilayah = $_SESSION['id_wilayah_dikelola'];

    $sqlviewwilayah = 'SELECT * FROM t_wilayah
                    WHERE id_wilayah = :id_wilayah
                    ORDER BY nama_wilayah';
    $stmt = $pdo->prepare($sqlviewwilayah);
    $stmt->execute(['id_wilayah' => $id_wilayah]);
    $row = $stmt->fetchAll();
} else if ($level_user == 4) {
    $sqlviewwilayah = 'SELECT * FROM t_wilayah
                    ORDER BY nama_wilayah';
    $stmt = $pdo->prepare($sqlviewwilayah);
    $stmt->execute();
    $row = $stmt->fetchAll();
}


if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan' || $_POST['submit'] == 'SimpanLanjut') {
        $id_wilayah = $_POST['dd_id_wilayah'];
        $nama_lokasi        = $_POST['tb_nama_lokasi'];
        $luas_lokasi        = $_POST['num_luas_lokasi'];
        $deskripsi_lokasi     = $_POST['tb_deskripsi_lokasi'];
        $id_user_pengelola     = 1;
        $kontak_lokasi     = $_POST['num_kontak_lokasi'];
        $nama_bank     = $_POST['tb_nama_bank'];
        $nama_rekening     = $_POST['tb_nama_rekening'];
        $nomor_rekening     = $_POST['num_nomor_rekening'];
        $longitude        = $_POST['tb_longitude'];
        $latitude        = $_POST['tb_latitude'];
        $batas_hari_pembayaran = $_POST['num_batas_hari_pembayaran'];
        $randomstring = substr(md5(rand()), 0, 7);
        $kapasitas_kapal = $_POST['kapasitas_kapal'];
        $kode_lokasi = $_POST['kode_lokasi'];
        $randomstring1 = substr(md5(rand()), 0, 7);
        // echo '<script> alert('.$kapasitas_kapal.');</script>';

        //Image upload
        if ($_FILES["image_uploads"]["size"] == 0) {
            $foto_lokasi = "images/image_default.jpg";
        } else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_lokasi/";
            $foto_lokasi = $target_dir . 'LOK_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_lokasi);
        }

        //---image upload end

        //Image upload TTD Digital
        if ($_FILES["image_uploads1"]["size"] == 0) {
            $ttd_digital = "images/image_default.jpg";
        } else if (isset($_FILES['image_uploads1'])) {
            $target_dir  = "images/ttd_digital/";
            $ttd_digital = $target_dir . 'TTD_' . $randomstring1 . '.png';
            move_uploaded_file($_FILES["image_uploads1"]["tmp_name"], $ttd_digital);
        }
        //---image upload end

        $sqllokasi = "INSERT INTO t_lokasi
                            (id_wilayah, nama_lokasi, deskripsi_lokasi, foto_lokasi, luas_lokasi, id_user_pengelola, kapasitas_kapal,
                            kontak_lokasi, nama_bank, nama_rekening, nomor_rekening, longitude, latitude, batas_hari_pembayaran, kode_lokasi, ttd_digital)
                            VALUES (:id_wilayah, :nama_lokasi, :deskripsi_lokasi, :foto_lokasi, :luas_lokasi,
                            :id_user_pengelola, :kapasitas_kapal, :kontak_lokasi, :nama_bank, :nama_rekening, :nomor_rekening, :longitude, :latitude, :batas_hari_pembayaran, :kode_lokasi, :ttd_digital)";

        $stmt = $pdo->prepare($sqllokasi);
        $stmt->execute([
            'id_wilayah' => $id_wilayah, 'nama_lokasi' => $nama_lokasi,
            'deskripsi_lokasi' => $deskripsi_lokasi, 'foto_lokasi' => $foto_lokasi,
            'luas_lokasi' => $luas_lokasi, 'id_user_pengelola' => $id_user_pengelola,
            'kontak_lokasi' => $kontak_lokasi, 'nama_bank' => $nama_bank, 'kapasitas_kapal' => $kapasitas_kapal,
            'nama_rekening' => $nama_rekening, 'nomor_rekening' => $nomor_rekening, 'longitude' => $longitude, 'latitude' => $latitude,
            'batas_hari_pembayaran' => $batas_hari_pembayaran, 'kode_lokasi' => $kode_lokasi, 'ttd_digital' => $ttd_digital
        ]);


        $last_lokasi_id = $pdo->lastInsertId();

        $sqltitik = "INSERT INTO t_titik
                            (id_lokasi, luas_titik, longitude, latitude, kondisi_titik, keterangan_titik, id_zona_titik)
                            VALUES ($last_lokasi_id, 0, 0,
                            0, 'Cukup', '-', 5)";
        $stmt = $pdo->prepare($sqltitik);
        $stmt->execute();

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            if ($_POST['submit'] == 'SimpanLanjut') {
                header("Location: atur_pengelola_lokasi.php?id_lokasi=$last_lokasi_id");
                return 1;
            }
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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Favicon -->
    <?= $favicon ?>
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
                <?= $logo_website ?>
            </a>
            <!-- END OF TOP SIDEBAR -->

            <!-- SIDEBAR -->
            <div class="sidebar">
                <!-- SIDEBAR MENU -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <?php print_sidebar(basename(__FILE__), $_SESSION['level_user']) ?>
                        <!-- Print sidebar -->
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
                    <a class="btn btn-outline-primary" href="kelola_lokasi.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Input Data Lokasi</span></h4>
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
                            <select id="dd_id_wilayah" name="dd_id_wilayah" class="form-control" onChange="loadKodeLokasi(this.value);">
                                <option value="">-- Pilih Wilayah --</option>
                                <?php foreach ($row as $rowitem) {
                                ?>
                                    <option value="<?= $rowitem->id_wilayah ?>">ID <?= $rowitem->id_wilayah ?> - <?= $rowitem->nama_wilayah ?></option>

                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tb_nama_lokasi">Nama Lokasi</label>
                            <input type="text" id="tb_nama_lokasi" name="tb_nama_lokasi" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="dd_kode_lokasi">Kode Lokasi</label>
                            <select id="dd_kode_lokasi" name="kode_lokasi" class="form-control" required>
                                <option value="">-- Pilih Wilayah Dahulu --</option>
                                <?php foreach ($rowkodelokasi as $kodelokasi) {
                                ?>
                                    <option value="<?= $kodelokasi->kode_lokasi ?>"> <?= $kodelokasi->kode_lokasi ?> - <?= $kodelokasi->nama_lokasi ?></option>

                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kapasitas_kapal">Kapasitas Kapal</label>
                            <label class="text-muted text-sm d-block">Jumlah bibit yang dapat diangkut kapal dalam satu perjalanan</label>
                            <input type="number" min="50" id="kapasitas_kapal" name="kapasitas_kapal" class="form-control" placeholder="Minimal 50 Bibit Per-kapal" required>
                        </div>

                        <div class="form-group">
                            <label for="num_luas_lokasi">Estimasi Luas Titik Total (ha)</label>
                            <input type="number" id="num_luas_lokasi" name="num_luas_lokasi" class="form-control" required>
                        </div>
                        <div class='form-group' id='fotowilayah'>
                            <div>
                                <label for='image_uploads'>Upload Foto Lokasi</label>
                                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <img id="preview" width="100px" src="#" alt="Preview Gambar" />

                            <script>
                                window.onload = function() {
                                    document.getElementById('preview').style.display = 'none';
                                };

                                function readURL(input) {
                                    if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                        alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                        input.value = "";
                                    };
                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();

                                        reader.onload = function(e) {
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
                        <div class='form-group'>
                            <div>
                                <label for='image_uploads1'>Upload Foto Tanda Tangan Digital</label>
                                <input type='file' class='form-control' id='image_uploads1' name='image_uploads1' accept='.jpg, .jpeg, .png' onchange="readURL1(this);">
                            </div>
                        </div>

                        <div class="form-group">
                            <img id="preview1" width="100px" src="#" alt="Preview Gambar" />

                            <script>
                                window.onload = function() {
                                    document.getElementById('preview1').style.display = 'none';
                                };

                                function readURL1(input) {
                                    //Validasi Size Upload Image
                                    var uploadField = document.getElementById("image_uploads1");

                                    // uploadField.onchange = function() {
                                    //     if (this.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                    //         alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                    //         this.value = "";
                                    //     };
                                    // };
                                    if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                        alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                        input.value = "";
                                    };
                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();

                                        reader.onload = function(e) {
                                            $('#preview1')
                                                .attr('src', e.target.result)
                                                .width(200);
                                            document.getElementById('preview1').style.display = 'block';
                                        };

                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="tb_deskripsi_lokasi">Deskripsi</label>
                            <input type="text" id="tb_deskripsi_lokasi" name="tb_deskripsi_lokasi" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="num_kontak_lokasi">Kontak Lokasi</label>
                            <input type="number" id="num_kontak_lokasi" name="num_kontak_lokasi" class="form-control number-input" required>
                        </div>
                        <div class="form-group">
                            <label for="tb_nama_bank">Nama Bank</label>
                            <input type="text" id="tb_nama_bank" name="tb_nama_bank" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tb_nama_rekening">Nama Rekening</label>
                            <input type="text" id="tb_nama_rekening" name="tb_nama_rekening" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="num_nomor_rekening">Nomor Rekening</label>
                            <input type="number" id="num_nomor_rekening" name="num_nomor_rekening" class="form-control number-input" required>
                        </div>
                        <label for="tblongitude">Koordinat Lokasi (Diperlukan agar lokasi muncul di peta)</label>
                        <div class="col-12 border rounded p-3 bg-light mb-2">
                            <div class="form-group">
                                <label for="tblatitude">Latitude</label>
                                <input type="text" name="tb_latitude" class="form-control number-input number-input" id="tblatitude" required>
                            </div>
                            <div class="form-group">
                                <label for="tblongitude">Longitude</label>
                                <input type="text" name="tb_longitude" class="form-control number-input number-input" id="tblongitude" required>
                            </div>
                            <button class="btn btn-act mb-1" onclick="getCoordinates()"><i class="nav-icon fas fa-map-marked-alt"></i> Deteksi Lokasi Anda</button><br>
                            <span class="" id="akurasi"></span><br>
                            <span class="text-muted small"> (Perlu izin browser)</span>
                        </div>
                        <div class="form-group">
                            <label for="num_batas_hari_pembayaran">Batas Pembayaran Donasi (Hari)</label>
                            <input type="number" value="3" id="num_batas_hari_pembayaran" name="num_batas_hari_pembayaran" class="form-control number-input">
                        </div>
                        <br>
                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit mb-3">Simpan</button>
                            <br>
                            <button type="submit" name="submit" value="SimpanLanjut" class="btn btn-blue">
                                <i class="icon fas fa-chevron-right"></i><i class="icon fas fa-chevron-right"></i> Simpan & Lanjut Pilih Calon Pengelola Lokasi</button>
                        </p>
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
        <?= $footer ?>
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
        function loadKodeLokasi(id_wilayah) {
            $.ajax({
                type: "POST",
                url: "list_populate.php",
                data: {
                    id_wilayah: id_wilayah,
                    type: 'load_kode_lokasi'
                },
                beforeSend: function() {
                    $("#dd_kode_lokasi").addClass("loader");
                },
                success: function(data) {
                    $("#dd_kode_lokasi").html(data);
                    $("#dd_kode_lokasi").removeClass("loader");
                }
            });
        }



        function getCoordinates() {
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