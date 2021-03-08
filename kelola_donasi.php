<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  ORDER BY id_donasi DESC';
$stmt = $pdo->prepare($sqlviewdonasi);
$stmt->execute();
$row = $stmt->fetchAll();
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

                <div class="col">

                        <a class="btn btn-primary float-right" href="kelola_batch.php" role="button">Kelola Batch Penanaman<i class="nav-icon fas fa-boxes ml-1"></i></a>

                        </div>

                <?php
                if(!empty($_GET['status'])){
                  if($_GET['status'] == 'updatesuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Update data donasi berhasil!
                      </div>';}
                    }
                ?>

                <?php if($_SESSION['level_user'] == '3') { ?>
                   <table class="table table-striped">
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
                              <th scope="row"><?=$rowitem->id_donasi?></th>
                              <td>Rp. <?=number_format($rowitem->nominal, 0)?></td>
                              <td><?=strftime('%A, %d %B %Y', $donasidate);?></td>
                              <td><?=$rowitem->nama_status_donasi?> <br><small class="text-muted">Update Terakhir:
                                <br><?=strftime('%A, %d %B %Y', $truedate);?></small></td>
                              <td>
                                <button type="button" class="btn btn-act">
                                <a href="edit_donasi.php?id_donasi=<?=$rowitem->id_donasi?>" class="fas fa-edit"></a>
                            	</button>
                                <button type="button" class="btn btn-act"><i class="far fa-trash-alt"></i></button>
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
                                                <img class="" height="60px" src="<?=$isi->foto_terumbu_karang?>?<?php if ($status='nochange'){echo time();}?>">
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
                <?php } ?>
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
