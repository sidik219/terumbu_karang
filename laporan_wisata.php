<?php
include 'build/config/connection.php';
session_start();

$laporan = $_GET['laporan'];

if(empty($laporan)){
    header('Location: kelola_wisata.php');
    // Jarak
} elseif ($laporan == 'wisata'){

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_data_wisata.xls");

    if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
        header('location: login.php?status=restrictedaccess');
    }

    $level_user = $_SESSION['level_user'];

    if($level_user == 2){
    $id_wilayah = $_SESSION['id_wilayah_dikelola'];
    $extra_query = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand = " t_lokasi.id_wilayah = $id_wilayah ";

    $join_wilayah = " LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah ";
    }
    else if($level_user == 3){
    $id_lokasi = $_SESSION['id_lokasi_dikelola'];
    $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand = " t_lokasi.id_lokasi = $id_lokasi ";

    $join_wilayah = " ";
    }
    else if($level_user == 4){
    $extra_query = " ";
    $extra_query_noand = " ";
    $join_wilayah = "  ";
    }

    // Tampil all data wisata
    $sqlviewwisata = 'SELECT * FROM t_wisata
                        LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                        LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi '.$join_wilayah.'
                        WHERE  '.$extra_query_noand.'
                        ORDER BY id_wisata ASC';
    $stmt = $pdo->prepare($sqlviewwisata);
    $stmt->execute();
    $rowwisata = $stmt->fetchAll();

    // =====================================================================================================

    // Tampil all data fasilitas
    $sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata
                            LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                            ORDER BY id_fasilitas_wisata ASC';

    $stmt = $pdo->prepare($sqlviewfasilitas);
    $stmt->execute();
    $rowfasilitas = $stmt->fetchAll();

    // Tampil untuk total biaya fasilitas
    $sqlviewfasilitas = 'SELECT SUM(biaya_fasilitas) AS total_biaya_fasilitas FROM tb_fasilitas_wisata';

    $stmt = $pdo->prepare($sqlviewfasilitas);
    $stmt->execute();
    $totalfasilitas = $stmt->fetchAll();

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
    ?>

    <!-- Table Wisata -->
    <h3>Laporan Data Wisata</h3>
    <table border="1">
        <thead style="background: #cccccc;">
            <tr>
                <th scope="col">ID Paket Wisata</th>
                <th scope="col">ID Lokasi</th>
                <th scope="col">Nama Paket Wisata</th>
                <th scope="col">Deskripsi Wisata</th>
                <th scope="col">Deskripsi Lengkap</th>
                <th scope="col">Foto Wisata</th>
                <th scope="col">Wisata</th>
                <th scope="col">Biaya Wisata</th>
                <th scope="col">Status Wisata</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rowwisata as $wisata) { ?>
            <tr>
                <th scope="row">
                    <?=$wisata->id_paket_wisata?></th>
                <th scope="row">
                    <?=$wisata->id_lokasi?></th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$wisata->nama_paket_wisata?></th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$wisata->deskripsi_wisata?></th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$wisata->deskripsi_panjang_wisata?></th>
                <th style="font-weight: normal; text-align: left;">
                    <img src='<?=$wisata->foto_wisata?>' width="100px"></th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$wisata->judul_wisata?></th>
                
                <?php
                $sqlviewpaket = 'SELECT SUM(biaya_fasilitas) 
                                AS total_biaya_fasilitas, nama_fasilitas, biaya_fasilitas 
                                FROM tb_fasilitas_wisata 
                                LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                $stmt = $pdo->prepare($sqlviewpaket);
                $stmt->execute(['id_paket_wisata' => $wisata->id_paket_wisata]);
                $sumfasilitas = $stmt->fetchAll();

                foreach ($sumfasilitas as $fasilitas) { ?>
                <th style="font-weight: normal; text-align: left;">
                    Rp. <?=number_format($fasilitas->total_biaya_fasilitas, 0)?></th>
                <?php } ?>

                <th style="font-weight: normal; text-align: left;">
                    <?=$wisata->status_aktif?></th>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <br>
    <!-- Table Fasilitas Wisata -->
    <h3>Laporan Data Fasilitas Wisata</h3>
    <table border="1">
        <thead style="background: #cccccc;">
            <tr>
                <th scope="col">ID Fasilitas</th>
                <th scope="col">Nama Fasilitas</th>
                <th scope="col">Biaya Fasilitas</th>
                <th scope="col">Update Terakhir</th>
                <th scope="col">Wisata</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rowfasilitas as $fasilitas) { 
                $truedate = strtotime($fasilitas->update_terakhir); ?>
            <tr>
                <th scope="row">
                    <?=$fasilitas->id_fasilitas_wisata?></th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$fasilitas->nama_fasilitas?></th>
                <th style="font-weight: normal;">
                    Rp. <?=number_format($fasilitas->biaya_fasilitas, 0)?></th>
                <td>
                    <small class="text-muted"><b>Update Terakhir</b>
                    <br><?=strftime('%A, %d %B %Y', $truedate).'<br> ('.ageCalculator($fasilitas->update_terakhir).' yang lalu)';?></small>
                </td>
                <th style="font-weight: normal;">
                    <?=$fasilitas->judul_wisata?></th>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot style="background: #cccccc;">
            <?php foreach ($totalfasilitas as $fasilitas) { ?>
            <tr>
                <th colspan="2">Total Biaya Fasilitas</th>
                <th colspan="3">
                    Rp. <?=number_format($fasilitas->total_biaya_fasilitas, 0)?>
                </th>
            </tr>
            <?php } ?>
        </tfoot>
    </table>

    <?php
    // Jarak
} elseif ($laporan == 'fasilitas') {

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_pengeluaran_fasilitas_wisata.xls");

    // Tampil all data fasilitas
    $sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata
                            ORDER BY id_fasilitas_wisata ASC';

    $stmt = $pdo->prepare($sqlviewfasilitas);
    $stmt->execute();
    $rowfasilitas = $stmt->fetchAll();

    // Tampil untuk total biaya fasilitas
    $sqlviewfasilitas = 'SELECT SUM(biaya_fasilitas) AS total_biaya_fasilitas FROM tb_fasilitas_wisata';

    $stmt = $pdo->prepare($sqlviewfasilitas);
    $stmt->execute();
    $sumfasilitas = $stmt->fetchAll();

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
    ?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col">ID Fasilitas</th>
                <th scope="col">Nama Fasilitas</th>
                <th scope="col">Biaya Fasilitas</th>
                <th scope="col">Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rowfasilitas as $fasilitas) { 
                $truedate = strtotime($fasilitas->update_terakhir); ?>
            <tr>
                <th scope="row">
                    <?=$fasilitas->id_fasilitas_wisata?></th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$fasilitas->nama_fasilitas?></th>
                <th style="font-weight: normal;">
                    Rp. <?=number_format($fasilitas->biaya_fasilitas, 0)?></th>
                <td>
                    <small class="text-muted"><b>Update Terakhir</b>
                    <br><?=strftime('%A, %d %B %Y', $truedate).'<br> ('.ageCalculator($fasilitas->update_terakhir).' yang lalu)';?></small>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <?php foreach ($sumfasilitas as $fasilitas) { ?>
            <tr>
                <th colspan="2">Total Biaya Fasilitas</th>
                <th colspan="2">
                    Rp. <?=number_format($fasilitas->total_biaya_fasilitas, 0)?>
                </th>
            </tr>
            <?php } ?>
        </tfoot>
    </table>

    <?php
    // Jarak
}
?>