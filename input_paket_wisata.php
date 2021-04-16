<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $nama_paket_wisata          = $_POST['tb_nama_paket_wisata'];
        $deskripsi_wisata           = $_POST['tb_deskripsi_wisata'];
        $deskripsi_panjang_wisata   = $_POST['deskripsi_panjang_wisata'];
        $status_aktif               = $_POST['rb_status_wisata'];
        $randomstring               = substr(md5(rand()), 0, 7);

        //Image upload
        if($_FILES["image_uploads"]["size"] == 0) {
            $foto_wisata = "images/image_default.jpg";
        }
        else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_wisata/";
            $foto_wisata = $target_dir .'WIL_'.$randomstring. '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wisata);
        }

        //---image upload end

        //Insert t_wisata
        $sqlpaketwisata = "INSERT INTO tb_paket_wisata
                            (nama_paket_wisata, deskripsi_wisata, deskripsi_panjang_wisata, foto_wisata, status_aktif)
                            VALUES (:nama_paket_wisata, :deskripsi_wisata, :deskripsi_panjang_wisata, :foto_wisata, :status_aktif)";

        $stmt = $pdo->prepare($sqlpaketwisata);
        $stmt->execute(['nama_paket_wisata' => $nama_paket_wisata,
                        'deskripsi_wisata' => $deskripsi_wisata,
                        'deskripsi_panjang_wisata' => $deskripsi_panjang_wisata,
                        'foto_wisata' => $foto_wisata,
                        'status_aktif' => $status_aktif
                        ]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            $last_paket_wisata_id = $pdo->lastInsertId();
        }

        //var_dump($_POST['nama_paket']);exit();
        $i = 0;
        foreach ($_POST['nama_paket'] as $nama_paket) {
            $id_paket_wisata   = $last_paket_wisata_id; //tb_paket_wisata
            $id_wisata         = $_POST['nama_paket'][$i]; //t_wisata

            $sqlinsertdetailpaket = "UPDATE t_wisata
                                        SET id_paket_wisata = :id_paket_wisata
                                        WHERE id_wisata = :id_wisata";

            $stmt = $pdo->prepare($sqlinsertdetailpaket);
            $stmt->execute(['id_paket_wisata' => $id_paket_wisata,
                            'id_wisata' => $id_wisata
                            ]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: kelola_wisata.php?status=insertfailed");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_wisata.php?status=addsuccess");
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
    <title>Kelola Wisata - TKJB</title>
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
                    <a class="btn btn-outline-primary" href="input_wisata.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Input Data Paket Wisata</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">

                    <div class="form-group">
                        <label for="tb_nama_paket_wisata">Nama Paket Wisata</label>
                        <input type="text" id="tb_nama_paket_wisata" name="tb_nama_paket_wisata" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="tb_deskripsi_wisata">Deskripsi Singkat Wisata</label>
                        <input type="text" id="tb_deskripsi_wisata" name="tb_deskripsi_wisata" class="form-control" required>
                    </div>


                    <div class="form-group">
                        <label for="isi_artikel">Deskripsi Lengkap Wisata:</label>
                        <textarea id="deskripsi_lengkap_wisata" name="deskripsi_panjang_wisata" required></textarea>
                    <script>
                            $('#deskripsi_lengkap_wisata').trumbowyg();
                    </script>
                    </div>

                    <div class="form-group field_wrapper">
                        <label for="paket_wisata">Paket Wisata</label><br>
                        <div class="form-group fieldGroup">
                            <div class="input-group">
                                <select class="form-control" name="nama_paket[]" id="exampleFormControlSelect1">
                                    <option selected disabled>Pilih Paket Wisata:</option>
                                    <?php
                                    $sqlviewwisata = 'SELECT * FROM t_wisata
                                                        LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                                                        ORDER BY id_wisata DESC';
                                    $stmt = $pdo->prepare($sqlviewwisata);
                                    $stmt->execute();
                                    $rowwisata = $stmt->fetchAll();

                                    foreach ($rowwisata as $paket) { ?>
                                    <option value="<?=$paket->id_wisata?>">
                                        <?=$paket->judul_wisata?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" class="btn btn-success addMore">
                                        <span class="fas fas fa-plus" aria-hidden="true"></span> Tambah Paket
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='form-group' id='fotowilayah'>
                        <div>
                            <label for='image_uploads'>Upload Foto Wisata</label>
                            <input type='file'  class='form-control' id='image_uploads'
                                name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                        </div>
                    </div>

                    <div class="form-group">
                        <img id="preview"  width="100px" src="#" alt="Preview Gambar"/>

                        <script>
                            window.onload = function() {
                            document.getElementById('preview').style.display = 'none';
                            };
                            function readURL(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();

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
                        <label for="rb_status_wisata">Status</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_status_aktif" name="rb_status_wisata" value="Aktif" class="form-check-input">
                                <label class="form-check-label" for="rb_status_aktif" style="color: green">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input checked type="radio" id="rb_status_tidak_aktif" name="rb_status_wisata" value="Tidak Aktif " class="form-check-input">
                                <label class="form-check-label" for="rb_status_tidak_aktif" style="color: gray">
                                    Tidak Aktif
                                </label>
                            </div>
                    </div>

                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
                    <br><br>

                    <!-- copy of input fields group -->
                    <div class="form-group fieldGroupCopy" style="display: none;">
                        <div class="input-group">
                            <select class="form-control" name="nama_paket[]" id="exampleFormControlSelect1">
                                <option selected disabled>Pilih Paket Wisata:</option>
                                <?php
                                $sqlviewwisata = 'SELECT * FROM t_wisata
                                                    LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                                                    ORDER BY id_wisata DESC';
                                $stmt = $pdo->prepare($sqlviewwisata);
                                $stmt->execute();
                                $rowwisata = $stmt->fetchAll();

                                foreach ($rowwisata as $paket) { ?>
                                <option value="<?=$paket->id_wisata?>">
                                    <?=$paket->judul_wisata?>
                                </option>
                                <?php } ?>
                            </select>
                            <div class="input-group-addon">
                                <a href="javascript:void(0)" class="btn btn-danger remove">
                                    <span class="fas fas fa-minus" aria-hidden="true"></span> Hapus Paket
                                </a>
                            </div>
                        </div>
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

    <script>
        $(document).ready(function(){
        //group add limit
        var maxGroup = 50;

        //add more fields group
        $(".addMore").click(function(){
            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="form-group fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);
            }else{
                alert('Maksimal '+maxGroup+' group yang boleh dibuat.');
            }
        });

        //remove fields group
        $("body").on("click",".remove",function(){
            $(this).parents(".fieldGroup").remove();
        });
    });
    </script>
</div>
<!-- Import Trumbowyg font size JS at the end of <body>... -->
<script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
</body>
</html>
