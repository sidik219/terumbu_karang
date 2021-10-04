<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

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

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $id_lokasi                  = $_POST['id_lokasi'];
        $id_asuransi                = $_POST['id_asuransi'];
        $nama_paket_wisata          = $_POST['nama_paket_wisata'];
        $tgl_pemesanan              = $_POST['tgl_pemesanan'];
        $tgl_akhir_pemesanan        = $_POST['tgl_akhir_pemesanan'];
        $status_aktif               = $_POST['status_aktif'];
        $randomstring               = substr(md5(rand()), 0, 7);

        //Image upload
        if ($_FILES["image_uploads"]["size"] == 0) {
            $foto_wisata = "images/image_default.jpg";
        }
        else if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_paket_wisata/";
            $foto_wisata = $target_dir .'PAW_'.$randomstring. '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wisata);
        }
        //---image upload end

        //Insert t_wisata
        $sqlpaketwisata = "INSERT INTO tb_paket_wisata
                            (id_lokasi,
                            id_asuransi,
                            nama_paket_wisata,
                            tgl_pemesanan, 
                            tgl_akhir_pemesanan, 
                            foto_wisata, 
                            status_aktif)
                            VALUES (:id_lokasi, 
                            :id_asuransi,
                            :nama_paket_wisata,
                            :tgl_pemesanan, 
                            :tgl_akhir_pemesanan, 
                            :foto_wisata, 
                            :status_aktif)";

        $stmt = $pdo->prepare($sqlpaketwisata);
        $stmt->execute(['id_lokasi' => $id_lokasi,
                        'id_asuransi' => $id_asuransi,
                        'nama_paket_wisata' => $nama_paket_wisata,
                        'tgl_pemesanan' => $tgl_pemesanan,
                        'tgl_akhir_pemesanan' => $tgl_akhir_pemesanan,
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

        //var_dump($_POST['nama_wisata']);exit();
        $i = 0;
        foreach ($_POST['nama_wisata'] as $nama_wisata) {
            $id_paket_wisata   = $last_paket_wisata_id; //tb_paket_wisata
            $id_wisata         = $_POST['nama_wisata'][$i]; //t_wisata
            $deskripsi_wisata  = $_POST['deskripsi_wisata'][$i]; //t_wisata

            //Update dan set id_paket_wisata ke wisata pilihan
            $sqlupdatewisata = "UPDATE t_wisata
                                SET id_paket_wisata = :id_paket_wisata,
                                    deskripsi_wisata = :deskripsi_wisata
                                WHERE id_wisata = :id_wisata";

            $stmt = $pdo->prepare($sqlupdatewisata);
            $stmt->execute(['id_wisata' => $id_wisata, 
                            'deskripsi_wisata' => $deskripsi_wisata, 
                            'id_paket_wisata' => $id_paket_wisata]);

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
                    <ul class="app-breadcrumb breadcrumb" style="margin-bottom: 20px;">
                        <li class="breadcrumb-item">
                            <a href="kelola_wisata.php" class="non">Kelola Wisata</a></li>
                        <li class="breadcrumb-item">
                            <a href="kelola_fasilitas_wisata.php" class="non">Data Fasilitas Wisata</a></li>
                        <li class="breadcrumb-item">
                            <a href="input_fasilitas_wisata.php" class="non">Input Fasilitas</a></li>
                        <li class="breadcrumb-item">
                            <a href="input_wisata.php" class="non">Input Wisata</a></li>
                        <li class="breadcrumb-item">
                            <a href="input_paket_wisata.php" class="tanda">Input Paket Wisata</a></li>
                    </ul>
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
                            <option value="">Pilih Lokasi</option>
                        <?php foreach ($rowlokasi as $lokasi) {  ?>
                            <option value="<?=$lokasi->id_lokasi?>">
                                ID <?=$lokasi->id_lokasi?> - <?=$lokasi->nama_lokasi?>
                            </option>
                        <?php } ?>
                    </select>
                    </div>

                    <!-- Asuransi -->
                    <div class="form-group">
                    <label for="id_asuransi">ID Asuransi</label>
                    <select id="id_asuransi" name="id_asuransi" class="form-control" required>
                            <option value="">Pilih Asuransi</option>
                        <?php foreach ($rowasuransi as $asuransi) {  ?>
                            <option value="<?=$asuransi->id_asuransi?>">
                                ID <?=$asuransi->id_asuransi?> - <?=$asuransi->biaya_asuransi?>
                            </option>
                        <?php } ?>
                    </select>
                    </div>
                    
                    <!-- Wisata -->
                    <div class="form-group field_wrapper">
                        <label for="nama_wisata">ID Wisata</label><br>
                        <div class="form-group fieldGroup">
                            <div class="input-group">
                                <select class="form-control" name="nama_wisata[]" id="nama_wisata" required>
                                    <option selected disabled>Pilih Wisata:</option>
                                    <?php
                                    $sqlviewwisata = 'SELECT * FROM t_wisata
                                                        WHERE id_paket_wisata IS NULL
                                                        ORDER BY id_wisata';
                                    $stmt = $pdo->prepare($sqlviewwisata);
                                    $stmt->execute();
                                    $rowwisata = $stmt->fetchAll();

                                    foreach ($rowwisata as $wisata) { ?>
                                    <option value="<?=$wisata->id_wisata?>">
                                        ID <?=$wisata->id_wisata?> - <?=$wisata->judul_wisata?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <input type="text" name="deskripsi_wisata[]" min="0" class="form-control" placeholder="Hari" />
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" class="btn btn-success addMore">
                                        <span class="fas fas fa-plus" aria-hidden="true"></span> Tambah Wisata
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-4">
                        <label for="">Keterangan:</label><br>
                        <small><b>Contoh Pengisian:</b></small><br>
                        <small>* Pilih Wisata: Wisata Diving dst</small><br>
                        <small>* Hari: Hari Pertama dst</small><br>
                        <small style="color: red;">* Hanya bisa satu wisata, untuk perhari</small><br>
                        <small style="color: red;">* Untuk menambahkan wisata baru, 
                            harus <a href="input_fasilitas_wisata.php"><b>input fasilitas</b></a> terlebih dahulu</small>
                    </div>

                    <div class="form-group">
                        <label for="nama_paket_wisata">Nama Paket Wisata</label>
                        <input type="text" id="nama_paket_wisata" name="nama_paket_wisata" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="tgl_pemesanan">Batas Pemesanan</label>
                        <div class="d-flex flex-row bd-highlight mb-3">
                            <div class="p-2 bd-highlight">
                                <label for="tgl_pemesanan">Tanggal Awal</label>
                                <input type="date" id="tgl_pemesanan" name="tgl_pemesanan" class="form-control" required>
                            </div>
                            <div class="p-2 bd-highlight">
                                <label for="tgl_akhir_pemesanan">Tanggal Akhir</label>
                                <input type="date" id="tgl_akhir_pemesanan" name="tgl_akhir_pemesanan" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class='form-group' id='fotowilayah'>
                        <div>
                            <label for='image_uploads'>Upload Foto Paket Wisata</label>
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
                        <label for="status_aktif">Status</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="status_aktif" name="status_aktif" value="Aktif" class="form-check-input">
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
                    </div>

                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
                    <br><br>

                    <!-- copy of input fields group -->
                    <div class="form-group fieldGroupCopy" style="display: none;">
                        <div class="input-group">
                            <select class="form-control" name="nama_wisata[]" id="nama_wisata" required>
                                <option selected disabled>Pilih Wisata:</option>
                                <?php
                                $sqlviewwisata = 'SELECT * FROM t_wisata
                                                    WHERE id_paket_wisata IS NULL
                                                    ORDER BY id_wisata';
                                $stmt = $pdo->prepare($sqlviewwisata);
                                $stmt->execute();
                                $rowwisata = $stmt->fetchAll();

                                foreach ($rowwisata as $wisata) { ?>
                                <option value="<?=$wisata->id_wisata?>">
                                    ID <?=$wisata->id_wisata?> - <?=$wisata->judul_wisata?>
                                </option>
                                <?php } ?>
                            </select>
                            <input type="text" name="deskripsi_wisata[]" min="0" class="form-control" placeholder="Hari" />
                            <div class="input-group-addon">
                                <a href="javascript:void(0)" class="btn btn-danger remove">
                                    <span class="fas fas fa-minus" aria-hidden="true"></span> Hapus Wisata
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
    <!-- Pembatasan Date Pemesanan -->
    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("tgl_pemesanan")[0].setAttribute('min', today);
    </script>
    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("tgl_akhir_pemesanan")[0].setAttribute('min', today);
    </script>
    <script>
        $(document).ready(function(){
        //group add limit
        var maxGroup = 3;

        //add more fields group
        $(".addMore").click(function(){
            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="form-group fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);
            }else{
                alert('Maksimal '+maxGroup+' paket wisata yang boleh dibuat.');
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
