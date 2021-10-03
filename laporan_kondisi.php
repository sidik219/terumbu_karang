<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewwilayah = 'SELECT *,
    
    COUNT(
        CASE WHEN kondisi_terumbu = "Rusak" THEN 1 ELSE NULL
    END
) AS jumlah_rusak,
CAST(
    SUM(
        CASE WHEN kondisi_terumbu = "Rusak" THEN ukuran_terumbu ELSE 0
    END
) AS DECIMAL(10, 2)
) AS ukuran_rusak,
COUNT(
    CASE WHEN kondisi_terumbu = "Baik" THEN 1 ELSE NULL
END
) AS jumlah_baik,
CAST(
    SUM(
        CASE WHEN kondisi_terumbu = "Baik" THEN ukuran_terumbu ELSE 0
    END
) AS DECIMAL(10, 2)
) AS ukuran_baik,
COUNT(
    CASE WHEN kondisi_terumbu = "Sangat Baik" THEN 1 ELSE NULL
END
) AS jumlah_sangat_baik,
CAST(
    SUM(
        CASE WHEN kondisi_terumbu = "Sangat Baik" THEN ukuran_terumbu ELSE 0
    END
) AS DECIMAL(10, 2)
) AS ukuran_sangat_baik,
CAST(SUM(ukuran_terumbu) AS DECIMAL(10,2)) AS ukuran_total_lokasi,
COUNT(t_history_pemeliharaan.id_detail_donasi) AS jumlah_terumbu_total
FROM
    t_lokasi
    LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah
    LEFT JOIN t_donasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
 	LEFT JOIN t_detail_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
    LEFT JOIN t_history_pemeliharaan ON t_history_pemeliharaan.id_detail_donasi = t_detail_donasi.id_detail_donasi
    
    WHERE kondisi_terumbu <> "Mati"
    GROUP BY t_wilayah.id_wilayah';

$stmt = $pdo->prepare($sqlviewwilayah);
$stmt->execute();
$rowwilayah = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laporan Kondisi - GoKarang</title>
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
                            <h4><span class="align-middle font-weight-bold">Laporan Kondisi Terumbu</span></h4>
                            <p class="text-muted text-sm"><i class="fas text-primary fa-info-circle"></i> Data dari kondisi dan ukuran terumbu karang yang telah memasuki tahap pemeliharaan.</p>
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

                        <!-- <a class="btn btn-primary float-right" target="_blank" href="generate_laporan.php?type=generate_csv_laporan_wilayah"><i class="fas fa-file-excel"></i> Unduh Laporan (CSV)</a> -->

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

                    <table class="table table-striped DataWilayah">

                <tbody>
                <?php
                    foreach ($rowwilayah as $rowitem) {
                        $total_ukuran_terumbu = 0;
                        $total_ukuran_rusak = 0;
                        $total_ukuran_baik = 0;
                        $total_ukuran_sangat_baik = 0;
                ?>
                        <tr>
                            <th scope="row" colspan="3"><?=$rowitem->nama_wilayah?></th>
                        </tr>
                        <tr>
                                <td colspan="3">
                            
                                <table class="table">

                                    <thead>
                                    <tr   class="bg-white border-top">
                                        <th scope="col">Nama Lokasi</th>
                                        <th scope="col">Jumlah Terumbu</th>
                                        <th scope="col">Ukuran Rusak</th>
                                        <th scope="col">Ukuran Baik</th>
                                        <th scope="col">Ukuran Sangat Baik</th>
                                        <th scope="col">Ukuran Total</th>

                                    </tr>
                                    </thead>
                                <?php
                                  $sql_lokasi = 'SELECT *,
    
    COUNT(
        CASE WHEN kondisi_terumbu = "Rusak" THEN 1 ELSE NULL
    END
) AS jumlah_rusak,
CAST(
    SUM(
        CASE WHEN kondisi_terumbu = "Rusak" THEN ukuran_terumbu ELSE 0
    END
) AS DECIMAL(10, 2)
) AS ukuran_rusak,
COUNT(
    CASE WHEN kondisi_terumbu = "Baik" THEN 1 ELSE NULL
END
) AS jumlah_baik,
CAST(
    SUM(
        CASE WHEN kondisi_terumbu = "Baik" THEN ukuran_terumbu ELSE 0
    END
) AS DECIMAL(10, 2)
) AS ukuran_baik,
COUNT(
    CASE WHEN kondisi_terumbu = "Sangat Baik" THEN 1 ELSE NULL
END
) AS jumlah_sangat_baik,
CAST(
    SUM(
        CASE WHEN kondisi_terumbu = "Sangat Baik" THEN ukuran_terumbu ELSE 0
    END
) AS DECIMAL(10, 2)
) AS ukuran_sangat_baik,
CAST(SUM(ukuran_terumbu) AS DECIMAL(10, 2)) AS ukuran_total_lokasi,
COUNT(t_history_pemeliharaan.id_detail_donasi) AS jumlah_terumbu_total
FROM
    t_lokasi
    LEFT JOIN t_donasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
 	LEFT JOIN t_detail_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
    LEFT JOIN t_history_pemeliharaan ON t_history_pemeliharaan.id_detail_donasi = t_detail_donasi.id_detail_donasi
    

    WHERE t_lokasi.id_wilayah = '.$rowitem->id_wilayah.'
    AND kondisi_terumbu <> "Mati"
   GROUP BY t_lokasi.id_lokasi';

                                    $stmt = $pdo->prepare($sql_lokasi);
                                    $stmt->execute();
                                    $rowlokasi = $stmt->fetchAll();

                                    $rusak=0; $baik=0; $sangat_baik=0;
                                    $rusak_luas = 0; $baik_luas = 0; $sangat_baik_luas = 0;

                                    foreach($rowlokasi as $lokasi) {
                                        $ps = $lokasi->kondisi_terumbu;
                                        if($ps == "Rusak"){
                                        $rusak_luas += $lokasi->ukuran_rusak;
                                        }
                                        else if($ps  == "Baik"){
                                        $baik_luas += $lokasi->ukuran_baik;
                                        }
                                        else if($ps  == "Sangat Baik"){
                                        $sangat_baik_luas += $lokasi->ukuran_sangat_baik;;
                                        }
                                    ?>


                                    <tr>
                                        <td><?=$lokasi->nama_lokasi?></td>
                                        <td><?=$lokasi->jumlah_terumbu_total?></td>
                                        <td><?=$lokasi->ukuran_rusak?></td>
                                        <td><?=$lokasi->ukuran_baik?></td>
                                        <td><?=$lokasi->ukuran_sangat_baik?></td>
                                        <td><?=($lokasi->ukuran_total_lokasi).' m<sup>2</sup>'?></td>                                        
                                    </tr>

                    <?php
                } //lokasi loop end
                ?>
                                    <thead>
                                    <tr   class="bg-white border-top">
                                        <th scope="col">Total:</th>
                                        <th scope="col"><?=$rowitem->jumlah_terumbu_total?></th>
                                        <th scope="col"><?=$rowitem->ukuran_rusak?></th>
                                        <th scope="col"><?=$rowitem->ukuran_baik?></th>
                                        <th scope="col"><?=$rowitem->ukuran_sangat_baik?></th>
                                        <th scope="col"><?=($rowitem->ukuran_total_lokasi).' m<sup>2</sup>'?></th>

                                    </tr>
                        
                        </table>

                        <hr/>


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
            filename:     `Laporan_Kondisi_GoKarang_${dateTime}.pdf`,

            image:        { type: 'jpeg', quality: 0.95 },
            html2canvas:  { scale: 3 },
            jsPDF:        { unit: 'cm', format: 'a3', orientation: 'landscape' }
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
