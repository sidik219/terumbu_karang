<?php
include 'build/config/connection.php';


//Load lokasi
if ($_POST['type'] == 'load_lokasi' && !empty($_POST["id_wilayah"])) {
    $id_wilayah = $_POST["id_wilayah"];
    $daftarlokasi = 'SELECT * FROM t_lokasi
                      WHERE id_wilayah = :id_wilayah
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
    <option value="<?php echo $titik->id_titik; ?>">ID <?php echo $titik->id_titik.'  ' .$titik->keterangan_titik ?></option>
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
      <span class="nama_donatur"><?=$donasi->nama_donatur?></span> <a class="btn btn-sm btn-outline-primary" href="edit_donasi.php?id_donasi=<?=$donasi->id_donasi?>">Rincian></a>
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
    $daftardonasi = 'SELECT * FROM t_donasi
                      WHERE id_lokasi = :id_lokasi AND t_donasi.id_status_donasi = 3
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
      <span class="nama_donatur"><?=$donasi->nama_donatur?></span> <a class="btn btn-sm btn-outline-primary" href="edit_donasi.php?id_donasi=<?=$donasi->id_donasi?>">Rincian></a>
      <button type="button" class="btn donasitambah" onclick="tambahPilihan(this)"><i class="nav-icon fas fa-plus-circle"></i></button>
    </div>
    <?php
        }
    }
    ?>



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

                  <?php
                    foreach($rowstatus as $status){
                  ?>

                  <div class="form-check mb-2">
                  <input class="form-check-input" type="radio" name="radio_status" id="radio_status<?=$status->id_status_donasi?>" value="<?=$status->id_status_donasi?>" <?php if($rowitem->id_status_donasi == $status->id_status_donasi) echo " checked"; ?>>
                  <label class="form-check-label <?php if($rowitem->id_status_donasi == $status->id_status_donasi) echo " font-weight-bold"; ?>" for="radio_status<?=$status->id_status_donasi?>">
                    <?=$status->nama_status_donasi?>
                  </label>
                </div>

                    <?php }?>

                <button type="submit" name="submit" value="Simpan" class="btn btn-primary mt-2">Update Status</button></p>

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



