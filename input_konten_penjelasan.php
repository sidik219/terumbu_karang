<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 3)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';


if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $judul_informasi = $_POST['judul_Informasi'];
        $isi_konten = $_POST['isi_konten'];

        // var_dump($_POST, $_FILES);
        // die;
        $randomstring       = substr(md5(rand()), 0, 7);
        //Image upload
        if ($_FILES["image_uploads"]["size"] == 0) {
            $foto_kegiatan = "images/image_default.jpg";
        } else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_konten/kegiatan/";
            $foto_kegiatan = $target_dir . 'INF_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_kegiatan);
        }
        //---image upload end
        //Insert t_konten_section
        $sqlpaketwisata = "INSERT INTO `t_konten_tangkolak_section` (judul, isi_konten, gambar) VALUES (:judul, :isi_konten, :gambar)";
        $stmt = $pdo->prepare($sqlpaketwisata);
        $stmt->execute([
            'judul' => $judul_informasi,
            'isi_konten' => $isi_konten,
            'gambar' => $foto_kegiatan
        ]);
        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            header("Location: kelola_konten_penjelasan.php?status=insertfailed");
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            header("Location: kelola_konten_penjelasan.php?status=addsuccess");
        }
    } else {
        // echo '<script>alert("Harap pilih paket wisata yang akan ditambahkan")</script>';
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
                    <h4><span class="align-middle font-weight-bold">Input Kelola Penjelasan</span></h4>
                    <p>Halaman Input Ini untuk pada halaman depan website</p>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <form action="" enctype="multipart/form-data" method="POST">
                        <div class="form-group pb-3">


                            <div class='form-group' id='fotowilayah'>
                                <div>
                                    <label for='image_uploads'>Upload Foto Informasi</label>
                                    <input type='file' class='form-control' required id='image_uploads' name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
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
                                        var uploadField = document.getElementById("image_uploads");

                                        uploadField.onchange = function() {
                                            if (this.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                                alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                                this.value = "";
                                            };
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
                            <div class="form-group">
                                <label for="judul_Informasi">Judul Informasi</label>
                                <input type="text" id="judul_Informasi" name="judul_Informasi" class="form-control" placeholder="Judul Informasi" required>
                            </div>
                            <label for="isi_konten">
                                <h5><b>Informasi Di Tangkolak:</b></h5>
                                <p class="small">Berikan Informasi yang menarik wisatawan untuk berwisata</p>
                            </label>
                            <div id="count">
                                <span id="current_count">0</span>
                                <span id="maximum_count">/ 1200</span>
                            </div>
                            <textarea id="isi_konten" name="isi_konten" maxlength="1200" required rows="10" cols="100 " class="form-control"></textarea>
                            <!-- <script>
                                $('#isi_konten').trumbowyg();
                            </script> -->
                        </div>

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
</body>

</html>

<script type="text/javascript">
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

</div>
<!-- Import Trumbowyg font size JS at the end of <body>... -->
<script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
</body>

</html>