<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

    $sqlviewlokasi = 'SELECT * FROM t_lokasi
                        ORDER BY nama_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $row = $stmt->fetchAll();

    if (isset($_POST['submit'])) {
        if($_POST['submit'] == 'Simpan'){
            $id_lokasi = $_POST['dd_id_lokasi'];
            $judul_perizinan        = $_POST['tb_judul_perizinan'];
            $deskripsi_perizinan        = $_POST['tb_deskripsi_perizinan'];
            $biaya_pergantian     = $_POST['biaya_pergantian_number'];
            $id_user     = $_POST['tb_id_user'];
            $id_titik[] = array_values(array_unique($_POST['dd_id_titik']));
            $id_status_perizinan     = 1;
            $tanggal_perizinan = date ('Y-m-d H:i:s', time());
            $nama_pemohon        = $_POST['tb_nama_pemohon'];
            $perusahaan_pemohon        = $_POST['tb_perusahaan_pemohon'];

            $i = 0;

            $randomstring = substr(md5(rand()), 0, 7);


            $sqlinsertperizinan = "INSERT INTO t_perizinan
                            (id_lokasi, judul_perizinan, deskripsi_perizinan, biaya_pergantian, id_user,
                            id_status_perizinan, tanggal_perizinan, nama_pemohon, perusahaan_pemohon)
                            VALUES (:id_lokasi, :judul_perizinan, :deskripsi_perizinan, :biaya_pergantian, :id_user,
                            :id_status_perizinan, :tanggal_perizinan, :nama_pemohon, :perusahaan_pemohon)";

            $stmt = $pdo->prepare($sqlinsertperizinan);
            $stmt->execute(['id_lokasi' => $id_lokasi, 'judul_perizinan' => $judul_perizinan,
            'deskripsi_perizinan' => $deskripsi_perizinan, 'biaya_pergantian' => $biaya_pergantian,
            'id_user' => $id_user,
            'id_status_perizinan' => $id_status_perizinan, 'tanggal_perizinan' => $tanggal_perizinan, 'nama_pemohon' => $nama_pemohon, 'perusahaan_pemohon' => $perusahaan_pemohon]);

            $last_id_perizinan = $pdo->lastInsertId();




            foreach($_FILES['doc_uploads']['name'] as $document){
              $nama_dokumen_perizinan = $_POST['tb_nama_dokumen_perizinan'][$i];
              //document upload
            if($_FILES["doc_uploads"]["size"] == 0) {
                $file_dokumen_perizinan = "";
            }
            else if (isset($_FILES['doc_uploads'])) {
                $target_dir  = "documents/Perizinan/";
                $target_file = $_FILES["doc_uploads"]['name'][$i];
                $file_dokumen_perizinan = $target_dir .'IZIN_'.$target_file.$randomstring.'.'. pathinfo($target_file,PATHINFO_EXTENSION);
                move_uploaded_file($_FILES["doc_uploads"]["tmp_name"][$i], $file_dokumen_perizinan);
            }

            //---document upload end
              $sqlinsertdocperizinan = "INSERT INTO t_dokumen_perizinan
                            (id_perizinan	,file_dokumen_perizinan, nama_dokumen_perizinan)
                            VALUES (:id_perizinan	, :file_dokumen_perizinan, :nama_dokumen_perizinan)";

            $stmt = $pdo->prepare($sqlinsertdocperizinan);
            $stmt->execute(['id_perizinan' => $last_id_perizinan	, 'file_dokumen_perizinan' => $file_dokumen_perizinan, 'nama_dokumen_perizinan' => $nama_dokumen_perizinan]);
            $i++;
            }



            $i = 0;
            foreach ($id_titik[0] as $titik){
              $id_titik_value = $titik;
              $id_zona_titik = 4;
              //Daftar titik yg dipakai
              $sqlinserttitikperizinan = "INSERT INTO t_detail_perizinan
                            (id_perizinan, id_titik)
                            VALUES (:id_perizinan, :id_titik)";

            $stmt = $pdo->prepare($sqlinserttitikperizinan);
            $stmt->execute(['id_perizinan' => $last_id_perizinan	, 'id_titik' => $id_titik_value]);

            //update status titik di t_titik

              $sqlupdatetitikperizinan = "UPDATE t_titik
                                        SET id_zona_titik = :id_zona_titik
                                        WHERE id_titik = :id_titik";

            $stmt = $pdo->prepare($sqlupdatetitikperizinan);
            $stmt->execute(['id_zona_titik' => $id_zona_titik	, 'id_titik' => $id_titik_value]);

            $i++;
            }





            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            echo "HAHAHAAHA INSERT FAILED !";
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_perizinan.php?status=addsuccess");
                }
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Perizinan - TKJB</title>
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
                        <a class="btn btn-outline-primary" href="kelola_perizinan.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Input Data Perizinan</h4></span>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
        <?php if($_SESSION['level_user'] == '2') { ?>
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">
                    <!-- <form action="edit_post_test.php" enctype="multipart/form-data" method="POST"> -->
                    <div class="form-group">
                        <label for="tb_judul_perizinan">Judul Perizinan</label>
                        <input type="text" id="tb_judul_perizinan" name="tb_judul_perizinan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tb_deskripsi_perizinan">Deskripsi Perizinan</label>
                        <input type="text" id="tb_deskripsi_perizinan" name="tb_deskripsi_perizinan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tb_nama_perizinan">Nama Pemohon</label>
                        <input type="text" id="tb_nama_perizinan" name="tb_nama_pemohon" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tb_perusahaan_perizinan">Perusahaan Pemohon</label>
                        <input type="text" id="tb_perusahaan_perizinan" name="tb_perusahaan_pemohon" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="dd_id_lokasi">ID Lokasi</label>
                        <select id="dd_id_lokasi" name="dd_id_lokasi" class="form-control mb-2" onchange="loadTitik(this.value);">
                        <option value="">Pilih Lokasi</option>
                            <?php foreach ($row as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_lokasi?>">ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group" id="titik-container">
                        <label for="dd_id_titik">Titik Bersangkutan</label>
                        <div class="row mb-3" id="rowtitik">
                          <div class="col">
                              <select id="dd_id_titik" name="dd_id_titik[]" class="form-control" required>
                              <option value="">Pilih Titik</option>
                              </select>
                          </div>
                          <div class="col-1">
                              <span onclick="deleteTitikInput(this)" class="btn btn-act"><i class="text-danger fas fa-times-circle"></i> </span>
                          </div>
                        </div>

                    </div>
                              <p class="text-center"><span onclick="addTitikInput()" class="btn btn-blue btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambah Titik</span></p>


                    <div class='form-group' id='doc-uploads'>
                      <label for='doc_uploads'>Upload Dokumen Proposal</label>
                        <div class="row border rounded shadow-sm mb-4 bg-light p-3" id="rowdocs">
                          <div class="col">
                              <input type='file'  class='form-control mb-2' id='doc_uploads'
                                name='doc_uploads[]' accept='.doc, .docx, .pdf, .xls, .xlsx, .ppt, .pptx, .csv' required>
                          </div>
                          <div class="col-auto">
                            <span onclick="deleteDocInput(this)" class="btn btn-act"><i class="text-danger fas fa-times-circle"></i> </span>
                          </div>
                          <div class="form-group col-12">
                        <label for="tb_id_user">Nama Dokumen</label>
                        <input type="text" id="tb_nama_dokumen_perizinan" name="tb_nama_dokumen_perizinan[]" class="form-control" required>
                    </div>
                        </div>
                    </div>

                    <p class="text-center"><span onclick="addDocInput()" class="btn btn-blue btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambah File Proposal</span></p>

                    <div class="form-group">
                        <label for="num_biaya_pergantian">Biaya Pergantian</label>
                        <input type="hidden" id="biaya_pergantian_number" name="biaya_pergantian_number" value="">
                        <div class="row">
                          <div class="col-auto text-center p-2">
                            Rp.
                          </div>
                          <div class="col">
                            <input onkeyup="formatNumber(this)" type="text" id="num_biaya_pergantian" name="num_biaya_pergantian" class="form-control number-input" required>
                          </div>
                        </div>

                    </div>
                    <div class="form-group">
                    <div class="form-group">
                        <!-- <label for="tb_id_user">ID User Pemohon</label> -->
                        <input type="hidden" id="tb_id_user" name="tb_id_user" class="form-control" value="1">
                    </div>
                    <div class="form-group">


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
    <br><br>
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
      function addDocInput(){
        var new_input_field = $('#rowdocs').clone().addClass('deleteable')
        $(new_input_field).appendTo("#doc-uploads").hide().fadeIn();
      }




      function deleteDocInput(e){
        var parent_row = $(e).parent().parent()
        if(parent_row.is('.deleteable')){
          parent_row.fadeOut(function(){
            parent_row.remove()
          })
        }
      }



      function addTitikInput(){
        var new_input_field = $('#rowtitik').clone().addClass('deleteable')
        $(new_input_field).appendTo("#titik-container").hide().fadeIn();
      }




      function deleteTitikInput(e){
        var parent_row = $(e).parent().parent()
        if(parent_row.is('.deleteable')){
          parent_row.fadeOut(function(){
            parent_row.remove()
          })
        }
      }



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


    var formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0

    // These options are needed to round to whole numbers if that's what you want.
    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});

function formatNumber(e){
      var formattedNumber = parseInt(e.value.replace(/\,/g,''))
      if(!isNaN(formattedNumber)){
        $('#biaya_pergantian_number').val(formattedNumber)
        $('#num_biaya_pergantian').val(formatter.format(formattedNumber))
      }else{
        $('#biaya_pergantian_number').val('0')
        $('#num_biaya_pergantian').val('0')
      }
}



    </script>

</body>
</html>
