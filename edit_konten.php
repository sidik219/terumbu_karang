<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_konten_wilayah = $_GET['id_konten_wilayah'];
$defaultpic = "images/image_default.jpg";

// Asuransi
// $sqlviewkonten = 'SELECT * FROM t_konten_wilayah
//                     ORDER BY id_konten_wilayah ASC';
// $stmt = $pdo->prepare($sqlviewkonten);
// $stmt->execute();
// $rowKonten = $stmt->fetchAll();

$sqleditkonten = 'SELECT * FROM t_konten_wilayah
                    WHERE id_konten_wilayah = :id_konten_wilayah';

$stmt = $pdo->prepare($sqleditkonten);
$stmt->execute(['id_konten_wilayah' => $id_konten_wilayah]);
$konten = $stmt->fetch();
// var_dump($konten);
// die;

// Jarak
// 
// Jarak
if (isset($_POST['submit'])) {
    $judul_konten_wilayah       = $_POST['judul_konten_wilayah'];
    $deskripsi_konten_wilayah   = $_POST['deskripsi_konten_wilayah'];
    $status_konten_wilayah      = $_POST['status_konten_wilayah'];
    $tanggal_sekarang           = date('Y-m-d H:i:s', time());

    $randomstring = substr(md5(rand()), 0, 7);
    // var_dump($_FILES);
    // die;
    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
        $foto_konten_wilayah = $konten->foto_konten_wilayah;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($konten->foto_konten_wilayah == $defaultpic) || (!$konten->foto_konten_wilayah)) {
            $target_dir  = "images/foto_konten/wilayah";
            $foto_konten_wilayah = $target_dir . 'WIS_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_konten_wilayah);
            $pic = "&new=";
        } else if (isset($konten->foto_konten_wilayah)) {
            $foto_konten_wilayah = $konten->foto_konten_wilayah;
            unlink($konten->foto_konten_wilayah);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $konten->foto_konten_wilayah);
            $pic = "&replace=";
        }
    }
    //---image upload end

    $sqlpaket = "UPDATE t_konten_wilayah
                    SET judul_konten_wilayah = :judul_konten_wilayah,
                        deskripsi_konten_wilayah = :deskripsi_konten_wilayah,
                        foto_konten_wilayah = :foto_konten_wilayah,
                        status_konten_wilayah = :status_konten_wilayah,
                        update_terakhir = :update_terakhir
                    WHERE id_konten_wilayah = :id_konten_wilayah";

    $stmt = $pdo->prepare($sqlpaket);
    $stmt->execute([
        'judul_konten_wilayah' => $judul_konten_wilayah,
        'deskripsi_konten_wilayah' => $deskripsi_konten_wilayah,
        'foto_konten_wilayah' => $foto_konten_wilayah,
        'status_konten_wilayah' => $status_konten_wilayah,
        'update_terakhir' => $tanggal_sekarang,
        'id_konten_wilayah' => $id_konten_wilayah
    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: edit_konten.php?status=insertfailed");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_konten.php?status=updatesuccess");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Konten - GoKarang</title>
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
                    <a class="btn btn-outline-primary" href="kelola_konten.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Edit Data Konten</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">

                        <div class="form-group">
                            <label for="judul_konten_wilayah">Judul Konten</label>
                            <input maxlength="50" type="text" id="judul_konten_wilayah" name="judul_konten_wilayah" value="<?= $konten->judul_konten_wilayah ?>" class="form-control" placeholder="Judul Konten" required>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi_konten_wilayah">Deskripsi Konten</label>
                            <input maxlength="65" type="text" id="deskripsi_konten_wilayah" name="deskripsi_konten_wilayah" value="<?= $konten->deskripsi_konten_wilayah ?>" class="form-control" placeholder="Deskripsi Konten" required>
                        </div>

                        <!-- Lokasi -->
                        <div class="form-group">
                            <label for="status_konten_wilayah">Kategori Konten</label>
                            <select id="status_konten_wilayah" name="status_konten_wilayah" class="form-control" required>
                                <option selected value="">Pilih Kategori</option>
                                <option <?php if ($konten->status_konten_wilayah == "Donasi Sekarang") echo 'selected'; ?> value="Donasi Sekarang">Donasi Sekarang</option>
                                <option <?php if ($konten->status_konten_wilayah == "Wisata Sekarang") echo 'selected'; ?> value="Wisata Sekarang">Wisata Sekarang</option>
                                <option <?php if ($konten->status_konten_wilayah == "Coralmaps") echo 'selected'; ?> value="Coralmaps">Coralmaps</option>
                            </select>
                        </div>
                        <!-- <p class="small"><b>*Harap Teliti Pada Saat Memilih Status Jika Ada Status Sama Maka Foto Tidak Muncul</b></p> -->
                        <div class='form-group' id='fotowilayah'>
                            <div>
                                <label for='image_uploads'>Upload Foto Konten</label>
                                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                            </div>
                        </div>

                        <div class="form-group">
                            <img id="preview" src="#" width="100px" alt="Preview Gambar" />
                            <div class="detail-toggle" id="main-toggle">
                                <a href="<?= $konten->foto_konten_wilayah ?>" data-toggle="lightbox">
                                    <img class="img-fluid" id="oldpic" src="<?= $konten->foto_konten_wilayah ?>" width="20%" <?php if ($konten->foto_konten_wilayah == NULL) echo "style='display: none;'"; ?>><br>

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
                                            if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                                alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                                input.value = "";
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
                                </a>
                            </div>
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

    </div>
    <!-- Menampilkan Bukti Pembayaran -->
    <script src="js/ekko-lightbox.min.js"></script>
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
</body>

</html>