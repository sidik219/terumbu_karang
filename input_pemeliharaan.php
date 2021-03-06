<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewlokasi = 'SELECT * FROM t_lokasi
                        ORDER BY id_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $rowlokasi = $stmt->fetchAll();




    if (isset($_POST['submit'])) {
      if (isset($_POST['id_batch'])){
              $id_lokasi        = $_POST['dd_id_lokasi'];
        $tanggal_pemeliharaan        = $_POST['date_pemeliharaan'];

        $update_status_batch_terakhir = date ('Y-m-d H:i:s', time());
        $id_status_pemeliharaan = 1;


        $sqlinsertbatch = "INSERT INTO t_pemeliharaan
                        (id_lokasi, tanggal_pemeliharaan, id_status_pemeliharaan)
                        VALUES (:id_lokasi, :tanggal_pemeliharaan, :id_status_pemeliharaan)";

        $stmt = $pdo->prepare($sqlinsertbatch);
        $stmt->execute(['id_lokasi' => $id_lokasi, 'tanggal_pemeliharaan' => $tanggal_pemeliharaan, 'id_status_pemeliharaan' => $id_status_pemeliharaan]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
        echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            $last_pemeliharaan_id = $pdo->lastInsertId();
            }

            foreach($_POST['id_batch'] as $id_batch_value){ //Insert ke t_detail_pemeliharaan
              $id_batch = $id_batch_value;
              $id_pemeliharaan = $last_pemeliharaan_id;

              $sqlinsertdetailpemeliharaan = "INSERT INTO t_detail_pemeliharaan
                        (id_pemeliharaan, id_batch)
                        VALUES (:id_pemeliharaan, :id_batch)";

              $stmt = $pdo->prepare($sqlinsertdetailpemeliharaan);
              $stmt->execute(['id_pemeliharaan' => $id_pemeliharaan, 'id_batch' => $id_batch]);


              //Update dan set id_status_batch ke batch pilihan
              $sqlbatch = "UPDATE t_batch
                        SET update_status_batch_terakhir = :update_status_batch_terakhir, id_status_batch = :id_status_batch
                        WHERE id_batch = :id_batch";

              $stmt = $pdo->prepare($sqlbatch);
              $stmt->execute(['id_batch' => $id_batch, 'update_status_batch_terakhir' => $update_status_batch_terakhir, 'id_status_batch' => 3 ]);

              $affectedrows = $stmt->rowCount();
              if ($affectedrows == '0') {
              header("Location: kelola_batch.php?status=insertfailed");
              } else {
                  //echo "HAHAHAAHA GREAT SUCCESSS !";
                  header("Location: kelola_batch.php?status=addsuccess");
                  }

            }

            foreach($_POST['id_donasi'] as $id_donasi_value){ ////Update dan set id_status_donasi ke donasi dalam batch pilihan
              $id_donasi = $id_donasi_value;
              $id_status_donasi = 5;
               $update_terakhir = date ('Y-m-d H:i:s', time());

              $sqlbatch = "UPDATE t_donasi
                        SET update_terakhir = :update_terakhir, id_status_donasi = :id_status_donasi
                        WHERE id_donasi = :id_donasi";

              $stmt = $pdo->prepare($sqlbatch);
              $stmt->execute(['id_donasi' => $id_donasi, 'update_terakhir' => $update_terakhir, 'id_status_donasi' => $id_status_donasi ]);

              $affectedrows = $stmt->rowCount();
              if ($affectedrows == '0') {
              header("Location: kelola_pemeliharaan.php?status=insertfailed");
              } else {
                  //echo "HAHAHAAHA GREAT SUCCESSS !";
                  header("Location: kelola_pemeliharaan.php?status=addsuccess");
                  }

            }


          }else{
          echo '<script>alert("Harap pilih batch yang akan ditambahkan")</script>';
        }

        }//submit post end


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
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                    <div class="container-fluid">
                        <a class="btn btn-outline-primary" href="kelola_pemeliharaan.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Input Rencana Pemeliharaan</h4></span>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
        <?php if($_SESSION['level_user'] == '3') { ?>
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                        <label for="dd_id_lokasi">Lokasi Pemeliharaan</label>
                        <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control" onchange="loadBatch(this.value);" required>
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($rowlokasi as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_lokasi?>">ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                         <label for="date_pemeliharaan">Rencana Tanggal Pemeliharaan</label>
                         <input type="date" id="date_pemeliharaan" name="date_pemeliharaan" class="form-control" required>
                     </div>

                     <div class="form-group">
                        <label for="dd_id_batch">Tambah Batch</label>
                            <div id="daftarbatch">
                                <span class="text-muted">Pilih lokasi dahulu</span>
                            </div>

                            <label class="mt-4" for="dd_id_batch">Batch Ditambahkan</label>
                            <div id="batchpilihan">

                            </div>
                    </div>


                    <br>
                    <p align="center">
                         <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
            <br><br>

            </section>
        <?php } ?>
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


    <br><br>
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


    <script>


      function loadBatch(id_lokasi){
      $.ajax({
        type: "POST",
        url: "list_populate.php",
        data:{
            id_lokasi: id_lokasi,
            type: 'load_batch'
        },
        beforeSend: function() {
          $("#daftarbatch").addClass("loader");
        },
        success: function(data){
          $("#daftarbatch").html(data);
          $("#daftarbatch").removeClass("loader");
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
        pilihanbaru.children('button').html('Hapus <i class="nav-icon fas fa-times-circle text-danger"></i>')
        pilihanbaru.append(`<input type='hidden' name='id_batch[]' value='${id_donasi}'>`)

        $(e).parent().find('.id_donasi_append').each(function (getIdDonasi){
          id_donasi_append = $(this).text()
          pilihanbaru.append(`<input type='hidden' name='id_donasi[]' value='${id_donasi_append}'>`)
        })



        $(e).parent().fadeOut(function() {
          $(e).parent().remove()
          $(pilihanbaru).appendTo('#batchpilihan').fadeIn().hide()
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
      pilihanbaru.children('input').remove()
      pilihanbaru.children('button').attr('onclick', 'tambahPilihan(this)')
      pilihanbaru.children('button').html('Tambahkan <i class="nav-icon fas fa-plus-circle"></i>')


      $(e).parent().fadeOut(function() {
          $(e).parent().remove()
          $(pilihanbaru).appendTo('#daftarbatch').fadeIn().hide()
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
