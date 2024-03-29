<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

if ($level_user == 2) {
    $id_wilayah = $_SESSION['id_wilayah_dikelola'];
    $extra_query = " AND t_wilayah.id_wilayah = $id_wilayah ";
    $extra_query_noand = " t_wilayah.id_wilayah = $id_wilayah ";
    $wilayah_join = " LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_donasi.id_lokasi
                    LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";
    $extra_query_k_lok = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_where = " WHERE t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_where_lok = " WHERE id_wilayah = $id_wilayah ";
} else if ($level_user == 4) {
    $extra_query = "  ";
    $extra_query_noand = "  ";
    $wilayah_join = " ";
    $extra_query_k_lok = " ";
    $extra_query_where = " ";
    $extra_query_where_lok = " ";
} else if ($level_user == 3) {
    $id_lokasi = $_SESSION['id_lokasi_dikelola'];
    $extra_query_where_lok = "LEFT JOIN t_lokasi ON t_lokasi.id_wilayah = t_wilayah.id_wilayah WHERE t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_where = " WHERE t_lokasi.id_lokasi = $id_lokasi ";
}

$sqlviewlokasi = 'SELECT * FROM t_lokasi ' . $extra_query_where . '
                        ORDER BY nama_lokasi';
$stmt = $pdo->prepare($sqlviewlokasi);
$stmt->execute();
$rowlokasi = $stmt->fetchAll();

$sqlviewwilayah = 'SELECT * FROM t_wilayah ' . $extra_query_where_lok . '
                        ORDER BY nama_wilayah';
$stmt = $pdo->prepare($sqlviewwilayah);
$stmt->execute();
$rowwilayah = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    $id_lokasi        = $_POST['dd_id_lokasi'];
    $id_wilayah        = $_POST['dd_id_wilayah'];
    $luas_titik        = $_POST['tbluas_titik'];
    $longitude        = $_POST['tblongitude'];
    $latitude        = $_POST['tblatitude'];
    $kondisi_titik        = "-";
    $keterangan_titik = $_POST['tb_keterangan_titik'];
    $id_zona_titik = $_POST['id_zona_titik'];
    $result = mysqli_query($conn, "SELECT keterangan_titik FROM t_titik WHERE keterangan_titik = '$keterangan_titik'");
    if (mysqli_fetch_assoc($result)) {
        header('location: input_titik.php?pesan=Titik_Telah_Terdaftar');
    } else {
        $sqltitik = "INSERT INTO t_titik
                            (id_lokasi, luas_titik, longitude, latitude, kondisi_titik, keterangan_titik, id_zona_titik)
                            VALUES (:id_lokasi, :luas_titik, :longitude,
                            :latitude, :kondisi_titik, :keterangan_titik, :id_zona_titik)";

        $stmt = $pdo->prepare($sqltitik);
        $stmt->execute([
            'id_lokasi' => $id_lokasi,
            'luas_titik' => $luas_titik, 'longitude' => $longitude,
            'latitude' => $latitude, 'kondisi_titik' => $kondisi_titik, 'keterangan_titik' => $keterangan_titik, 'id_zona_titik' => $id_zona_titik
        ]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            header("Location: kelola_titik.php?status=addsuccess");
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Titik - Terumbu Karang</title>
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
                    <a class="btn btn-outline-primary" href="kelola_titik.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Input Data Titik</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                    if (!empty($_GET['pesan'])) {
                        if ($_GET['pesan'] == 'Titik_Telah_Terdaftar') {
                            echo '<div class="alert alert-warning" role="alert">
                          Titik Telah Terdaftar.
                      </div>';
                        }
                    }
                    ?>
                    <form action="" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label for="tb_keterangan_titik">Keterangan/Nama Titik</label>
                            <input type="text" name="tb_keterangan_titik" class="form-control" id="tb_keterangan_titik">
                        </div>

                        <div class="form-group">
                            <label for="rb_status_wisata">Zona Titik</label><br>
                            <?php
                            $sqlviewzonatitik = 'SELECT * FROM t_zona_titik';
                            $stmt = $pdo->prepare($sqlviewzonatitik);
                            $stmt->execute();
                            $rowzona = $stmt->fetchAll();

                            foreach ($rowzona as $zona) {
                                $index = 1;
                            ?>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="idzona<?= $zona->id_zona_titik ?>" name="id_zona_titik" value="<?= $zona->id_zona_titik ?>" class="form-check-input" <?php if ($index == 1) echo 'checked required' ?>>
                                    <label class="form-check-label" for="idzona<?= $zona->id_zona_titik ?>">
                                        <?= $zona->nama_zona_titik ?>
                                    </label>
                                </div>
                            <?php $index++;
                            } ?>
                        </div>

                        <div class="form-group">
                            <label for="dd_id_wilayah">Wilayah</label>
                            <select id="dd_id_wilayah" name="dd_id_wilayah" class="form-control" onChange="loadLokasi(this.value);" required>
                                <option value="">Pilih Wilayah</option>
                                <?php foreach ($rowwilayah as $rowitem) {
                                ?>
                                    <option value="<?= $rowitem->id_wilayah ?>">ID <?= $rowitem->id_wilayah ?> - <?= $rowitem->nama_wilayah ?></option>

                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dd_id_lokasi">Lokasi</label>
                            <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control" required>
                                <option value="">Pilih Lokasi</option>
                                <?php foreach ($rowlokasi as $rowitem) {
                                ?>
                                    <option value="<?= $rowitem->id_lokasi ?>">ID <?= $rowitem->id_lokasi ?> - <?= $rowitem->nama_lokasi ?></option>

                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Luas Titik (ha)</label>
                            <input type="number" name="tbluas_titik" class="form-control number-input" id="#" required>
                        </div>
                        <label for="tblongitude">Koordinat Titik</label>
                        <div class="col-12 border rounded p-3 bg-light mb-2">
                            <div class="form-group">
                                <label for="tblatitude">Latitude</label>
                                <input type="text" name="tblatitude" class="form-control number-input" id="tblatitude" required>
                            </div>
                            <div class="form-group">
                                <label for="tblongitude">Longitude</label>
                                <input type="text" name="tblongitude" class="form-control number-input" id="tblongitude" required>
                            </div>
                            <button class="btn btn-act mb-1" onclick="getCoordinates()"><i class="nav-icon fas fa-map-marked-alt"></i> Deteksi Lokasi Anda</button><br>
                            <span class="" id="akurasi"></span><br>
                            <span class="text-muted small"> (Perlu izin browser)</span>
                        </div>


                        <div class="form-group d-none">
                            <label for="rb_status_wisata">Kondisi</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_kondisi_kurang" name="rb_kondisi_titik" value="Kurang" class="form-check-input">
                                <label class="form-check-label" for="rb_kondisi_kurang" style="color: #DE4C4F">
                                    Kurang (0-24%)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input checked type="radio" id="rb_kondisi_cukup" name="rb_kondisi_titik" value="Cukup" class="form-check-input">
                                <label class="form-check-label" for="rb_kondisi_cukup" style="color: #D8854F">
                                    Cukup (25-49%)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_kondisi_baik" name="rb_kondisi_titik" value="Baik" class="form-check-input">
                                <label class="form-check-label" for="rb_kondisi_baik" style="color: #EEA637">
                                    Baik (50-74%)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_kondisi_sangat_baik" name="rb_kondisi_titik" value="Sangat Baik" class="form-check-input">
                                <label class="form-check-label" for="rb_kondisi_sangat_baik" style="color: #A7A737">
                                    Sangat Baik (75-100%)
                                </label>
                            </div>
                        </div>
                        <br>
                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
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

    <script async>
        function loadLokasi(id_wilayah) {
            $.ajax({
                type: "POST",
                url: "list_populate.php",
                data: {
                    id_wilayah: id_wilayah,
                    type: 'load_lokasi'
                },
                beforeSend: function() {
                    $("#dd_id_lokasi").addClass("loader");
                },
                success: function(data) {
                    $("#dd_id_lokasi").html(data);
                    $("#dd_id_lokasi").removeClass("loader");
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