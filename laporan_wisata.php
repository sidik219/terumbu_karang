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

    $sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata
                            ORDER BY id_fasilitas_wisata DESC';
    $stmt = $pdo->prepare($sqlviewfasilitas);
    $stmt->execute();
    $rowfasilitas = $stmt->fetchAll();

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
                <th scope="row"><?=$fasilitas->id_fasilitas_wisata?></th>
                <td><?=$fasilitas->nama_fasilitas?></td>
                <td>Rp. <?=number_format($fasilitas->biaya_fasilitas, 0)?></td>
                <td>
                    <small class="text-muted"><b>Update Terakhir</b>
                    <br><?=strftime('%A, %d %B %Y', $truedate).'<br> ('.ageCalculator($fasilitas->update_terakhir).' yang lalu)';?></small>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php
    // Jarak
}
?>