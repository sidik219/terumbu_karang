<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_paket_wisata = $_GET['id_paket_wisata'];
$defaultpic = "images/image_default.jpg";

// Lokasi
$sqlviewlokasi = 'SELECT * FROM t_lokasi
                            ORDER BY id_lokasi ASC';
$stmt = $pdo->prepare($sqlviewlokasi);
$stmt->execute();
$rowlokasi = $stmt->fetchAll();

// Asuransi
$sqlviewasuransi = 'SELECT * FROM t_asuransi
                            ORDER BY id_asuransi ASC';
$stmt = $pdo->prepare($sqlviewasuransi);
$stmt->execute();
$rowasuransi = $stmt->fetchAll();

// Wisata
$sqlviewwisata = 'SELECT * FROM t_wisata
                LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                ORDER BY id_wisata ASC';
$stmt = $pdo->prepare($sqlviewwisata);
$stmt->execute(['id_paket_wisata' => $id_paket_wisata]);
$rowwisata = $stmt->fetchAll();
// var_dump($rowwisata);
// die;

// Paket Wisata
$sqleditpaket = 'SELECT * FROM tb_paket_wisata
                        WHERE id_paket_wisata = :id_paket_wisata';

$stmt = $pdo->prepare($sqleditpaket);
$stmt->execute(['id_paket_wisata' => $id_paket_wisata]);
$rowpaket = $stmt->fetch();

// Jarak
// 
// Jarak
if (isset($_POST['submit'])) {

    $id_lokasi                  = $_POST['id_lokasi'];
    $id_asuransi                = $_POST['id_asuransi'];
    $nama_paket_wisata          = $_POST['nama_paket_wisata'];
    $tgl_pemesanan              = $_POST['tgl_pemesanan'];
    $tgl_akhir_pemesanan        = $_POST['tgl_akhir_pemesanan'];
    $status_aktif               = $_POST['status_aktif'];

    $id_wisata = $_POST["nama_wisata"];
    $judul_wisata = $_POST["judul_wisata"];
    $jadwal_wisata = $_POST["jadwal_wisata"];
    $deskripsi_wisata = $_POST["deskripsi_wisata"];

    $hitung = count($id_wisata);
    for ($x = 0; $x < $hitung; $x++) {
        // echo $id_wisata[$x];
        // echo $judul_wisata[$x];
        // echo $deskripsi_wisata[$x];
        $sqlupdatewisata = "UPDATE t_wisata
        SET id_paket_wisata = :id_paket_wisata,
            judul_wisata = :judul_wisata,
            jadwal_wisata = :jadwal_wisata,
            deskripsi_wisata = :deskripsi_wisata
        WHERE id_wisata = :id_wisata";

        $stmt = $pdo->prepare($sqlupdatewisata);
        $stmt->execute([
            'id_wisata' => $id_wisata[$x],
            'judul_wisata' => $judul_wisata[$x],
            'jadwal_wisata' => $jadwal_wisata[$x],
            'deskripsi_wisata' => $deskripsi_wisata[$x],
            'id_paket_wisata' => $id_paket_wisata
        ]);
    }


    $randomstring = substr(md5(rand()), 0, 7);

    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
        $foto_wisata = $rowpaket->foto_wisata;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($rowpaket->foto_wisata == $defaultpic) || (!$rowpaket->foto_wisata)) {
            $target_dir  = "images/foto_paket_wisata/";
            $foto_wisata = $target_dir . 'WIS_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wisata);
            $pic = "&new=";
        } else if (isset($rowpaket->foto_wisata)) {
            $foto_wisata = $rowpaket->foto_wisata;
            unlink($rowpaket->foto_wisata);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $rowpaket->foto_wisata);
            $pic = "&replace=";
        }
    }
    //---image upload end

    $sqlpaket = "UPDATE tb_paket_wisata
                            SET id_lokasi = :id_lokasi,
                                id_asuransi = :id_asuransi,
                                nama_paket_wisata = :nama_paket_wisata,
                                tgl_pemesanan = :tgl_pemesanan,
                                tgl_akhir_pemesanan = :tgl_akhir_pemesanan,
                                foto_wisata = :foto_wisata,
                                status_aktif = :status_aktif
                            WHERE id_paket_wisata = :id_paket_wisata";

    $stmt = $pdo->prepare($sqlpaket);
    $stmt->execute([
        'id_lokasi' => $id_lokasi,
        'id_asuransi' => $id_asuransi,
        'nama_paket_wisata' => $nama_paket_wisata,
        'tgl_pemesanan' => $tgl_pemesanan,
        'tgl_akhir_pemesanan' => $tgl_akhir_pemesanan,
        'foto_wisata' => $foto_wisata,
        'status_aktif' => $status_aktif,
        'id_paket_wisata' => $id_paket_wisata
    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: kelola_wisata.php?status=insertfailed");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_wisata.php?status=updatesuccess");
        // $last_paket_wisata_id = $pdo->lastInsertId();
    }

    //var_dump($_POST['nama_wisata']);exit();
    // $i = 0;
    // foreach ($_POST['nama_wisata'] as $nama_wisata) {
    //     $id_paket_wisata   = $last_paket_wisata_id; //tb_paket_wisata
    //     $id_wisata         = $_POST['nama_wisata'][$i]; //t_wisata
    //     $judul_wisata      = $_POST['judul_wisata'][$i]; //t_wisata
    //     $jadwal_wisata     = $_POST['jadwal_wisata'][$i]; //t_wisata
    //     $deskripsi_wisata  = $_POST['deskripsi_wisata'][$i]; //t_wisata

    //     //Update dan set id_paket_wisata ke wisata pilihan
    $sqlupdatewisata = "UPDATE t_wisata
                            SET id_paket_wisata = :id_paket_wisata,
                                judul_wisata = :judul_wisata,
                                jadwal_wisata = :jadwal_wisata,
                                deskripsi_wisata = :deskripsi_wisata
                            WHERE id_wisata = :id_wisata";

    $stmt = $pdo->prepare($sqlupdatewisata);
    $stmt->execute([
        'id_wisata' => $id_wisata,
        'judul_wisata' => $judul_wisata,
        'jadwal_wisata' => $jadwal_wisata,
        'deskripsi_wisata' => $deskripsi_wisata,
        'id_paket_wisata' => $id_paket_wisata
    ]);

    //     $affectedrows = $stmt->rowCount();
    //     if ($affectedrows == '0') {
    //         header("Location: kelola_wisata.php?status=insertfailed");
    //     } else {
    //         //echo "HAHAHAAHA GREAT SUCCESSS !";
    //         header("Location: kelola_wisata.php?status=updatesuccess");
    //     }
    //     $i++;
    // } //End Foreach
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Wisata - GoKarang</title>
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
                    <a class="btn btn-outline-primary" href="kelola_wisata.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Edit Data Paket Wisata</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">

                        <!-- Lokasi -->
                        <div class="form-group">
                            <label for="id_lokasi">ID Lokasi</label>
                            <select id="id_lokasi" name="id_lokasi" class="form-control" required>
                                <option value="" disabled>Pilih Lokasi</option>
                                <?php foreach ($rowlokasi as $lokasi) {  ?>
                                    <option <?php if ($lokasi->id_lokasi == $rowpaket->id_lokasi) echo 'selected'; ?> value="<?= $lokasi->id_lokasi ?>">ID <?= $lokasi->id_lokasi ?> - <?= $lokasi->nama_lokasi ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Asuransi -->
                        <div class="form-group">
                            <label for="id_asuransi">ID Asuransi</label>
                            <select id="id_asuransi" name="id_asuransi" class="form-control" required>
                                <option value="" disabled>Pilih Asuransi</option>
                                <?php foreach ($rowasuransi as $asuransi) {  ?>
                                    <option <?php if ($asuransi->id_asuransi == $rowpaket->id_asuransi) echo 'selected'; ?> value="<?= $asuransi->id_asuransi ?>">ID <?= $asuransi->id_asuransi ?> - <?= $asuransi->biaya_asuransi ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Wisata -->
                        <div class="form-group field_wrapper">
                            <label for="nama_wisata">ID Wisata</label><br>
                            <?php foreach ($rowwisata as $wisata) : ?>
                                <div class="form-group fieldGroup">
                                    <div class="flex-column">
                                        <!-- Id Wisata -->
                                        <input type="hidden" name="nama_wisata[]" value="<?= $wisata->id_wisata ?>" class="form-control" placeholder="Hari" required />
                                        <!-- Judul Wisata -->
                                        <input type="text" name="judul_wisata[]" value="<?= $wisata->judul_wisata ?>" class="form-control mb-2" placeholder="Hari" required />
                                        <!-- Jadwal Wisata -->
                                        <input type="text" name="jadwal_wisata[]" value="<?= $wisata->jadwal_wisata ?>" class="form-control mb-2" placeholder="Hari" required />
                                        <!-- Deskripsi Wisata -->
                                        <input type="text" name="deskripsi_wisata[]" value="<?= $wisata->deskripsi_wisata ?>" class="form-control" placeholder="Hari" required />
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-4">
                            <label for="">Keterangan:</label><br>
                            <small><b>Contoh Pengisian:</b></small><br>
                            <small>* Pilih Wisata: Wisata Diving dst</small><br>
                            <small>* Hari: Hari Pertama dst</small><br>
                            <small style="color: red;">* Hanya bisa satu wisata, untuk perhari</small><br>
                            <small style="color: red;">* Untuk menambahkan wisata baru, harus <a href="input_pengadaan_fasilitas.php"><b>input fasilitas</b></a> terlebih dahulu</small><br>
                            <small style="color: red;">* Belum Bisa menambahkan wisata baru, pada saat edit wisata</small>
                        </div>

                        <div class="form-group">
                            <label for="nama_paket_wisata">Nama Paket Wisata</label>
                            <input type="text" id="nama_paket_wisata" name="nama_paket_wisata" value="<?= $rowpaket->nama_paket_wisata ?>" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="tgl_pemesanan">Batas Pemesanan</label>
                            <div class="d-flex flex-row bd-highlight mb-3">
                                <div class="p-2 bd-highlight">
                                    <label for="tgl_pemesanan">Tanggal Awal</label>

                                    <input type="date" id="tgl_pemesanan" name="tgl_pemesanan" value="<?= $rowpaket->tgl_pemesanan ?>" class="form-control" required>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <label for="tgl_pemesanan">Tanggal Akhir</label>
                                    <input type="date" id="tgl_akhir_pemesanan" name="tgl_akhir_pemesanan" value="<?= $rowpaket->tgl_akhir_pemesanan ?>" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class='form-group' id='fotowilayah'>
                            <div>
                                <label for='image_uploads'>Upload Foto Wisata</label>
                                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                            </div>
                        </div>

                        <div class="form-group">
                            <img id="preview" src="#" width="100px" alt="Preview Gambar" />
                            <a href="<?= $rowpaket->foto_wisata ?>" data-toggle="lightbox">
                                <img class="img-fluid" id="oldpic" src="<?= $rowpaket->foto_wisata ?>" width="20%" <?php if ($rowpaket->foto_wisata == NULL) echo "style='display: none;'"; ?>></a>
                            <br>

                            <small class="text-muted">
                                <?php if ($rowpaket->foto_wisata == NULL) {
                                    echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                                } else {
                                    echo "Klik gambar untuk memperbesar";
                                }

                                ?>
                            </small>

                            <script>
                                const actualBtn = document.getElementById('image_uploads');
                                const fileChosen = document.getElementById('file-input-label');

                                actualBtn.addEventListener('change', function() {
                                    fileChosen.innerHTML = '<b>File dipilih :</b> ' + this.files[0].name
                                })
                                window.onload = function() {
                                    document.getElementById('preview').style.display = 'none';
                                };

                                function readURL(input) {
                                    //Validasi Size Upload Image
                                    var uploadField = document.getElementById("image_uploads");

                                    uploadField.onchange = function() {
                                        if (this.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                            alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                            this.value = "";
                                        };
                                    };

                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();
                                        document.getElementById('oldpic').style.display = 'none';
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

                        <!-- Alternatif dah biar cpt wkwk :V -->
                        <div class="form-group">
                            <label for="status_aktif">Status</label><br>
                            <?php if ($rowpaket->status_aktif == "Aktif") { ?>
                                <div class="form-check form-check-inline">
                                    <input checked type="radio" id="status_aktif" name="status_aktif" value="<?= $rowpaket->status_aktif ?>" class="form-check-input">
                                    <label class="form-check-label" for="status_aktif" style="color: green">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_tidak_aktif" name="status_aktif" value="Tidak Aktif" class="form-check-input">
                                    <label class="form-check-label" for="status_tidak_aktif" style="color: gray">
                                        Tidak Aktif
                                    </label>
                                </div>
                            <?php } elseif ($rowpaket->status_aktif == "Tidak Aktif") { ?>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_aktif" name="status_aktif" value="Aktif" class="form-check-input">
                                    <label class="form-check-label" for="status_aktif" style="color: green">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input checked type="radio" id="status_tidak_aktif" name="status_aktif" value="<?= $rowpaket->status_aktif ?>" class="form-check-input">
                                    <label class="form-check-label" for="status_tidak_aktif" style="color: gray">
                                        Tidak Aktif
                                    </label>
                                </div>
                            <?php } ?>
                        </div>

                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                        </p>
                    </form><br><br>

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
    <div>
        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- overlayScrollbars -->
        <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.js"></script>

        <!-- Pembatasan Date Pemesanan -->
        <script>
            var today = new Date().toISOString().split('T')[0];
            document.getElementsByName("tgl_pemesanan")[0].setAttribute('min', today);
        </script>
        <script>
            var today = new Date().toISOString().split('T')[0];
            document.getElementsByName("tgl_akhir_pemesanan")[0].setAttribute('min', today);
        </script>

    </div>

</body>

</html>