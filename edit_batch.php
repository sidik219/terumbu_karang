<?php
session_start();
if(!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
include 'build/config/connection.php';
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$id_batch = $_GET['id_batch'];

$sqlviewlokasi = 'SELECT * FROM t_lokasi
                        ORDER BY id_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $rowlokasi = $stmt->fetchAll();

         $sqlstatus = 'SELECT * FROM t_status_batch';
    $stmt = $pdo->prepare($sqlstatus);
    $stmt->execute();
    $rowstatus = $stmt->fetchAll();




        $sqlviewbatch = 'SELECT t_batch.id_batch, t_batch.id_lokasi, t_batch.id_titik, t_batch.tanggal_penanaman,
                      t_batch.update_status_batch_terakhir, nama_lokasi, keterangan_titik, nama_status_batch, t_batch.id_status_batch, status_cabut_label
                      FROM t_batch
                      LEFT JOIN t_lokasi ON t_batch.id_lokasi = t_lokasi.id_lokasi
                      LEFT JOIN t_titik ON t_batch.id_titik = t_titik.id_titik
                      LEFT JOIN t_status_batch ON t_batch.id_status_batch = t_status_batch.id_status_batch
                      WHERE id_batch = :id_batch';
        $stmt = $pdo->prepare($sqlviewbatch);
        $stmt->execute(['id_batch' => $id_batch]);
        $rowbatch = $stmt->fetch();

        $sqlviewdonasi = 'SELECT *, SUM(t_detail_donasi.jumlah_terumbu) AS jumlah_bibit_donasi FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                  LEFT JOIN t_detail_donasi ON  t_donasi.id_donasi = t_detail_donasi.id_donasi
                  WHERE t_donasi.id_status_donasi = 3 AND t_donasi.id_lokasi = :id_lokasi GROUP BY t_detail_donasi.id_donasi';
        $stmt = $pdo->prepare($sqlviewdonasi);
        $stmt->execute(['id_lokasi' => $rowbatch->id_lokasi]);
        $rowdonasi = $stmt->fetchAll();

        $sqlviewtitik = 'SELECT * FROM t_titik WHERE id_lokasi = :id_lokasi
                        ORDER BY id_titik';
        $stmt = $pdo->prepare($sqlviewtitik);
        $stmt->execute(['id_lokasi' => $rowbatch->id_lokasi]);
        $rowtitik = $stmt->fetchAll();

        $sqlviewdetailbatch = 'SELECT t_donasi.id_donasi, nama_donatur, SUM(t_detail_donasi.jumlah_terumbu) AS jumlah_bibit_donasi FROM `t_detail_batch`
                              LEFT JOIN t_donasi ON t_donasi.id_batch = t_detail_batch.id_batch
                              LEFT JOIN t_detail_donasi ON  t_donasi.id_donasi = t_detail_donasi.id_donasi
                              WHERE t_detail_batch.id_batch = :id_batch
                              AND t_donasi.id_donasi = t_detail_batch.id_donasi GROUP BY t_detail_donasi.id_donasi';
        $stmt = $pdo->prepare($sqlviewdetailbatch);
        $stmt->execute(['id_batch' => $id_batch]);
        $rowdetailbatch = $stmt->fetchAll();

        $sqlviewjumlah = 'SELECT t_donasi.id_donasi, nama_donatur, SUM(t_detail_donasi.jumlah_terumbu) AS jumlah_bibit_donasi FROM `t_detail_batch`
                              LEFT JOIN t_donasi ON t_donasi.id_batch = t_detail_batch.id_batch
                              LEFT JOIN t_detail_donasi ON  t_donasi.id_donasi = t_detail_donasi.id_donasi
                              WHERE t_detail_batch.id_batch = :id_batch
                              AND t_donasi.id_donasi = t_detail_batch.id_donasi';
        $stmt = $pdo->prepare($sqlviewjumlah);
        $stmt->execute(['id_batch' => $id_batch]);
        $rowjumlah = $stmt->fetch();



        if (isset($_POST['submit'])) {  // SUBMIT QUERIES ------------------!
        $id_titik        = $_POST['dd_id_titik'];
        $tanggal_penanaman        = $_POST['date_penanaman'];
        $status_cabut_label = $_POST['radio_label'];

        $update_status_batch_terakhir = date ('Y-m-d H:i:s', time());
        $id_status_batch = $_POST['radio_status'];

          //Kosongkan entry batch dari t_detail_batch
        $sqldeleteisibatch = "DELETE FROM t_detail_batch
                        WHERE id_batch = :id_batch";

        $stmt = $pdo->prepare($sqldeleteisibatch);
        $stmt->execute(['id_batch' => $id_batch]);

        $sqlinsertbatch = "UPDATE t_batch
                        SET tanggal_penanaman = :tanggal_penanaman, update_status_batch_terakhir = :update_status_batch_terakhir, id_status_batch = :id_status_batch, id_titik = :id_titik, status_cabut_label = :status_cabut_label
                        WHERE id_batch = :id_batch";

        $stmt = $pdo->prepare($sqlinsertbatch);
        $stmt->execute(['tanggal_penanaman' => $tanggal_penanaman, 'update_status_batch_terakhir' => $update_status_batch_terakhir, 'id_status_batch' => $id_status_batch, 'id_titik' => $id_titik, 'id_batch' => $id_batch, 'status_cabut_label' => $status_cabut_label]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
        //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            $last_batch_id = $pdo->lastInsertId();
            }

            foreach($_POST['id_donasi'] as $id_donasi_value){ //Insert ke t_detail_batch
              $id_donasi = $id_donasi_value;
              $id_status_donasi = 4;
              $id_batch = $_GET['id_batch'];

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
            }

            if(isset($_POST['id_donasi_dihapus'])){
              foreach($_POST['id_donasi_dihapus'] as $id_donasi_value){ //Delete list hapus dari t_detail_batch
              $id_donasi = $id_donasi_value;
              $id_batch = NULL;
              $id_status_donasi = 3;

              $sqlinsertdetailbatch = "DELETE FROM t_detail_batch
                        WHERE id_donasi = :id_donasi AND  id_batch = :id_batch";

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
              header("Location: kelola_batch.php?status=updatefailed");
              } else {
                  //echo "HAHAHAAHA GREAT SUCCESSS !";
                  header("Location: kelola_batch.php?status=updatesuccess");
                  }

              }

            }header("Location: kelola_batch.php?status=updatesuccess");




        }

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
                        <a class="btn btn-outline-primary" href="kelola_batch.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Edit Data Batch</span></h4>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid bg-white p-3">
                    <form action="" enctype="multipart/form-data" method="POST">

                     <div class="col-12 mb-2 border rounded bg-white p-3">
                  <h5 class="font-weight-bold">Status Batch</h5>

                  <?php
                    foreach($rowstatus as $status){
                  ?>

                  <div class="form-check mb-2">
                  <input class="form-check-input" type="radio" name="radio_status" id="radio_status<?=$status->id_status_batch?>" value="<?=$status->id_status_batch?>" <?php if($rowbatch->id_status_batch == $status->id_status_batch) echo " checked"; ?>>
                  <label class="form-check-label <?php if($rowbatch->id_status_batch == $status->id_status_batch) echo " font-weight-bold"; ?>" for="radio_status<?=$status->id_status_batch?>">
                    <?=$status->nama_status_batch?>
                  </label>
                </div>

                    <?php }?>


                          <div class="col-12 mt-3 mb-2 border rounded bg-white p-3 text-sm">
                          <span class="font-weight-bold"><i class="fas fa-tag text-warning"></i> Status Label</span>

                                <div class="form-check mt-2 mb-2">
                                  <input class="form-check-input" type="radio" name="radio_label" id="radio_label0" value="0" <?php if($rowbatch->status_cabut_label == 0) echo " checked"; ?>>
                                  <label class="form-check-label <?php if($rowbatch->status_cabut_label == 0) echo " font-weight-bold"; ?>" for="radio_label0">
                                    Belum Cabut
                                  </label>
                              </div>

                              <div class="form-check mt-2 mb-2">
                                  <input class="form-check-input" type="radio" name="radio_label" id="radio_label1" value="1" <?php if($rowbatch->status_cabut_label == 1) echo " checked"; ?>>
                                  <label class="form-check-label <?php if($rowbatch->status_cabut_label == 1) echo " font-weight-bold"; ?>" for="radio_label1">
                                    Sudah Cabut
                                  </label>
                              </div>

                          </div>

                <button type="submit" name="submit" value="Simpan" class="btn btn-primary btn-blue mt-2">Update Status</button></p>

          </div>

                    <div class="form-group">
                        <label for="dd_id_lokasi">Lokasi Penanaman</label>
                        <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control-plaintext" onChange="loadTitik(this.value);" readonly disabled required>
                            <option value="">Pilih Lokasi</option>
                            <?php foreach ($rowlokasi as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_lokasi?>" <?php if($rowitem->id_lokasi == $rowbatch->id_lokasi) echo "selected";?>>ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dd_id_titik">Titik Penanaman</label>
                        <select id="dd_id_titik" name="dd_id_titik" class="form-control" required>
                          <option value="">Pilih Titik</option>
                          <?php
                            foreach ($rowtitik as $titik) {
                                ?>
                            <option value="<?php echo $titik->id_titik; ?>" <?php if($titik->id_titik == $rowbatch->id_titik) echo "selected";?>>ID <?php echo $titik->id_titik.'  ' .$titik->keterangan_titik ?></option>
                          <?php
                            }?>

                        </select>
                    </div>

                    <div class="form-group">
                         <label for="date_penanaman">Perkiraan Tanggal Penanaman</label>
                         <div class="file-form">
                         <input type="date" id="date_penanaman" name="date_penanaman" value="<?=$rowbatch->tanggal_penanaman?>" class="form-control" required>
                         </div>
                     </div>


                      <label class="mt-4" for="dd_id_donasi">Donasi baru yang dapat Ditambahkan</label>
                            <div id="daftardonasi">

                            <?php
                              foreach ($rowdonasi as $donasi) {
                                  ?>
                          <div class="border rounded p-1 batch-donasi mb-2 shadow-sm" id="donasi<?=$donasi->id_donasi?>">
                            ID <span class="id_donasi"><?=$donasi->id_donasi?></span> -
                            <span class="nama_donatur"><?=$donasi->nama_donatur?></span>  || Jumlah : <span class="jumlah"><?=$donasi->jumlah_bibit_donasi?></span>
                            <a data-id='<?=$donasi->id_donasi?>' class="btn btn-sm btn-outline-primary  userinfo">Rincian></a>
                            <button type="button" class="btn donasitambah" onclick="tambahPilihan(this)"><i class="nav-icon fas fa-plus-circle"></i></button>
                          </div>
                          <?php
                              }?>

                            </div>

                     <label class="mt-4" for="dd_id_donasi">Donasi dalam Batch : <span id="jumlah_bibit"><?= $rowjumlah->jumlah_bibit_donasi ?></span> Bibit</label>
                            <div id="donasipilihan">

                            <?php
                              foreach ($rowdetailbatch as $detailbatch) {
                                  ?>
                          <div class="border rounded p-1 batch-donasi mb-2 shadow-sm" id="donasi<?=$detailbatch->id_donasi?>">
                            ID <span class="id_donasi"><?=$detailbatch->id_donasi?></span> -
                            <span class="nama_donatur"><?=$detailbatch->nama_donatur?></span>   || Jumlah : <span class="jumlah"><?=$detailbatch->jumlah_bibit_donasi?></span>
                            <a data-id='<?=$detailbatch->id_donasi?>' class="btn btn-sm btn-outline-primary userinfo">Rincian></a>
                            <button type="button" class="btn donasitambah" onclick="hapusPilihan(this)"><i class="nav-icon fas fa-times-circle text-danger"></i></button>
                            <input type='hidden' name='id_donasi[]' value='<?=$detailbatch->id_donasi?>'>
                          </div>
                          <?php
                              }?>
                            </div>




                    <br>
                    <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
                    </form>
            <br><br>

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
    <br><br>
    <footer class="main-footer">
        <?= $footer ?>
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
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <script async>
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
        }
      });
    }



    function tambahPilihan(e){
        id_donasi = $(e).siblings('.id_donasi').text()
        jumlah_bibit = parseInt($(e).siblings('.jumlah').text())
        pilihanbaru = $(e).parent().clone()
        pilihanbaru.children('button').attr('onclick', 'hapusPilihan(this)')
        pilihanbaru.children('button').html('<i class="nav-icon fas fa-times-circle text-danger"></i>')
        pilihanbaru.children('input').remove()
        pilihanbaru.append(`<input type='hidden' name='id_donasi[]' value='${id_donasi}'>`)


        $(e).parent().fadeOut(function() {
          $(e).parent().remove()
          $(pilihanbaru).appendTo('#donasipilihan').fadeIn().hide()
          $(pilihanbaru).fadeIn()
          jumlah_total = parseInt($('#jumlah_bibit').text())
          $('#jumlah_bibit').text(jumlah_bibit + jumlah_total)
      });
    }

    function hapusPilihan(e){
      id_donasi = $(e).siblings('.id_donasi').text()
      jumlah_bibit_hapus = parseInt($(e).siblings('.jumlah').text())
      jumlah_total = parseInt($('#jumlah_bibit').text())
      $('#jumlah_bibit').text(jumlah_total - jumlah_bibit_hapus)
      pilihanbaru = $(e).parent().clone()
      pilihanbaru.addClass('batch-donasi')
      pilihanbaru.removeClass('batch-pilihan')
      pilihanbaru.children('button').attr('onclick', 'tambahPilihan(this)')
      pilihanbaru.children('input').remove()
      pilihanbaru.children('button').html('<i class="nav-icon fas fa-plus-circle"></i>')
      pilihanbaru.append(`<input type='hidden' name='id_donasi_dihapus[]' value='${id_donasi}'>`)



      $(e).parent().fadeOut(function() {
          $(e).parent().remove()
          $(pilihanbaru).appendTo('#daftardonasi').fadeIn().hide()
          $(pilihanbaru).fadeIn()
      });
    }


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
