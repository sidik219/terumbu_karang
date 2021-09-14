<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

// GET ID Reservasi
$id_reservasi = $_GET['id_reservasi'];

$sqlreservasi = 'SELECT * FROM t_reservasi_wisata
                WHERE id_reservasi = :id_reservasi';

$stmt = $pdo->prepare($sqlreservasi);
$stmt->execute(['id_reservasi' => $id_reservasi]);
$reservasi = $stmt->fetch();

// Select Data Pengeluaran Berdasarkan ID Reservasi
$sqlpengeluaran = 'SELECT * FROM t_laporan_pengeluaran
                    LEFT JOIN t_reservasi_wisata ON t_laporan_pengeluaran.id_reservasi = t_reservasi_wisata.id_reservasi
                    WHERE t_reservasi_wisata.id_reservasi = :id_reservasi
                    ORDER BY id_pengeluaran DESC';

$stmt = $pdo->prepare($sqlpengeluaran);
$stmt->execute(['id_reservasi' => $id_reservasi]);
$rowPengeluaran = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $i = 0;
        foreach ($_POST['nama_pengeluaran'] as $nama_pengeluaran) {
            $id_reservasi       = $_POST['id_reservasi'];
            $nama_pengeluaran   = $_POST['nama_pengeluaran'][$i];
            $biaya_pengeluaran  = $_POST['biaya_pengeluaran'][$i];
            $tanggal_sekarang   = date ('Y-m-d H:i:s', time());

            //Insert t_kerjasama
            $sqlpengeluaran = "INSERT INTO t_laporan_pengeluaran (id_reservasi, nama_pengeluaran, biaya_pengeluaran, update_terakhir)
                                                VALUES (:id_reservasi, :nama_pengeluaran, :biaya_pengeluaran, :update_terakhir)";

            $stmt = $pdo->prepare($sqlpengeluaran);
            $stmt->execute(['id_reservasi' => $id_reservasi,
                            'nama_pengeluaran' => $nama_pengeluaran,
                            'biaya_pengeluaran' => $biaya_pengeluaran,
                            'update_terakhir' => $tanggal_sekarang
                            ]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: kelola_laporan_wisata.php?status=insertfailed");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                // echo "<meta http-equiv='refresh' content='0'>";
                header('Location: kelola_laporan_wisata.php?id_reservasi='.$id_reservasi.'&status=addsuccess');
            }
            $i++;
        } //End Foreach
    } else {
        echo '<script>alert("Harap inputkan nama pengeluaran yang akan ditambahkan")</script>';
    }
}

function ageCalculator($dob){
    $birthdate = new DateTime($dob);
    $today   = new DateTime('today');
    $ag = $birthdate->diff($today)->y;
    $mn = $birthdate->diff($today)->m;
    $dy = $birthdate->diff($today)->d;
    if ($mn == 0)
    {
        return "$dy Hari";
    }
    elseif ($ag == 0)
    {
        return "$mn Bulan  $dy Hari";
    }
    else
    {
        return "$ag Tahun $mn Bulan $dy Hari";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Laporan Pengeluaran Wisata - GoKarang</title>
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
                    <a class="btn btn-outline-primary" href="kelola_reservasi_wisata.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Kelola Laporan Pengeluaran Wisata</span></h4>
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
                                        Update Laporan Pengeluaran berhasil!
                                        </div>'; }
                            else if($_GET['status'] == 'addsuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Data Laporan Pengeluaran berhasil ditambahkan!
                                        </div>'; }
                            else if($_GET['status'] == 'deletesuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Data Laporan Pengeluaran berhasil dihapus!
                                        </div>'; }
                        }
                    ?>

                    <form action="" enctype="multipart/form-data" method="POST">
                    <!-- Hidden Input -->
                    <input type="hidden" name="id_reservasi" value="<?=$reservasi->id_reservasi?>">

                    <div class="form-group field_wrapper">
                        <label for="paket_wisata">Nama Pengeluaran</label><br>
                        <div class="form-group fieldGroup">
                            <div class="input-group">
                                <input type="text" name="nama_pengeluaran[]" min="0" class="form-control" placeholder="Nama Pengeluaran"/>
                                <input type="number" name="biaya_pengeluaran[]" min="0" class="form-control" placeholder="Biaya Pengeluaran"/>
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" class="btn btn-success addMore">
                                        <span class="fas fas fa-plus" aria-hidden="true"></span> Tambah Pengeluaran
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
                            <input type="text" name="nama_pengeluaran[]" min="0" class="form-control" placeholder="Nama Pengeluaran"/>
                            <input type="number" name="biaya_pengeluaran[]" min="0" class="form-control" placeholder="Biaya Pengeluaran"/>
                            <div class="input-group-addon">
                                <a href="javascript:void(0)" class="btn btn-danger remove">
                                    <span class="fas fas fa-minus" aria-hidden="true"></span> Hapus Pengeluaran
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Cetak Laporan Fasilitas -->
                    <a class="btn btn-success" href="laporan_wisata.php?type=pengeluaran">
                    <i class="fas fa-file-excel"></i> Cetak Laporan Pengeluaran</a>

                    <!-- Select Data Reservasi Untuk Laporan Pengeluaran Berdasarkan ID -->
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">ID Pengeluaran</th>
                                <th scope="col">ID Reservasi</th>
                                <th scope="col">Nama Pengeluaran</th>
                                <th scope="col">Biaya Pengeluaran</th>
                                <th scope="col">Update Terakhir</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sum = 0;

                            foreach ($rowPengeluaran as $pengeluaran) { 
                            $truedate = strtotime($pengeluaran->update_terakhir);
                            
                            $sum+= $pengeluaran->biaya_pengeluaran;
                            // var_dump($sum);
                            ?>
                            <tr>
                                <th scope="row"><?=$pengeluaran->id_pengeluaran?></th>
                                <td><?=$pengeluaran->id_reservasi?></td>
                                <td><?=$pengeluaran->nama_pengeluaran?></td>
                                <td>Rp. <?=number_format($pengeluaran->biaya_pengeluaran, 0)?></td>
                                <td>
                                    <small class="text-muted"><b>Update Terakhir</b>
                                    <br><?=strftime('%A, %d %B %Y', $truedate).'<br> ('.ageCalculator($pengeluaran->update_terakhir).' yang lalu)';?></small>
                                </td>
                                <td>
                                    <a href="hapus.php?type=pengeluaran&id_pengeluaran=<?=$pengeluaran->id_pengeluaran?>&id_reservasi=<?=$id_reservasi?>" class="far fa-trash-alt btn btn-act"></a>
                                </td>
                            </tr>
                            <?php } ?>

                            <!-- Hasil -->
                            <?php 
                            $total_paket = $reservasi->total; // get data dari DB t_reservasi_wisata
                            $total_saldo = $total_paket - $sum;
                            ?>
                            <tr>
                                <th scope="row" colspan="5" style="text-align: right;">Biaya Awal:</th>
                                <td>Rp. <?=number_format($reservasi->total, 0)?></td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="5" style="text-align: right;">Total Biaya Pengeluaran:</th>
                                <td>Rp. <?=number_format($sum, 0)?></td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="5" style="text-align: right;">Sisa Biaya Awal:</th>
                                <td>Rp. <?=number_format($total_saldo, 0)?></td>
                            </tr>
                        </tbody>
                    </table>

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
                alert('Maksimal '+maxGroup+' Data Pengeluaran wisata yang boleh dibuat.');
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
