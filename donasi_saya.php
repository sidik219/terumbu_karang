<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_user = $_SESSION['id_user'];
$sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  LEFT JOIN t_batch ON t_batch.id_batch = t_donasi.id_batch
                WHERE id_user = :id_user
                ORDER BY id_donasi DESC';
$stmt = $pdo->prepare($sqlviewdonasi);
$stmt->execute(['id_user' => $id_user]);
$row = $stmt->fetchAll();


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
    <title>Donasi Saya - GoKarang</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
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
            <a href="dashboard_user.php" class="brand-link">
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
                            <h4><span class="align-middle font-weight-bold">Donasi Saya</span></h4>
                        </div>

                        <div class="col">

                        <a class="btn btn-primary float-right" href="map.php" role="button">Donasi Sekarang (+)</a>

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
                          Update bukti pembayaran donasi berhasil!
                      </div>';}
                      else if($_GET['status'] == 'addsuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Donasi berhasil dibuat! Harap upload bukti pembayaran agar donasi diproses pengelola
                      </div>';}
                    }

                ?>

                    <div>

                        <?php
                          foreach ($row as $rowitem) {
                            $truedate = strtotime($rowitem->update_terakhir);
                            $donasidate = strtotime($rowitem->tanggal_donasi);
                          ?>
                            <div class="blue-container border rounded shadow-sm mb-4 p-4">
                                <div class="row"><!-- First row -->

                                  <div class="col-12 mb-3">
                                      <span class="badge badge-pill badge-primary mr-2"> ID Donasi <?=$rowitem->id_donasi?> </span>
                                      <?php echo empty($rowitem->id_batch) ? '' : '<span class="badge badge-pill badge-info mr-2"> ID Batch '.$rowitem->id_batch.'</span>';?>

                                  </div>

                                    <div class="col-md mb-3">
                                      <div class="mb-2">
                                          <span class="font-weight-bold"><i class="nav-icon text-success fas fas fa-money-bill-wave"></i> Nominal</span>
                                          <br>
                                          <span class="mb-3">Rp. <?=number_format($rowitem->nominal, 0)?></span>
                                      </div>
                                      <div class="mb-3">
                                          <span class="font-weight-bold"><i class="nav-icon text-secondary fas fas fa-calendar-alt"></i> Tanggal Donasi</span>
                                          <br>
                                          <?=strftime('%A, %d %B %Y', $donasidate);?>
                                      </div>



                                  </div>


                                    <div class="col-md mb-3">
                                      <div class="mb-2">
                                          <span class="font-weight-bold"><i class="nav-icon text-info fas fas fa-comment-dots"></i> Pesan/Ekspresi</span>
                                          <br><?=$rowitem->pesan?><br>
                                      </div>
                                      <div class="mb-3">
                                          <span class="font-weight-bold"><i class="nav-icon text-warning fas fas fa-list-alt"></i> Status</span>
                                          <br><?=$rowitem->nama_status_donasi?>

                                            <?php echo ($rowitem->id_status_donasi <= 2 || $rowitem->id_status_donasi == 6) ? '<a href="edit_donasi_saya.php?id_donasi='.$rowitem->id_donasi.'" class="btn btn-sm btn-primary userinfo"><i class="fas fa-file-invoice-dollar"></i> Upload Bukti Donasi</a>' : ''; ?>

                                          <br><small class="text-muted"><b>Update Terakhir</b>
                                          <br><?=strftime('%A, %d %B %Y', $truedate).'<br> ('.ageCalculator($rowitem->update_terakhir).' yang lalu)';?></small>

                                      </div>
                                        <div class="mb-3">
                                            <?php if ($rowitem->id_status_donasi == 3) { ?>
                                                <!-- Invoice Reservasi Wisata -->
                                                <a href="invoice_donasi.php?id_donasi=<?=$rowitem->id_donasi?>" class="btn btn-sm btn-primary userinfo">
                                                    <i class="fas fa-file-invoice"></i> Download Inovice Donasi Terumbu Karang</a>
                                            <?php } ?>
                                        </div>
                                  </div>


                                <div class="col-md mb-3">
                                      <span class="font-weight-bold"><i class="nav-icon text-danger fas fas fa-map-marker-alt"></i> Lokasi Penanaman</span><br>
                                      <img height='75px' class="rounded" src=<?=$rowitem->foto_lokasi;?>><br><br>
                                      <span class=""><?="$rowitem->nama_lokasi (ID $rowitem->id_lokasi)";?></span>
                                      <br><a target="_blank" href="http://maps.google.com/maps/search/?api=1&query=<?=$rowitem->latitude?>,<?=$rowitem->longitude?>&z=8"
                                                                                                                                      class="btn btn-act"><i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a>
                                  <div class="mt-2">
                                            <span class="font-weight-bold"><i class="nav-icon text-primary fas fas fa-phone"></i> Kontak Pengelola Lokasi</span>
                                            <br><?=$rowitem->kontak_lokasi?><br>
                                        </div>

                                                                                                                                    </div>



                            </div><!-- First Row -->


                          <p class=" btn btn-blue btn-primary" onclick="toggleDetail()">
                            <i class="icon fas fa-chevron-down"></i>
                            Daftar Terumbu Karang
                          </p>

                            <div class="detail-toggle" id="main-toggle">
                              <?php
                                                $id_donasi = $rowitem->id_donasi;
                                                $sqlviewisi = 'SELECT * FROM t_detail_donasi
                                                LEFT JOIN t_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
                                                LEFT JOIN t_terumbu_karang ON t_detail_donasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                                                WHERE t_detail_donasi.id_donasi = :id_donasi';
                                                $stmt = $pdo->prepare($sqlviewisi);
                                                $stmt->execute(['id_donasi' => $id_donasi]);
                                                $rowisi = $stmt->fetchAll();
                                            foreach ($rowisi as $isi){
                                              $sqlviewhistoryitems = 'SELECT * FROM t_history_pemeliharaan
                                                                      WHERE t_history_pemeliharaan.id_detail_donasi = :id_detail_donasi
                                                                      ORDER BY tanggal_pemeliharaan DESC
                                                                      ';

                                                $stmt = $pdo->prepare($sqlviewhistoryitems);
                                                $stmt->execute(['id_detail_donasi' => $isi->id_detail_donasi]);
                                                $rowhistory = $stmt->fetchAll();


                                                ?>
                                                <div class="row  mb-2 p-3 border rounded shadow-sm bg-light border subdetail"><!--DONASI CONTAINER START-->

                                                <div class="col-sm-12 col-md-auto mb-1">
                                                    <img class="rounded" height="40px" src="<?=$isi->foto_terumbu_karang?>?">
                                                </div>
                                                <div class="col-sm mb-1">
                                                    <span class="font-weight-bold">Jenis</span><br><span ><?= $isi->nama_terumbu_karang?></span>
                                                </div>
                                                <div class="col-8">
                                                    <span class="font-weight-bold">Jumlah</span><br><span><?= $isi->jumlah_terumbu?></span><br/>
                                                </div>

                                                <?php

                                                ?>


                                                <div class="daftarhistory col-12 mt-1">
                                                  <div class='' id='fototk<?=$isi->id_detail_donasi?>'>
                                                        <div>
                                                            <label for='image_uploads<?=$isi->id_detail_donasi?>'><i class="nav-icon fas fas fa-history"></i> History Pemeliharaan</label><span class="small text-muted"></span> <br>
                                                        </div>
                                                    </div>
                                                  <?php
                                                   $sqlviewhistoryitemdetail = 'SELECT * FROM t_history_pemeliharaan
                                                                              WHERE id_detail_donasi = :id_detail_donasi
                                                                              ORDER BY tanggal_pemeliharaan DESC
                                                                              LIMIT 1';

                                                        $stmt = $pdo->prepare($sqlviewhistoryitemdetail);
                                                        $stmt->execute(['id_detail_donasi' => $isi->id_detail_donasi]);
                                                        $rowhistory = $stmt->fetchAll();

                                                        if (empty($rowhistory)) {
                                                            echo '<span class="text-small text-muted">Belum tahap pemeliharaan</span>';
                                                        }
                                                    foreach ($rowhistory as $history){
                                                      $peliharadate =  strtotime($history->tanggal_pemeliharaan);
                                                  ?>


                                                    <div class="form-group border shadow-sm p-3 mb-2 bg-white">
                                                      <div class="row">
                                                          <div class="col">
                                                        <div class="col-12 mb-2">
                                                          <!-- <span class="badge badge-pill badge-success mr-2"> ID Pemeliharaan //$history->id_pemeliharaan</span> -->
                                                        </div>
                                                        <div class="col mb-2">
                                                          <span class="font-weight-bold"><i class="nav-icon text-pink fas fa-birthday-cake"></i> Umur Terumbu Karang </span>
                                                          <br> <span><?=ageCalculator($rowitem->tanggal_penanaman)?></span>
                                                        </div>
                                                        <div class="col mb-2">
                                                          <span class="font-weight-bold"><i class="nav-icon text-primary fas fas fa-calendar-alt"></i> Pemeliharaan Terkini</span>
                                                          <br> <span><?=strftime('%A, %d %B %Y', $peliharadate).' ('.ageCalculator($history->tanggal_pemeliharaan).' yang lalu)'?></span>
                                                        </div>
                                                        <div class="col">
                                                            <span class="font-weight-bold"><i class="nav-icon text-danger fas fas fa-heartbeat"></i> Kondisi</span>
                                                          <br> <?php echo empty($history->kondisi_terumbu) ? '<span class="text-small text-muted">Belum ada laporan</span>' : $history->kondisi_terumbu; ?>
                                                          </div>
                                                      </div>

                                                      <div class="col">
                                                                  <img class="preview-images rounded" id="preview<?=$isi->id_detail_donasi?>"  width="100px" src="#" alt="Preview Gambar"/>
                                                                  <img class="rounded" id="oldpic<?=$isi->id_detail_donasi?>" src="<?php echo empty($history->foto_pemeliharaan) ? '' : $history->foto_pemeliharaan?>" width="130px">
                                                                  <input type="hidden" name="oldpic[]" class="form-control" value="<?php echo empty($history->foto_pemeliharaan) ? '' : $history->foto_pemeliharaan ?>">
                                                                  <br>

                                                          </div>
                                                      </div>







                                                    </div>

                                                    <?php } ?>

                                                </div>  <!-- Daftarhisory End -->






                                                </div><!-- Batch box thing end -->



                                            <?php   }
                                            ?>
                                        </div>



                          </div> <!-- Main div -->



                            <?php //$index++;
                            } ?>

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

    <!-- Modal -->
   <div class="modal fade" id="buktiModal" role="dialog">
    <div class="modal-dialog modal-lg">
     <!-- Modal content-->
     <div class="modal-content  bg-light">
      <div class="modal-header">
        <h4 class="modal-title">Form Bukti Donasi</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
     </div>
    </div>
   </div>

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
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <script>
      $(document).ready(function() {
            $('.preview-images').hide()

            $('.detail-toggle').hide()


            });


      function toggleDetail(e){
        var e = event.target
        $(e).siblings('.detail-toggle').fadeToggle()
      }

    </script>

</body>
</html>
