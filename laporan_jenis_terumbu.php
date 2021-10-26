<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

//Tanggal pemeliharaan terawal
$sqltahunterawal = 'SELECT YEAR(MIN(t_history_pemeliharaan.tanggal_pemeliharaan)) AS tahun_terawal FROM t_pemeliharaan 
                    LEFT JOIN t_history_pemeliharaan ON t_history_pemeliharaan.id_pemeliharaan = t_pemeliharaan.id_pemeliharaan
                    LIMIT 1';

$stmt = $pdo->prepare($sqltahunterawal);
$stmt->execute();
$tahunterawal = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laporan Jenis Terumbu - GoKarang</title>
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
    <script type="text/javascript" src="js/daterangepicker/jquery.min.js"></script>
    <script type="text/javascript" src="js/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="js/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
    <link rel="stylesheet" type="text/css" href="js/daterangepicker/daterangepicker.css" />
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
            <div class="content-header print-hide">
                <div class="container-fluid">
                
                <div class="row print-hide">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Laporan Jenis Terumbu Karang</span></h4>
                            <p class="text-muted text-sm"><i class="fas text-primary fa-info-circle"></i> Data dari kondisi dan ukuran terumbu karang 
                            yang telah memasuki tahap pemeliharaan berdasarkan jenis terumbu karang yang telah ditanam.</p>
                            <div id="datalaporan">
                        <div class="row">
                            <div class="col">
                                <div class="row print-hide">
                <div class="row">
                    <div class="col float-left text-middle">
                    Periode:
                    </div>
                </div>
                <div class="col float-left">
                      <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>

                    <script type="text/javascript">
                    
                    $(function() {  
                        moment.locale('id')

                        var start = moment().subtract(30, 'days');
                        var end = moment().add(23, 'hours');
                        var tahunterawal = moment(`<?=$tahunterawal->tahun_terawal?>`).format('DD-MM-YYYY');     
                        
                        function cb(start, end) {
                          starto = start.format('Y-MM-DD');
                          endo = end.format('Y-MM-DD');

                            $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY')); //apply date range to element                            
                            updateTabelLaporan(starto, endo)                            
                            $('#periode_laporan').text(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'))                            
                        }

                        $('#reportrange').daterangepicker({
                            "autoApply": true,
                            locale: 'id',
                            language: 'id',
                            startDate: start,
                            endDate: end,
                            ranges: {
                              'Hari ini': [moment().startOf('day'), moment().add(23, 'hours')],                              
                              '7 hari terakhir': [moment().subtract(6, 'days'), moment().add(23, 'hours')],
                              'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                              'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                              'Tahun ini': [moment().startOf('year'), moment().endOf('year')],
                              'Tahun lalu': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                              'Tampilkan semua': [tahunterawal, moment().add(23, 'hours')]
                            }
                        }, cb);

                        cb(start, end)
                    });                    
                    </script>                    
                    </div>
                
                </div>
                            </div>
                            <!-- <div class="col-auto">
                                <span class="text-bold">Tanggal Laporan :</span>
                            </div>
                            <div class="col">
                                <?= strftime("%A, %d %B %Y");?>
                            </div> -->
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

                    
                <div class="row text-center mb-3">
                      <div class="col">
                          <h5 class="mb-0"><span class="align-middle font-weight-bold mb-0">Laporan Jenis Terumbu Karang</span></h5>
                          <h5 class="mt-0 font-weight-bold text-muted text-sm">Periode <span id="periode_laporan"></span></h5>
                          
                      </div>
                    </div>
                <!-- Response AJAX call filter tabel laporan ditaro dalam sini -->
                <div id="table-container">              
                </div>
                <!-- Container tabel laporan end -->

                <div class="row info-cetak text-center">
                    <div class="col float-right">
                        <?= isset($_SESSION['nama_wilayah_dikelola']) ? $_SESSION['nama_wilayah_dikelola'] : '' ?>
                        <?= isset($_SESSION['nama_lokasi_dikelola']) ? $_SESSION['nama_lokasi_dikelola'] : '' ?>
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
    <!-- <script src="plugins/jquery/jquery.min.js"></script> -->
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

            $('body').LoadingOverlay("show");

            periode_laporan = $('#periode_laporan').text().split(" ").join("");
            

            $('#btn-unduh').css('left', '9999px')
            $('#clientPrintContent').css('background-color', 'white')
            $('.collapse').show()
            $('.main-sidebar').show()
            $('#clientPrintContent, .main-header, .navbar navbar-expand, .navbar-white, .navbar-light').css('margin-left', 0)

            $('.capture-hide').remove()
            $('.print-hide').remove()
            $('body').addClass('text-sm')

            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            var dateTime = date+'_'+time;

            var element = document.getElementById('clientPrintContent');
            var opt = {
            margin:       [1.5,2,2,2],
            filename:     `Laporan_Donasi_GoKarang_periode-${periode_laporan}_diunduh-pada${dateTime}.pdf`,

            image:        { type: 'jpeg', quality: 0.95 },
            html2canvas:  { scale: 2 },
            pagebreak: {avoid: ['tr', '.info-cetak']},
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

        function updateTabelLaporan(start, end, sortir){
  // starto = start
  // endo = end
  id_wilayah_dikelola =  <?=!empty($_SESSION['id_wilayah_dikelola']) ? $_SESSION['id_wilayah_dikelola'] : '1'?>

  id_lokasi_dikelola = <?=!empty($_SESSION['id_lokasi_dikelola']) ? $_SESSION['id_lokasi_dikelola'] : '1'?>

  level_user = <?=$_SESSION['level_user']?>


  // AJAX request
  $.ajax({
    url: 'list_populate.php',
    type: 'post',
    data: {start: start, 
            end: end,
            sortir: sortir,
            level_user : level_user,
            id_wilayah_dikelola : id_wilayah_dikelola,
            id_lokasi_dikelola : id_lokasi_dikelola,
            type : 'load_laporan_jenis'},
    beforeSend : function(){$('#table-container').LoadingOverlay("show");},
    success: function(response){
      // Attach response to target container/element
      $('#table-container').html(response);
      console.log(start, end)
      $('#table-container').LoadingOverlay("hide");
    }
  });
}


    </script>

</body>
</html>
