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



