<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
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
            $deskripsi_paket_wisata     = $_POST['deskripsi_paket_wisata'];
            $deskripsi_panjang_wisata   = $_POST['deskripsi_panjang_wisata'];
            $status_aktif               = $_POST['status_aktif'];

            $randomstring = substr(md5(rand()), 0, 7);

            //Image upload
            if($_FILES["image_uploads"]["size"] == 0) {
                $foto_wisata = $rowpaket->foto_wisata;
                $pic = "&none=";
            }
            else if (isset($_FILES['image_uploads'])) {
                if (($rowpaket->foto_wisata == $defaultpic) || (!$rowpaket->foto_wisata)){
                    $target_dir  = "images/foto_wisata/";
                    $foto_wisata = $target_dir .'WIS_'.$randomstring. '.jpg';
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wisata);
                    $pic = "&new=";
                }
                else if (isset($rowpaket->foto_wisata)){
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
                                deskripsi_paket_wisata = :deskripsi_paket_wisata,
                                deskripsi_panjang_wisata = :deskripsi_panjang_wisata,
                                foto_wisata = :foto_wisata,
                                status_aktif = :status_aktif
                            WHERE id_paket_wisata = :id_paket_wisata";

            $stmt = $pdo->prepare($sqlpaket);
            $stmt->execute(['id_lokasi' => $id_lokasi,
                            'id_asuransi' => $id_asuransi,
                            'nama_paket_wisata' => $nama_paket_wisata,
                            'deskripsi_paket_wisata' => $deskripsi_paket_wisata,
                            'deskripsi_panjang_wisata' => $deskripsi_panjang_wisata,
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
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($rowlokasi as $lokasi) {  ?>
                            <option <?php if($lokasi->id_lokasi == $rowpaket->id_lokasi) echo 'selected'; ?> value="<?=$lokasi->id_lokasi?>">ID <?=$lokasi->id_lokasi?> - <?=$lokasi->nama_lokasi?></option>
                        <?php } ?>
                    </select>
                    </div>

                    <!-- Asuransi -->
                    <div class="form-group">
                    <label for="id_asuransi">ID Asuransi</label>
                    <select id="id_asuransi" name="id_asuransi" class="form-control" required>
                            <option value="">Pilih Asuransi</option>
                            <?php foreach ($rowasuransi as $asuransi) {  ?>
                            <option <?php if($asuransi->id_asuransi == $rowpaket->id_asuransi) echo 'selected'; ?> value="<?=$asuransi->id_asuransi?>">ID <?=$asuransi->id_asuransi?> - <?=$asuransi->biaya_asuransi?></option>
                        <?php } ?>
                    </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_paket_wisata">Nama Paket Wisata</label>
                        <input type="text" id="nama_paket_wisata" name="nama_paket_wisata" value="<?=$rowpaket->nama_paket_wisata?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_paket_wisata">Deskripsi Paket Wisata</label>
                        <input type="text" id="deskripsi_paket_wisata" name="deskripsi_paket_wisata" value="<?=$rowpaket->deskripsi_paket_wisata?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="isi_artikel">Deskripsi Lengkap Wisata:</label>
                        <textarea id="deskripsi_panjang_wisata" name="deskripsi_panjang_wisata" placeholder="Di isi jika perlu" required></textarea>
                        <script>
                                $('#deskripsi_panjang_wisata').trumbowyg();
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
                            <a href="<?=$rowpaket->foto_wisata?>" data-toggle="lightbox">
                            <img class="img-fluid" id="oldpic" src="<?=$rowpaket->foto_wisata?>" width="20%" <?php if($rowpaket->foto_wisata == NULL) echo "style='display: none;'"; ?>></a>
                        <br>

                        <small class="text-muted">
                            <?php if($rowpaket->foto_wisata == NULL){
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
                        <label for="status_aktif">Status</label><br>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="rb_status_aktif" name="status_aktif" value="<?=$rowpaket->status_aktif?>" class="form-check-input">
                                <label class="form-check-label" for="rb_status_aktif" style="color: green">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input checked type="radio" id="rb_status_tidak_aktif" name="status_aktif" value="<?=$rowpaket->status_aktif?> " class="form-check-input">
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
