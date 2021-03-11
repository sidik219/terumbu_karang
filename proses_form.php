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
                                <br>ID <?=$rowitem->id_jenis?> - <?=$rowitem->nama_jenis?>
                              </div>
                              <div class="col-sm">
                                <label>Sub-jenis</label>
                                <br><?=$rowitem->id_terumbu_karang?> - <?=$rowitem->nama_terumbu_karang?>
                              </div>
                              <div class="col">

                              </div>
                            </div>
                            <div class="row">
                              <div class="col">
                               <label for="num_biaya_pergantian">Harga Patokan</label>
                        <input type="hidden" id="biaya_pergantian_number" name="num_harga_patokan_lokasi_angka" value="<?=$rowitem->harga_patokan_lokasi?>">
                        <input type="hidden" id="hid_type" name="type" value="update_modal_patokan_harga_terumbu">
                        <input type="hidden" id="hid_id_detail_lokasi" name="id_detail_lokasi" value="<?=$id_detail_lokasi?>">
                        <div class="row">
                          <div class="col-auto text-center p-2">
                            Rp.
                          </div>
                          <div class="col">
                            <input onkeyup="formatNumber(this)" type="text" id="num_biaya_pergantian" value="<?=$rowitem->harga_patokan_lokasi?>" min="1" name="harga_patokan_lokasi_formatted" class="form-control number-input" required>
                          </div>
                        </div>
                        <div class="row mt-2">
                          <div class="col">
                            <label for="num_biaya_pergantian">Stok</label>
                            <input type="number" min="0" id="num_stok" name="stok_terumbu" value="<?=$rowitem->stok_terumbu?>" class="form-control number-input" required>
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





?>
