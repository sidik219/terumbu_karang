<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
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

$sqlviewwisata = 'SELECT * FROM t_wisata
                  LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                  LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi '.$join_wilayah.'
                  WHERE  '.$extra_query_noand.'
                  ORDER BY id_wisata DESC';
$stmt = $pdo->prepare($sqlviewwisata);
$stmt->execute();
$row = $stmt->fetchAll();

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
                            <h4><span class="align-middle font-weight-bold">Kelola Wisata</span></h4>
                        </div>
                        <div class="col">

                        <a class="btn btn-primary float-right" href="kelola_fasilitas_wisata.php" role="button">Input Data Baru (+)</a>

                        </div>
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
                                        Update paket wisata berhasil!
                                        </div>'; }
                            else if($_GET['status'] == 'addsuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Data paket wisata berhasil ditambahkan!
                                        </div>'; }
                        }
                    ?>
                     <table class="table table-striped table-responsive-sm">
                     <thead>
                            <tr>
                            <th scope="col">ID Paket Wisata</th>
                            <th scope="col">ID Lokasi</th>
                            <th scope="col">Nama Paket Wisata</th>
                            <th scope="col">Deskripsi Wisata</th>
                            <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php foreach ($row as $rowitem) { ?>
                            <tr>
                              <th scope="row"><?=$rowitem->id_paket_wisata?></th>
                              <td><?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></td>
                              <td><?=$rowitem->nama_paket_wisata?></td>
                              <td><?=$rowitem->deskripsi_wisata?></td>
                              <td>
                                <a href="edit_wisata.php?id_wisata=<?=$rowitem->id_wisata?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                <a href="hapus.php?type=wisata&id_wisata=<?=$rowitem->id_wisata?>" class="far fa-trash-alt btn btn-act"></a>
                              </td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                <!--collapse start -->
                                <div class="row  m-0">
                                    <div class="col-12 cell detailcollapser<?=$rowitem->id_wisata?>"
                                        data-toggle="collapse"
                                        data-target=".cell<?=$rowitem->id_wisata?>, .contentall<?=$rowitem->id_wisata?>">
                                        <p class="fielddetail<?=$rowitem->id_wisata?> btn btn-act">
                                            <i class="icon fas fa-chevron-down"></i>
                                            Rincian Wisata</p>
                                    </div>
                                    <div class="col-12 cell<?=$rowitem->id_wisata?> collapse contentall<?=$rowitem->id_wisata?> border rounded shadow-sm p-3">

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Deskripsi Lengkap
                                        </div>
                                        <div class="col isi">
                                            <?=$rowitem->deskripsi_panjang_wisata?>
                                        </div>
                                    </div>

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Foto Wisata
                                        </div>
                                        <div class="col isi">
                                            <img src="<?=$rowitem->foto_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
                                        </div>
                                    </div>

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            wisata
                                        </div>
                                        <div class="col isi">
                                            <?=$rowitem->judul_wisata?>
                                        </div>
                                    </div>

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Biaya Wisata
                                        </div>
                                        <?php
                                        $sqlviewpaket = 'SELECT SUM(biaya_fasilitas) AS total_biaya_fasilitas, nama_fasilitas, biaya_fasilitas 
                                                            FROM tb_fasilitas_wisata 
                                                            LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                            LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                            WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                            AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                        $stmt = $pdo->prepare($sqlviewpaket);
                                        $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                        $rowfasilitas = $stmt->fetchAll();

                                        foreach ($rowfasilitas as $fasilitas) { ?>
                                        <div class="col isi">
                                            Rp. <?=number_format($fasilitas->total_biaya_fasilitas, 0)?>
                                        </div>
                                    </div>

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Fasilitas Wisata
                                        </div>
                                        <div class="col isi">
                                            <?php
                                            $sqlviewpaket = 'SELECT * FROM tb_fasilitas_wisata 
                                                                LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                                LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                                WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                                AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlviewpaket);
                                            $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                            $rowfasilitas = $stmt->fetchAll();

                                            foreach ($rowfasilitas as $fasilitas) { ?>
                                            <i class="text-info fas fa-arrow-circle-right"></i>
                                            <?=$fasilitas->nama_fasilitas?><br>
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row  mb-3">
                                        <div class="col-md-3 kolom font-weight-bold">
                                            Status
                                        </div>
                                        <div class="col isi">
                                            <?=$rowitem->status_aktif?>
                                        </div>
                                    </div>

                                </div>
                                <!--collapse end -->
                                </td>
                            </tr>
                          <?php } ?>
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
