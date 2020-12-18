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
