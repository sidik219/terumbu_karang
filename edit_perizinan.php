<?php include 'build/config/connection.php';
//session_start();

//if (isset($_SESSION['level_user']) == 0) {
    //header('location: login.php');
//}
  $id_perizinan = $_GET['id_perizinan'];

    $sqlviewlokasi = 'SELECT * FROM t_lokasi
                        ORDER BY nama_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $row = $stmt->fetchAll();

        $sqlviewperizinan = 'SELECT * FROM t_perizinan
                              LEFT JOIN t_status_perizinan ON t_perizinan.id_status_perizinan = t_status_perizinan.id_status_perizinan
                            WHERE id_perizinan = :id_perizinan';
        $stmt = $pdo->prepare($sqlviewperizinan);
        $stmt->execute(['id_perizinan' => $id_perizinan]);
        $rowperizinan = $stmt->fetch();

    if (isset($_POST['submit'])) {
        if($_POST['submit'] == 'Simpan'){
            $judul_perizinan        = $_POST['tb_judul_perizinan'];
            $deskripsi_perizinan        = $_POST['tb_deskripsi_perizinan'];
            $biaya_pergantian     = $_POST['biaya_pergantian_number'];
            $id_titik = array_values(array_unique($_POST['dd_id_titik']));
            $id_status_perizinan     = $_POST['id_status_perizinan'];
            $i = 0;

            $randomstring = substr(md5(rand()), 0, 7);


            $sqlupdateperizinan = "UPDATE t_perizinan
                            SET judul_perizinan = :judul_perizinan, deskripsi_perizinan = :deskripsi_perizinan, biaya_pergantian = :biaya_pergantian, id_status_perizinan = :id_status_perizinan
                            WHERE id_perizinan = :id_perizinan";

            $stmt = $pdo->prepare($sqlupdateperizinan);
            $stmt->execute(['judul_perizinan' => $judul_perizinan,
            'deskripsi_perizinan' => $deskripsi_perizinan, 'biaya_pergantian' => $biaya_pergantian, 'id_status_perizinan' => $id_status_perizinan, 'id_perizinan' => $id_perizinan]);



            if(!empty($_POST['id_dokumen_old_dihapus'])){
              foreach($_POST['id_dokumen_old_dihapus'] as $perizinan_dihapus){
              $id_dokumen_perizinan = $perizinan_dihapus;

              $sqldeletedocperizinan = "DELETE FROM t_dokumen_perizinan
                            WHERE id_dokumen_perizinan = :id_dokumen_perizinan";

            $stmt = $pdo->prepare($sqldeletedocperizinan);
            $stmt->execute(['id_dokumen_perizinan' => $id_dokumen_perizinan]);
            }
            }




            if(!empty($_POST['id_detail_old_dihapus'])){
              foreach($_POST['id_detail_old_dihapus'] as $detail_dihapus){
              $id_detail_perizinan = $detail_dihapus;

              $sqldeletedetperizinan = "DELETE FROM t_detail_perizinan
                            WHERE id_detail_perizinan = :id_detail_perizinan";

            $stmt = $pdo->prepare($sqldeletedetperizinan);
            $stmt->execute(['id_detail_perizinan' => $id_detail_perizinan]);
            }
            }




            if($_FILES['doc_uploads']['size'][0] != 0){
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
                $stmt->execute(['id_perizinan' => $id_perizinan	, 'file_dokumen_perizinan' => $file_dokumen_perizinan, 'nama_dokumen_perizinan' => $nama_dokumen_perizinan]);
                $i++;
                }
            }




            $i = 0;
            if(!empty($id_titik[0])){
                foreach ($id_titik as $titik){
                $id_titik_value = $titik;

                $sqlinserttitikperizinan = "INSERT INTO t_detail_perizinan
                              (id_perizinan, id_titik)
                              VALUES (:id_perizinan, :id_titik)";

              $stmt = $pdo->prepare($sqlinserttitikperizinan);
              $stmt->execute(['id_perizinan' => $id_perizinan	, 'id_titik' => $id_titik_value]);
              $i++;
              }
            }






            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            echo "HAHAHAAHA INSERT FAILED !";
            header("Location: kelola_perizinan.php?status=nochange");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_perizinan.php?status=updatesuccess");
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
                        <li class="nav-item">
                            <a href="kelola_batch.php" class="nav-link">
                                  <i class="nav-icon fas fa-boxes"></i>
                                  <p> Kelola Batch </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola_perizinan.php" class="nav-link">
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

                        <li class="nav-item menu-open">
                             <a href="kelola_perizinan.php" class="nav-link active">
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
                        <a class="btn btn-outline-primary" href="kelola_perizinan.php">< Kembali</a><br><br>
                        <h4><span class="align-middle font-weight-bold">Edit Data Perizinan</h4></span>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
        <?php //if($_SESSION['level_user'] == '1') { ?>
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">
                    <!-- <form action="edit_post_test.php" enctype="multipart/form-data" method="POST"> -->

                    <div class="form-group">
                        <label for="tb_judul_perizinan">Judul Perizinan</label>
                        <input type="text" id="tb_judul_perizinan" value="<?=$rowperizinan->judul_perizinan?>" name="tb_judul_perizinan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tb_deskripsi_perizinan">Deskripsi Perizinan</label>
                        <input type="text" id="tb_deskripsi_perizinan" value="<?=$rowperizinan->deskripsi_perizinan?>" name="tb_deskripsi_perizinan" class="form-control" required>
                    </div>




                    <label>Status :
                            <?php
                                $sqlstatusperizinan = 'SELECT * FROM t_status_perizinan';

                                $stmt = $pdo->prepare($sqlstatusperizinan);
                                $stmt->execute();
                                $rowstatusperizinan = $stmt->fetchAll();
                                $i = 0;
                                foreach ($rowstatusperizinan as $statusperizinan){
                                    ?>
                                    <div class="ml-2 form-check form-check-inline">
                                    <input <?php if($statusperizinan->id_status_perizinan == $rowperizinan->id_status_perizinan){echo 'checked';}?> class="form-check-input" type="radio" name="id_status_perizinan" id="inlineRadio<?=$i?>" value="<?=$statusperizinan->id_status_perizinan?>">
                                    <label class="form-check-label" for="inlineRadio<?=$i?>">
                                        <span class="status-perizinan badge <?php if($statusperizinan->id_status_perizinan == 1){echo 'badge-warning';}elseif($statusperizinan->id_status_perizinan == 2){echo 'badge-success';}
                                        elseif($statusperizinan->id_status_perizinan == 3){echo 'badge-danger';}?> p-2"><?=$statusperizinan->nama_status_perizinan?></span>

                                </label>
                                    </div>
                                <?php $i++; } ?>




                                    </label>





                    <div class="form-group"  id="hapus-div">
                        <label for="dd_id_lokasi">ID Lokasi</label>
                        <select disabled id="dd_id_lokasi" name="dd_id_lokasi" class="form-control mb-2">
                        <option value="">Pilih Lokasi</option>
                            <?php foreach ($row as $rowitem) {
                            ?>
                            <option <?php echo ($rowitem->id_lokasi == $rowperizinan->id_lokasi) ? 'selected' : '' ?> value="<?=$rowitem->id_lokasi?>">ID <?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group" id="titik-container-pilihan">
                        <label for="dd_id_titik">Titik Pilihan Sebelumnya</label>

                        <?php
                           $sqlviewdetailperizinan = 'SELECT * FROM t_detail_perizinan
                                                      LEFT JOIN t_titik ON t_titik.id_titik = t_detail_perizinan.id_titik
                            WHERE id_perizinan = :id_perizinan';
                            $stmt = $pdo->prepare($sqlviewdetailperizinan);
                            $stmt->execute(['id_perizinan' => $id_perizinan]);
                            $rowdetailperizinan = $stmt->fetchAll();
                          foreach($rowdetailperizinan as $detailperizinan){

                        ?>
                        <div class="row mb-3" id="rowtitik_old">
                          <div class="col">
                              <select name="dd_id_titik_old[]" class="form-control" required>
                              <option class="old_titik" selected value="<?=$detailperizinan->id_titik?>">ID <?=$detailperizinan->id_titik?> <?=$detailperizinan->keterangan_titik?></option>
                              <input type="hidden" class="id_detail_perizinan_dihapus" value="<?=$detailperizinan->id_detail_perizinan?>">
                              </select>
                          </div>
                                <div class="col-1">
                                    <span onclick="deleteOldTitikInput(this)" class="btn btn-act"><i class="text-danger fas fa-times-circle"></i> </span>
                                </div>
                        </div>

                        <?php } ?>


                    </div>



                    <div class="form-group" id="titik-container">
                        <label for="dd_id_titik">Titik Tambahan</label>


                        <div class="row mb-3" id="rowtitik">
                          <div class="col">
                              <select id="dd_id_titik" name="dd_id_titik[]" class="form-control">
                              <option value="">Pilih Titik</option>
                              <?php
                              $daftartitik = 'SELECT * FROM t_titik
                                WHERE id_lokasi = :id_lokasi
                                  ORDER BY id_titik';
                                $stmt = $pdo->prepare($daftartitik);
                                $stmt->execute(['id_lokasi' => $rowitem->id_lokasi]);
                                $rowtitik = $stmt->fetchAll();

                            ?>
                            <?php
                                foreach ($rowtitik as $titik) {
                                    ?>
                            <option value="<?php echo $titik->id_titik; ?>">ID <?php echo $titik->id_titik.'  ' .$titik->keterangan_titik .' - '. $titik->luas_titik .' m&#178;'?></option>
                            <?php
                                }
                                ?>
                              </select>
                          </div>
                          <div class="col-1">
                              <span onclick="deleteTitikInput(this)" class="btn btn-act"><i class="text-danger fas fa-times-circle"></i> </span>
                          </div>
                        </div>




                    </div>
                              <p class="text-center"><span onclick="addTitikInput()" class="btn btn-blue btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambah Titik</span></p>


                    <div class='form-group mb-5' id='doc-uploads-old'>
                      <label for='doc_uploads'>Dokumen Proposal Sebelumnya</label>
                          <?php
                                            $sqlviewdocperizinan = 'SELECT * FROM t_dokumen_perizinan
                                            WHERE id_perizinan = :id_perizinan
                                            ORDER BY id_perizinan DESC';
                                            $stmt = $pdo->prepare($sqlviewdocperizinan);
                                            $stmt->execute(['id_perizinan' => $id_perizinan]);
                                            $rowdocperizinan = $stmt->fetchAll();

                                            foreach($rowdocperizinan as $docperizinan){
                                            ?>
                                                <div class="row olddocrow">
                                                  <div class="col border-bottom p-3"><?=$docperizinan->nama_dokumen_perizinan?></div>
                                                  <div class="col-1 border-bottom p-3">
                                                    <input type="hidden" value="<?=$docperizinan->id_dokumen_perizinan?>" class="id_dokumen_perizinan_old">
                                                </div>
                                                <div class="col-1 p-3 border-bottom">
                                                    <span onclick="deleteOldDoc(this)" class="btn btn-act"><i class="text-danger fas fa-times-circle"></i> </span>
                                                </div>
                                                <div class="col border-bottom p-3"><a class="btn btn-blue btn-primary btn-small p-1" href='<?=$docperizinan->file_dokumen_perizinan?>'><i class="fas fa-download"></i> Unduh File</a>

                                              </div>
                                                </div>



                                           <?php } ?>
                    </div>




                    <div class='form-group' id='doc-uploads'>
                      <label for='doc_uploads'>Upload Dokumen Proposal Tambahan</label>
                        <div class="row border rounded shadow-sm mb-4 bg-light p-3" id="rowdocs">
                          <div class="col">
                              <input type='file'  class='form-control mb-2' id='doc_uploads'
                                name='doc_uploads[]' accept='.doc, .docx, .pdf, .xls, .xlsx, .ppt, .pptx, .csv'>
                          </div>
                          <div class="col-auto">
                            <span onclick="deleteDocInput(this)" class="btn btn-act"><i class="text-danger fas fa-times-circle"></i> </span>
                          </div>
                          <div class="form-group col-12">
                        <label for="tb_id_user">Nama Dokumen</label>
                        <input type="text" id="tb_nama_dokumen_perizinan" name="tb_nama_dokumen_perizinan[]" class="form-control">
                        </div>
                      </div>
                    </div>

                    <p class="text-center"><span onclick="addDocInput()" class="btn btn-blue btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambah File Proposal</span></p>




                    <div class="form-group">
                        <label for="num_biaya_pergantian">Biaya Pergantian</label>
                        <input type="hidden" value="<?=$rowperizinan->biaya_pergantian?>" id="biaya_pergantian_number" name="biaya_pergantian_number">
                        <div class="row">
                          <div class="col-auto text-center p-2">
                            Rp.
                          </div>
                          <div class="col">
                            <input onkeyup="formatNumber(this)" value="<?=number_format($rowperizinan->biaya_pergantian)?>" type="text" id="num_biaya_pergantian" name="num_biaya_pergantian" class="form-control number-input" required>
                          </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="tb_id_user">ID User Pemohon</label>
                        <input type="number" value="<?=$rowperizinan->id_user?>" id="tb_id_user" name="tb_id_user" class="form-control">
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


      function deleteOldDoc(e){
        var parent_row = $(e).parent().parent()
        var id_dokumen_old = parent_row.find('.id_dokumen_perizinan_old').val()

        $(`<input type="text" value="${id_dokumen_old}" class="id_doc_old_hidden" name="id_dokumen_old_dihapus[]">`).appendTo('#hapus-div')
        parent_row.fadeOut(function(){
          parent_row.remove()
        })

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

      function deleteOldTitikInput(e){
        var parent_row = $(e).parent().parent()
        var id_detail_p_hapus = parent_row.find('.id_detail_perizinan_dihapus').val()
        var input_id_detail = document.createElement('input')
        input_id_detail.type = 'text'
        input_id_detail.value = id_detail_p_hapus
        input_id_detail.name = 'id_detail_hapus[]'
        document.getElementById('hapus-div').appendChild(input_id_detail)

        parent_row.fadeOut(function(){
          parent_row.remove()
        })
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
  $('#biaya_pergantian_number').val(formattedNumber)
  $('#num_biaya_pergantian').val(formatter.format(formattedNumber))
}



    </script>

</body>
</html>
