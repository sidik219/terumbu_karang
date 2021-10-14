<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

if($level_user == 2){
  $id_wilayah = $_SESSION['id_wilayah_dikelola'];
  $extra_query = " AND t_lokasi.id_wilayah = $id_wilayah ";
  $extra_query_noand = " t_lokasi.id_wilayah = $id_wilayah ";

  $join_wilayah = " LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah ";
}
else if($level_user == 3){
  $id_lokasi = $_SESSION['id_lokasi_dikelola'];
  $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
  $extra_query_noand = " t_lokasi.id_lokasi = $id_lokasi ";

  $join_wilayah = " ";
}
else if($level_user == 4){
  $extra_query = " ";
  $extra_query_noand = " ";
  $join_wilayah = "  ";
}

$sqlviewkegiatan = 'SELECT * FROM t_berita_kegiatan ORDER BY id_kegiatan DESC';

$stmt = $pdo->prepare($sqlviewkegiatan);
$stmt->execute();
$rowKegiatan = $stmt->fetchAll();

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
    <title>Kelola Konten - GoKarang</title>
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
                <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Kelola Berita Kegiatan</span></h4>
                        </div>
                        <div class="col">
                            <a class="btn btn-primary float-right" href="input_konten_kegiatan.php" role="button">Input Data Baru (+)</a>
                        </div>
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
                                    Data Kegiatan berhasil diupdate!
                                    </div>';
                        } else if ($_GET['status'] == 'addsuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                    Data Kegiatan berhasil ditambahkan!
                                    </div>';
                        } else if ($_GET['status'] == 'deletesuccess') {
                            echo '<div class="alert alert-success" role="alert">
                                    Data Kegiatan berhasil dihapus!
                                    </div>';
                        }
                    }
                    ?>
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">ID Kegiatan</th>
                                <th scope="col">Judul Kegiatan</th>
                                <th scope="col">Tanggal Kegiatan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rowKegiatan as $kegiatan) { 
                            $reservasidate = strtotime($kegiatan->tgl_kegiatan);?>
                            <tr>
                                <th scope="row"><?=$kegiatan->id_kegiatan?></th>
                                <td><?=$kegiatan->judul_kegiatan?></td>
                                <td>
                                    <?= strftime('%A, %d %B %Y', $reservasidate); ?>
                                </td>
                                <td>
                                    <a href="edit_konten_kegiatan.php?id_kegiatan=<?=$kegiatan->id_kegiatan?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                    <a  onclick="return konfirmasiHapusKegiatan(event)"
                                        href="hapus.php?type=berita_kegiatan&id_kegiatan=<?=$kegiatan->id_kegiatan?>" 
                                        class="far fa-trash-alt btn btn-act"></a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                <!--collapse start -->
                                <div class="row  m-0">
                                    <table>
                                    <div class="col-12 cell detailcollapser<?=$kegiatan->id_kegiatan?>"
                                        data-toggle="collapse"
                                        data-target=".cell<?=$kegiatan->id_kegiatan?>, .contentall<?=$kegiatan->id_kegiatan?>">
                                        <p class="fielddetail<?=$kegiatan->id_kegiatan?> btn btn-act">
                                            <i class="icon fas fa-chevron-down"></i>
                                            Rincian Kegiatan</p>
                                    </div>

                                    <!-- Data Untuk Wisata -->
                                    <div class="col-12 cell<?=$kegiatan->id_kegiatan?> collapse contentall<?=$kegiatan->id_kegiatan?> border rounded shadow-sm p-3">
                                        <!-- paket -->
                                        <div class="row  mb-3">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Deskripsi Kegiatan
                                            </div>
                                            
                                            <div class="col isi">
                                                <?=$kegiatan->deskripsi_kegiatan?>
                                            </div>
                                        </div>

                                        <div class="row  mb-3">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Foto Kegiatan
                                            </div>
                                            
                                            <div class="col isi">
                                                <img src="<?=$kegiatan->foto_kegiatan?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
                                            </div>
                                        </div>
                                    </div>
                                    </table>

                                </div>
                                <!--collapse end -->
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
    <!-- Konfirmasi Hapus -->
    <script>
        function konfirmasiHapusKegiatan(event){
        jawab = true
        jawab = confirm('Yakin ingin menghapus? Data Kegiatan akan hilang permanen!')

        if (jawab){
            // alert('Lanjut.')
            return true
        }
        else{
            event.preventDefault()
            return false

        }
    }
    </script>
</div>

</body>
</html>