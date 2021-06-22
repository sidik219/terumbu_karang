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
    <title>Kelola Laporan Baru - GoKarang</title>
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
                            <h4><span class="align-middle font-weight-bold">Laporan Wilayah Baru</span></h4>
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

                    <table class="table table-striped DataWilayah">

                    <thead>
                            <tr>
                              <th class="text-center align-middle" rowspan="2" scope="col">Kabupaten</th>


                              <?php //Print header tabel
                              $sqlviewarsip = 'SELECT * FROM t_arsip_wilayah GROUP BY tahun_arsip_wilayah
                                                ORDER BY tahun_arsip_wilayah ASC';
                              $stmt = $pdo->prepare($sqlviewarsip);
                              $stmt->execute();
                              $rowtahun = $stmt->fetchAll();

                              foreach($rowtahun as $tahun){
                              ?>
                              <th class="text-center" colspan="4" scope="col"><?=$tahun->tahun_arsip_wilayah?></th>

                              <?php } ?>



                            <tr>
                            <?php
                            foreach($rowtahun as $tahun){
                              ?>
                              <th class="text-center" scope="col">Kurang</th>
                              <th class="text-center" scope="col">Cukup</th>
                              <th class="text-center" scope="col">Baik</th>
                              <th class="text-center" scope="col">Sangat Baik</th>
                              <?php } ?>
                            </tr>

                          </tr>
                      </thead>



                <tbody>
                <?php

                    $sqlviewluas = 'SELECT * FROM t_arsip_wilayah
                                    LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_arsip_wilayah.id_wilayah
                                                ORDER BY tahun_arsip_wilayah ASC';
                              $stmt = $pdo->prepare($sqlviewluas);
                              $stmt->execute();
                              $rowluas = $stmt->fetchAll();

                    foreach ($rowluas as $luas) {
                        $total_luas_lokasi = 0;
                        $total_persentase_sebaran = 0;
                ?>
                        <tr>
                            <td><?=$luas->nama_wilayah?></td>
                            <td class="text-center" ><?=$luas->kurang?></td>
                            <td class="text-center" ><?=$luas->cukup?></td>
                            <td class="text-center" ><?=$luas->baik?></td>
                            <td class="text-center" ><?=$luas->sangat_baik?></td>

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
            filename:     `Laporan_Wilayah_GoKarang_${dateTime}.pdf`,
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
