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
                            WHERE id_wisata = :id_wisata';

        $stmt = $pdo->prepare($sqleditwisata);
        $stmt->execute(['id_wisata' => $id_wisata]);
        $wisata = $stmt->fetch();

        if (isset($_POST['submit'])) {
            if ($_POST['submit'] == 'Simpan') {
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
                    header("Location: kelola_wisata.php?status=nochange");
                } else {
                    //echo "HAHAHAAHA GREAT SUCCESSS !";
                    $last_wisata_id = $pdo->lastInsertId();
                }

                $i = 0;
                foreach ($_POST['nama_fasilitas'] as $nama_fasilitas) {
                    $nama_fasilitas    = $_POST['nama_fasilitas'][$i];
                    $biaya_fasilitas   = $_POST['biaya_fasilitas'][$i];
                    $id_wisata         = $_GET['id_wisata'];

                    $tanggal_sekarang = date ('Y-m-d H:i:s', time());

                    $sqlinsertfasilitas = "INSERT INTO tb_fasilitas_wisata (nama_fasilitas, biaya_fasilitas, id_wisata, update_terakhir)
                                        VALUES (:nama_fasilitas, :biaya_fasilitas, :id_wisata, :update_terakhir)";

                    $stmt = $pdo->prepare($sqlinsertfasilitas);
                    $stmt->execute(['nama_fasilitas' => $nama_fasilitas,
                                    'biaya_fasilitas' => $biaya_fasilitas,
                                    'id_wisata' => $id_wisata,
                                    'update_terakhir' => $tanggal_sekarang
                                    ]);

                    $affectedrows = $stmt->rowCount();
                    if ($affectedrows == '0') {
                        header("Location: kelola_wisata.php?status=insertfailed");
                    } else {
                        //echo "HAHAHAAHA GREAT SUCCESSS !";
                        header("Location: kelola_wisata.php?status=updatesuccess");
                    }
                    $i++;
                } //End Foreach
            } else {
                echo '<script>alert("Harap pilih paket donasi yang akan ditambahkan")</script>';
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

                    <div class="form-group field_wrapper">
                        <label for="paket_wisata">Fasilitas Wisata</label><br>
                        <div class="form-group fieldGroup">
                            <div class="input-group">
                                <?php
                                $sqlviewpaket = 'SELECT * FROM tb_fasilitas_wisata 
                                                    LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                    WHERE t_wisata.id_wisata = :id_wisata
                                                    AND t_wisata.id_wisata = t_wisata.id_wisata';

                                $stmt = $pdo->prepare($sqlviewpaket);
                                $stmt->execute(['id_wisata' => $wisata->id_wisata]);
                                $rowfasilitas = $stmt->fetchAll();

                                foreach ($rowfasilitas as $fasilitas) { ?>
                                <input type="text" name="nama_fasilitas[]" value="<?=$fasilitas->nama_fasilitas?>" class="form-control" placeholder="Nama Fasilitas"/>
                                <input type="number" name="biaya_fasilitas[]" value="<?=$fasilitas->biaya_fasilitas?>" class="form-control" placeholder="Biaya Fasilitas"/>
                                <?php } ?>
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" class="btn btn-success addMore">
                                        <span class="fas fas fa-plus" aria-hidden="true"></span> Tambah Fasilitas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form><br><br>

                    <!-- copy of input fields group -->
                    <div class="form-group fieldGroupCopy" style="display: none;">
                        <div class="input-group">
                            <input type="text" name="nama_fasilitas[]" class="form-control" placeholder="Nama Fasilitas"/>
                            <input type="number" name="biaya_fasilitas[]" min="0" class="form-control" placeholder="Biaya Fasilitas"/>
                            
                            <div class="input-group-addon">
                                <a href="javascript:void(0)" class="btn btn-danger remove">
                                    <span class="fas fas fa-minus" aria-hidden="true"></span> Hapus Fasilitas
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

    <!-- Fasilitas wisata -->
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

</body>
</html>
