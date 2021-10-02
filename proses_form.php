<?php
include 'build/config/connection.php';


//PATOKAN HARGA--------------------------------
//Tambah
if ($_POST['type'] == 'save_modal_patokan_harga_terumbu' && !empty($_POST["id_lokasi"])) {
  $id_lokasi = $_POST["id_lokasi"];
  $id_terumbu_karang = $_POST['dd_id_tk'];
  $harga_patokan_lokasi = $_POST['num_harga_patokan_lokasi_angka'];
  $stok_terumbu = $_POST['stok_terumbu'];

  $insertpatokan = 'INSERT INTO t_detail_lokasi
                      (id_lokasi, id_terumbu_karang, harga_patokan_lokasi, stok_terumbu)
                      VALUES (:id_lokasi, :id_terumbu_karang, :harga_patokan_lokasi, :stok_terumbu)';
  $stmt = $pdo->prepare($insertpatokan);
  $stmt->execute(['id_lokasi' => $id_lokasi, 'id_terumbu_karang' => $id_terumbu_karang, 'harga_patokan_lokasi' => $harga_patokan_lokasi, 'stok_terumbu' => $stok_terumbu]);
}

//Save modal Biaya Pemeliharaan
if ($_POST['type'] == 'save_modal_biaya_pemeliharaan' && !empty($_POST["id_lokasi"])) {
  $id_lokasi = $_POST["id_lokasi"];
  $jasa_penanaman = $_POST['jasa_penanaman_angka'];
  $biaya_sewa_kapal = $_POST['num_biaya_sewa_kapal_angka'];
  $biaya_solar = $_POST['biaya_solar_angka'];
  $biaya_laboratorium = $_POST['biaya_laboratorium_angka'];
  $kapasitas_kapal = $_POST['kapasitas_kapal'];

  $updatebiaya = 'UPDATE t_lokasi
                      SET jasa_penanaman = :jasa_penanaman, biaya_sewa_kapal = :biaya_sewa_kapal, biaya_solar = :biaya_solar,
                      biaya_laboratorium = :biaya_laboratorium, kapasitas_kapal = :kapasitas_kapal
                      WHERE id_lokasi = :id_lokasi';
  $stmt = $pdo->prepare($updatebiaya);
  $stmt->execute([
    'id_lokasi' => $id_lokasi, 'jasa_penanaman' => $jasa_penanaman, 'biaya_sewa_kapal' => $biaya_sewa_kapal, 'biaya_solar' => $biaya_solar,
    'biaya_laboratorium' => $biaya_laboratorium, 'kapasitas_kapal' => $kapasitas_kapal,
  ]);
}

//Load untuk Edit
if ($_POST['type'] == 'load_modal_patokan_harga_terumbu' && !empty($_POST["id_detail_lokasi"])) {
  $id_detail_lokasi = $_POST["id_detail_lokasi"];

  $loadpatokan = 'SELECT * FROM t_detail_lokasi
                    LEFT JOIN t_terumbu_karang ON t_detail_lokasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                    LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                    WHERE id_detail_lokasi = :id_detail_lokasi';
  $stmt = $pdo->prepare($loadpatokan);
  $stmt->execute(['id_detail_lokasi' => $id_detail_lokasi]);
  $rowitem = $stmt->fetch();

?>

  <div class="col border rounded p-2 bg-light">
    <div class="row mb-2">
      <div class="col-sm">
        <label>Jenis</label>
        <br>ID <?= $rowitem->id_jenis ?> - <?= $rowitem->nama_jenis ?>
      </div>
      <div class="col-sm">
        <label>Sub-jenis</label>
        <br><?= $rowitem->id_terumbu_karang ?> - <?= $rowitem->nama_terumbu_karang ?>
      </div>
      <div class="col">

      </div>
    </div>
    <div class="row">
      <div class="col">
        <label for="num_biaya_pergantian">Harga Patokan</label>
        <input type="hidden" id="biaya_pergantian_number3" name="num_harga_patokan_lokasi_angka" value="<?= $rowitem->harga_patokan_lokasi ?>">
        <input type="hidden" id="hid_type" name="type" value="update_modal_patokan_harga_terumbu">
        <input type="hidden" id="hid_id_detail_lokasi" name="id_detail_lokasi" value="<?= $id_detail_lokasi ?>">
        <div class="row">
          <div class="col-auto text-center p-2">
            Rp.
          </div>
          <div class="col">
            <input type="text" id="num_biaya_pergantian3" value="<?= number_format($rowitem->harga_patokan_lokasi) ?>" min="1" name="harga_patokan_lokasi_formatted" class="form-control number-input" required>
          </div>
        </div>
        <div class="row mt-2 d-none">
          <div class="col">
            <label for="num_biaya_pergantian">Stok</label>
            <input type="number" min="0" id="num_stok" name="stok_terumbu" value="1" class="form-control number-input" required>
          </div>
        </div>

      <?php
    }


    //Edit
    if ($_POST['type'] == 'update_modal_patokan_harga_terumbu' && !empty($_POST["id_detail_lokasi"])) {
      $id_detail_lokasi = $_POST["id_detail_lokasi"];
      $harga_patokan_lokasi = $_POST['harga_patokan_lokasi_formatted'];
      $stok_terumbu = $_POST['stok_terumbu'];

      $updatepatokan = 'UPDATE t_detail_lokasi
                      SET harga_patokan_lokasi = :harga_patokan_lokasi, stok_terumbu = :stok_terumbu
                      WHERE id_detail_lokasi = :id_detail_lokasi';
      $stmt = $pdo->prepare($updatepatokan);
      $stmt->execute(['id_detail_lokasi' => $id_detail_lokasi, 'harga_patokan_lokasi' => $harga_patokan_lokasi, 'stok_terumbu' => $stok_terumbu]);
    }


    //BIAYA OPERASIONAL--------------------------------
    //Tambah
    if ($_POST['type'] == 'save_modal_biaya_operasional' && !empty($_POST["id_lokasi"])) {
      $id_lokasi = $_POST["id_lokasi"];
      $nama_biaya_operasional = $_POST['nama_biaya_operasional'];
      $jumlah_biaya_operasional = $_POST['jumlah_biaya_operasional'];

      $insertbiayaop = 'INSERT INTO t_biaya_operasional
                      (id_lokasi, nama_biaya_operasional, jumlah_biaya_operasional)
                      VALUES (:id_lokasi, :nama_biaya_operasional, :jumlah_biaya_operasional)';
      $stmt = $pdo->prepare($insertbiayaop);
      $stmt->execute(['id_lokasi' => $id_lokasi, 'nama_biaya_operasional' => $nama_biaya_operasional, 'jumlah_biaya_operasional' => $jumlah_biaya_operasional]);
    }


    //Load untuk Edit
    if ($_POST['type'] == 'load_modal_biaya_operasional' && !empty($_POST["id_biaya_operasional"])) {
      $id_biaya_operasional = $_POST["id_biaya_operasional"];

      $loadpatokan = 'SELECT * FROM t_biaya_operasional
                    WHERE id_biaya_operasional = :id_biaya_operasional';
      $stmt = $pdo->prepare($loadpatokan);
      $stmt->execute(['id_biaya_operasional' => $id_biaya_operasional]);
      $rowitem = $stmt->fetch();

      ?>

        <div class="col border rounded p-2 bg-light">
          <div class="row mb-2">
            <div class="col-sm">
              <label for="num_biaya_pergantian">Nama Biaya Operasional</label>
              <input type="text" class="form-control" name="nama_biaya_operasional" value="<?= $rowitem->nama_biaya_operasional ?>" required />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label for="num_biaya_pergantian">Harga Patokan</label>
              <input type="hidden" id="biaya_pergantian_number3" name="jumlah_biaya_operasional" value="<?= $rowitem->jumlah_biaya_operasional ?>">
              <input type="hidden" id="hid_type" name="type" value="update_modal_biaya_operasional">
              <input type="hidden" id="hid_id_detail_lokasi" name="id_biaya_operasional" value="<?= $id_biaya_operasional ?>">
              <div class="row">
                <div class="col-auto text-center p-2">
                  Rp.
                </div>
                <div class="col">
                  <input onkeyup="formatNumber3(this)" type="text" id="num_biaya_pergantian3" value="<?= number_format($rowitem->jumlah_biaya_operasional) ?>" min="1" name="harga_patokan_lokasi_formatted" class="form-control number-input" required>
                </div>
              </div>



            <?php
          }


          //Edit
          if ($_POST['type'] == 'update_modal_biaya_operasional' && !empty($_POST["id_biaya_operasional"])) {
            $id_biaya_operasional = $_POST["id_biaya_operasional"];
            $jumlah_biaya_operasional = $_POST['jumlah_biaya_operasional'];
            $nama_biaya_operasional = $_POST['nama_biaya_operasional'];

            $updatepatokan = 'UPDATE t_biaya_operasional
                      SET jumlah_biaya_operasional = :jumlah_biaya_operasional, nama_biaya_operasional = :nama_biaya_operasional
                      WHERE id_biaya_operasional = :id_biaya_operasional';
            $stmt = $pdo->prepare($updatepatokan);
            $stmt->execute(['id_biaya_operasional' => $id_biaya_operasional, 'jumlah_biaya_operasional' => $jumlah_biaya_operasional, 'nama_biaya_operasional' => $nama_biaya_operasional]);
          }











          //Rekening Bersama--------------------------------
          //Tambah
          if ($_POST['type'] == 'save_modal_rekber') {
            $nama_pemilik_rekening = $_POST['nama_pemilik_rekening'];
            $nomor_rekening = $_POST['nomor_rekening'];
            $nama_bank = $_POST['nama_bank'];
            $id_wilayah = $_POST['id_wilayah'];

            $insertrekening = 'INSERT INTO t_rekening_bank
                      (nama_pemilik_rekening, nomor_rekening, nama_bank, id_wilayah)
                      VALUES (:nama_pemilik_rekening, :nomor_rekening, :nama_bank, :id_wilayah)';
            $stmt = $pdo->prepare($insertrekening);
            $stmt->execute(['nama_pemilik_rekening' => $nama_pemilik_rekening, 'nomor_rekening' => $nomor_rekening, 'nama_bank' => $nama_bank, 'id_wilayah' => $id_wilayah]);
          }

          //Load untuk Edit
          if ($_POST['type'] == 'load_modal_rekber') {
            $id_rekening_bank = $_POST["id_rekening_bank"];

            $loadrekening = 'SELECT * FROM t_rekening_bank
                    WHERE id_rekening_bank = :id_rekening_bank';
            $stmt = $pdo->prepare($loadrekening);
            $stmt->execute(['id_rekening_bank' => $id_rekening_bank]);
            $rowitem = $stmt->fetch();

            ?>

              <div class="col border rounded p-2 bg-light">
                <div class="row">
                  <div class="col">
                    <div class="row mt-2">
                      <div class="col">
                        <label for="nama_pemilik_rekening">Nama Pemilik Rekening</label>
                        <input type="text" id="nama_pemilik_rekening" name="nama_pemilik_rekening" class="form-control" value="<?= $rowitem->nama_pemilik_rekening ?>" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col">
                        <label for="nomor_rekening">Nomor Rekening</label>
                        <input type="text" id="nomor_rekening" name="nomor_rekening" class="form-control" value="<?= $rowitem->nomor_rekening ?>" required>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col">
                        <label for="nama_bank">Nama Bank</label>
                        <input type="text" id="nama_bank" name="nama_bank" class="form-control" value="<?= $rowitem->nama_bank ?>" required>
                        <input type="hidden" id="hid_type" name="type" value="update_modal_rekber">
                        <input type="hidden" id="hid_type" name="id_rekening_bank" value="<?= $rowitem->id_rekening_bank ?>">
                      </div>
                    </div>
                  </div>

                <?php
              }


              //Edit
              if ($_POST['type'] == 'update_modal_rekber') {
                $id_rekening_bank = $_POST["id_rekening_bank"];
                $nama_pemilik_rekening = $_POST['nama_pemilik_rekening'];
                $nomor_rekening = $_POST['nomor_rekening'];
                $nama_bank = $_POST['nama_bank'];

                $updatepatokan = 'UPDATE t_rekening_bank
                      SET nama_pemilik_rekening = :nama_pemilik_rekening, nomor_rekening = :nomor_rekening, nama_bank = :nama_bank
                      WHERE id_rekening_bank = :id_rekening_bank';
                $stmt = $pdo->prepare($updatepatokan);
                $stmt->execute(['id_rekening_bank' => $id_rekening_bank, 'nama_pemilik_rekening' => $nama_pemilik_rekening, 'nomor_rekening' => $nomor_rekening, 'nama_bank' => $nama_bank]);
              }



                ?>