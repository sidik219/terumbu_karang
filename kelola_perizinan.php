<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewperizinan = 'SELECT * FROM t_perizinan
                      LEFT JOIN t_lokasi ON t_perizinan.id_lokasi = t_lokasi.id_lokasi
                      LEFT JOIN t_status_perizinan ON t_perizinan.id_status_perizinan = t_status_perizinan.id_status_perizinan
                ORDER BY id_perizinan DESC';
$stmt = $pdo->prepare($sqlviewperizinan);
$stmt->execute();
$rowperizinan = $stmt->fetchAll();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Perizinan - TKJB</title>
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
                <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Kelola Perizinan</span></h4>
                        </div>
                        <div class="col">

                        <a class="btn btn-primary float-right" href="input_perizinan.php" role="button">Input Data Baru (+)</a>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

              <?php
                if(!empty($_GET['status'])){
                  if($_GET['status'] == 'updatesuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Update data berhasil
                      </div>';}
                      else if($_GET['status'] == 'addsuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Data baru berhasil ditambahkan
                      </div>';}
                      else if($_GET['status'] == 'deletesuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Data berhasil dihapus
                      </div>';
                    }
                  }
                ?>
                <table class="table table-striped table-responsive-sm">
                     <thead>
                            <tr>
                                <th scope="col">ID Perizinan</th>
                                <th scope="col">Judul Perizinan</th>
                                <th scope="col">Pemohon</th>
                                <th scope="col">ID Lokasi</th>
                                <th scope="col">Biaya Pergantian</th>
                                <th scope="col">Status Perizinan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                    <tbody>
                          <?php
                          foreach ($rowperizinan as $perizinan) {
                          ?>
                          <tr>
                              <th scope="row"><?=$perizinan->id_perizinan?></th>
                              <td><?=$perizinan->judul_perizinan?></td>
                              <td><?=$perizinan->perusahaan_pemohon?></td>
                              <td><?=$perizinan->id_lokasi?> - <?=$perizinan->nama_lokasi?></td>
                              <td>Rp. <?=number_format($perizinan->biaya_pergantian)?></td>
                              <td>
                              <?php
                                  if($perizinan->id_status_perizinan == 1){
                                    echo '<span class="status-pemeliharaan badge badge-warning">'.$perizinan->nama_status_perizinan.'</span>';
                                  }
                                  else if ($perizinan->id_status_perizinan == 2){
                                    echo '<span class="status-pemeliharaan badge badge-success">'.$perizinan->nama_status_perizinan.'</span>';
                                  }
                                  else if ($perizinan->id_status_perizinan == 3){
                                    echo '<span class="status-pemeliharaan badge badge-danger">'.$perizinan->nama_status_perizinan.'</span>';
                                  }
                                ?>
                              <td>
                              <a href="edit_perizinan.php?id_perizinan=<?=$perizinan->id_perizinan?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                <a href="hapus.php?type=perizinan&id_perizinan=<?=$perizinan->id_perizinan?>" class="far fa-trash-alt btn btn-act"></a>
                              </td>
                          </tr>
                          <tr>
                                <td colspan="7">
                                    <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$perizinan->id_perizinan?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$perizinan->id_perizinan?>, .contentall<?=$perizinan->id_perizinan?>">
                                <p
                                    class="fielddetail<?=$perizinan->id_perizinan?>  btn btn-act">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Perizinan</p>
                            </div>
                            <div class="col-12 cell<?=$perizinan->id_perizinan?> collapse contentall<?=$perizinan->id_perizinan?> border rounded shadow-sm p-3 bg-light">
                                <div class="row mb-2 border p-2 bg-white rounded shadow-sm">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Deskripsi Perizinan
                                    </div>
                                    <div class="col isi">
                                        <?=$perizinan->deskripsi_perizinan?>
                                    </div>
                                </div>
                                <div class="row  mb-2 border p-2 bg-white rounded shadow-sm">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Daftar Titik
                                    </div>
                                    <div class="col isi">
                                      <?php
                                            $sqlviewtitikperizinan = 'SELECT * FROM t_detail_perizinan
                                            LEFT JOIN t_titik ON t_detail_perizinan.id_titik = t_titik.id_titik
                                            WHERE id_perizinan = :id_perizinan
                                            ORDER BY id_perizinan DESC';
                                            $stmt = $pdo->prepare($sqlviewtitikperizinan);
                                            $stmt->execute(['id_perizinan' => $perizinan->id_perizinan]);
                                            $rowtitikperizinan = $stmt->fetchAll();
                                            $luas_total = 0;

                                            foreach($rowtitikperizinan as $titikperizinan){
                                              $luas_total += $titikperizinan->luas_titik;
                                            ?>
                                              <div class="row">
                                                <div class="col border-bottom p-1"><b>ID <?=$titikperizinan->id_titik?></b> <?=$titikperizinan->keterangan_titik?> <br>Luas : <?=$titikperizinan->luas_titik?> ha</div>
                                                <div class="col border-bottom p-1"><a target="_blank" href="http://maps.google.com/maps/search/?api=1&query=<?=$titikperizinan->latitude?>,<?=$titikperizinan->longitude?>&zoom=8"
                                                                                                                                      class="btn btn-act"><i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a> </div>
                                              </div>

                                           <?php } ?>
                                              <span class="font-weight-bold mt-2 border-top ">Luas Total : <?=$luas_total?>  ha</span>
                                    </div>
                                </div>

                                <div class="row mb-2 border p-2 bg-white rounded shadow-sm">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Dokumen Proposal
                                    </div>
                                    <div class="col isi">
                                        <?php
                                            $sqlviewdocperizinan = 'SELECT * FROM t_dokumen_perizinan
                                            WHERE id_perizinan = :id_perizinan
                                            ORDER BY id_perizinan DESC';
                                            $stmt = $pdo->prepare($sqlviewdocperizinan);
                                            $stmt->execute(['id_perizinan' => $perizinan->id_perizinan]);
                                            $rowdocperizinan = $stmt->fetchAll();

                                            foreach($rowdocperizinan as $docperizinan){
                                            ?>
                                              <div class="row">
                                                <div class="col border-bottom p-3"><?=$docperizinan->nama_dokumen_perizinan?></div>
                                                <div class="col border-bottom p-3"><a class="btn btn-blue btn-primary btn-small p-1" href='<?=$docperizinan->file_dokumen_perizinan?>'><i class="fas fa-download"></i> Unduh File</a> </div>
                                              </div>

                                           <?php } ?>
                                    </div>
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
        <strong>Copyright &copy; 2020 .</strong> Terumbu Karang Jawa Barat
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</body>
</html>
