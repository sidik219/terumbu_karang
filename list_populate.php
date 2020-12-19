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

