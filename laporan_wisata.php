<?php
include 'build/config/connection.php';
session_start();

if ($_GET['type'] == 'wisata'){

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
    // $sqlviewwisata = 'SELECT * FROM t_wisata
    //                     LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
    //                     LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi '.$join_wilayah.'
    //                     WHERE  '.$extra_query_noand.'
    //                     ORDER BY id_wisata ASC';
    // $stmt = $pdo->prepare($sqlviewwisata);
    // $stmt->execute();
    // $rowwisata = $stmt->fetchAll();

    // Tampil all data Paket Wisata
    $sqlviewpaket = 'SELECT * FROM tb_paket_wisata
                    ORDER BY id_paket_wisata DESC';
    $stmt = $pdo->prepare($sqlviewpaket);
    $stmt->execute();
    $rowpaket = $stmt->fetchAll();

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
                <th scope="col">Wisata</th>
                <th scope="col">Biaya Wisata</th>
                <th scope="col">Status Wisata</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rowpaket as $paket) { ?>
            <tr>
                <th scope="row">
                    <?=$paket->id_paket_wisata?>
                </th>
                <th scope="row">
                    <?=$paket->id_lokasi?>
                </th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$paket->nama_paket_wisata?>
                </th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$paket->deskripsi_paket_wisata?>
                </th>
                <th style="font-weight: normal; text-align: left;">
                    <?=$paket->deskripsi_panjang_wisata?>
                </th>
                <th style="font-weight: normal; text-align: left;">
                    <?php
                    $sqlviewwisata = 'SELECT * FROM t_wisata
                                    LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                    WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                    AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata
                                    ORDER BY id_wisata DESC';

                    $stmt = $pdo->prepare($sqlviewwisata);
                    $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                    $rowwisata = $stmt->fetchAll();

                    foreach ($rowwisata as $wisata) { ?>
                    <?=$wisata->judul_wisata?>
                    <?php } ?>
                </th>
                
                <?php
                $sqlviewpaket = 'SELECT SUM(biaya_fasilitas) 
                                AS total_biaya_fasilitas, nama_fasilitas, biaya_fasilitas 
                                FROM tb_fasilitas_wisata 
                                LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                $stmt = $pdo->prepare($sqlviewpaket);
                $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                $sumfasilitas = $stmt->fetchAll();

                foreach ($sumfasilitas as $fasilitas) { ?>
                <th style="font-weight: normal; text-align: left;">
                    Rp. <?=number_format($fasilitas->total_biaya_fasilitas, 0)?></th>
                <?php } ?>

                <th style="font-weight: normal; text-align: left;">
                    <?=$paket->status_aktif?></th>
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

<?php } elseif ($_GET['type'] == 'fasilitas') {

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

<?php } elseif ($_GET['type'] == 'all_pengeluaran') {

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_keseluruhan_pengeluaran_wisata.xls");
    
    $sqlreservasi = 'SELECT SUM(total) AS sum_paket FROM t_reservasi_wisata';

    $stmt = $pdo->prepare($sqlreservasi);
    $stmt->execute();
    $reservasi = $stmt->fetch();

    // Select Data Pengeluaran Berdasarkan ID Reservasi
    $sqlpengeluaran = 'SELECT * FROM t_laporan_pengeluaran
                        LEFT JOIN t_reservasi_wisata ON t_laporan_pengeluaran.id_reservasi = t_reservasi_wisata.id_reservasi
                        ORDER BY id_pengeluaran DESC';

    $stmt = $pdo->prepare($sqlpengeluaran);
    $stmt->execute();
    $rowPengeluaran = $stmt->fetchAll();
    
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
    
    <!-- Select Data Reservasi Untuk Laporan Pengeluaran Berdasarkan ID -->
    <table border="1">
        <thead>
            <tr>
                <th scope="col">ID Pengeluaran</th>
                <th scope="col">ID Reservasi</th>
                <th scope="col">Nama Pengeluaran</th>
                <th scope="col">Biaya Pengeluaran</th>
                <th scope="col">Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sum_paket = 0;
            $sum_pengeluaran = 0;

            foreach ($rowPengeluaran as $pengeluaran) { 
            $truedate = strtotime($pengeluaran->update_terakhir);
            
            $sum_pengeluaran+= $pengeluaran->biaya_pengeluaran;
            // var_dump($sum);
            ?>
            <tr>
                <th scope="row"><?=$pengeluaran->id_pengeluaran?></th>
                <td><?=$pengeluaran->id_reservasi?></td>
                <td><?=$pengeluaran->nama_pengeluaran?></td>
                <td>Rp. <?=number_format($pengeluaran->biaya_pengeluaran, 0)?></td>
                <td>
                    <small class="text-muted"><b>Update Terakhir</b>
                    <br><?=strftime('%A, %d %B %Y', $truedate).'<br> ('.ageCalculator($pengeluaran->update_terakhir).' yang lalu)';?></small>
                </td>
            </tr>
            <?php } ?>

            <!-- Hasil -->
            <?php 
            $total_paket= $reservasi->sum_paket; // get data dari DB t_reservasi_wisata
            $total_saldo = $total_paket - $sum_pengeluaran;
            ?>
            <tr>
                <th scope="row" colspan="4" style="text-align: right;">Biaya Pemasukan:</th>
                <td>Rp. <?=number_format($total_paket, 0)?></td>
            </tr>
            <tr>
                <th scope="row" colspan="4" style="text-align: right;">Biaya Pengeluaran:</th>
                <td>Rp. <?=number_format($sum_pengeluaran, 0)?></td>
            </tr>
            <tr>
                <th scope="row" colspan="4" style="text-align: right;">Total Sisa Biaya:</th>
                <td>Rp. <?=number_format($total_saldo, 0)?></td>
            </tr>
        </tbody>
    </table>

<?php } elseif ($_GET['type'] == 'pengeluaran') {

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_pengeluaran_wisata.xls");

    // GET ID Reservasi
    $id_reservasi = $_GET['id_reservasi'];

    $sqlreservasi = 'SELECT * FROM t_reservasi_wisata
                    WHERE id_reservasi = :id_reservasi';

    $stmt = $pdo->prepare($sqlreservasi);
    $stmt->execute(['id_reservasi' => $id_reservasi]);
    $reservasi = $stmt->fetch();

    // Select Data Pengeluaran Berdasarkan ID Reservasi
    $sqlpengeluaran = 'SELECT * FROM t_laporan_pengeluaran
                        LEFT JOIN t_reservasi_wisata ON t_laporan_pengeluaran.id_reservasi = t_reservasi_wisata.id_reservasi
                        WHERE t_reservasi_wisata.id_reservasi = :id_reservasi
                        ORDER BY id_pengeluaran DESC';

    $stmt = $pdo->prepare($sqlpengeluaran);
    $stmt->execute(['id_reservasi' => $id_reservasi]);
    $rowPengeluaran = $stmt->fetchAll();
    
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
    <!-- Select Data Reservasi Untuk Laporan Pengeluaran Berdasarkan ID -->
    <table border="1">
        <thead>
            <tr>
                <th scope="col">ID Pengeluaran</th>
                <th scope="col">ID Reservasi</th>
                <th scope="col">Nama Pengeluaran</th>
                <th scope="col">Biaya Pengeluaran</th>
                <th scope="col">Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sum_paket = 0;
            $sum_pengeluaran = 0;

            foreach ($rowPengeluaran as $pengeluaran) { 
            $truedate = strtotime($pengeluaran->update_terakhir);
            
            $sum_pengeluaran+= $pengeluaran->biaya_pengeluaran;
            // var_dump($sum);
            ?>
            <tr>
                <th scope="row"><?=$pengeluaran->id_pengeluaran?></th>
                <td><?=$pengeluaran->id_reservasi?></td>
                <td><?=$pengeluaran->nama_pengeluaran?></td>
                <td>Rp. <?=number_format($pengeluaran->biaya_pengeluaran, 0)?></td>
                <td>
                    <small class="text-muted"><b>Update Terakhir</b>
                    <br><?=strftime('%A, %d %B %Y', $truedate).'<br> ('.ageCalculator($pengeluaran->update_terakhir).' yang lalu)';?></small>
                </td>
            </tr>
            <?php } ?>

            <!-- Hasil -->
            <?php 
            $sum_paket = $reservasi->total; // get data dari DB t_reservasi_wisata
            $total_saldo = $sum_paket - $sum_pengeluaran;
            ?>
            <tr>
                <th scope="row" colspan="4" style="text-align: right;">Biaya Reservasi:</th>
                <td>Rp. <?=number_format($reservasi->total, 0)?></td>
            </tr>
            <tr>
                <th scope="row" colspan="4" style="text-align: right;">Biaya Pengeluaran:</th>
                <td>Rp. <?=number_format($sum_pengeluaran, 0)?></td>
            </tr>
            <tr>
                <th scope="row" colspan="4" style="text-align: right;">Total Sisa Biaya:</th>
                <td>Rp. <?=number_format($total_saldo, 0)?></td>
            </tr>
        </tbody>
    </table>
<?php } ?>