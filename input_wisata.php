<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewlokasi = 'SELECT * FROM t_lokasi
                    ORDER BY nama_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $rowlokasi = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $id_lokasi                  = $_POST['dd_id_lokasi'];
        $judul_wisata               = $_POST['tb_judul_wisata'];
        $deskripsi_wisata           = $_POST['tb_deskripsi_wisata'];

        //Insert t_wisata
        $sqlwisata = "INSERT INTO t_wisata
                            (id_lokasi, judul_wisata, deskripsi_wisata)
                            VALUES (:id_lokasi, :judul_wisata, :deskripsi_wisata)";

        $stmt = $pdo->prepare($sqlwisata);
        $stmt->execute(['id_lokasi'         => $id_lokasi,
                        'judul_wisata'      => $judul_wisata,
                        'deskripsi_wisata'  => $deskripsi_wisata
                        ]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            $last_wisata_id = $pdo->lastInsertId();
        }
        //var_dump($_POST['nama_fasilitas']);var_dump($_POST['biaya_fasilitas']);exit();
        $i = 0;
        foreach ($_POST['nama_fasilitas'] as $nama_fasilitas) {
            $nama_fasilitas    = $_POST['nama_fasilitas'][$i];
            $biaya_fasilitas   = $_POST['biaya_fasilitas'][$i];
            $id_wisata         = $last_wisata_id;

            $tanggal_sekarang = date ('Y-m-d H:i:s', time());

            $sqlinsertfasilitas = "INSERT INTO tb_fasilitas_wisata (nama_fasilitas, biaya_fasilitas, id_wisata, update_terakhir)
                                        VALUES (:nama_fasilitas, :biaya_fasilitas, :id_wisata, :update_terakhir)";

            $stmt = $pdo->prepare($sqlinsertfasilitas);
            $stmt->execute(['nama_fasilitas' => $nama_fasilitas,
                            'biaya_fasilitas' => $biaya_fasilitas,
                            'id_wisata' => $id_wisata,
                            'update_terakhir' => $update_terakhir
                            ]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: input_wisata.php?status=insertfailed");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: input_wisata.php?status=addsuccess");
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
                    <a class="btn btn-outline-primary" href="input_fasilitas_wisata.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Input Data Wisata</span></h4>
                </div>
                <div align="right">
                    <a class="btn btn-outline-primary" href="input_paket_wisata.php">
                    Selanjutnya Paket Wisata <i class="fas fa-angle-right"></i></a>
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
                                        Update bukti pembayaran reservasi wisata berhasil!
                                        </div>'; }
                            else if($_GET['status'] == 'addsuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Input data wisata dan fasilitas berhasil ditambahkan!
                                        </div>'; }
                        }
                    ?>
                    <form action="" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                    <label for="dd_id_lokasi">ID Lokasi</label>
                    <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control" required>
                            <option value="">Pilih Lokasi</option>
                        <?php foreach ($rowlokasi as $rowitem) {  ?>
                            <option value="<?=$rowitem->id_lokasi?>">ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></option>
                        <?php } ?>
                    </select>
                    </div>

                    <div class="form-group">
                        <label for="tb_judul_wisata">Judul Wisata</label>
                        <input type="text" id="tb_judul_wisata" name="tb_judul_wisata" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="tb_deskripsi_wisata">Deskripsi Singkat Wisata</label>
                        <input type="text" id="tb_deskripsi_wisata" name="tb_deskripsi_wisata" class="form-control" required>
                    </div>

                    <div class="form-group field_wrapper">
                        <label for="paket_wisata">Fasilitas Wisata</label><br>
                        <div class="form-group fieldGroup">
                            <div class="input-group">
                                <input type="text" name="nama_fasilitas[]" class="form-control" placeholder="Nama Fasilitas"/>
                                <input type="number" name="biaya_fasilitas[]" min="0" class="form-control" placeholder="Biaya Fasilitas"/>
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
