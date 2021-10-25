<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';
$id_lokasi = $_GET['id_lokasi'];

$sqlviewdetaillokasi = 'SELECT * FROM t_biaya_operasional
                            WHERE t_biaya_operasional.id_lokasi = :id_lokasi';
$stmt = $pdo->prepare($sqlviewdetaillokasi);
$stmt->execute(['id_lokasi' => $id_lokasi]);
$rowdetail = $stmt->fetchAll();

// $sqlviewbiaya = 'SELECT * FROM t_lokasi
//                         WHERE id_lokasi = :id_lokasi';
// $stmt = $pdo->prepare($sqlviewbiaya);
// $stmt->execute(['id_lokasi' => $id_lokasi]);
// $rowbiaya = $stmt->fetch();

// $biaya_rekomendasi = ($rowbiaya->biaya_pemeliharaan + $rowbiaya->jasa_penanaman + $rowbiaya->biaya_sewa_kapal + $rowbiaya->biaya_solar +
//                       $rowbiaya->biaya_laboratorium) / $rowbiaya->kapasitas_kapal;

$sqlviewbiaya = 'SELECT *, SUM(jumlah_biaya_operasional) AS biaya_total FROM t_biaya_operasional
                      LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_biaya_operasional.id_lokasi 
                            WHERE t_biaya_operasional.id_lokasi = :id_lokasi';
$stmt = $pdo->prepare($sqlviewbiaya);
$stmt->execute(['id_lokasi' => $id_lokasi]);
$rowbiaya = $stmt->fetch();

$biaya_rekomendasi = 0;
if ($rowbiaya->biaya_total != 0) {
  $biaya_rekomendasi = $rowbiaya->biaya_total / $rowbiaya->kapasitas_kapal;
}




// function alertStokTerumbu($stok){
//   if($stok == 0){
//     echo '<span class="text-danger"><i class="fas fa-exclamation-circle text-danger"></i> Stok Habis</span>';
//   }
//   elseif($stok < 10){
//     echo '<span class="text-warning"><i class="fas fa-exclamation-circle text-warning"></i> Stok Rendah</span>';
//   }
// }

if (isset($_POST['submit_biaya'])) {
  $biaya_pemeliharaan = $_POST['biaya_pemeliharaan'];

  $sqllokasi = "UPDATE t_lokasi
                        SET biaya_pemeliharaan = :biaya_pemeliharaan
                        WHERE id_lokasi = :id_lokasi";

  $stmt = $pdo->prepare($sqllokasi);
  $stmt->execute(['id_lokasi' => $id_lokasi, 'biaya_pemeliharaan' => $biaya_pemeliharaan]);

  header("Refresh: 0");

  // $affectedrows = $stmt->rowCount();
  // if ($affectedrows == '0') {
  //     header("Location: kelola_lokasi.php?status=nochange");
  // } else {
  //     //echo "HAHAHAAHA GREAT SUCCESSS !";
  //     header("Location: kelola_lokasi.php?status=updatesuccess");
  //     }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Kelola Biaya Operasional - GoKarang</title>
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

<body onload="hitungMargin(<?= $rowbiaya->biaya_pemeliharaan ?>)" class="hold-transition sidebar-mini layout-fixed">
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
            <?php print_sidebar(basename(__FILE__), $_SESSION['level_user']) ?>
            <!-- Print sidebar -->
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
          <div class="row">
            <div class="col">
              <a href="kelola_lokasi.php"><button class="btn btn-warning btn-back mb-1" type="button"><i class="fas fa-angle-left"></i> Kembali</button></a>
            </div>
            <div class="col"><a class="btn btn-outline-success float-right font-weight-bold" href="kelola_harga_terumbu.php?id_lokasi=<?= $id_lokasi ?>">
                <i class="icon fas fa-chevron-right"></i><i class="icon fas fa-chevron-right"></i> Kelola Pilihan Terumbu Karang yang Disediakan</a>
            </div>
          </div>

          <h5><span class="align-middle font-small font-weight-bold">Kelola Biaya Operasional</span></h5>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <label class="text-muted text-sm d-block"><i class="fas text-primary fa-info-circle"></i> Tentukan biaya operasional yang dibutuhkan untuk pemeliharaan terumbu karang</label>
          <div class="col text-center">
            <span onclick="//addDocInput()" data-toggle="modal" data-target=".tambah-modal" class="btn btn-blue btn-sm btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambah Biaya Operasional</span>
          </div>

          <table class="table table-striped table-responsive-sm">
            <thead>
              <tr>
                <th scope="col">Nama Biaya Operasional</th>
                <th scope="col">Biaya</th>
                <th class="" scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody id="tbody-append">
              <?php foreach ($rowdetail as $rowitem) { ?>
                <tr>
                  <td><?= $rowitem->nama_biaya_operasional ?></td>
                  <td>Rp. <?= number_format($rowitem->jumlah_biaya_operasional) ?></td>
                  <td class="">
                    <a href="#" onclick='loadPatokanTerumbu(this.dataset.id_biaya_operasional)' data-nama_biaya_operasional='<?= $rowitem->nama_biaya_operasional ?>' data-id_biaya_operasional='<?= $rowitem->id_biaya_operasional ?>' class="fas fa-edit mr-3 btn btn-act"></a>
                    <a onclick="return konfirmasiHapusPengadaan(event)" href="hapus.php?type=biaya_operasional&id_biaya_operasional=<?= $rowitem->id_biaya_operasional ?>&id_lokasi=<?= $id_lokasi ?>" class="far fa-trash-alt btn btn-act"></a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          <hr class="m-0" />
          <hr class="m-0" />
          <table class="table table-striped table-responsive-sm <?= empty($rowdetail) ? ' d-none ' : '' ?>">
            <thead>
              <tr>
                <th scope="col">Total:</th>
                <th scope="col"></th>
                <th scope="col">Rp. <?= number_format($rowbiaya->biaya_total, 0) ?></th>
                <th scope="col"></th>
                <th scope="col">Kapasitas Kapal: <?= $rowbiaya->kapasitas_kapal ?></th>
                <?php if ($_SESSION['level_user'] == 4) : ?>
                  <th scope="col">
                    <a href="edit_lokasi.php?id_lokasi=<?= $id_lokasi ?>" class="mr-3 btn btn-act"><i class="fas fa-edit"></i> Ubah Kapasitas Kapal</a>
                  </th>
                <?php endif ?>
              </tr>
            </thead>
          </table>

          <form method="POST">
            <div class="col text-center">

            </div>


            <div class="form-group">
              <label for="num_biaya_pergantian">Biaya Pemeliharaan</label>
              <label class="text-muted text-sm d-block"><i class="fas text-primary fa-info-circle"></i> Biaya pemeliharaan akan ditambahkan ke harga patokan terumbu karang
                untuk menutup biaya operasional. Dihitung dari total biaya operasional dibagi kapasitas kapal</label>
              <?= ($rowbiaya->kapasitas_kapal >= 1) ? "<label class='text-sm d-block'>Biaya minimum agar Balik Modal : Rp. " . number_format($biaya_rekomendasi) . " </label>" : "" ?>
              <input type="hidden" id="biaya_pergantian_number21" name="biaya_pemeliharaan" value="<?= $rowbiaya->biaya_pemeliharaan ?>">
              <div class="row">
                <div class="col-auto text-center p-2">
                  Rp.
                </div>
                <div class="col">
                  <input onkeyup="formatNumber2(this)" type="text" value="<?= number_format($rowbiaya->biaya_pemeliharaan) ?>" id="num_biaya_pergantian2" name="num_biaya_pemeliharaan" class="form-control number-input" required>
                </div>
                <div class="col">
                  <div id="pesanMargin"></div>
                </div>
              </div>
            </div>
            <div class="col text-center">
              <button type="submit" value="submit_biaya" name="submit_biaya" class="btn btn-primary btn-sm btn-blue"><i class="fas fa-save"></i> Simpan Biaya</button>
            </div>

          </form>



          <div class="terumbu-karang form-group mt-1">




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
          <h5 class="modal-title">Tambah Biaya Operasional</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="tambah_form">
          <div class="col border rounded p-2 bg-light">
            <div class="row mb-1">
              <div class="col-sm">
                <label for="num_biaya_pergantian">Nama Biaya Operasional</label>
                <input type="text" class="form-control" name="nama_biaya_operasional" id="nama_biaya_operasional" required />
              </div>
            </div>

            <div class="row">
              <div class="col">
                <label for="num_biaya_pergantian">Biaya</label>
                <input type="hidden" id="biaya_pergantian_number" name="jumlah_biaya_operasional" value="">
                <input type="hidden" id="hid_id_lokasi" name="id_lokasi" value="<?= $id_lokasi ?>">
                <input type="hidden" id="hid_type" name="type" value="save_modal_biaya_operasional">
                <div class="row">
                  <div class="col-auto text-center p-2">
                    Rp.
                  </div>
                  <div class="col">
                    <input type="text" id="num_biaya_pergantian" min="1" name="harga_patokan_lokasi_formatted" class="form-control number-input" required>
                  </div>
                </div>

        </form>
        <div class="col text-center">
          <span onclick="simpanPatokanTerumbu()" class="btn btn-blue btn-sm btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambahkan</span>
          <button type="button" class="btn-sm btn-secondary rounded-pill border-0" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>

  </div>
  <?php //} 
  ?>
  </div>
  </div>
  </div>

  <!-- Tambah modal end -->





  <!-- Edit Large modal -->
  <div class="modal fade edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Biaya Operasional</h5>
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
  <?php //} 
  ?>
  </div>
  </div>
  </div>

  <!-- Edit modal end -->

  <script>
    function simpanPatokanTerumbu() {
      var value1 = document.getElementById('nama_biaya_operasional').value;
      var value2 = document.getElementById('num_biaya_pergantian').value;
      // var value3 = document.getElementById('num_biaya_pergantian').value;
      if (value1 === '' || value2 === '') {
        alert('Semua Data harus Terisi');
      } else {
        var isiform = $('#tambah_form').serialize()
        $.ajax({
          type: 'POST',
          url: 'proses_form.php',
          data: isiform,
          success: function() {
            alert('Data berhasil ditambahkan')
            location.reload();
          }
        })
      }
    }

    function loadPatokanTerumbu(id_biaya_operasional) {
      var id_biaya_operasional_int = parseInt(id_biaya_operasional)
      $.ajax({
        type: 'POST',
        url: 'proses_form.php',
        data: {
          id_biaya_operasional: id_biaya_operasional_int,
          type: 'load_modal_biaya_operasional'
        },
        success: function(rowitem) {
          $('#edit_form').html(rowitem)
          $('.edit-modal').modal("toggle")
        },
        error: function() {
          alert('query failed')
        }

      })
    }

    function updatePatokanTerumbu() {
      var value1 = document.getElementById('nama_biaya_operasional1').value;
      var value2 = document.getElementById('num_biaya_pergantian3').value;
      console.log(value1);
      console.log(value2);
      // var value3 = document.getElementById('num_biaya_pergantian').value;
      if (value1 === '' || value2 === '') {
        alert('Semua Data harus Terisi');
        return false;
      } else {
        var isiform = $('#edit_form').serialize()
        $.ajax({
          type: 'POST',
          url: 'proses_form.php',
          data: isiform,
          success: function() {
            alert('Data berhasil diupdate')
            location.reload();
          }

        })
      }

      var formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      });





      var formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0

        // These options are needed to round to whole numbers if that's what you want.
        //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
      });


      // function formatNumber(e) {
      //   var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      //   if (!isNaN(formattedNumber)) {
      //     $('#biaya_pergantian_number').val(formattedNumber)
      //     $('#num_biaya_pergantian').val(formatter.format(formattedNumber))
      //   } else {
      //     $('#biaya_pergantian_number').val('0')
      //     $('#num_biaya_pergantian').val('0')
      //   }
      // }

      // function formatNumber1(e) {
      //   var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      //   if (!isNaN(formattedNumber)) {
      //     $('#biaya_pergantian_number1').val(formattedNumber)
      //     $('#num_biaya_pergantian1').val(formatter.format(formattedNumber))
      //   } else {
      //     $('#biaya_pergantian_number1').val('0')
      //     $('#num_biaya_pergantian1').val('0')
      //   }
      // }

      function hitungMargin(biaya) {
        var biaya_rekomendasi;
        var margin;
        biaya_rekomendasi = <?= $biaya_rekomendasi; ?>;

        margin = ((biaya / biaya_rekomendasi) * 100) - 100;
        persenMargin = margin.toFixed(2);
        var persanMargin;

        if (persenMargin >= 0) {
          pesanMargin = `<span class="text-success text-small">Untung <b>${persenMargin}%</b> </span>`;
        } else {
          pesanMargin = `<span class="text-danger text-small">Rugi <b>${persenMargin}%</b> </span>`;
        }

        if (biaya_rekomendasi == 0 || biaya == null) {
          pesanMargin = '';
        }

        $('#pesanMargin').html(pesanMargin);

      }


      //Biaya pemeliharaan formatting
      function formatNumber2(e) {
        var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
        if (!isNaN(formattedNumber)) {
          $('#biaya_pergantian_number21').val(formattedNumber)
          $('#num_biaya_pergantian2').val(formatter.format(formattedNumber))
          hitungMargin(formattedNumber)
        } else {
          $('#biaya_pergantian_number21').val('0')
          $('#num_biaya_pergantian2').val('0')
        }
      }

      function formatNumber3(e) {
        var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
        if (!isNaN(formattedNumber)) {
          $('#biaya_pergantian_number3').val(formattedNumber)
          $('#num_biaya_pergantian3').val(formatter.format(formattedNumber))
        } else {
          $('#biaya_pergantian_number3').val('0')
          $('#num_biaya_pergantian3').val('0')
        }
      }


      function simpanRekomendasiBaiaya() {
        var isiform = $('#hitung_form').serialize()
        $.ajax({
          type: 'POST',
          url: 'proses_form.php',
          data: isiform,
          success: function() {
            alert('Memproses data...')
            location.reload();
          }

        })
      }
    }
  </script>

  <script>
    function konfirmasiHapusPengadaan(event) {
      jawab = true
      jawab = confirm('Yakin ingin menghapus? Data Operasional akan hilang permanen!')

      if (jawab) {
        // alert('Lanjut.')
        return true
      } else {
        event.preventDefault()
        return false

      }
    }
  </script>

</body>

</html>