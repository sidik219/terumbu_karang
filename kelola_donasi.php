<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

if($level_user == 2){
  $id_wilayah = $_SESSION['id_wilayah_dikelola'];
  $extra_query = " AND t_lokasi.id_wilayah = $id_wilayah ";
  $extra_query_noand = " t_lokasi.id_wilayah = $id_wilayah ";
}
else if($level_user == 3){
  $id_lokasi = $_SESSION['id_lokasi_dikelola'];
  $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
  $extra_query_noand = " t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level_user == 4){
  $extra_query = "  ";
  $extra_query_noand = " 1 ";
}

if(isset($_GET['id_status_donasi'])){
  $id_status_donasi =$_GET['id_status_donasi'];

  if($id_status_donasi == 1){ //donasi baru
    $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  WHERE t_donasi.id_status_donasi = 1  '.$extra_query.'
                  ORDER BY id_donasi DESC';
  }
  elseif($id_status_donasi == 2){ //donasi butuh verifikasi
    $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  WHERE t_donasi.id_status_donasi = 2  '.$extra_query.'
                  ORDER BY id_donasi DESC';
  }
  elseif($id_status_donasi == 6){ //donasi bermasalah
    $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  WHERE t_donasi.id_status_donasi = 6  '.$extra_query.'
                  ORDER BY id_donasi DESC';
  }
  $stmt = $pdo->prepare($sqlviewdonasi);
  $stmt->execute();
  $row = $stmt->fetchAll();
}
elseif(isset($_GET['id_batch'])){ //donasi belum masuk batch
  $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  WHERE t_donasi.id_batch IS NULL AND t_donasi.id_status_donasi = 3  '.$extra_query.'
                  ORDER BY id_donasi DESC';

  $stmt = $pdo->prepare($sqlviewdonasi);
  $stmt->execute();
  $row = $stmt->fetchAll();
}else{ //umum
  $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi WHERE  '.$extra_query_noand.'
                  ORDER BY id_donasi DESC';

  $stmt = $pdo->prepare($sqlviewdonasi);
  $stmt->execute();
  $row = $stmt->fetchAll();
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
    <title>Kelola Donasi - Terumbu Karang</title>
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
                            <h4><span class="align-middle font-weight-bold">Kelola Donasi</span></h4>
                        </div>
                        <!-- <div class="col">

                        <a class="btn btn-primary float-right" href="input_donasi.php" role="button">Input Data Baru (+)</a>

                        </div> -->
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
                          Update data donasi berhasil!
                      </div>';}
                    }
                ?>

                <div class="row <?php if(!($_SESSION['level_user'] == 3)){echo " d-none ";} ?>">
                    <div class="col text-sm-center">
                        <a class="btn btn-primary float-md-right" href="kelola_batch.php" role="button">Kelola Batch Penanaman<i class="nav-icon fas fa-boxes ml-1"></i></a>
                    </div>
                </div>


                <div class="row">
                      <div class="col">
                        <div class="dropdown show">
                          <a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pilih Kategori
                          </a>

                          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="kelola_donasi.php">Tampilkan Semua</a>
                            <a class="dropdown-item" href="kelola_donasi.php?id_status_donasi=1">Donasi Baru</a>
                            <a class="dropdown-item" href="kelola_donasi.php?id_status_donasi=2">Perlu Verifikasi</a>
                            <a class="dropdown-item" href="kelola_donasi.php?id_batch=isnull">Belum Masuk Batch</a>
                            <a class="dropdown-item" href="kelola_donasi.php?id_status_donasi=6">Bermasalah</a>
                        </div>
                    </div>
                      </div>
                </div>


                   <table class="table table-striped table-responsive-sm">
                     <thead>
                            <tr>
                                <th scope="col">ID Donasi</th>
                                <!-- <th scope="col">ID User</th> -->
                                <th scope="col">Nominal</th>
                                <!-- <th scope="col">Bukti Donasi</th> -->
                                <th scope="col">Tanggal Donasi</th>
                                <th scope="col">Status Donasi</th>
                                <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                    <tbody>
                        <?php
                          foreach ($row as $rowitem) {
                            $truedate = strtotime($rowitem->update_terakhir);
                            $donasidate = strtotime($rowitem->tanggal_donasi);
                          ?>
                          <tr>
                              <th scope="row"><?=$rowitem->id_donasi?>
                                  <?php echo empty($rowitem->id_batch) ? '' : '<br><span class="badge badge-pill badge-info mr-2"> ID Batch '.$rowitem->id_batch.'</span>';?>
                              </th>
                              <td>Rp. <?=number_format($rowitem->nominal, 0)?></td>
                              <td><?=strftime('%A, %d %B %Y', $donasidate);?></td>
                              <td><?=$rowitem->nama_status_donasi?> <br><small class="text-muted">Update Terakhir:
                                <br><?=strftime('%A, %d %B %Y', $truedate);?>
                                <br>(<?=ageCalculator($rowitem->update_terakhir).' yang lalu'?>)

                              </small></td>
                              <td>
                                <button type="button" class="btn btn-act <?php if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){echo " d-none ";} ?>">
                                <a href="edit_donasi.php?id_donasi=<?=$rowitem->id_donasi?>" class="fas fa-edit"></a>
                            	</button>
                                <button type="button" class="btn btn-act <?php if(!($_SESSION['level_user'] == 4)){echo " d-none ";} ?>"><i class="far fa-trash-alt"></i></button>
                              </td>

                          </tr>

                          <tr>
                                <td colspan="5">
                                    <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$rowitem->id_donasi?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$rowitem->id_donasi?>, .contentall<?=$rowitem->id_donasi?>">
                                <p
                                    class="fielddetail<?=$rowitem->id_donasi?> btn btn-act">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Donasi</p>
                            </div>
                            <div class="col-12 cell<?=$rowitem->id_donasi?> collapse contentall<?=$rowitem->id_donasi?>    border rounded shadow-sm p-3">
                            <div class="row mb-3  border-bottom">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Nama Donatur
                                    </div>
                                    <div class="col isi">
                                        <?php
                                            echo $rowitem->nama_donatur;
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3 border-bottom">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Nomor Rekening Donatur
                                    </div>
                                    <div class="col isi">
                                        <?php
                                            echo $rowitem->nomor_rekening_donatur;
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-2 border-bottom">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Bank Donatur
                                    </div>
                                    <div class="col isi">
                                        <?php
                                            echo $rowitem->bank_donatur;
                                        ?>
                                    </div>
                                </div>
                              <div class="row mb-2 border-bottom">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Lokasi Penanaman
                                    </div>
                                    <div class="col isi">
                                        <?="$rowitem->nama_lokasi (ID $rowitem->id_lokasi)";?>
                                    </div>
                                </div>
                                <div class="row mb-3 border-bottom">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Pesan/Ekspresi
                                    </div>
                                    <div class="col isi">
                                        <?php
                                            echo $rowitem->pesan;
                                        ?>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Pilihan
                                    </div>
                                    <div class="col isi">
                                        <?php
                                              $sqlviewisi = 'SELECT jumlah_terumbu, nama_terumbu_karang, foto_terumbu_karang FROM t_detail_donasi
                                              LEFT JOIN t_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
                                              LEFT JOIN t_terumbu_karang ON t_detail_donasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                                              WHERE t_detail_donasi.id_donasi = :id_donasi';
                                              $stmt = $pdo->prepare($sqlviewisi);
                                              $stmt->execute(['id_donasi' => $rowitem->id_donasi]);
                                              $rowisi = $stmt->fetchAll();
                                           foreach ($rowisi as $isi){
                                             ?>
                                             <div class="row  mb-3">
                                               <div class="col">
                                                <img class="rounded" height="60px" src="<?=$isi->foto_terumbu_karang?>?<?php if ($status='nochange'){echo time();}?>">
                                              </div>
                                              <div class="col">
                                                <span><?= $isi->nama_terumbu_karang?>
                                              </div>
                                              <div class="col">
                                                x<?= $isi->jumlah_terumbu?></span><br/>
                                              </div>

                                             <hr class="mb-2"/>
                                             </div>

                                        <?php   }
                                        ?>
                                    </div>
                                </div>

                                <!-- <div class="row  mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Foto Wilayah
                                    </div>
                                    <div class="col isi">
                                        <img src="<?=$rowitem->foto_wilayah?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
                                    </div>
                                </div> -->

                            </div>
                        </div>

                        <!--collapse end -->
                                </td>
                            </tr>
                            <?php //$index++;
                            } ?>
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

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>
</html>
