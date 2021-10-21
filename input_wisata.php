<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlfasilitaswisata = 'SELECT * FROM tb_fasilitas_wisata
                        ORDER BY id_fasilitas_wisata
                        DESC LIMIT 3';
$stmt = $pdo->prepare($sqlfasilitaswisata);
$stmt->execute();
$rowfasilitas = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $judul_wisata               = $_POST['judul_wisata'];
        $randomstring               = substr(md5(rand()), 0, 7);

        //Image upload
        if ($_FILES["image_uploads"]["size"] == 0) {
            $image_wisata = "images/image_default.jpg";
        } else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_wisata/";
            $image_wisata = $target_dir . 'WIS_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $image_wisata);
        }
        //---image upload end

        //Insert t_wisata
        $sqlwisata = "INSERT INTO t_wisata
                            (judul_wisata, image_wisata)
                            VALUES (:judul_wisata, :image_wisata)";

        $stmt = $pdo->prepare($sqlwisata);
        $stmt->execute([
            'judul_wisata'      => $judul_wisata,
            'image_wisata'  => $image_wisata
        ]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            // header("Location: input_wisata.php?status=insertfailed");
        } else {
            // echo "HAHAHAAHA GREAT SUCCESSS !";
            // header("Location: input_wisata.php?status=addsuccess");
            $last_wisata_id = $pdo->lastInsertId();
        }
        // var_dump($_POST['nama_fasilitas']);var_dump($_POST['biaya_fasilitas']);exit();
        $i = 0;
        foreach ($_POST['id_fasilitas_wisata'] as $id_fasilitas_wisata) {
            $id_fasilitas_wisata    = $_POST['id_fasilitas_wisata'][$i];
            $id_wisata              = $last_wisata_id;

            $sqlupdatefasilitas = "UPDATE tb_fasilitas_wisata
                                    SET id_wisata = :id_wisata
                                    WHERE id_fasilitas_wisata = :id_fasilitas_wisata";

            $stmt = $pdo->prepare($sqlupdatefasilitas);
            $stmt->execute([
                'id_fasilitas_wisata' => $id_fasilitas_wisata,
                'id_wisata' => $id_wisata
            ]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: input_wisata.php?status=insertfailed");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: input_paket_wisata.php?status=addsuccess");
            }
            $i++;
        } //End Foreach
    } else {
        echo '<script>alert("Harap pilih paket wisata yang akan ditambahkan")</script>';
    }
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
    <link rel="stylesheet" type="text/css" href="css/style-card.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="js/trumbowyg/dist/ui/trumbowyg.min.css">
    <script src="js/trumbowyg/dist/trumbowyg.js"></script>
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
                    <a class="btn btn-outline-primary" href="input_fasilitas_wisata.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Input Data Wisata</span></h4>
                            <ul class="app-breadcrumb breadcrumb" style="margin-bottom: 20px;">
                                <li class="breadcrumb-item">
                                    <a href="kelola_wisata.php" class="non">Kelola Wisata</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="kelola_fasilitas_wisata.php" class="non">Data Fasilitas Wisata</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="input_fasilitas_wisata.php" class="non">Input Fasilitas</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="input_wisata.php" class="tanda">Input Wisata</a>
                                </li>
                            </ul>
                </div>
                <div align="right">
                    <a class="btn btn-outline-primary" href="input_paket_wisata.php">
                        Selanjutnya Input Paket Wisata <i class="fas fa-angle-right"></i></a>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                    if (!empty($_GET['status'])) {
                        if ($_GET['status'] == 'updatesuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                        Input data wisata gagal ditambahkan!
                                        </div>';
                        } else if ($_GET['status'] == 'addsuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                        Input data wisata berhasil ditambahkan! Selanjutnya isi data paket wisata.
                                        </div>';
                        } else if ($_GET['status'] == 'addsuccess1') {
                            echo '<div class="alert alert-success" role="alert">
                                        Input data fasilitas berhasil ditambahkan! Selanjutnya isi data wisata.
                                        </div>';
                        }
                    }
                    ?>
                    <form action="" enctype="multipart/form-data" method="POST">
                        <?php
                        foreach ($rowfasilitas as $fasilitas) { ?>
                            <input type="hidden" name="id_fasilitas_wisata[]" value="<?= $fasilitas->id_fasilitas_wisata ?>">
                        <?php } ?>

                        <div class="form-group">
                            <label for="judul_wisata">Judul Wisata</label>
                            <input type="text" id="judul_wisata" name="judul_wisata" class="form-control" Placeholder="Judul Wisata" required>
                        </div>

                        <div class='form-group'>
                            <div>
                                <label for='image_uploads'>Upload Foto Wisata</label>
                                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                            </div>
                        </div>

                        <div class="form-group">
                            <img id="preview" width="100px" src="#" alt="Preview Gambar" />

                            <script>
                                window.onload = function() {
                                    document.getElementById('preview').style.display = 'none';
                                };

                                function readURL(input) {
                                    //Validasi Size Upload Image
                                    // var uploadField = document.getElementById("image_uploads");

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

                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                        </p>
                    </form>

                    <!-- Keterangan -->
                    <div>
                        <label for="">Keterangan:</label><br>
                        <small><b>Contoh Pengisian:</b></small><br>
                        <small>* Judul Wisata: Wisata Diving</small><br>
                        <small>* Upload Foto Wisata: "Foto tentang wisata tersebut"</small><br>
                        <small style="color: red;">* Untuk menambahkan wisata baru,
                            harus <a href="input_fasilitas_wisata.php"><b>input fasilitas</b></a> terlebih dahulu</small>
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
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.js"></script>
    </div>
    <!-- Import Trumbowyg font size JS at the end of <body>... -->
    <script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
</body>

</html>