<?php
include 'build/config/connection.php';

function ageCalculator($dob){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $ag = $birthdate->diff($today)->y;
        $mn = $birthdate->diff($today)->m;
        $dy = $birthdate->diff($today)->d;
        if ($mn == 0)
        {
            return "$dy Hari";
        }
        elseif ($ag == 0)
        {
            return "$mn Bulan  $dy Hari";
        }
        else
        {
            return "$ag Tahun $mn Bulan $dy Hari";
        }
    }

    function alertPemeliharaan($dob){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $mn = $birthdate->diff($today)->m;
        if ($mn >= 3)
        {
            return '<i class="fas fa-exclamation-circle text-danger"></i> Perlu Pemeliharaan Kembali';
        }
    }

    function alertCabutLabel($dob, $slabel){
      if($slabel == 0){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $mn = $birthdate->diff($today)->m;
        if ($mn >= 11)
        {
            return '<i class="fas fa-exclamation-circle text-danger"></i> Perlu Cabut Label';
        }
      }
    }


//Load lokasi
if ($_POST['type'] == 'load_lokasi' && !empty($_POST["id_wilayah"])) {
  $level_user = $_SESSION['level_user'];

if($level_user == 2){
  $id_wilayah = $_SESSION['id_wilayah_dikelola'];
  $extra_query = " AND t_wilayah.id_wilayah = $id_wilayah ";
  $extra_query_noand = " t_wilayah.id_wilayah = $id_wilayah ";
  $wilayah_join = " LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_donasi.id_lokasi
                    LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";
  $extra_query_k_lok = " AND t_lokasi.id_wilayah = $id_wilayah ";
  $extra_query_where = " WHERE t_wilayah.id_wilayah = $id_wilayah ";
}
else if($level_user == 4){
  $extra_query = "  ";
  $extra_query_noand = "  ";
  $wilayah_join = " ";
  $extra_query_k_lok = " ";
  $extra_query_where = " ";
}
else if($level_user == 3){
  $id_lokasi = $_SESSION['id_lokasi_dikelola'];
  $extra_query_where_lok = "LEFT JOIN t_lokasi ON t_lokasi.id_wilayah = t_wilayah.id_wilayah WHERE t_lokasi.id_lokasi = $id_lokasi ";
  $extra_query_where = " WHERE t_lokasi.id_lokasi = $id_lokasi ";
  $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
}
    $id_wilayah = $_POST["id_wilayah"];
    $daftarlokasi = 'SELECT * FROM t_lokasi
                      WHERE id_wilayah = :id_wilayah '.$extra_query.'
                        ORDER BY nama_lokasi';
        $stmt = $pdo->prepare($daftarlokasi);
        $stmt->execute(['id_wilayah' => $id_wilayah]);
        $rowlokasi = $stmt->fetchAll();

    ?>
    <option value="">Pilih Lokasi</option>
    <?php
        foreach ($rowlokasi as $lokasi) {
            ?>
    <option value="<?php echo $lokasi->id_lokasi; ?>"><?php echo $lokasi->nama_lokasi; ?></option>
    <?php
        }
    }
    ?>

<?php

//Load Titik
if ($_POST['type'] == 'load_titik' && !empty($_POST["id_lokasi"])) {
    $id_lokasi = $_POST["id_lokasi"];
    $daftartitik = 'SELECT * FROM t_titik
                      WHERE id_lokasi = :id_lokasi
                        ORDER BY id_titik';
        $stmt = $pdo->prepare($daftartitik);
        $stmt->execute(['id_lokasi' => $id_lokasi]);
        $rowtitik = $stmt->fetchAll();

    ?>
    <option value="">Pilih Titik</option>
    <?php
        foreach ($rowtitik as $titik) {
            ?>
    <option value="<?php echo $titik->id_titik; ?>">ID <?php echo $titik->id_titik.'  ' .$titik->keterangan_titik .' - '. $titik->luas_titik .' m&#178;'?></option>
    <?php
        }
    }
    ?>


<?php
//Load TK
if ($_POST['type'] == 'load_tk' && !empty($_POST["id_jenis"])) {
    $id_jenis = $_POST["id_jenis"];
    $daftartk = 'SELECT * FROM t_terumbu_karang
                      WHERE id_jenis = :id_jenis
                        ORDER BY id_terumbu_karang';
        $stmt = $pdo->prepare($daftartk);
        $stmt->execute(['id_jenis' => $id_jenis]);
        $rowtk = $stmt->fetchAll();

    ?>
    <option value="">-Pilih Sub-jenis-</option>
    <?php
        foreach ($rowtk as $tk) {
            ?>
    <option value="<?php echo $tk->id_terumbu_karang; ?>">ID <?php echo $tk->id_terumbu_karang.'  ' .$tk->nama_terumbu_karang?></option>
    <?php
        }
    }
    ?>



<?php
//Load Daftar detail lokasi berdasarkan id_jenis
if ($_POST['type'] == 'load_detail_lokasi' && !empty($_POST["id_jenis"])) {
    $id_jenis = $_POST["id_jenis"];
    $sqlviewdetaillokasi = 'SELECT * FROM t_detail_lokasi
                            LEFT JOIN t_terumbu_karang ON t_detail_lokasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                            LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                            WHERE t_terumbu_karang.id_jenis = :id_jenis';

    $stmt = $pdo->prepare($sqlviewdetaillokasi);
    $stmt->execute(['id_jenis' => $id_jenis]);
    $rowdetail = $stmt->fetchAll();
?>
    <option value="1" disabled>Pilih Terumbu Karang:</option>
    <option value="1" selected>Tidak Donasi</option>
    <?php foreach ($rowdetail as $detail) { ?>
    <option value="<?php echo $detail->id_terumbu_karang.' - '.$detail->harga_patokan_lokasi; ?>">
        <?php echo $detail->nama_terumbu_karang.' - '.$detail->harga_patokan_lokasi?></option>

<?php
    }
}
?>

<?php
//Load Daftar Donasi berdasarkan id_lokasi
if ($_POST['type'] == 'load_donasi' && !empty($_POST["id_lokasi"])) {
    $id_lokasi = $_POST["id_lokasi"];
    $daftardonasi = 'SELECT * FROM t_donasi
                      WHERE id_lokasi = :id_lokasi AND t_donasi.id_status_donasi = 3 AND t_donasi.id_batch = NULL
                        ORDER BY id_donasi';
        $stmt = $pdo->prepare($daftardonasi);
        $stmt->execute(['id_lokasi' => $id_lokasi]);
        $rowdonasi = $stmt->fetchAll();

    ?>
    <?php
        foreach ($rowdonasi as $donasi) {
            ?>
    <div class="border rounded p-1 batch-donasi" id="donasi<?=$donasi->id_donasi?>">
      ID <span class="id_donasi"><?=$donasi->id_donasi?></span> -
      <span class="nama_donatur"><?=$donasi->nama_donatur?></span> <a data-id='<?=$donasi->id_donasi?>' class="btn btn-sm btn-outline-primary userinfo">Rincian></a>
      <button type="button" class="btn donasitambah" onclick="tambahPilihan(this)"><i class="nav-icon fas fa-plus-circle"></i></button>
    </div>
    <?php
        }
    }
    ?>


<?php
//Load Daftar Donasi berdasarkan id_batch & belum punya id_batch (NULL)
if ($_POST['type'] == 'load_donasi' && !empty($_POST["id_lokasi"])) {
    $id_lokasi = $_POST["id_lokasi"];
    $daftardonasi = 'SELECT *, SUM(t_detail_donasi.jumlah_terumbu) AS jumlah_bibit_donasi FROM t_donasi
                      LEFT JOIN t_detail_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
                      WHERE id_lokasi = :id_lokasi AND t_donasi.id_status_donasi = 3
                      GROUP BY t_detail_donasi.id_donasi
                        ORDER BY t_donasi.id_donasi';
        $stmt = $pdo->prepare($daftardonasi);
        $stmt->execute(['id_lokasi' => $id_lokasi]);
        $rowdonasi = $stmt->fetchAll();

    ?>
    <?php
        foreach ($rowdonasi as $donasi) {
            ?>
    <div class="border rounded p-1 batch-donasi  mb-2 shadow-sm" id="donasi<?=$donasi->id_donasi?>">
      ID <span class="id_donasi"><?=$donasi->id_donasi?></span> -
      <span class="nama_donatur"><?=$donasi->nama_donatur?></span> || Jumlah : <span class="jumlah"><?=$donasi->jumlah_bibit_donasi?></span> <a data-id='<?=$donasi->id_donasi?>' class="btn btn-sm btn-outline-primary userinfo">Rincian></a>
      <button type="button" class="btn donasitambah" onclick="tambahPilihan(this)"><i class="nav-icon fas fa-plus-circle"></i></button>
    </div>
    <?php
        }
    }
    ?>


<?php
//Load Daftar Batch berdasarkan id_lokasi
if ($_POST['type'] == 'load_batch' && !empty($_POST["id_lokasi"])) {
    $id_lokasi = $_POST["id_lokasi"];
    $sqlviewbatch = 'SELECT t_batch.id_batch, t_batch.id_lokasi, t_batch.id_titik, t_batch.tanggal_penanaman,
                      t_batch.update_status_batch_terakhir, t_batch.tanggal_pemeliharaan_terakhir, nama_lokasi, keterangan_titik, nama_status_batch, status_cabut_label
                      FROM t_batch
                      LEFT JOIN t_lokasi ON t_batch.id_lokasi = t_lokasi.id_lokasi
                      LEFT JOIN t_titik ON t_batch.id_titik = t_titik.id_titik
                      LEFT JOIN t_status_batch ON t_batch.id_status_batch = t_status_batch.id_status_batch
                      WHERE t_batch.id_lokasi = :id_lokasi AND status_cabut_label != 1
                      ORDER BY tanggal_pemeliharaan_terakhir';
    $stmt = $pdo->prepare($sqlviewbatch);
    $stmt->execute(['id_lokasi' => $_POST['id_lokasi']]);
    $rowbatch = $stmt->fetchAll();

    ?>
    <?php
        foreach ($rowbatch as $batch) {
            ?>
    <div class="border rounded p-1 batch-donasi mb-2 shadow-sm" id="donasi<?=$batch->id_batch?>">
      <span class="font-weight-bold">ID Batch : </span> <span class="id_donasi"><?=$batch->id_batch?></span>  <br>
      <b>Usia : </b>
      <?=ageCalculator($batch->tanggal_penanaman)?>
      <br><small class="font-weight-bold">Pemeliharaan Terakhir : </small> <small class="tanggal_pemeliharaan"><?php if($batch->tanggal_pemeliharaan_terakhir == null){echo 'Belum pernah pemeliharaan';}else{echo $batch->tanggal_pemeliharaan_terakhir.
        ' ('.ageCalculator($batch->tanggal_pemeliharaan_terakhir).' yang lalu)<br><span class="font-weight-bold text-danger">'.alertPemeliharaan($batch->tanggal_pemeliharaan_terakhir).'</span>
        <br><span class="font-weight-bold text-danger">'.alertCabutLabel($batch->tanggal_penanaman, $batch->status_cabut_label).'</span>';}?>
          </small>

      <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$batch->id_batch?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$batch->id_batch?>, .contentall<?=$batch->id_batch?>">
                                <p
                                    class="fielddetail<?=$batch->id_batch?> btn btn-act">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Batch</p>
                            </div>
                            <div class="col-12 cell<?=$batch->id_batch?> collapse contentall<?=$batch->id_batch?>">
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
                                        ID <span class="id_donasi_append"><?=$detailbatch->id_donasi?></span> - <?=$detailbatch->nama_donatur?> <a data-id='<?=$detailbatch->id_donasi?>' class="btn btn-sm btn-outline-primary userinfo p-1">Rincian></a>
                                    </div>

                                </div>

                                  <?php } ?>

                            </div>
                        </div>

                        <!--collapse end -->

      <button type="button" class="btn btn-blue btn-primary donasitambah" onclick="tambahPilihan(this)">Tambahkan <i class="nav-icon fas fa-plus-circle"></i></button>
    </div>
    <?php
        }
    }
    ?>



<!-- BEGIN RINCIAN DONASI MODAL -->
<!-- BEGIN RINCIAN DONASI MODAL -->
<!-- BEGIN RINCIAN DONASI MODAL -->

<?php
//Load Data Donasi berdasarkan id_batch untuk tombol Rincian>
if ($_POST['type'] == 'load_rincian_donasi' && !empty($_POST["id_donasi"])) {
    $id_donasi = $_POST['id_donasi'];

    $sql = 'SELECT * FROM t_donasi, t_lokasi
    WHERE id_donasi = :id_donasi
    AND t_donasi.id_lokasi = t_lokasi.id_lokasi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_donasi' => $id_donasi]);
    $rowitem = $stmt->fetch();

     $sqlstatus = 'SELECT * FROM t_status_donasi';
    $stmt = $pdo->prepare($sqlstatus);
    $stmt->execute();
    $rowstatus = $stmt->fetchAll();
?>
<section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST">


                     <div class="row">
                      <div class="col-12 border rounded bg-white p-0 mb-3">
                        <h5 class="card-header mb-1 font-weight-bold">Pesan/Ekspresi</h5>
                              <span class="font-weight-bold font-italic mb-3 pl-3 pt-4 pb-4"><?=$rowitem->pesan?></span>
                      </div>


                                    <div class="col-12 border rounded bg-white p-0  mb-3">
                                      <h5 class="card-header mb-1 font-weight-bold">Terumbu Karang Pilihan</h5><br/>
                                        <?php
                                              $sqlviewisi = 'SELECT jumlah_terumbu, nama_terumbu_karang, foto_terumbu_karang FROM t_detail_donasi
                                              LEFT JOIN t_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
                                              LEFT JOIN t_terumbu_karang ON t_detail_donasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                                              WHERE t_detail_donasi.id_donasi = :id_donasi';
                                              $stmt = $pdo->prepare($sqlviewisi);
                                              $stmt->execute(['id_donasi' => $rowitem->id_donasi]);
                                              $rowisi = $stmt->fetchAll();
                                           foreach ($rowisi as $isi){
                                             ?>
                                             <div class="row  mb-3 pl-3">
                                               <div class="col">
                                                <img class="" height="60px" src="<?=$isi->foto_terumbu_karang?>?<?php if ($status='nochange'){echo time();}?>">
                                              </div>
                                              <div class="col">
                                                <span><?= $isi->nama_terumbu_karang?>
                                              </div>
                                              <div class="col">
                                                x<?= $isi->jumlah_terumbu?></span><br/>
                                              </div>

                                             <hr class="mb-2"/>
                                             </div>

                                        <?php   }
                                        ?>
                                    </div>


                      <div class="col-12 mb-2 border rounded bg-white p-3">
                  <h5 class="font-weight-bold">Status Donasi</h5>



                  <div class="form-check mb-2">
                  <label class="form-check-label <?php if($rowitem->id_status_donasi == $rowitem->id_status_donasi) echo " font-weight-bold"; ?>" for="radio_status<?=$rowitem->id_status_donasi?>">
                    <?=$rowstatus[$rowitem->id_status_donasi-1]->nama_status_donasi?>
                  </label>
                </div>



          </div>


                      <div class="col-lg-9 border rounded bg-white mb-2">
                             <div class="" style="width:100%;">
                <div class="">
                    <h5 class="card-header mb-2 pl-0">Rincian Pembayaran</h5>
            <span class="">Lokasi : </span>  <span class="text-info font-weight-bolder"><?=$rowitem->nama_lokasi?></span>
            <div class="d-block my-3">
              <div class="custom-control custom-radio">
                <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
              </div>
<hr class="mb-2"/>

            <div class="row">
                <div class="col">
                     <span class="font-weight-bold">Nama Donatur
                </div>
                <div class="col-lg-8 mb-2">
                     <span class=""><?=$rowitem->nama_donatur?></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <span class="font-weight-bold">Nomor Rekening Donatur  </span>
                </div>
                <div class="col-lg-8  mb-2">
                    <span class=""><?=$rowitem->nomor_rekening_donatur?></span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <span class="font-weight-bold">Bank Donatur  </span>
                </div>
                <div class="col-lg-8  mb-2">
                    <span class=""><?=$rowitem->bank_donatur?></span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <span class="font-weight-bold">Nominal  </span>
                </div>
                <div class="col-lg-8  mb-2">
                    <span class="font-weight-bold">Rp. <?=number_format($rowitem->nominal, 0)?></span>
                </div>
            </div>
                </div>
            </div>
            </div>
                    </div>
            <br><br>
                      <div class="col-lg-3 border rounded bg-white p-3 mb-2  text-center">
                          <div class="form-group">
                        <label for="file_bukti_donasi">Bukti Donasi</label><hr class="m-0">
                        <div class='form-group' id='buktidonasi'>
                        <!-- <div>
                            <input type='file'  class='form-control' id='image_uploads'
                                name='image_uploads' accept='.jpg, .jpeg, .png' onchange="readURL(this);">
                        </div> -->
                    </div>
                    <div class="form-group">
                        <img id="preview" src="#"  width="100px" alt="Preview Gambar" <?php if($rowitem->bukti_donasi == NULL) echo " style='display:none;'"; ?>>
                        <a href="<?=$rowitem->bukti_donasi?>" data-toggle="lightbox"><img class="img-fluid" id="oldpic" src="<?=$rowitem->bukti_donasi?>" width="50%" <?php if($rowitem->bukti_donasi == NULL) echo " style='display:none;'"; ?>></a>
                        <br>
                        <small class="text-muted">
                            <?php if($rowitem->bukti_donasi == NULL){
                                echo "Bukti transfer belum diupload";
                            }else{
                                echo "Klik gambar untuk memperbesar";
                            }

                            ?>
                        </small>
                        <script>
                            window.onload = function() {
                            document.getElementById('preview').style.display = 'none';
                            };
                            function readURL(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                                    document.getElementById('oldpic').style.display = 'none';
                                    reader.onload = function (e) {
                                        $('#preview')
                                            .attr('src', e.target.result)
                                            .width(200);
                                            document.getElementById('preview').style.display = 'block';
                                    };

                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
                    </div>
                    </div>



                    <p align="center">
                    <!-- <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p> -->
                    </form>
                      </div>



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
<?php }?>
<!-- END RINCIAN DONASI MODAL -->

<!-- END RINCIAN DONASI MODAL -->

<!-- END RINCIAN DONASI MODAL -->





