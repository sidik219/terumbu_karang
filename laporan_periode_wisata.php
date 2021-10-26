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

//umum
$sqltahunterawal = 'SELECT MIN(tanggal_pesan) AS tahun_terawal FROM t_reservasi_wisata 
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    WHERE id_status_reservasi_wisata = 2 '. $extra_query. ' LIMIT 1';

$stmt = $pdo->prepare($sqltahunterawal);
$stmt->execute();
$tahunterawal = $stmt->fetch();

$sqlhitungtotal = 'SELECT COUNT(t_laporan_pengeluaran.id_pengeluaran) AS total_reservasi, 
                            SUM(t_laporan_pengeluaran.biaya_pengeluaran) AS biaya_pengeluaran 
                FROM t_laporan_pengeluaran
                LEFT JOIN t_reservasi_wisata ON t_laporan_pengeluaran.id_reservasi = t_reservasi_wisata.id_reservasi
                LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                WHERE  '.$extra_query_noand.' 
                ORDER BY id_pengeluaran DESC';

$stmt = $pdo->prepare($sqlhitungtotal);
$stmt->execute();
$rowtotal = $stmt->fetch();

function ageCalculator($dob){
    $birthdate = new DateTime($dob);
    $today   = new DateTime('today');
    $ag = $birthdate->diff($today)->y;
    $mn = $birthdate->diff($today)->m;
    $dy = $birthdate->diff($today)->d;
    if ($dy == 0)
    {
        return "Hari ini";
    }
    if ($dy == 1)
    {
        return "Kemarin";
    }
    if ($mn == 0)
    {
        return "$dy Hari yang lalu";
    }
    elseif ($ag == 0)
    {
        return "$mn Bulan  $dy Hari yang lalu";
    }
    else
    {
        return "$ag Tahun $mn Bulan $dy Hari yang lalu";
    }
}

function alertPembayaran($dob)
{
    $birthdate = new DateTime($dob);
    $today   = new DateTime('today');
    $mn = $birthdate->diff($today)->m;
    $dy = $birthdate->diff($today)->d;

    $tglbatas = $birthdate->add(new DateInterval('P3D'));
    $tglbatas_formatted = strftime('%A, %e %B %Y pukul %R', $tglbatas->getTimeStamp());
    $batas_waktu_pesan = '<br><b>Batas pembayaran:</b><br>' . $tglbatas_formatted;
    if ($dy <= 3) {
        //jika masih dalam batas waktu
        return  $batas_waktu_pesan . '<br> <i class="fas fa-exclamation-circle text-primary"></i><small> Menunggu bukti pembayaran wisatawan</small>';
    } else if ($dy > 3) {
        //overdue
        return $batas_waktu_pesan . '<br><i class="fas fa-exclamation-circle text-danger"></i><small> Sudah lewat batas waktu pembayaran.</small><br>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laporan Wisata - GoKarang</title>
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
                <div class="row">
                        <div class="col text-sm">
                            <h4><span class="align-middle font-weight-bold">Laporan Wisata</span></h4>
                            <small class="text-muted"><i class="nav-icon text-info fas fa-info-circle"></i> Daftar reservasi wisata yang telah selesai melakukan kelola biaya pengeluaran</small>
                        </div>
                        <div class="col">

                        <!-- <a class="btn btn-primary float-left" href="input_donasi.php" role="button">Input Data Baru (+)</a> -->                                            

                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
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

                                    var start = moment().subtract(29, 'days');
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
                                        'Hari ini': [moment(), moment().add(23, 'hours')],                              
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
                        <div class="col text-sm-center">
                            <!-- <a class="btn btn-primary float-md-right" href="#" role="button">Cetak Laporan Donasi<i class="nav-icon fas fa-boxes ml-1"></i></a> -->
                            <!-- <a class="btn btn-primary float-md-right" href="laporan_wisata.php?type=all_pengeluaran" role="button">
                                <i class="nav-icon far fa-file-excel ml-1"></i> Cetak Laporan Pengeluaran</a> -->
                            <a class="btn btn-primary float-right  mr-2" onclick="savePDF()" href="#" role="button">
                                <i class="fas fa-file-pdf"></i> Unduh Laporan (PDF)</a>
                        </div>
                    </div>


                    <!-- <div class="row mb-2">
                        <div class="col">
                            <div class="dropdown show">
                            <a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Sortir Donasi
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="laporan_donasi.php">Default</a>
                                <a class="dropdown-item" onclick="cb(moment().subtract(29, 'days'), moment(), ' SORT BY nominal DESC ');">Nominal Tertinggi</a>
                                <a class="dropdown-item" onclick="cb(moment().subtract(29, 'days'), moment(), ' SORT BY nominal ASC ');">Nominal Terendah</a>
                            </div>
                        </div>
                        </div>
                    </div> -->

                    <div class="row text-center mt-5 mb-5">
                        <div class="col">
                            <h5 class="mb-0"><span class="align-middle font-weight-bold mb-0">Laporan Periode Penghasilan Reservasi Wisata </span></h5>
                            <h5 class="mt-0 font-weight-bold text-muted text-sm">Periode <span id="periode_laporan"></span></h5>
                            
                        </div>
                    </div>

                    <!-- Response AJAX call filter tabel laporan ditaro dalam sini -->
                    <div id="table-container"></div>
                   
                </div>
                <input type="hidden" id="nominal_order" value="asc">
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
   <div class="modal fade" id="empModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content  bg-light">
                <div class="modal-header">
                    <h4 class="modal-title">Rincian Donasi</h4>
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
    <!-- <script src="plugins/jquery/jquery.min.js"></script> -->
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script src="js/jspdf.min.js"></script>
    <script src="js/standard_fonts_metrics.js"></script>
    <script src="js/split_text_to_size.js"></script>
    <script src="js/from_html.js"></script>
    <script src="js/html2pdf.bundle.min.js"></script>

    <script>

    $(document).ready(function(){});

    function listenRincian(){
        $('.userinfo').click(function(){

        var id_donasi = $(this).data('id');

        // AJAX request
        $.ajax({
                url: 'list_populate.php',
                type: 'post',
                data: {id_donasi: id_donasi, type : 'load_rincian_donasi'},
                beforeSend : function(){
                $('#empModal').modal('show');
                $('#empModal').LoadingOverlay("show");
                },
                success: function(response){
                // Add response in Modal body
                $('.modal-body').html(response);
                $('#empModal').LoadingOverlay("hide");
                }
            });
        });
    }

    <?php 
        $sortquery = ' ';
        if (isset($_GET['sort'])){
            if($_GET['sort'] = 'sortByNominalDESC'){
                $sortquery = ' ORDER BY nominal DESC ';
            }
            elseif($_GET['sort']= 'sortByNominalASC'){
                $sortquery = ' ORDER BY nominal ASC ';
            }
        }
    ?>

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
                    type : 'load_laporan_reservasi'},
            beforeSend : function(){$('#table-container').LoadingOverlay("show");},
            success: function(response){
                // Attach response to target container/element
                $('#table-container').html(response);
                console.log(start, end)
                listenRincian()
                $('#table-container').LoadingOverlay("hide");
            }
        });
    }

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
            filename:     `Laporan_Penghasilan_Reservasi_Wisata_GoKarang_periode-${periode_laporan}_diunduh-pada${dateTime}.pdf`,
            image:        { type: 'jpeg', quality: 0.95 },
            html2canvas:  { scale: 2 },
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


    function sortNominal(){
        
    }

    $(function() {
        $("#tabel_laporan_wisata").tablesorter();
    });
    </script>
</body>
</html>
