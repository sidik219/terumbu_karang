<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if(isset($_GET['awal'])){
        $awal      = (int) $_GET['awal'];
        $akhir     = (int) $_GET['akhir'];
        if($akhir < $awal){
          $temp = $awal;
          $awal = $akhir;
          $akhir = $temp;
        }

        $limit_hasil = ' ';
    }
    else{
        $awal      = (int) " 1 ";
        $akhir     = (int) " 9999 ";
        $limit_hasil = ' LIMIT 3 ';
    }

    $filter_wilayah = ' ';
    if($level_user == 2){
        $id_wilayah = $_SESSION['id_wilayah_dikelola'];

        $filter_wilayah = ' AND t_wilayah.id_wilayah = '.$id_wilayah; //
    }

    $query_periode = ' tahun_arsip_lokasi BETWEEN :awal AND :akhir ';
    $query_periode_and = ' AND tahun_arsip_lokasi BETWEEN :awal AND :akhir ';

$sqlwilayah = 'SELECT * FROM t_wilayah WHERE id_wilayah = '.$_SESSION['id_wilayah_dikelola'];
$stmt = $pdo->prepare($sqlwilayah);
$stmt->execute();
$wilayah = $stmt->fetch();

$sqltahun = 'SELECT * FROM t_arsip_lokasi GROUP BY tahun_arsip_lokasi ORDER BY tahun_arsip_lokasi ASC';
$stmt = $pdo->prepare($sqltahun);
$stmt->execute();
$rowtahunsemua = $stmt->fetchAll();

$sqlviewarsip = 'SELECT * FROM t_arsip_lokasi WHERE '.$query_periode.' GROUP BY tahun_arsip_lokasi ORDER BY tahun_arsip_lokasi ASC '.$limit_hasil;
$stmt = $pdo->prepare($sqlviewarsip);
$stmt->execute(['awal' => $awal, 'akhir' => $akhir]);
$rowtahun = $stmt->fetchAll();




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laporan Sebaran - GoKarang</title>
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
        <div id="clientPrintContent" class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                <div class="row">
                        <div class="col">
                            <!-- <h4><span class="align-middle font-weight-bold">Laporan Wilayah Baru</span></h4> -->
                            <div id="datalaporan">
                        <!-- <div class="row">
                            <div class="col-auto">
                                <span class="text-bold">Tanggal Laporan :</span>
                            </div>
                            <div class="col">
                                <?= strftime("%A, %d %B %Y");?>
                            </div>
                        </div> -->
                </div>
                <form method="GET" action="" enctype="multipart/form-data" name="filter_tahun">
                <div class="row capture-hide">
                  <div class="col">
                    Periode :
                    <select  class="form-select" name="awal" id="awal">
                      <?php
                        foreach($rowtahunsemua as $tahun){
                      ?>
                        <option <?=$awal == $tahun->tahun_arsip_lokasi ? 'selected' : ' ' ?> value="<?=$tahun->tahun_arsip_lokasi?>"><?=$tahun->tahun_arsip_lokasi?></option>
                      <?php } ?>
                    </select>
                          -
                    <select  class="form-select" name="akhir" id="akhir">
                      <?php
                        foreach($rowtahunsemua as $tahun){
                      ?>
                        <option <?=$akhir == $tahun->tahun_arsip_lokasi ? 'selected' : ' ' ?> value="<?=$tahun->tahun_arsip_lokasi?>"><?=$tahun->tahun_arsip_lokasi?></option>
                      <?php } ?>
                    </select>
                    <button type="submit" name="submit" value="filter_tahun" class="btn btn-sm btn-info">Terapkan</button></p>
                  </div>
                </form>
              </div>

      </div>
                        <div id="btn-unduh" class="col">

                        <!-- <a class="btn btn-primary float-right" onclick="saveCSVs()" href="#" role="button"><i class="fas fa-file-excel"></i> Unduh Laporan (CSV)</a> -->

                        <a class="btn btn-primary float-right" target="_blank" href="generate_laporan_baru.php?type=generate_csv_laporan_wilayah"><i class="fas fa-file-excel"></i> Unduh Laporan (CSV)</a>

                        <a class="btn btn-primary float-right  mr-2" onclick="savePDF()" href="#" role="button"><i class="fas fa-file-pdf"></i> Unduh Laporan (PDF)</a>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <table class="table table-striped table-bordered DataWilayah">
                    <div class="row text-center">
                      <div class="col">
                          <h4 class="mb-0"><span class="align-middle font-weight-bold mb-0">Laporan Luas Sebaran Terumbu Karang<br><?= $wilayah->nama_wilayah ?></span></h4>
                          <h5 class="mt-0 font-weight-bold"><?= $awal != 1 ? ' Tahun '.$awal : ''; ?><?= $awal != $akhir && $akhir != 9999 ? ' - '.$akhir : ''; ?></h5>
                          <span class="align-middle mt-2">*Data dalam satuan hektar (ha)</span>
                      </div>
                    </div>

                    <thead>
                            <tr>
                                <th class="text-center align-middle" rowspan="2" scope="col">Lokasi</th>


                                <?php //Print header tabel


                                foreach($rowtahun as $tahun){
                                ?>
                                <th class="text-center" colspan="4" scope="col"><?=$tahun->tahun_arsip_lokasi?></th>

                                <?php }  ?>

                              <tr>

                              <?php
                              foreach($rowtahun as $tahun){
                                ?>
                                <th class="text-center" scope="col">Jumlah Titik</th><th class="text-center" scope="col">Luas Sebaran</th><th class="text-center" scope="col">Luas Total</th> <th class="text-center" scope="col">Persentase Sebaran</th>
                                <?php } ?>
                              </tr>
                            </tr>

                      </thead>



                <tbody class="table-hover">
                <?php

                    $sqlviewluasnama = 'SELECT * FROM t_wilayah
                                    LEFT JOIN t_arsip_lokasi ON t_wilayah.id_wilayah = t_arsip_lokasi.id_wilayah 
                                    LEFT JOIN t_lokasi ON t_lokasi.id_wilayah = t_wilayah.id_wilayah
                                    WHERE  '.$query_periode.' '.$filter_wilayah. ' 
                                     ORDER BY tahun_arsip_lokasi ASC'.$limit_hasil;
                              $stmt = $pdo->prepare($sqlviewluasnama);
                              $stmt->execute(['awal' => $awal, 'akhir' => $akhir]);
                              $rowluasnama = $stmt->fetchAll();



                    foreach ($rowluasnama as $luasnama) {

                        $sqlviewluastahunan = 'SELECT * FROM t_wilayah
                                    LEFT JOIN t_lokasi ON t_lokasi.id_wilayah = t_wilayah.id_wilayah
                                    LEFT JOIN t_arsip_lokasi ON t_wilayah.id_wilayah = t_arsip_lokasi.id_wilayah
                                   WHERE t_wilayah.id_wilayah = :id_wilayah  '.$query_periode_and.' '.$filter_wilayah. ' AND t_lokasi.id_lokasi = '. $luasnama->id_lokasi.'  
                                   ORDER BY tahun_arsip_lokasi ASC'.$limit_hasil;
                              $stmt = $pdo->prepare($sqlviewluastahunan);
                              $stmt->execute(['id_wilayah' => $luasnama->id_wilayah, 'awal' => $awal, 'akhir' => $akhir]);
                              $rowluastahunan = $stmt->fetchAll();
                ?>
                        <tr>
                            <td><?=$luasnama->nama_lokasi?></td>

                            <?php foreach ($rowluastahunan as $luastahunan) {


                            ?>
                            <td class="text-center border"><?=$luastahunan->total_titik_l?></td> <td class="text-center border"><?=$luastahunan->total_luas_l?></td><td class="text-center border"><?=$luastahunan->luas_sebaran_l?></td><td class="text-center border"><?=$luastahunan->persentase_sebaran_l?></td>

                            <?php }?>



                        </tr>


                <?php }
                
                echo '<tr  class="table-active border-top">
                          <th>Total</th>

                      ';

                      foreach($rowtahun as $tahun){
                        $sqlhitungluas = 'SELECT id_wilayah, sum(total_titik_l) as total_kurang, sum(total_luas_l) as total_cukup, SUM(luas_sebaran_l) as total_baik, SUM(persentase_sebaran_l) as total_sangat_baik
                                        FROM t_arsip_lokasi WHERE tahun_arsip_lokasi = :tahun  '.$query_periode_and.' GROUP BY id_lokasi '.$limit_hasil;
                                $stmt = $pdo->prepare($sqlhitungluas);
                                $stmt->execute(['tahun' => $tahun->tahun_arsip_lokasi, 'awal' => $awal, 'akhir' => $akhir]);
                                $rowhitung = $stmt->fetchAll();
                              foreach($rowhitung as $hitungan){ ?>

                                <td class="text-center border"><?= $hitungan->total_kurang?> </td><td class="text-center border"><?= $hitungan->total_cukup?>
                                </td><td class="text-center border"><?= $hitungan->total_baik?> </td class="text-center border"><td class="text-center border"><?= $hitungan->total_sangat_baik?>%</td>

                              <?php }

                      }
                echo '</tr>';

                
                    if($_SESSION['level_user'] == 4){
                ?>
                <tr class="table-active border-top">
                    <th scope="row">Total Keseluruhan</th>

                    <?php
                        
                        $sqlhitungluas = 'SELECT id_wilayah, sum(total_titik_l) as total_kurang, sum(total_luas_l) as total_cukup, SUM(luas_sebaran_l) as total_baik, SUM(persentase_sebaran_l) as total_sangat_baik FROM t_arsip_lokasi  '.$query_periode.' tahun_arsip_lokasi '.$limit_hasil;
                                $stmt = $pdo->prepare($sqlhitungluas);
                                $stmt->execute(['awal' => $awal, 'akhir' => $akhir]);
                                $rowhitung = $stmt->fetchAll();
                              foreach($rowhitung as $hitungan){ ?>

                                <th class="text-center border"><?= $hitungan->total_kurang?> </th><th class="text-center border"><?= $hitungan->total_cukup?>
                                </th><th class="text-center border"><?= $hitungan->total_baik?> </th class="text-center border"><th class="text-center border"><?= $hitungan->total_sangat_baik?>%</th>

                              <?php 
                              }  
                            }


                    ?>




                </tr>
                </tbody>
                </table>

                <div class="row info-cetak text-center">
                    <div class="col float-right">
                        <?= isset($_SESSION['nama_wilayah_dikelola']) ? $_SESSION['nama_wilayah_dikelola'] : '' ?>
                        <br>
                        <?= strftime('%A, %e %B %Y', date(time())); ?>
                        <br>
                        <br>
                        <br>
                        <b><u><?= $_SESSION['nama_user'] ?></u></b>
                        <br>
                        <?= $_SESSION['organisasi_user'] ?>
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

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <script src="js/jspdf.min.js"></script>
    <script src="js/standard_fonts_metrics.js"></script>
    <script src="js/split_text_to_size.js"></script>
    <script src="js/from_html.js"></script>
    <script src="js/html2pdf.bundle.min.js"></script>

    <script>
        function savePDF(){
            $('#btn-unduh').css('left', '9999px')
            $('#clientPrintContent').css('background-color', 'white')
            $('.collapse').show()
            $('.main-sidebar').show()
            $('#clientPrintContent, .main-header, .navbar navbar-expand, .navbar-white, .navbar-light').css('margin-left', 0)

            $('.capture-hide').hide()

            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            var dateTime = date+'_'+time;

            var element = document.getElementById('clientPrintContent');
            var opt = {
            margin:       [1.5,2,2,2],
            filename:     `Laporan_Wilayah_GoKarang_${dateTime}.pdf`,

            image:        { type: 'jpeg', quality: 0.95 },
            html2canvas:  { scale: 3 },
            jsPDF:        { unit: 'cm', format: 'a2', orientation: 'landscape' }
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();

            setTimeout(function (){
            $('#btn-unduh').css('left', '0')
            }, 1000)

            setTimeout(function (){
            location.reload()
            }, 3000)


        }


    </script>

</body>
</html>
