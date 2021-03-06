<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewwilayah = 'SELECT *, SUM(luas_titik) AS total_titik,
                    COUNT(t_titik.id_titik) AS jumlah_titik,
                    SUM(t_lokasi.luas_lokasi) / (SELECT COUNT(t_titik.id_titik) GROUP BY t_titik.id_titik) AS total_lokasi,
                    (SUM(t_titik.luas_titik) / (SUM(t_lokasi.luas_lokasi) / (SELECT COUNT(t_titik.id_titik) GROUP BY t_titik.id_titik))) * 100 AS persentase_sebaran

                    FROM t_titik, t_lokasi, t_wilayah
					          WHERE t_titik.id_lokasi = t_lokasi.id_lokasi
                    AND t_lokasi.id_wilayah = t_wilayah.id_wilayah
                    GROUP BY t_wilayah.id_wilayah
ORDER BY t_lokasi.id_wilayah ASC';

// 'SELECT *,
//             SUM(luas_titik) AS luas_total, COUNT(id_titik) AS jumlah_titik,

//             -- COUNT(case when kondisi_titik = "Kurang" then 1 else null end) as jumlah_kurang,
//             -- COUNT(case when kondisi_titik = "Cukup" then 1 else null end) as jumlah_cukup,
//             -- COUNT(case when kondisi_titik = "Baik" then 1 else null end) as jumlah_baik,
//             -- COUNT(case when kondisi_titik = "Sangat Baik" then 1 else null end) as jumlah_sangat_baik

//             FROM t_wilayah
//             LEFT JOIN t_titik ON t_wilayah.id_wilayah = t_titik.id_wilayah
//             LEFT JOIN t_lokasi ON t_wilayah.id_wilayah = t_lokasi.id_wilayah
//             GROUP BY nama_wilayah';

$stmt = $pdo->prepare($sqlviewwilayah);
$stmt->execute();
$rowwilayah = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Laporan - TKJB</title>
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</a>
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
        <div id="clientPrintContent" class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Laporan Wilayah</span></h4>
                            <div id="datalaporan">
                        <div class="row">
                            <div class="col-auto">
                                <span class="text-bold">Tanggal Laporan :</span>
                            </div>
                            <div class="col">
                                <?= strftime("%A, %d %B %Y");?>
                            </div>
                        </div>
                </div>
                        </div>
                        <div id="btn-unduh" class="col">

                        <!-- <a class="btn btn-primary float-right" onclick="saveCSVs()" href="#" role="button"><i class="fas fa-file-excel"></i> Unduh Laporan (CSV)</a> -->

                        <a class="btn btn-primary float-right" target="_blank" href="generate_laporan.php?type=generate_csv_laporan_wilayah"><i class="fas fa-file-excel"></i> Unduh Laporan (CSV)</a>

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

                <!-- <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID Laporan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        </thead>
                <tbody>
                        <tr>
                            <th scope="row">1212</th>
                            <td>
                            <button type="button" class="btn btn-act">
                            <a href="edit_laporan.php" class="fas fa-edit"></a>
                            </button>
                            <button type="button" class="btn btn-act"><i class="far fa-trash-alt"></i></button>
                            </td>
                        </tr>
                </tbody>
                </table>   -->

                <?php if($_SESSION['level_user'] == '2') { ?>
                    <table class="table table-striped DataWilayah">

                <tbody>
                <?php
                    foreach ($rowwilayah as $rowitem) {
                        $total_luas_lokasi = 0;
                        $total_persentase_sebaran = 0;
                ?>
                        <tr>
                            <th scope="row" colspan="3"><?=$rowitem->nama_wilayah?></th>
                        </tr>
                        <tr>
                                <td colspan="3">
                                    <!--collapse start -->
                            <div class="row  m-0 d-flex flex-row-reverse">
                            <div class="col-12 cell detailcollapser<?=$rowitem->id_wilayah?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$rowitem->id_wilayah?>, .contentall<?=$rowitem->id_wilayah?>">
                                <p
                                    class="fielddetail<?=$rowitem->id_wilayah?>">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Wilayah</p>
                            </div>
                            <div class="col-12 cell<?=$rowitem->id_wilayah?> collapse contentall<?=$rowitem->id_wilayah?>">
                                <!-- <h5>Kondisi Lokasi</h5> -->
                                <table class="table">
                                    <!-- <th>Nama Lokasi</th>
                                    <th>Jumlah Titik</th>
                                    <th>Sebaran</th> -->
                                    <thead>
                                    <tr   class="bg-white border-top">
                                        <th scope="col">Nama Lokasi</th>
                                        <th scope="col">Jumlah Titik</th>
                                        <th scope="col">Luas Sebaran / Luas Total</th>
                                        <th scope="col">Persentase Sebaran</th>
                                    </tr>
                                    </thead>
                                <?php
                                  $sql_lokasi = 'SELECT *, SUM(luas_titik) AS total_titik,
                                    COUNT(id_titik) AS jumlah_titik,
                                    SUM(luas_lokasi)  / COUNT(id_titik) AS total_lokasi,
                                    (SUM(t_titik.luas_titik) / (SUM(t_lokasi.luas_lokasi) / COUNT(t_titik.id_titik)) ) * 100 AS persentase_sebaran

                                    FROM `t_titik`, t_lokasi, t_wilayah
                                    WHERE t_titik.id_lokasi = t_lokasi.id_lokasi
                                    AND t_lokasi.id_wilayah = t_wilayah.id_wilayah
                                    AND t_lokasi.id_wilayah = '.$rowitem->id_wilayah.'
                                    GROUP BY t_lokasi.id_lokasi
                                    ORDER BY persentase_sebaran DESC';

                                    $stmt = $pdo->prepare($sql_lokasi);
                                    $stmt->execute();
                                    $rowlokasi = $stmt->fetchAll();

                                    $kurang = 0; $cukup=0; $baik=0; $sangat_baik=0;
                                    $kurang_luas = 0; $cukup_luas = 0; $baik_luas = 0; $sangat_baik_luas = 0;

                                    foreach($rowlokasi as $lokasi) {
                                    $ps = $lokasi->persentase_sebaran;
                                    if($ps >= 0 && $ps < 25){
                                    $kondisi_lokasi = 'Kurang';
                                    $kurang_luas += $lokasi->total_titik;
                                    }
                                    else if($ps >= 25 && $ps < 50){
                                    $kondisi_lokasi = 'Cukup';
                                    $cukup_luas += $lokasi->total_titik;
                                    }
                                    else if($ps >= 50 && $ps < 75){
                                    $kondisi_lokasi = 'Baik';
                                    $baik_luas += $lokasi->total_titik;
                                    }
                                    else{
                                    $kondisi_lokasi = 'Sangat Baik';
                                    $sangat_baik_luas += $lokasi->total_titik;
                                    }?>


                                    <tr>
                                        <td><?=$lokasi->nama_lokasi?></td>
                                        <td><?=$lokasi->jumlah_titik?></td>
                                        <td><?=number_format($lokasi->total_titik).' / '.number_format($lokasi->total_lokasi).' m<sup>2</sup>'?></td>
                                        <td><?=number_format($lokasi->persentase_sebaran, 1).'% ( '.$kondisi_lokasi.' )'?></td>
                                    </tr>




                    <?php
                    $total_luas_lokasi += $lokasi->total_lokasi;
                    $total_persentase_sebaran += $lokasi->persentase_sebaran ;

                } //lokasi loop end

                $ps = number_format($rowitem->total_titik / $total_luas_lokasi * 100, 1);
                      if($ps >= 0 && $ps < 25){
                        $kondisi_wilayah = 'Kurang';
                      }
                      else if($ps >= 25 && $ps < 50){
                        $kondisi_wilayah = 'Cukup';
                      }
                      else if($ps >= 50 && $ps < 75){
                        $kondisi_wilayah = 'Baik';
                      }
                      else{
                        $kondisi_wilayah = 'Sangat Baik';
                      }

                ?>

                                    <tr class="table-active border-top">
                            <th scope="row">Total</th>
                            <th><?=$rowitem->jumlah_titik?></th>
                            <th><?=number_format($rowitem->total_titik).' / '.number_format($total_luas_lokasi).' m<sup>2</sup>'?></th>
                            <th><?=$ps.'% ( '.$kondisi_wilayah.' )'?></th>
                        </tr>
                        </table>

                        <hr/>
<div class="row mb-4 ml-1 break-after">
                                        <div class="col-sm">
                                            <span class="mr-4"><b>Kurang :</b> <?=number_format($kurang_luas) .' m<sup>2</sup>'?></span>
                                        </div>
                                        <div class="col-sm">
                                            <span class="mr-4"><b>Cukup :</b> <?=number_format($cukup_luas) .' m<sup>2</sup>'?></span>
                                        </div>
                                        <div class="col-sm">
                                            <span class="mr-4"><b>Baik :</b> <?=number_format($baik_luas) .' m<sup>2</sup>'?></span>
                                        </div>
                                        <div class="col-sm">
                                            <span class="mr-4"><b>Sangat Baik :</b> <?=number_format($sangat_baik_luas) .' m<sup>2</sup>'?></span>
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
            <?php } ?>

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

            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            var dateTime = date+'_'+time;

            var element = document.getElementById('clientPrintContent');
            var opt = {
            margin:       [1.5,2,2,2],
            filename:     `Laporan_Wilayah_TKJB_${dateTime}.pdf`,
            pagebreak: { mode: 'avoid-all', after: '.break-after' },
            image:        { type: 'jpeg', quality: 0.95 },
            html2canvas:  { scale: 3 },
            jsPDF:        { unit: 'cm', format: 'a4', orientation: 'landscape' }
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
