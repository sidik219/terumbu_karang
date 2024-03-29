<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';
$id_lokasi = $_GET['id_lokasi'];

$sqlviewdetaillokasi = 'SELECT * FROM t_detail_lokasi
                            LEFT JOIN t_terumbu_karang ON t_detail_lokasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                            LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                            WHERE t_detail_lokasi.id_lokasi = :id_lokasi';
$stmt = $pdo->prepare($sqlviewdetaillokasi);
$stmt->execute(['id_lokasi' => $id_lokasi]);
$rowdetail = $stmt->fetchAll();

$sqlviewbiaya = 'SELECT * FROM t_lokasi
                            WHERE id_lokasi = :id_lokasi';
$stmt = $pdo->prepare($sqlviewbiaya);
$stmt->execute(['id_lokasi' => $id_lokasi]);
$rowbiaya = $stmt->fetch();

$biaya_rekomendasi = ($rowbiaya->biaya_pemeliharaan + $rowbiaya->jasa_penanaman + $rowbiaya->biaya_sewa_kapal + $rowbiaya->biaya_solar +
  $rowbiaya->biaya_laboratorium) / $rowbiaya->kapasitas_kapal;


function alertStokTerumbu($stok)
{
  if ($stok == 0) {
    echo '<span class="text-danger"><i class="fas fa-exclamation-circle text-danger"></i> Stok Habis</span>';
  } elseif ($stok < 10) {
    echo '<span class="text-warning"><i class="fas fa-exclamation-circle text-warning"></i> Stok Rendah</span>';
  }
}

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
  <title>Kelola Harga Terumbu - GoKarang</title>
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
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col">
              <a href="kelola_lokasi.php"><button class="btn btn-warning btn-back mb-2" type="button"><i class="fas fa-angle-left"></i> Kembali</button></a>
            </div>

            <div class="col">
               <div class="col"><a class="btn btn-outline-success float-right font-weight-bold" href="kelola_biaya_operasional.php?id_lokasi=<?= $id_lokasi ?>">
                <i class="icon fas fa-chevron-right"></i><i class="icon fas fa-chevron-right"></i> Kelola Biaya Operasional</a>
            </div>
            </div>
          </div>
          
          <h4><span class="align-middle font-weight-bold">Kelola Harga Patokan Terumbu</span></h4>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          <!-- <form method="POST">
                 <div class="col text-center">

                              </div>


                <div class="form-group">
                        <label for="num_biaya_pergantian">Biaya Pemeliharaan</label>
                        <label class="text-muted text-sm d-block">Biaya jasa pemeliharaan akan ditambahkan ke harga terumbu karang</label>
                        <?= ($rowbiaya->kapasitas_kapal > 1) ? "<label class='text-sm d-block'>Biaya agar Balik Modal : Rp. " . number_format($biaya_rekomendasi) . " </label>" : "" ?>
                        <input type="hidden" id="biaya_pergantian_number2" name="biaya_pemeliharaan" value="<?= $rowbiaya->biaya_pemeliharaan ?>">
                        <div class="row">
                          <div class="col-auto text-center p-2">
                            Rp.
                          </div>
                          <div class="col">
                            <input onkeyup="formatNumber2(this)" type="text" value="<?= number_format($rowbiaya->biaya_pemeliharaan) ?>" id="num_biaya_pergantian2" name="num_biaya_pemeliharaan" class="form-control number-input" required>
                          </div>
                          <div class="col">
                          <span onclick="//addDocInput()" data-toggle="modal" data-target=".hitung-modal" class="btn btn-sm  btn-secondary"><i class="fas fa-file-invoice-dollar"></i> Hitung Biaya</span>
                          </div>
                        </div>
                </div>
                <div class="col text-center">
                                <button type="submit" value="submit_biaya" name="submit_biaya" class="btn btn-primary btn-sm btn-blue"><i class="fas fa-save"></i> Simpan Biaya</button>
                </div>

                </form> -->



          <div class="terumbu-karang form-group mt-0">

            <label for="num_biaya_pergantian  ">Terumbu Karang yang Disediakan</label>
            <label class="text-muted text-sm d-block"><i class="fas text-primary fa-info-circle"></i> Tentukan Terumbu karang yang dapat dipilih donatur</label>
            <div class="col text-center">
              <span onclick="//addDocInput()" data-toggle="modal" data-target=".tambah-modal" class="btn btn-blue btn btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambah Terumbu</span>
            </div>
            <?php
            if (!empty($_GET['status'])) {
              if ($_GET['status'] == 'deletesuccess') {
                echo '<div class="alert alert-success" role="alert">
                            Harga Patokan Terumbu Terhapus!
                            </div>';
              }
            }
            ?>
            <table class="table table-striped table-responsive-sm">
              <thead>
                <tr>
                  <th scope="col">ID Terumbu</th>
                  <th scope="col">Jenis</th>
                  <th scope="col">Sub-jenis</th>
                  <th scope="col">Harga</th>
                  <!-- <th scope="col">Stok</th> -->
                  <th class="" scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody id="tbody-append">
                <?php foreach ($rowdetail as $rowitem) { ?>
                  <tr>
                    <th scope="row">
                      <?= $rowitem->id_terumbu_karang ?>
                    </th>
                    <td><?= $rowitem->nama_jenis ?></td>
                    <td><?= $rowitem->nama_terumbu_karang ?></td>
                    <td>Rp. <?= number_format($rowitem->harga_patokan_lokasi) ?></td>
                    <!-- <td>
                              <?= $rowitem->stok_terumbu ?>
                              <br><?= alertStokTerumbu($rowitem->stok_terumbu) ?>
                          </td> -->
                    <td class="">
                      <a href="#" onclick='loadPatokanTerumbu(this.dataset.id_detail_lokasi)' data-nama_jenis='<?= $rowitem->nama_terumbu_karang ?>' data-id_tk='<?= $rowitem->id_terumbu_karang ?>' data-harga_patokan='<?= $rowitem->harga_patokan_lokasi ?>' data-id_detail_lokasi='<?= $rowitem->id_detail_lokasi ?>' class="fas fa-edit mr-3 btn btn-act"></a>
                      <a onclick="return konfirmasiHapusPengadaan(event)" href="hapus.php?type=detail_lokasi&id_detail_lokasi=<?= $rowitem->id_detail_lokasi ?>&id_lokasi=<?= $id_lokasi ?>" class="far fa-trash-alt btn btn-act"></a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>

            <script>
              function konfirmasiHapusPengadaan(event) {
                jawab = true
                jawab = confirm('Yakin ingin menghapus? Data Terumbu akan hilang permanen!')

                if (jawab) {
                  // alert('Lanjut.')
                  return true
                } else {
                  event.preventDefault()
                  return false

                }
              }
            </script>

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
          <h5 class="modal-title">Tambah Terumbu</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form id="tambah_form">
          <div class="col border rounded p-2 bg-light">
            <div class="row">
              <div class="col-sm">
                <label>Jenis</label>
                <select id="dd_id_wilayah" name="dd_id_jenis" class="form-control" onchange="loadTk(this.value);">
                  <option value="">-Pilih Jenis-</option>
                  <?php
                  $sqlviewjenis = 'SELECT * FROM t_jenis_terumbu_karang';
                  $stmt = $pdo->prepare($sqlviewjenis);
                  $stmt->execute();
                  $rowjenis = $stmt->fetchAll();
                  foreach ($rowjenis as $jenis) {
                  ?>
                    <option value="<?= $jenis->id_jenis ?>">
                      ID <?= $jenis->id_jenis ?> - <?= $jenis->nama_jenis ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-sm">
                <label>Sub-jenis</label>
                <select id="dd_id_jenis" name="dd_id_tk" class="form-control" required>
                  <option value="">-Pilih Sub-jenis-</option>
                </select>
              </div>
              <div class="col">

              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="num_biaya_pergantian">Harga Patokan</label>
                <input type="hidden" id="biaya_pergantian_number" name="num_harga_patokan_lokasi_angka" value="">
                <input type="hidden" id="hid_id_lokasi" name="id_lokasi" value="<?= $id_lokasi ?>">
                <input type="hidden" id="hid_type" name="type" value="save_modal_patokan_harga_terumbu">
                <div class="row">
                  <div class="col-auto text-center p-2">
                    Rp.
                  </div>
                  <div class="col">
                    <input onkeyup="formatNumber(this)" type="text" id="num_biaya_pergantian" min="1" name="harga_patokan_lokasi_formatted" class="form-control number-input" required>
                  </div>
                </div>

                <div class="row mt-2 d-none">
                  <div class="col">
                    <label for="num_biaya_pergantian">Stok</label>
                    <input type="number" min="0" id="num_stok" name="stok_terumbu" value="1" class="form-control number-input" required>
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








  <!-- Hitung Large modal -->
  <div class="modal fade hitung-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hitung Biaya Pemeliharaan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="hitung_form">
          <div class="col border rounded p-2 bg-light">

            <div class="row">
              <div class="col">
                <label class="text-muted text-sm d-block">Tentukan biaya untuk penanaman dan 4 kali pemeliharaan</label>
                <label for="num_sewa">Biaya Sewa Kapal</label>
                <input type="hidden" id="num_biaya_sewa_kapal_angka" name="num_biaya_sewa_kapal_angka" value="<?= $rowbiaya->biaya_sewa_kapal ?>">

                <div class="row">
                  <div class="col-auto text-center p-2">
                    Rp.
                  </div>
                  <div class="col">
                    <input onkeyup="formatNumberSewa(this)" value="<?= number_format($rowbiaya->biaya_sewa_kapal) ?>" type="text" id="num_sewa" min="1" name="num_biaya_sewa_kapal_angka_f" class="form-control number-input" required>
                  </div>
                </div>

                <label for="num_solar">Biaya Solar</label>
                <input type="hidden" id="biaya_solar_angka" name="biaya_solar_angka" value="<?= $rowbiaya->biaya_solar ?>">


                <div class="row">
                  <div class="col-auto text-center p-2">
                    Rp.
                  </div>
                  <div class="col">
                    <input onkeyup="formatNumberSolar(this)" value="<?= number_format($rowbiaya->biaya_solar) ?>" type="text" id="num_solar" min="1" name="biaya_solar_angka_f" class="form-control number-input" required>
                  </div>
                </div>

                <label for="num_lab">Biaya Laboratorium</label>
                <input type="hidden" id="biaya_laboratorium_angka" name="biaya_laboratorium_angka" value="<?= $rowbiaya->biaya_laboratorium ?>">


                <div class="row">
                  <div class="col-auto text-center p-2">
                    Rp.
                  </div>
                  <div class="col">
                    <input onkeyup="formatNumberLab(this)" type="text" value="<?= number_format($rowbiaya->biaya_laboratorium) ?>" id="num_lab" min="1" name="biaya_laboratorium_angka_f" class="form-control number-input" required>
                  </div>
                </div>

                <label for="num_jasa">Upah Jasa Penanaman</label>
                <input type="hidden" id="jasa_penanaman_angka" name="jasa_penanaman_angka" value="<?= $rowbiaya->jasa_penanaman ?>">
                <input type="hidden" id="hid_id_lokasi" name="id_lokasi" value="<?= $id_lokasi ?>">
                <input type="hidden" id="hid_type" name="type" value="save_modal_biaya_pemeliharaan">
                <div class="row">
                  <div class="col-auto text-center p-2">
                    Rp.
                  </div>
                  <div class="col">
                    <input onkeyup="formatNumberJasa(this)" type="text" value="<?= number_format($rowbiaya->jasa_penanaman) ?>" id="num_jasa" min="1" name="jasa_penanaman_angka_f" class="form-control number-input" required>
                  </div>
                </div>


                <div class="row mt-2">
                  <div class="col">
                    <label for="num_kapasitas">Kapasitas Bibit per Kapal</label>
                    <input type="number" min="0" id="kapasitas_kapal" value="<?= number_format($rowbiaya->kapasitas_kapal) ?>" name="kapasitas_kapal" class="form-control number-input" required>
                  </div>
                </div>
        </form>
        <div class="col text-center">
          <button type="submit" onclick="simpanRekomendasiBaiaya()" class="btn btn-blue btn-sm btn-primary mt-2 mb-2 text-center"><i class="fas fa-donate"></i> Hitung Biaya</button>
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

  <!-- Hitung modal end -->






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
  <?php //} 
  ?>
  </div>
  </div>
  </div>

  <!-- Edit modal end -->

  <script>
    function simpanPatokanTerumbu() {
      var value1 = document.getElementById('dd_id_wilayah').value;
      var value2 = document.getElementById('dd_id_jenis').value;
      var value3 = document.getElementById('num_biaya_pergantian').value;
      if (value1 === '' || value2 === '' || value3 === '') {
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
          },
          error: () => {
            alert('Sub-jenis terumbu karang sudah terdaftar. Harap input sub-jenis lain.')
          }

        })
      }
    }

    function loadPatokanTerumbu(id_detail_lokasi) {
      var id_detail_lokasi_int = parseInt(id_detail_lokasi)
      $.ajax({
        type: 'POST',
        url: 'proses_form.php',
        data: {
          id_detail_lokasi: id_detail_lokasi_int,
          type: 'load_modal_patokan_harga_terumbu'
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
      var value3 = document.getElementById('num_biaya_pergantian3').value;
      if (value3 === '') {
        alert('Semua Data harus Terisi');
        return false
      } else
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



    function loadTk(id_jenis) {
      $.ajax({
        type: "POST",
        url: "list_populate.php",
        data: {
          id_jenis: id_jenis,
          type: 'load_tk'
        },
        beforeSend: function() {
          $("#dd_id_jenis").addClass("loader");
        },
        success: function(data) {
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


    function formatNumber(e) {
      var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      if (!isNaN(formattedNumber)) {
        $('#biaya_pergantian_number').val(formattedNumber)
        $('#num_biaya_pergantian').val(formatter.format(formattedNumber))
      } else {
        $('#biaya_pergantian_number').val('0')
        $('#num_biaya_pergantian').val('0')
      }
    }

    function formatNumber1(e) {
      var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      if (!isNaN(formattedNumber)) {
        $('#biaya_pergantian_number1').val(formattedNumber)
        $('#num_biaya_pergantian1').val(formatter.format(formattedNumber))
      } else {
        $('#biaya_pergantian_number1').val('0')
        $('#num_biaya_pergantian1').val('0')
      }
    }



    function formatNumber2(e) {
      var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      if (!isNaN(formattedNumber)) {
        $('#biaya_pergantian_number2').val(formattedNumber)
        $('#num_biaya_pergantian2').val(formatter.format(formattedNumber))
      } else {
        $('#biaya_pergantian_number2').val('0')
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

    function formatNumberSewa(e) {
      var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      if (!isNaN(formattedNumber)) {
        $('#num_biaya_sewa_kapal_angka').val(formattedNumber)
        $('#num_sewa').val(formatter.format(formattedNumber))
      } else {
        $('#num_biaya_sewa_kapal_angka').val('0')
        $('#num_sewa').val('0')
      }
    }

    function formatNumberSolar(e) {
      var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      if (!isNaN(formattedNumber)) {
        $('#biaya_solar_angka').val(formattedNumber)
        $('#num_solar').val(formatter.format(formattedNumber))
      } else {
        $('#biaya_solar_angka').val('0')
        $('#num_solar').val('0')
      }
    }

    function formatNumberLab(e) {
      var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      if (!isNaN(formattedNumber)) {
        $('#biaya_laboratorium_angka').val(formattedNumber)
        $('#num_lab').val(formatter.format(formattedNumber))
      } else {
        $('#biaya_laboratorium_angka').val('0')
        $('#num_lab').val('0')
      }
    }

    function formatNumberJasa(e) {
      var formattedNumber = parseInt(e.value.replace(/\,/g, ''))
      if (!isNaN(formattedNumber)) {
        $('#jasa_penanaman_angka').val(formattedNumber)
        $('#num_jasa').val(formatter.format(formattedNumber))
      } else {
        $('#jasa_penanaman_angka').val('0')
        $('#num_jasa').val('0')
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
  </script>



</body>

</html>