<?php include 'build/config/connection.php';
//session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}
$sqlviewpemeliharaan = 'SELECT * FROM t_pemeliharaan
                          LEFT JOIN t_lokasi ON t_pemeliharaan.id_lokasi = t_lokasi.id_lokasi
                          LEFT JOIN t_status_pemeliharaan ON t_pemeliharaan.id_status_pemeliharaan = t_status_pemeliharaan.id_status_pemeliharaan
                          ORDER BY t_pemeliharaan.id_status_pemeliharaan';
$stmt = $pdo->prepare($sqlviewpemeliharaan);
    $stmt->execute();
    $rowpemeliharaan = $stmt->fetchAll();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Pemeliharaan - TKJB</title>
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
                <img src="dist/img/KKPlogo.png"  class="brand-image img-circle">
                <!-- BRAND TEXT (TOP) -->
                <span class="brand-text font-weight-bold">TKJB</span>
            </a>
            <!-- END OF TOP SIDEBAR -->

            <!-- SIDEBAR -->
            <div class="sidebar">
                <!-- SIDEBAR MENU -->
                <nav class="mt-2">
                   <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php //if($_SESSION['level_user'] == '1') { ?>
                        <li class="nav-item ">
                           <a href="dashboard_admin.php" class="nav-link ">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_donasi.php" class="nav-link ">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Kelola Donasi </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_wisata.php" class="nav-link">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Kelola Wisata </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_reservasi_wisata.php" class="nav-link">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p> Kelola Reservasi </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_wilayah.php" class="nav-link ">
                                <i class="nav-icon fas fa-globe-asia"></i>
                                <p> Kelola Wilayah </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_lokasi.php" class="nav-link ">
                                <i class="nav-icon fas fa-map-marker" aria-hidden="true"></i>
                                <p> Kelola Lokasi </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_titik.php" class="nav-link ">
                                 <i class="nav-icon fas fa-crosshairs"></i>
                                 <p> Kelola Titik </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_detail_titik.php" class="nav-link">
                                 <i class="nav-icon fas fa-podcast"></i>
                                 <p> Kelola Detail Titik </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_batch.php" class="nav-link ">
                                  <i class="nav-icon fas fa-boxes"></i>
                                  <p> Kelola Batch </p>
                            </a>
                        </li>
                        <li class="nav-item menu-open">
                            <a href="kelola_pemeliharaan.php" class="nav-link active">
                                  <i class="nav-icon fas fa-heart"></i>
                                  <p> Kelola Pemeliharaan </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                             <a href="kelola_jenis_tk.php" class="nav-link ">
                                   <i class="nav-icon fas fa-certificate"></i>
                                   <p> Kelola Jenis Terumbu </p>
                             </a>
                        </li>
                        <li class="nav-item ">
                            <a href="kelola_tk.php" class="nav-link ">
                                  <i class="nav-icon fas fa-disease"></i>
                                  <p> Kelola Terumbu Karang </p>
                            </a>
                        </li>

                        <li class="nav-item ">
                             <a href="kelola_perizinan.php" class="nav-link ">
                                    <i class="nav-icon fas fa-scroll"></i>
                                    <p> Kelola Perizinan </p>
                             </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_laporan.php" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p> Kelola Laporan </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_user.php" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p> Kelola User </p>
                            </a>
                        </li>
                    <?php //} ?>
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
                            <h4><span class="align-middle font-weight-bold">Kelola Pemeliharaan</span></h4>
                        </div>
                        <div class="col">

                        <a class="btn btn-primary float-right" href="input_pemeliharaan.php" role="button">Input Data Baru (+)</a>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
            <?php //if($_SESSION['level_user'] == '1') { ?>
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
                <table class="table table-striped">
                     <thead>
                            <tr>
                                <th scope="col">ID Pemeliharaan</th>
                                <th scope="col">ID Lokasi</th>
                                <th scope="col">Tanggal Pemeliharaan</th>
                                <th scope="col">Status Pemeliharaan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                    <tbody>
                      <?php
                      foreach($rowpemeliharaan as $pemeliharaan){

                      ?>
                          <tr>
                              <th scope="row"><?=$pemeliharaan->id_pemeliharaan?></th>
                              <td><?=$pemeliharaan->id_lokasi?> - <?=$pemeliharaan->nama_lokasi?></td>
                              <td><?=$pemeliharaan->tanggal_pemeliharaan?></td>
                              <td>
                                <?php
                                  if($pemeliharaan->id_status_pemeliharaan == 1){
                                    echo '<span class="status-pemeliharaan badge badge-warning">'.$pemeliharaan->nama_status_pemeliharaan.'</span>';
                                  }
                                  else{
                                    echo '<span class="status-pemeliharaan badge badge-success">'.$pemeliharaan->nama_status_pemeliharaan.'</span>';
                                  }

                                ?>

                            </td>
                              <td>
                                <a href="edit_pemeliharaan.php?id_pemeliharaan=<?=$pemeliharaan->id_pemeliharaan?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                <button type="button" class="btn btn-act"><i class="far fa-trash-alt"></i></button>
                              </td>
                          </tr>
                          <tr>
                                <td colspan="5">
                                    <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$pemeliharaan->id_pemeliharaan?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$pemeliharaan->id_pemeliharaan?>, .contentall<?=$pemeliharaan->id_pemeliharaan?>">
                                <p
                                    class="fielddetail<?=$pemeliharaan->id_pemeliharaan?>  btn btn-act">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Pemeliharaan</p>
                            </div>
                            <div class="col-12 cell<?=$pemeliharaan->id_pemeliharaan?> collapse contentall<?=$pemeliharaan->id_pemeliharaan?>  border rounded shadow-sm">
                            <div class="col-md-3 kolom font-weight-bold">
                                        Daftar Batch
                                    </div>

                                <?php
                                  $sqlviewdetailpemeliharaan = 'SELECT * FROM t_detail_pemeliharaan
                                                        WHERE id_pemeliharaan = :id_pemeliharaan';
                                  $stmt = $pdo->prepare($sqlviewdetailpemeliharaan);
                                  $stmt->execute(['id_pemeliharaan' => $pemeliharaan->id_pemeliharaan]);
                                  $rowdetailpemeliharaan = $stmt->fetchAll();

                                  foreach($rowdetailpemeliharaan as $detailpemeliharaan){
                                ?>
                                <div class="row mb-2 ml-1">
                                    <div class="col-12 isi mb-1">
                                        <span class="badge badge-pill badge-info">ID Batch <?=$detailpemeliharaan->id_batch?></span>
                                    </div>
                                    <div class="col isi ml-2 bg-light rounded">
                                      <div class="ml-2 kolom font-weight-bold">
                                        <small class="font-weight-bold">Daftar Donasi</small>
                                    </div>
                                        <?php
                                  $sqlviewdetailbatch = 'SELECT * FROM t_detail_batch
                                                        LEFT JOIN t_donasi ON t_donasi.id_batch = t_detail_batch.id_batch
                                                        WHERE t_donasi.id_batch = :id_batch
                                                        AND t_donasi.id_donasi = t_detail_batch.id_donasi';
                                  $stmt = $pdo->prepare($sqlviewdetailbatch);
                                  $stmt->execute(['id_batch' => $detailpemeliharaan->id_batch]);
                                  $rowdetailbatch = $stmt->fetchAll();

                                  foreach($rowdetailbatch as $detailbatch){
                                ?>
                                <div class="row mb-3 ml-1 border-bottom small">
                                    <div class="col isi">
                                        ID <?=$detailbatch->id_donasi?> - <?=$detailbatch->nama_donatur?>
                                    </div>
                                    <div class="col-lg-9 isi">
                                        <a data-id='<?=$detailbatch->id_donasi?>' class="btn btn-sm btn-outline-primary userinfo p-1 small">Rincian></a>
                                    </div>
                                </div>

                                  <?php } ?>
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
            <?php //} ?>
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
