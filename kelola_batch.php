<?php include 'build/config/connection.php';
session_start();

if (isset($_SESSION['level_user']) == 0) {
    header('location: login.php');
}
    $sqlviewbatch = 'SELECT t_batch.id_batch, t_batch.id_lokasi, t_batch.id_titik, t_batch.tanggal_penanaman,
                      t_batch.update_status_batch_terakhir, nama_lokasi, keterangan_titik, nama_status_batch, t_titik.latitude, t_titik.longitude
                      FROM t_batch
                      LEFT JOIN t_lokasi ON t_batch.id_lokasi = t_lokasi.id_lokasi
                      LEFT JOIN t_titik ON t_batch.id_titik = t_titik.id_titik
                      LEFT JOIN t_status_batch ON t_batch.id_status_batch = t_status_batch.id_status_batch
                      ORDER BY update_status_batch_terakhir DESC';
    $stmt = $pdo->prepare($sqlviewbatch);
    $stmt->execute();
    $rowbatch = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Batch - Terumbu Karang</title>
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
                    <!-- SESSION lvl Untuk Lokasi -->
                    <?php if($_SESSION['level_user'] == '3') { ?>
                        <li class="nav-item"> <!-- Wilayah & Lokasi -->
                           <a href="dashboard_admin.php" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_donasi.php" class="nav-link">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Kelola Donasi </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_wisata.php" class="nav-link">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Kelola Wisata </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_reservasi_wisata.php" class="nav-link">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p> Kelola Reservasi </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Wilayah & Lokasi -->
                            <a href="kelola_lokasi.php" class="nav-link">
                                <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                                <p> Kelola Lokasi </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Wilayah & Lokasi -->
                            <a href="kelola_titik.php" class="nav-link">
                                 <i class="nav-icon fas fa-crosshairs"></i>
                                 <p> Kelola Titik </p>
                            </a>
                        </li>
                        <li class="nav-item menu-open"> <!-- Lokasi -->
                            <a href="kelola_batch.php" class="nav-link active">
                                  <i class="nav-icon fas fa-boxes"></i>
                                  <p> Kelola Batch </p>
                            </a>
                        </li>
                        <li class="nav-item"> <!-- Lokasi -->
                            <a href="kelola_pemeliharaan.php" class="nav-link">
                                  <i class="nav-icon fas fa-heart"></i>
                                  <p> Kelola Pemeliharaan </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_user.php" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p> Kelola User </p>
                            </a>
                        </li>
                    <?php } ?>
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
                            <h4><span class="align-middle font-weight-bold">Kelola Batch</span></h4>
                        </div>
                        <div class="col">

                        <a class="btn btn-primary float-right" href="input_batch.php" role="button">Input Data Baru (+)</a>

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
                          Update data berhasil
                      </div>';}
                      else if($_GET['status'] == 'addsuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Data baru berhasil ditambahkan
                      </div>';}
                      else if($_GET['status'] == 'deletesuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Data berhasil dihapus
                      </div>';
                    }
                  }
                ?>
            <?php if($_SESSION['level_user'] == '3') { ?>
                <table class="table table-striped">
                     <thead>
                            <tr>
                                <th scope="col">ID Batch</th>
                                <th scope="col">Lokasi</th>
                                <th scope="col">ID Titik</th>
                                <th scope="col">Tanggal Penanaman</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                    <tbody>
                      <?php
                          foreach ($rowbatch as $batch) {

                            $truedate = strtotime($batch->update_status_batch_terakhir);
                          ?>
                          <tr>
                              <th scope="row"><?=$batch->id_batch?></th>
                              <td>ID <?=$batch->id_lokasi?> - <?=$batch->nama_lokasi?></td>
                              <td><?=$batch->id_titik?> <?=$batch->keterangan_titik?><br><a target="_blank" href="http://maps.google.com/maps/search/?api=1&query=<?=$batch->latitude?>,<?=$batch->longitude?>&zoom=8"
                                                                                                                                      class="btn btn-act"><i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a></td>
                              <td><?=$batch->tanggal_penanaman?></td>
                              <td><?=$batch->nama_status_batch?>
                                <small class="text-muted">
                                  <br>Update Terakhir:
                                  <br><?=strftime('%A, %d %B %Y', $truedate);?></small>
                              </td>
                              <td>
                                <button type="button" class="btn btn-act">
                                <a href="edit_batch.php?id_batch=<?=$batch->id_batch?>" class="fas fa-edit"></a>
                                </button>
                                <button type="button" class="btn btn-act"><i class="far fa-trash-alt"></i></button>
                              </td>
                          </tr>
                          <tr>
                                <td colspan="6">
                                    <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$batch->id_batch?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$batch->id_batch?>, .contentall<?=$batch->id_batch?>">
                                <p
                                    class="fielddetail<?=$batch->id_batch?>  btn btn-act">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Batch</p>
                            </div>
                            <div class="col-12 cell<?=$batch->id_batch?> collapse contentall<?=$batch->id_batch?>   border rounded shadow-sm p-3">
                            <div class="col-md-3 kolom font-weight-bold">
                                        Daftar Donasi
                                    </div>
                                <?php
                                  $sqlviewdetailbatch = 'SELECT * FROM t_detail_batch
                                                        LEFT JOIN t_donasi ON t_donasi.id_batch = t_detail_batch.id_batch
                                                        WHERE t_donasi.id_batch = :id_batch
                                                        AND t_donasi.id_donasi = t_detail_batch.id_donasi';
                                  $stmt = $pdo->prepare($sqlviewdetailbatch);
                                  $stmt->execute(['id_batch' => $batch->id_batch]);
                                  $rowdetailbatch = $stmt->fetchAll();

                                  foreach($rowdetailbatch as $detailbatch){
                                ?>
                                <div class="row mb-2 ml-1 border-bottom">
                                    <div class="col isi">
                                        ID <?=$detailbatch->id_donasi?> - <?=$detailbatch->nama_donatur?>
                                    </div>
                                    <div class="col-lg-9 isi">
                                        <a data-id='<?=$detailbatch->id_donasi?>' class="btn btn-sm btn-outline-primary userinfo p-1">Rincian></a>
                                    </div>
                                </div>

                                  <?php } ?>

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
