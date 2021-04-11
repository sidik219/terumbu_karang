<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

    $sqlviewrekeningbersama = 'SELECT * FROM t_rekening_bank';
    $stmt = $pdo->prepare($sqlviewrekeningbersama);
    $stmt->execute();
    $rowdetail = $stmt->fetchAll();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Rekening Bersama - GoKarang</title>
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
            <div class="content-header pb-0">
                <div class="container-fluid">
                      <a href="kelola_lokasi.php"><button class="btn btn-warning btn-back mb-2" type="button"><i class="fas fa-angle-left"></i> Kembali</button></a>
                <h4><span class="align-middle font-weight-bold">Kelola Rekening Bersama</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="terumbu-karang form-group mt-0">
                      <label class="text-muted text-sm d-block mt-0">Tentukan rekening yang digunakan sebagai metode pembayaran donasi</label>
                      <div class="col text-center">
                                <span onclick="//addDocInput()" data-toggle="modal" data-target=".tambah-modal" class="btn btn-blue btn btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambah Rekening</span>
                              </div>

                    <table class="table table-striped table-responsive-sm">
                    <thead>
                            <tr>
                            <th scope="col">ID Rekening</th>
                            <th scope="col">Nama Pemilik</th>
                            <th scope="col">Nomor Rekening</th>
                            <th scope="col">Bank</th>
                            <th class="" scope="col">Aksi</th>
                            </tr>
                        </thead>
                    <tbody id="tbody-append">
                        <?php foreach ($rowdetail as $rowitem) { ?>
                            <tr>
                            <th scope="row">
                              <?=$rowitem->id_terumbu_karang?>
                          </th>
                            <td><?=$rowitem->nama_jenis?></td>
                            <td><?=$rowitem->nama_terumbu_karang?></td>
                            <td>Rp. <?=number_format($rowitem->harga_patokan_lokasi)?></td>
                            <td>
                              <?=$rowitem->stok_terumbu?>
                              <br><?=alertStokTerumbu($rowitem->stok_terumbu) ?>
                          </td>
                            <td class="">
                                <a href="#" onclick='loadPatokanTerumbu(this.dataset.id_detail_lokasi)'
                                data-nama_jenis='<?=$rowitem->nama_terumbu_karang?>' data-id_tk='<?=$rowitem->id_terumbu_karang?>'
                                data-harga_patokan='<?=$rowitem->harga_patokan_lokasi?>' data-id_detail_lokasi='<?=$rowitem->id_detail_lokasi?>' class="fas fa-edit mr-3 btn btn-act"></a>
                                <a href="hapus.php?type=detail_lokasi&id_detail_lokasi=<?=$rowitem->id_detail_lokasi?>&id_lokasi=<?=$id_lokasi?>" class="far fa-trash-alt btn btn-act"></a>
                                </td>
                            </tr>
                           <?php } ?>
                    </tbody>
                  </table>


<!-- data-toggle="modal" data-target=".edit-modal" -->


            <!-- BUTTON SUBMIT -->


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
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>






<!-- Tambah Large modal -->
<div class="modal fade tambah-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Tambah Rekening</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="tambah_form">
      <div class="col border rounded p-2 bg-light">
                            <div class="row">
                              <div class="col">
                        <div class="row mt-2">
                          <div class="col">
                            <label for="tb_pemilik">Nama Pemilik Rekening</label>
                            <input type="text" id="tb_pemilik" name="nama_pemilik_rekening" class="form-control" required>
                          </div>
                        </div>
                        <div class="row mt-2">
                          <div class="col">
                            <label for="tb_norek">Nomor Rekening</label>
                            <input type="text" id="tb_norek" name="nomor_rekening" class="form-control" required>
                          </div>
                        </div>
                        <div class="row mt-2">
                          <div class="col">
                            <label for="tb_pemilik">Nama Bank</label>
                            <input type="text" id="tb_pemilik" name="nama_pemilik_rekening" class="form-control" required>
                          </div>
                        </div>
                        </form>
                              <div class="col text-center">
                                <span onclick="simpanRekening()" class="btn btn-blue btn-sm btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambahkan</span>
                                <button type="button" class="btn-sm btn-secondary rounded-pill border-0" data-dismiss="modal">Batal</button>
                              </div>
                            </div>
                      </div>

                    </div>
                <?php //} ?>
    </div>
  </div>
</div>

<!-- Tambah modal end -->





<!-- Edit Large modal -->
<div class="modal fade edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Edit Harga Terumbu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit_form">



      </form>
                              <div class="col text-center">
                                <span onclick="updatePatokanTerumbu()" class="btn btn-blue btn-sm  btn-primary mt-2 mb-2 text-center"><i class="fas fa-save"></i> Simpan</span>
                                <button type="button" class="btn-sm btn-secondary rounded-pill border-0" data-dismiss="modal">Batal</button>
                              </div>
                            </div>
                      </div>

                    </div>
                <?php //} ?>
    </div>
  </div>
</div>

<!-- Edit modal end -->

<script>
  function simpanPatokanTerumbu(){
    var isiform = $('#tambah_form').serialize()
    $.ajax({
        type:'POST',
        url:'proses_form.php',
        data:isiform,
        success: function(){
            alert('Data berhasil ditambahkan')
            location.reload();
        }

    })
  }

  function loadPatokanTerumbu(id_detail_lokasi){
    var id_detail_lokasi_int = parseInt(id_detail_lokasi)
    $.ajax({
        type:'POST',
        url:'proses_form.php',
        data:
        {id_detail_lokasi:id_detail_lokasi_int,
         type:'load_modal_patokan_harga_terumbu'
        },
        success: function(rowitem){
            $('#edit_form').html(rowitem)
            $('.edit-modal').modal("toggle")
        },
        error: function(){
          alert('query failed')
        }

    })
  }

  function updatePatokanTerumbu(){
    var isiform = $('#edit_form').serialize()
    $.ajax({
        type:'POST',
        url:'proses_form.php',
        data:isiform,
        success: function(){
            alert('Data berhasil diupdate')
            location.reload();
        }

    })
  }

  var formatter = new Intl.NumberFormat('en-US', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    });



      function loadTk(id_jenis){
      $.ajax({
        type: "POST",
        url: "list_populate.php",
        data:{
            id_jenis: id_jenis,
            type: 'load_tk'
        },
        beforeSend: function() {
          $("#dd_id_jenis").addClass("loader");
        },
        success: function(data){
          $("#dd_id_jenis").html(data);
          $("#dd_id_jenis").removeClass("loader");
        }
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

function formatNumber1(e){
      var formattedNumber = parseInt(e.value.replace(/\,/g,''))
      if(!isNaN(formattedNumber)){
        $('#biaya_pergantian_number1').val(formattedNumber)
        $('#num_biaya_pergantian1').val(formatter.format(formattedNumber))
      }else{
        $('#biaya_pergantian_number1').val('0')
        $('#num_biaya_pergantian1').val('0')
      }
}



function formatNumber2(e){
  var formattedNumber = parseInt(e.value.replace(/\,/g,''))
      if(!isNaN(formattedNumber)){
        $('#biaya_pergantian_number2').val(formattedNumber)
        $('#num_biaya_pergantian2').val(formatter.format(formattedNumber))
      }else{
        $('#biaya_pergantian_number2').val('0')
        $('#num_biaya_pergantian2').val('0')
      }
}

function formatNumber3(e){
  var formattedNumber = parseInt(e.value.replace(/\,/g,''))
      if(!isNaN(formattedNumber)){
        $('#biaya_pergantian_number3').val(formattedNumber)
        $('#num_biaya_pergantian3').val(formatter.format(formattedNumber))
      }else{
        $('#biaya_pergantian_number3').val('0')
        $('#num_biaya_pergantian3').val('0')
      }
}



</script>



</body>
</html>


