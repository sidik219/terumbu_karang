<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 3)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';
$id_konten = $_GET['id_konten'];
$defaultpic = "img/image_default.jpg";

$sqleditkegiatan = 'SELECT * FROM t_konten_tangkolak_section
                    WHERE t_konten_tangkolak_section.id_konten = :id_konten';

$stmt = $pdo->prepare($sqleditkegiatan);
$stmt->execute(['id_konten' => $id_konten]);
$kegiatan = $stmt->fetch();
// var_dump($row);
// die;

// $sqlviewtitik = 'SELECT * FROM t_konten_tangkolak_section 
// WHERE t_konten_tangkolak_section.id_konten = :id_konten';
// $stmt = $pdo->prepare($sqlviewtitik);
// $stmt->execute(['id_konten' => $id_konten]);
// $rowkonten = $stmt->fetchAll();
// var_dump($rowkonten);
// die;

if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $isi_konten = $_POST['informasi'];
    $randomstring       = substr(md5(rand()), 0, 7);
    // $gambar = $_POST['gambar'];
    // var_dump($_POST, $_FILES);
    // die;
    //Image upload
    if ($_FILES["image_uploads"]["size"] == 0) {
        $gambar = $kegiatan->gambar;
        $pic = "&none=";
    } else if (isset($_FILES['image_uploads'])) {
        if (($kegiatan->gambar == $defaultpic) || (!$kegiatan->gambar)) {
            $target_dir  = "images/foto_konten/kegiatan/";
            $gambar = $target_dir . 'INF_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $gambar);
            $pic = "&new=";
        } else if (isset($kegiatan->gambar)) {
            $gambar = $kegiatan->gambar;
            unlink($kegiatan->gambar);
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $kegiatan->gambar);
            $pic = "&replace=";
        }
    }
    //---image upload end
    $sqlkonten = "UPDATE t_konten_tangkolak_section SET isi_konten = :isi_konten,
    judul = :judul, gambar= :gambar
    WHERE t_konten_tangkolak_section.id_konten = :id_konten";
    $stmt = $pdo->prepare($sqlkonten);
    $stmt->execute([
        'judul' => $judul,
        'isi_konten' => $isi_konten,
        'id_konten' => $id_konten,
        'gambar' => $gambar
    ]);
    // $stmt->execute();
    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: kelola_konten_penjelasan.php?status=updatesuccess");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_konten_penjelasan.php?status=updatesuccess");
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
    <script src="js/trumbowyg/dist/trumbowyg.min.js"></script>
    <!-- Favicon -->
    <link rel="icon" href="dist/img/KKPlogo.png" type="image/x-icon" />
    <style>
        img {
            width: 250px;
        }
    </style>

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
                    <a class="btn btn-outline-primary" href="kelola_konten_penjelasan.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Edit Kelola Konten</span></h4>
                            <p>Halaman Input Ini untuk pada halaman depan website</p>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">
                        <div class='form-group'>
                            <div>
                                <label for='image_uploads'>Upload Foto Informasi</label>
                                <input type='file' class='form-control' id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                            </div>
                        </div>
                        <div class="form-group">
                            <img id="preview" src="#" width="100px" alt="Preview Gambar" />
                            <div class="detail-toggle" id="main-toggle">
                                <a href="<?= $kegiatan->gambar ?>" data-toggle="lightbox">
                                    <img class="img-fluid" id="oldpic" src="<?= $kegiatan->gambar ?>" width="20%" <?php if ($kegiatan->gambar == NULL) echo "style='display: none;'"; ?>>
                                    <br>

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
                        <div class="form-group">
                            <label for="judul">Judul Informasi</label>
                            <input type="text" id="judul" name="judul" value="<?= $kegiatan->judul ?>" class="form-control" placeholder="Judul Konten" required>
                        </div>

                        <div id="count">
                            <span id="current_count">0</span>
                            <span id="maximum_count">/ 1350</span>
                        </div>
                        <div class="form-group">
                            <label for="informasi">Deskripsi Informasi</label>
                            <textarea id="informasi" maxlength="1350" required rows="10" cols="100 " class="form-control" name="informasi" class="form-control" placeholder="Deskripsi Konten" required><?= $kegiatan->isi_konten; ?></textarea>
                            <!-- <script>
                                $('#informasi').trumbowyg();
                            </script> -->
                        </div>



                        <p align="center">
                            <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                        </p>
                    </form><br><br>
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

        <!-- jQuery library -->
        <!-- Pembatasan Date Pemesanan -->
        <script>
            $('textarea').keyup(function() {
                var characterCount = $(this).val().length,
                    current_count = $('#current_count'),
                    maximum_count = $('#maximum_count'),
                    count = $('#count');
                current_count.text(characterCount);
                if (current_count == maximum_count) {
                    alert('Sudah Melebihi Batas Input');
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                //group add limit
                var maxGroup = 3;

                //add more fields group
                $(".addMore").click(function() {
                    if ($('body').find('.fieldGroup').length < maxGroup) {
                        var fieldHTML = '<div class="form-group fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';
                        $('body').find('.fieldGroup:last').after(fieldHTML);
                    } else {
                        alert('Maksimal ' + maxGroup + ' paket wisata yang boleh dibuat.');
                    }
                });

                //remove fields group
                $("body").on("click", ".remove", function() {
                    $(this).parents(".fieldGroup").remove();
                });
            });
        </script>
    </div>
    <!-- Import Trumbowyg font size JS at the end of <body>... -->
    <script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
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