<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

        $id_wisata = $_GET['id_wisata'];
        $defaultpic = "images/image_default.jpg";

        $sqlviewlokasi = 'SELECT * FROM t_lokasi
                            ORDER BY nama_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $rowlokasi = $stmt->fetchAll();

        $sqleditwisata = 'SELECT * FROM t_wisata
                            LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                            LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                            WHERE id_wisata = :id_wisata';

        $stmt = $pdo->prepare($sqleditwisata);
        $stmt->execute(['id_wisata' => $id_wisata]);
        $wisata = $stmt->fetch();
        
        // Jarak
        // 
        // Jarak
        if (isset($_POST['submit'])) {
            $id_lokasi                  = $_POST['dd_id_lokasi'];
            $judul_wisata               = $_POST['tb_judul_wisata'];
            $deskripsi_wisata           = $_POST['tb_deskripsi_wisata'];
            
            $sqldeletefasilitas = "DELETE FROM tb_fasilitas_wisata
                                    WHERE id_wisata = :id_wisata";

            $stmt = $pdo->prepare($sqldeletefasilitas);
            $stmt->execute(['id_wisata' => $id_wisata]);
            
            $sqlwisata = "UPDATE t_wisata
                            SET id_lokasi = :id_lokasi, 
                                judul_wisata = :judul_wisata, 
                                deskripsi_wisata = :deskripsi_wisata
                            WHERE id_wisata = :id_wisata";

            $stmt = $pdo->prepare($sqlwisata);
            $stmt->execute(['id_wisata' => $id_wisata,
                            'id_lokasi' => $id_lokasi,
                            'judul_wisata' => $judul_wisata,
                            'deskripsi_wisata' => $deskripsi_wisata
                            ]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: kelola_wisata.php?status=insertfailed");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_wisata.php?status=updatesuccess");
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
                    <a class="btn btn-outline-primary" href="kelola_wisata.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Edit Data Wisata</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                        if(!empty($_GET['status'])) {
                            if($_GET['status'] == 'updatesuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Update data wisata dan fasilitas wisata berhasil!
                                        </div>'; }
                            else if($_GET['status'] == 'addsuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Input data wisata dan fasilitas wisata berhasil ditambahkan!
                                        </div>'; }
                        }
                    ?>
                    <form action="" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                    <label for="dd_id_lokasi">ID Lokasi</label>
                    <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control" required>
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($rowlokasi as $lokasi) {  ?>
                            <option <?php if($lokasi->id_lokasi == $wisata->id_lokasi) echo 'selected'; ?> value="<?=$lokasi->id_lokasi?>">ID <?=$lokasi->id_lokasi?> - <?=$lokasi->nama_lokasi?></option>
                        <?php } ?>
                    </select>
                    </div>

                    <div class="form-group">
                        <label for="tb_judul_wisata">Judul Wisata</label>
                        <input type="text" id="tb_judul_wisata" name="tb_judul_wisata" value="<?=$wisata->judul_wisata?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="tb_deskripsi_wisata">Deskripsi Singkat Wisata</label>
                        <input type="text" id="tb_deskripsi_wisata" name="tb_deskripsi_wisata" value="<?=$wisata->deskripsi_wisata?>" class="form-control" required>
                    </div>

                    <!-- Edit Paket Wisata -->
                    <h4 style="margin-top: 80px;">
                    <span class="align-middle font-weight-bold">Edit Paket Wisata</span></h4>
                    <hr>

                    <div class="form-group">
                        <label for="nama_paket_wisata">Nama Paket Wisata</label>
                        <input type="text" id="nama_paket_wisata" name="nama_paket_wisata" value="<?=$wisata->nama_paket_wisata?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_paket_wisata">Deskripsi Paket Wisata</label>
                        <input type="text" id="deskripsi_paket_wisata" name="deskripsi_paket_wisata" value="<?=$wisata->deskripsi_paket_wisata?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="isi_artikel">Deskripsi Lengkap Wisata:</label>
                        <textarea id="deskripsi_lengkap_wisata" name="deskripsi_panjang_wisata" placeholder="Di isi jika perlu" required></textarea>
                        <script>
                                $('#deskripsi_lengkap_wisata').trumbowyg();
                        </script>
                    </div>

                    <div class='form-group' id='fotowilayah'>
                        <div>
                            <label for='image_uploads'>Upload Foto Wisata</label>
                            <input type='file'  class='form-control' id='image_uploads'
                                name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                        </div>
                    </div>

                    <div class="form-group">
                        <img id="preview" src="#"  width="100px" alt="Preview Gambar"/>
                            <a href="<?=$wisata->foto_wisata?>" data-toggle="lightbox"><img class="img-fluid" id="oldpic" src="<?=$wisata->foto_wisata?>" width="20%" <?php if($wisata->foto_wisata == NULL) echo " style='display:none;'"; ?>></a>
                        <br>

                        <small class="text-muted">
                            <?php if($wisata->foto_wisata == NULL){
                                echo "Bukti transfer belum diupload<br>Format .jpg .jpeg .png";
                            }else{
                                echo "Klik gambar untuk memperbesar";
                            }

                            ?>
                        </small>

                        <script>
                            const actualBtn = document.getElementById('image_uploads');

                            const fileChosen = document.getElementById('file-input-label');

                            actualBtn.addEventListener('change', function(){
                            fileChosen.innerHTML = '<b>File dipilih :</b> '+this.files[0].name
                            })
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
                        <label for="rb_status_wisata">Status</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_status_aktif" name="rb_status_wisata" value="<?=$wisata->status_aktif?>" class="form-check-input">
                                <label class="form-check-label" for="rb_status_aktif" style="color: green">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input checked type="radio" id="rb_status_tidak_aktif" name="rb_status_wisata" value="<?=$wisata->status_aktif?> " class="form-check-input">
                                <label class="form-check-label" for="rb_status_tidak_aktif" style="color: gray">
                                    Tidak Aktif
                                </label>
                            </div>
                    </div>

                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
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

</body>
</html>
