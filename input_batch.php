<?php include 'build/config/connection.php';
//session_start();

$sqlviewlokasi = 'SELECT * FROM t_lokasi
                        ORDER BY id_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $rowlokasi = $stmt->fetchAll();

        $sqlviewtitik = 'SELECT * FROM t_titik
                        ORDER BY id_titik';
        $stmt = $pdo->prepare($sqlviewtitik);
        $stmt->execute();
        $rowtitik = $stmt->fetchAll();

        $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  WHERE t_donasi.id_status_donasi = 3';
        $stmt = $pdo->prepare($sqlviewdonasi);
        $stmt->execute();
        $rowdonasi = $stmt->fetchAll();



        if (isset($_POST['submit'])) {
          if (isset($_POST['id_donasi'])){
              $id_lokasi        = $_POST['dd_id_lokasi'];
        $id_titik        = $_POST['dd_id_titik'];
        $tanggal_penanaman        = $_POST['date_penanaman'];

        $update_status_batch_terakhir = date ('Y-m-d H:i:s', time());
        $id_status_batch = 1;


        $sqlinsertbatch = "INSERT INTO t_batch
                        (id_lokasi, tanggal_penanaman, update_status_batch_terakhir, id_status_batch, id_titik)
                        VALUES (:id_lokasi, :tanggal_penanaman, :update_status_batch_terakhir, :id_status_batch, :id_titik)";

        $stmt = $pdo->prepare($sqlinsertbatch);
        $stmt->execute(['id_lokasi' => $id_lokasi, 'tanggal_penanaman' => $tanggal_penanaman, 'update_status_batch_terakhir' => $update_status_batch_terakhir, 'id_status_batch' => $id_status_batch, 'id_titik' => $id_titik]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
        //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            $last_batch_id = $pdo->lastInsertId();
            }

            foreach($_POST['id_donasi'] as $id_donasi_value){ //Insert ke t_detail_batch
              $id_donasi = $id_donasi_value;
              $id_batch = $last_batch_id;
              $id_status_donasi = 4;

              $sqlinsertdetailbatch = "INSERT INTO t_detail_batch
                        (id_donasi, id_batch)
                        VALUES (:id_donasi, :id_batch)";

              $stmt = $pdo->prepare($sqlinsertdetailbatch);
              $stmt->execute(['id_donasi' => $id_donasi, 'id_batch' => $id_batch]);


              //Update dan set id_batch ke donasi pilihan
              $sqldonasi = "UPDATE t_donasi
                        SET id_batch = :id_batch, update_terakhir = :update_terakhir, id_status_donasi = :id_status_donasi
                        WHERE id_donasi = :id_donasi";

              $stmt = $pdo->prepare($sqldonasi);
              $stmt->execute(['id_donasi' => $id_donasi, 'id_batch' => $id_batch, 'update_terakhir' => $update_status_batch_terakhir, 'id_status_donasi' => $id_status_donasi ]);

              $affectedrows = $stmt->rowCount();
              if ($affectedrows == '0') {
              header("Location: kelola_batch.php?status=insertfailed");
              } else {
                  //echo "HAHAHAAHA GREAT SUCCESSS !";
                  header("Location: kelola_batch.php?status=addsuccess");
                  }

            }
          }else{
          echo '<script>alert("Harap pilih donasi yang akan ditambahkan")</script>';
        }

        }//submit post end


//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Batch - TKJB</title>
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
                    <?php //if($_SESSION['level_user'] == '1') { ?>
                        <li class="nav-item ">
                           <a href="dashboard_admin.php" class="nav-link ">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item">
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
                        <li class="nav-item menu-open">
                            <a href="kelola_batch.php" class="nav-link active">
                                  <i class="nav-icon fas fa-boxes"></i>
                                  <p> Kelola Batch </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_pemeliharaan.php" class="nav-link">
                                  <i class="nav-icon fas fa-heart"></i>
                                  <p> Kelola Pemeliharaan </p>
                            </a>
                        </li>
                        <li class="nav-item  ">
                             <a href="kelola_jenis_tk.php" class="nav-link">
                                   <i class="nav-icon fas fa-certificate"></i>
                                   <p> Kelola Jenis Terumbu </p>
                             </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_tk.php" class="nav-link">
                                  <i class="nav-icon fas fa-disease"></i>
                                  <p> Kelola Terumbu Karang </p>
                            </a>
                        </li>

                        <li class="nav-item">
                             <a href="kelola_perizinan.php" class="nav-link">
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
                        <a class="btn btn-outline-primary" href="kelola_batch.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Input Data Batch</span></h4>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
        <?php //if($_SESSION['level_user'] == '1') { ?>
            <section class="content">
                <div class="container-fluid bg-white border rounded p-3">
                    <form action="" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <label for="dd_id_lokasi">Lokasi Penanaman</label>
                        <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control" onchange="loadTitik(this.value);" required>
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($rowlokasi as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_lokasi?>">ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dd_id_titik">Titik Penanaman</label>
                        <select id="dd_id_titik" name="dd_id_titik" class="form-control" required>
                          <option value="">Pilih Titik</option>

                        </select>
                    </div>

                    <div class="form-group">
                         <label for="date_penanaman">Perkiraan Tanggal Penanaman</label>
                         <div class="file-form">
                         <input type="date" id="date_penanaman" name="date_penanaman" class="form-control" required>
                         </div>
                     </div>

                     <div class="form-group">
                        <label for="dd_id_donasi">Donasi dapat ditambahkan ke Batch</label>
                            <div id="daftardonasi">
                                <span class="text-muted">Pilih lokasi dahulu</span>
                            </div>

                            <label class="mt-4" for="dd_id_donasi">Donasi dalam Batch</label>
                            <div id="donasipilihan">

                            </div>
                    </div>



                    <br>
                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
            <br><br>

            </section>
        <?php //} ?>
            <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->
        </div>
        <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <br><br>
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
   <br/>







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

    <script async>

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
    function loadTitik(id_lokasi){
      $.ajax({
        type: "POST",
        url: "list_populate.php",
        data:{
            id_lokasi: id_lokasi,
            type: 'load_titik'
        },
        beforeSend: function() {
          $("#dd_id_titik").addClass("loader");
        },
        success: function(data){
          $("#dd_id_titik").html(data);
          $("#dd_id_titik").removeClass("loader");
          loadDonasi(id_lokasi)
        }
      });
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
    }

    function loadDonasi(id_lokasi){
      $.ajax({
        type: "POST",
        url: "list_populate.php",
        data:{
            id_lokasi: id_lokasi,
            type: 'load_donasi'
        },
        beforeSend: function() {
          $("#daftardonasi").addClass("loader");
        },
        success: function(data){
          $("#daftardonasi").html(data);
          $("#daftardonasi").removeClass("loader");
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
        }
      });

    }



    function tambahPilihan(e){
        id_donasi = $(e).siblings('.id_donasi').text()
        pilihanbaru = $(e).parent().clone()
        pilihanbaru.removeClass('batch-donasi')
        pilihanbaru.addClass('batch-pilihan')
        pilihanbaru.children('button').attr('onclick', 'hapusPilihan(this)')
        pilihanbaru.children('button').html('<i class="nav-icon fas fa-times-circle text-danger"></i>')
        pilihanbaru.append(`<input type='hidden' name='id_donasi[]' value='${id_donasi}'>`)


        $(e).parent().fadeOut(function() {
          $(e).parent().remove()
          $(pilihanbaru).appendTo('#donasipilihan').hide()
          $(pilihanbaru).fadeIn()
      });

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
    }

    function hapusPilihan(e){
      pilihanbaru = $(e).parent().clone()
      pilihanbaru.addClass('batch-donasi')
      pilihanbaru.removeClass('batch-pilihan')
      pilihanbaru.children('button').attr('onclick', 'tambahPilihan(this)')
      pilihanbaru.children('input').remove()
      pilihanbaru.children('button').html('<i class="nav-icon fas fa-plus-circle"></i>')


      $(e).parent().fadeOut(function() {
          $(e).parent().remove()
          $(pilihanbaru).appendTo('#daftardonasi').fadeIn().hide()
          $(pilihanbaru).fadeIn()
      });

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
    }


    </script>



</body>
</html>
