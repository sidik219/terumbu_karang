<?php
include 'build/config/connection.php';
session_start();

$laporan = $_GET['laporan'];

if(empty($laporan)){
    header('Location: kelola_wisata.php');
    // Jarak
} elseif ($laporan == 'wisata'){

    // Jarak
} elseif ($laporan == 'fasilitas') {

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_pengeluaran_fasilitas_wisata.xls");

    // Tampil all data
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