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
                  LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
                  WHERE t_donasi.id_status_donasi = 1  '.$extra_query.'
                  ORDER BY id_donasi DESC';
  }
  elseif($id_status_donasi == 2){ //donasi butuh verifikasi
    $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
                  WHERE t_donasi.id_status_donasi = 2  '.$extra_query.'
                  ORDER BY id_donasi DESC';
  }
  elseif($id_status_donasi == 6){ //donasi bermasalah
    $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
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
                  LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
                  WHERE t_donasi.id_batch IS NULL AND t_donasi.id_status_donasi = 3  '.$extra_query.'
                  ORDER BY id_donasi DESC';

  $stmt = $pdo->prepare($sqlviewdonasi);
  $stmt->execute();
  $row = $stmt->fetchAll();
}else{ //umum
  $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi 
                  LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
                  WHERE  '.$extra_query_noand.' AND t_donasi.id_status_pengadaan_bibit = 4 
                  ORDER BY id_donasi DESC';

  $stmt = $pdo->prepare($sqlviewdonasi);
  $stmt->execute();
  $row = $stmt->fetchAll();

  $sqlhitungtotal = 'SELECT COUNT(t_donasi.id_donasi) AS total_donasi, SUM(t_donasi.nominal) AS total_nominal FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi 
                  LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
                  WHERE  '.$extra_query_noand.' AND t_donasi.id_status_pengadaan_bibit = 4 
                  ORDER BY id_donasi DESC';

  $stmt = $pdo->prepare($sqlhitungtotal);
  $stmt->execute();
  $rowtotal = $stmt->fetch();
}




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



    function alertPembayaran($dob){ 
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $mn = $birthdate->diff($today)->m;
        $dy = $birthdate->diff($today)->d;

        $tglbatas = $birthdate->add(new DateInterval('P3D'));
        $tglbatas_formatted = strftime('%A, %e %B %Y pukul %R', $tglbatas->getTimeStamp() );
        $batas_waktu_pesan = '<br><b>Batas pembayaran:</b><br>'. $tglbatas_formatted;
        if ($dy <= 3)
        { 
            //jika masih dalam batas waktu
            return  $batas_waktu_pesan .'<br> <i class="fas fa-exclamation-circle text-primary"></i><small> Menunggu bukti pembayaran donatur</small>';
        }
        else if ($dy > 3){
            //overdue
            return $batas_waktu_pesan .'<br><i class="fas fa-exclamation-circle text-danger"></i><small> Sudah lewat batas waktu pembayaran.</small><br>
            ';
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
                            <h4><span class="align-middle font-weight-bold">Laporan Donasi</span></h4>
                            <small class="text-muted"><i class="nav-icon text-info fas fa-info-circle"></i> Daftar donasi yang telah selesai pengadaan bibit</small>
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

                <div class="row">
                    <div class="col text-sm-center">
                        <a class="btn btn-primary float-md-right" href="#" role="button">Cetak Laporan Donasi<i class="nav-icon fas fa-boxes ml-1"></i></a>
                    </div>
                </div>


                <div class="row">
                      <div class="col">
                        <!-- <div class="dropdown show">
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
                    </div> -->
                      </div>
                </div>


                   <table class="table table-striped table-responsive-sm">
                     <thead>
                            <tr>
                                <th scope="col">ID Donasi</th>
                                <th scope="col">Lokasi</th>
                                <th scope="col">Nominal</th>
                                <th scope="col">Nama Donatur</th>
                                <th scope="col">Tanggal Donasi</th>
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
                              <td><?= $rowitem->nama_lokasi ?></td>
                              <td>Rp. <?=number_format($rowitem->nominal, 0)?></td>
                              <td><?= $rowitem->nama_donatur ?></td>
                              <td><?=strftime('%A, %e %B %Y', $donasidate);?> <br>  <?php if($rowitem->id_status_donasi == 1){
                                              echo alertPembayaran($rowitem->tanggal_donasi);
                                          }  ?> 
                                          
                                          
                                         
                             </td>
                            
                              <td>
                                <a data-id='<?=$rowitem->id_donasi?>' class="btn btn-sm btn-outline-primary userinfo p-1">Rincian></a>
                                
                              </td>

                          </tr>

                          
                            <?php //$index++;
                            } ?>
                    </tbody>                    
                  </table>
                
                <hr class="m-0"/>
                <hr class="m-0"/>
                   <table class="table table-striped table-responsive-sm">
                     <thead>
                            <tr>
                                <th scope="col">Total: <?= $rowtotal->total_donasi ?> Donasi</th>
                                <th scope="col"></th>
                                <th scope="col">Rp. <?=  number_format($rowtotal->total_nominal, 0)?></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                          </thead>
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
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


<script>
       $(document).ready(function(){

 $('.userinfo').click(function(){

   var id_donasi = $(this).data('id');

   // AJAX request
   $.ajax({
    url: 'list_populate.php',
    type: 'post',
    data: {id_donasi: id_donasi, type : 'load_rincian_donasi'},
    success: function(response){
      // Add response in Modal body
      $('.modal-body').html(response);

      // Display Modal
      $('#empModal').modal('show');
    }
  });
 });
});

    </script>

</body>
</html>
