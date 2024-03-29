<?php
include 'build/config/connection.php';
session_start();

if ($_GET['type'] == 'wisata') {
    // Laporan Data Wisata
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_data_wisata.xls");

    if (!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
        header('location: login.php?status=restrictedaccess');
    }

    $level_user = $_SESSION['level_user'];

    if ($level_user == 2) {
        $id_wilayah = $_SESSION['id_wilayah_dikelola'];
        $extra_query = " AND t_lokasi.id_wilayah = $id_wilayah ";
        $extra_query_noand = " t_lokasi.id_wilayah = $id_wilayah ";

        $join_wilayah = " LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah ";
    } else if ($level_user == 3) {
        $id_lokasi = $_SESSION['id_lokasi_dikelola'];
        $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
        $extra_query_noand = " t_lokasi.id_lokasi = $id_lokasi ";

        $join_wilayah = " ";
    } else if ($level_user == 4) {
        $extra_query = " ";
        $extra_query_noand = " ";
        $join_wilayah = "  ";
    }

    // Tampil all data Paket Wisata
    $sqlviewpaket = 'SELECT * FROM tb_paket_wisata
                    LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                    ORDER BY id_paket_wisata ASC';
    $stmt = $pdo->prepare($sqlviewpaket);
    $stmt->execute();
    $rowpaket = $stmt->fetchAll();

    function ageCalculator($dob)
    {
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $ag = $birthdate->diff($today)->y;
        $mn = $birthdate->diff($today)->m;
        $dy = $birthdate->diff($today)->d;
        if ($mn == 0) {
            return "$dy Hari";
        } elseif ($ag == 0) {
            return "$mn Bulan  $dy Hari";
        } else {
            return "$ag Tahun $mn Bulan $dy Hari";
        }
    }
?>

    <!-- Table Wisata -->
    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="9">LAPORAN DATA WISATA</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">ID Paket Wisata</th>
                <th scope="col">Nama Paket Wisata</th>
                <th scope="col">Status Paket</th>
                <th scope="col">Batas Pemesanan</th>
                <th scope="col">Asuransi</th>
                <th scope="col">Biaya Wisata</th>
                <th scope="col">Wisata</th>
                <th scope="col">Fasilitas Wisata</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $sum_asuransi = 0;
            $sum_fasilitas = 0;

            foreach ($rowpaket as $paket) {
                $awaldate = strtotime($paket->tgl_pemesanan);
                $akhirdate = strtotime($paket->tgl_akhir_pemesanan); ?>
                <tr>
                    <th><?= $no ?></th>
                    <th scope="row"><?= $paket->id_paket_wisata ?></th>
                    <td><?= $paket->nama_paket_wisata ?></td>
                    <td><?= $paket->status_aktif ?></td>
                    <td>
                        <?php
                        // tanggal sekarang
                        $tgl_sekarang = date("Y-m-d");
                        // tanggal pembuatan batas pemesanan paket wisata
                        $tgl_awal = $paket->tgl_pemesanan;
                        // tanggal berakhir pembuatan batas pemesanan paket wisata
                        $tgl_akhir = $paket->tgl_akhir_pemesanan;
                        // jangka waktu + 365 hari
                        $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                        //tanggal expired
                        $tgl_exp = date("Y-m-d", $jangka_waktu);

                        if ($tgl_sekarang >= $tgl_exp) { ?>
                            Sudah Tidak Berlaku.
                        <?php } else { ?>
                            Masih dalam jangka waktu.
                        <?php } ?>
                    </td>
                    <td>Rp. <?= number_format($paket->biaya_asuransi, 0) ?></td>
                    <td>
                        <?php
                        $sqlviewpaket = 'SELECT SUM(biaya_kerjasama) AS total_biaya_fasilitas, biaya_asuransi
                                        FROM tb_fasilitas_wisata
                                        LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                        LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                        LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                        LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                        LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                                        WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                        AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                        $stmt = $pdo->prepare($sqlviewpaket);
                        $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                        $rowfasilitas = $stmt->fetchAll();

                        foreach ($rowfasilitas as $fasilitas) {

                            // Menjumlahkan biaya asuransi dan biaya paket wisata
                            $asuransi       = $fasilitas->biaya_asuransi;
                            $wisata         = $fasilitas->total_biaya_fasilitas;
                            $total_paket    = $asuransi + $wisata;

                            // total sum
                            $sum_asuransi += $fasilitas->biaya_asuransi;
                            $sum_fasilitas += $fasilitas->total_biaya_fasilitas;
                        ?>
                            Rp. <?= number_format($total_paket, 0) ?>
                        <?php } ?>
                    </td>
                    <td>
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
                            <?= $wisata->judul_wisata ?><br>
                        <?php } ?>
                    </td>
                    <td>
                        <?php
                        $sqlviewpaket = 'SELECT * FROM tb_fasilitas_wisata
                                        LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                        LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                        LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                        LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                        WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                        AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                        $stmt = $pdo->prepare($sqlviewpaket);
                        $stmt->execute(['id_paket_wisata' => $paket->id_paket_wisata]);
                        $rowfasilitas = $stmt->fetchAll();

                        foreach ($rowfasilitas as $fasilitas) { ?>
                            <?= $fasilitas->pengadaan_fasilitas ?><br>
                        <?php } ?>
                    </td>
                </tr>
            <?php $no++;
            } ?>
        </tbody>
        <tfoot>
            <!-- Hasil -->
            <?php
            $asuransi       = $sum_asuransi;
            $fasilitas      = $sum_fasilitas;
            $total_wisata   = $asuransi + $fasilitas;
            ?>
            <tr>
                <th colspan="8">Total Biaya Wisata</th>
                <th>
                    Rp. <?= number_format($total_wisata, 0) ?>
                </th>
            </tr>
        </tfoot>
    </table>

<?php } elseif ($_GET['type'] == 'fasilitas') {
    // Laporan Data Fasilitas Wisata
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_data_fasilitas_wisata.xls");

    // Tampil all data fasilitas
    $sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata
                        LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                        LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                        ORDER BY id_fasilitas_wisata ASC';

    $stmt = $pdo->prepare($sqlviewfasilitas);
    $stmt->execute();
    $rowfasilitas = $stmt->fetchAll();

    // Tampil untuk total biaya fasilitas
    $sqlviewfasilitas = 'SELECT SUM(biaya_kerjasama) AS total_biaya_fasilitas FROM tb_fasilitas_wisata
                        LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                        LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan';

    $stmt = $pdo->prepare($sqlviewfasilitas);
    $stmt->execute();
    $sumfasilitas = $stmt->fetchAll();

    function ageCalculator($dob)
    {
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $ag = $birthdate->diff($today)->y;
        $mn = $birthdate->diff($today)->m;
        $dy = $birthdate->diff($today)->d;
        if ($mn == 0) {
            return "$dy Hari";
        } elseif ($ag == 0) {
            return "$mn Bulan  $dy Hari";
        } else {
            return "$ag Tahun $mn Bulan $dy Hari";
        }
    }
?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="7">LAPORAN DATA FASILITAS WISATA</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">ID Fasilitas</th>
                <th scope="col">Nama Fasilitas</th>
                <th scope="col">Biaya Fasilitas</th>
                <th scope="col">Status Kerjasama</th>
                <th scope="col">Status Pengadaan</th>
                <th scope="col">Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rowfasilitas as $fasilitas) {
                $truedate = strtotime($fasilitas->update_terakhir); ?>
                <tr>
                    <th><?= $no ?></th>
                    <th scope="row"><?= $fasilitas->id_fasilitas_wisata ?></th>
                    <td><?= $fasilitas->pengadaan_fasilitas ?></td>
                    <td>Rp. <?= number_format($fasilitas->biaya_kerjasama, 0) ?></td>
                    <td>
                        <?php if ($fasilitas->status_kerjasama == "Melakukan Kerjasama") { ?>
                            <span class="badge badge-pill badge-success"><?= $fasilitas->status_kerjasama ?></span>
                        <?php } elseif ($fasilitas->status_kerjasama == "Tidak Melakukan Kerjasama") { ?>
                            <span class="badge badge-pill badge-warning"><?= $fasilitas->status_kerjasama ?></span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($fasilitas->status_pengadaan == "Baik") { ?>
                            <span class="badge badge-pill badge-success"><?= $fasilitas->status_pengadaan ?></span>
                        <?php } elseif ($fasilitas->status_pengadaan == "Rusak") { ?>
                            <span class="badge badge-pill badge-warning"><?= $fasilitas->status_pengadaan ?></span>
                        <?php } elseif ($fasilitas->status_pengadaan == "Hilang") { ?>
                            <span class="badge badge-pill badge-danger"><?= $fasilitas->status_pengadaan ?></span>
                        <?php } ?>
                    </td>
                    <td>
                        <small class="text-muted"><b>Update Terakhir</b>
                            <br><?= strftime('%A, %d %B %Y', $truedate) . '<br> (' . ageCalculator($fasilitas->update_terakhir) . ' yang lalu)'; ?></small>
                    </td>
                </tr>
            <?php $no++;
            } ?>
        </tbody>
        <tfoot>
            <?php foreach ($sumfasilitas as $fasilitas) { ?>
                <tr>
                    <th colspan="6">Total Biaya Fasilitas</th>
                    <th>
                        Rp. <?= number_format($fasilitas->total_biaya_fasilitas, 0) ?>
                    </th>
                </tr>
            <?php } ?>
        </tfoot>
    </table>

<?php } elseif ($_GET['type'] == 'all_pengeluaran') {
    // Laporan Pengeluaran
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_pengeluaran_wisata.xls");

    $url_sekarang = basename(__FILE__);
    include 'hak_akses.php';

    $level_user = $_SESSION['level_user'];

    if ($level_user == 2) {
        $id_wilayah = $_SESSION['id_wilayah_dikelola'];
        $extra_query = " AND t_lokasi.id_wilayah = $id_wilayah ";
        $extra_query_noand = " t_lokasi.id_wilayah = $id_wilayah ";
    } else if ($level_user == 3) {
        $id_lokasi = $_SESSION['id_lokasi_dikelola'];
        $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
        $extra_query_noand = " t_lokasi.id_lokasi = $id_lokasi ";
    } else if ($level_user == 4) {
        $extra_query = "  ";
        $extra_query_noand = " 1 ";
    }

    // Tinggal GET value tanggal yg sudah diset pada laporan periode wisata
    $start = date("2021-09-01");
    $end = date("2021-09-06");

    $sqlviewdonasi = 'SELECT * FROM t_laporan_pengeluaran
                    LEFT JOIN t_reservasi_wisata ON t_laporan_pengeluaran.id_reservasi = t_reservasi_wisata.id_reservasi
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                    WHERE  ' . $extra_query_noand . '
                    AND t_reservasi_wisata.id_status_reservasi_wisata = 2
                    AND tanggal_pesan BETWEEN "' . $start . '" 
                    AND "' . $end . '"';

    $stmt = $pdo->prepare($sqlviewdonasi);
    $stmt->execute();
    $row = $stmt->fetchAll();

    // Select Data Pengeluaran Berdasarkan ID Reservasi
    $sqlhitungtotal = 'SELECT COUNT(t_laporan_pengeluaran.id_pengeluaran) AS total_reservasi, 
                            SUM(t_laporan_pengeluaran.biaya_pengeluaran) AS biaya_pengeluaran
                    FROM t_laporan_pengeluaran
                    LEFT JOIN t_reservasi_wisata ON t_laporan_pengeluaran.id_reservasi = t_reservasi_wisata.id_reservasi
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                    WHERE  ' . $extra_query_noand . '
                    AND t_reservasi_wisata.id_status_reservasi_wisata = 2
                    AND tanggal_pesan BETWEEN "' . $start . '" 
                    AND "' . $end . '"';

    $stmt = $pdo->prepare($sqlhitungtotal);
    $stmt->execute();
    $rowtotal = $stmt->fetch();

    function ageCalculator($dob)
    {
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $ag = $birthdate->diff($today)->y;
        $mn = $birthdate->diff($today)->m;
        $dy = $birthdate->diff($today)->d;
        if ($mn == 0) {
            return "$dy Hari";
        } elseif ($ag == 0) {
            return "$mn Bulan  $dy Hari";
        } else {
            return "$ag Tahun $mn Bulan $dy Hari";
        }
    }
?>

    <!-- Select Data Reservasi Untuk Laporan Pengeluaran Berdasarkan ID -->
    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="9">LAPORAN SELURUH PENGELUARAN WISATA</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">ID Pengeluaran</th>
                <th scope="col">ID Reservasi</th>
                <th scope="col">Nama Pengeluaran</th>
                <th scope="col">Lokasi</th>
                <th scope="col">Nama Wisatawan</th>
                <th scope="col">Tanggal Reservasi</th>
                <th scope="col">Tanggal Pengeluaran</th>
                <th scope="col">Biaya Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $pemasukan = 0;
            $pengeluaran = 0;

            foreach ($row as $rowitem) {
                $truedate = strtotime($rowitem->update_terakhir);
                $reservasidate = strtotime($rowitem->tgl_reservasi);
                $pemasukan = $rowitem->total;
            ?>
                <tr>
                    <th><?= $no ?></th>
                    <th scope="row"><?= $rowitem->id_pengeluaran ?></th>
                    <th><?= $rowitem->id_reservasi ?></th>
                    <td><?= $rowitem->nama_pengeluaran ?></td>
                    <td><?= $rowitem->nama_lokasi ?></td>
                    <td><?= $rowitem->nama_user ?></td>
                    <td>
                        <?= strftime('%A, %e %B %Y', $reservasidate); ?><br>
                        <?php if ($rowitem->id_status_reservasi_wisata == 1) {
                            echo alertPembayaran($rowitem->tgl_reservasi);
                        } ?>
                    </td>
                    <td>
                        <small class="text-muted"><b>Update Terakhir</b>
                            <br><?= strftime('%A, %d %B %Y', $truedate) . '<br> (' . ageCalculator($rowitem->update_terakhir) . ' yang lalu)'; ?></small>
                    </td>
                    <td class="nominal">Rp. <?= number_format($rowitem->biaya_pengeluaran, 0) ?></td>
                </tr>
            <?php $no++;
            } ?>

            <!-- Hasil -->
            <?php
            $pengeluaran = $rowtotal->biaya_pengeluaran;
            $total = $pemasukan - $pengeluaran;
            ?>
            <tr>
                <th scope="col" colspan="9" style="text-align: left;">Total: <?= $rowtotal->total_reservasi ?> Reservasi</th>
            </tr>
            <tr>
                <th scope="row" colspan="8" style="text-align: right;">Biaya Pemasukan:</th>
                <td>Rp. <?= number_format($pemasukan, 0) ?></td>
            </tr>
            <tr>
                <th scope="row" colspan="8" style="text-align: right;">Biaya Pengeluaran:</th>
                <td>Rp. <?= number_format($pengeluaran, 0) ?></td>
            </tr>
            <tr>
                <th scope="row" colspan="8" style="text-align: right;">Total Sisa Biaya:</th>
                <td>Rp. <?= number_format($total, 0) ?></td>
            </tr>
        </tbody>
    </table>

<?php } elseif ($_GET['type'] == 'pengeluaran') {
    // Laporan Laba Rugi
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_laba_rugi.xls");

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
                        ORDER BY id_pengeluaran ASC';

    $stmt = $pdo->prepare($sqlpengeluaran);
    $stmt->execute(['id_reservasi' => $id_reservasi]);
    $rowPengeluaran = $stmt->fetchAll();

    function ageCalculator($dob)
    {
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $ag = $birthdate->diff($today)->y;
        $mn = $birthdate->diff($today)->m;
        $dy = $birthdate->diff($today)->d;
        if ($mn == 0) {
            return "$dy Hari";
        } elseif ($ag == 0) {
            return "$mn Bulan  $dy Hari";
        } else {
            return "$ag Tahun $mn Bulan $dy Hari";
        }
    }
?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="6">LAPORAN LABA RUGI</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">ID Pengeluaran</th>
                <th scope="col">ID Reservasi</th>
                <th scope="col">Nama Pengeluaran</th>
                <th scope="col">Biaya Pengeluaran</th>
                <th scope="col">Update Terakhir</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $sum_paket = 0;
            $sum_pengeluaran = 0;

            foreach ($rowPengeluaran as $pengeluaran) {
                $truedate = strtotime($pengeluaran->update_terakhir);

                $sum_pengeluaran += $pengeluaran->biaya_pengeluaran;
                // var_dump($sum);
            ?>
                <tr>
                    <th><?= $no ?></th>
                    <th scope="row"><?= $pengeluaran->id_pengeluaran ?></th>
                    <td><?= $pengeluaran->id_reservasi ?></td>
                    <td><?= $pengeluaran->nama_pengeluaran ?></td>
                    <td>Rp. <?= number_format($pengeluaran->biaya_pengeluaran, 0) ?></td>
                    <td>
                        <small class="text-muted"><b>Update Terakhir</b>
                            <br><?= strftime('%A, %d %B %Y', $truedate) . '<br> (' . ageCalculator($pengeluaran->update_terakhir) . ' yang lalu)'; ?></small>
                    </td>
                </tr>
            <?php $no++;
            } ?>

            <!-- Hasil -->
            <?php
            $sum_paket = $reservasi->total; // get data dari DB t_reservasi_wisata
            $total_saldo = $sum_paket - $sum_pengeluaran;
            ?>
            <tr>
                <th scope="row" colspan="5" style="text-align: right;">Biaya Reservasi:</th>
                <td>Rp. <?= number_format($reservasi->total, 0) ?></td>
            </tr>
            <tr>
                <th scope="row" colspan="5" style="text-align: right;">Biaya Pengeluaran:</th>
                <td>Rp. <?= number_format($sum_pengeluaran, 0) ?></td>
            </tr>
            <tr>
                <th scope="row" colspan="5" style="text-align: right;">Total Sisa Biaya:</th>
                <td>Rp. <?= number_format($total_saldo, 0) ?></td>
            </tr>
        </tbody>
    </table>
<?php } elseif ($_GET['type'] == 'all_reservasi') {
    // Laporan Reservasi Wisata
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_reservasi_wisata.xls");

    $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                  LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                  LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                  LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                  WHERE t_reservasi_wisata.id_status_reservasi_wisata = 2 
                  ORDER BY id_reservasi ASC';
    $stmt = $pdo->prepare($sqlviewreservasi);
    $stmt->execute();
    $rowReservasi = $stmt->fetchAll();

?>

    <table border="1">
        <thead>
            <tr>
                <th scope="col" colspan="12">LAPORAN RESERVASI WISATA</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">ID Reservasi</th>
                <th scope="col">Nama User</th>
                <th scope="col">Nama Lokasi</th>
                <th scope="col">Tanggal Reservasi</th>
                <th scope="col">Status Reservasi</th>
                <th scope="col">Nama Paket Wisata</th>
                <th scope="col">Jumlah Peserta</th>
                <th scope="col">Jumlah Donasi</th>
                <th scope="col">Total (Rp.)</th>
                <th scope="col">Keterangan</th>
                <th scope="col">No HP</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rowReservasi as $reservasi) {
                $truedate = strtotime($reservasi->update_terakhir);
                $reservasidate = strtotime($reservasi->tgl_reservasi);
            ?>
                <tr>
                    <th><?= $no ?></th>
                    <th scope="row"><?= $reservasi->id_reservasi ?></th>
                    <td><?= $reservasi->nama_user ?></td>
                    <td><?= $reservasi->nama_lokasi ?></td>
                    <td>
                        <?= strftime('%A, %d %B %Y', $reservasidate); ?>
                    </td>
                    <td>
                        <?= $reservasi->nama_status_reservasi_wisata ?>
                        <br><small class="text-muted">Update Terakhir:
                            <br><?= strftime('%A, %d %B %Y', $truedate); ?></small>
                    </td>
                    <td><?= $reservasi->nama_paket_wisata ?></td>
                    <td><?= $reservasi->jumlah_peserta ?></td>
                    <td>Rp. <?= number_format($reservasi->jumlah_donasi, 0) ?></td>
                    <td>Rp. <?= number_format($reservasi->total, 0) ?></td>
                    <td><?= $reservasi->keterangan ?></td>
                    <td><?= $reservasi->no_hp ?></td>
                </tr>
            <?php $no++;
            } ?>
        </tbody>
    </table>

<?php } elseif ($_GET['type'] == 'laporan_donasi') {
    // Laporan Donasi
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_donasi.xls");

    $url_sekarang = basename(__FILE__);
    include 'hak_akses.php';

    $level_user = $_SESSION['level_user'];

    if ($level_user == 2) {
        $id_wilayah = $_SESSION['id_wilayah_dikelola'];
        $extra_query = " AND t_lokasi.id_wilayah = $id_wilayah ";
        $extra_query_noand = " t_lokasi.id_wilayah = $id_wilayah ";
    } else if ($level_user == 3) {
        $id_lokasi = $_SESSION['id_lokasi_dikelola'];
        $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
        $extra_query_noand = " t_lokasi.id_lokasi = $id_lokasi ";
    } else if ($level_user == 4) {
        $extra_query = "  ";
        $extra_query_noand = " 1 ";
    }

    $sqlviewdonasi = 'SELECT * FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi 
                  LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
                  WHERE  ' . $extra_query_noand . ' AND t_donasi.id_status_pengadaan_bibit = 4 
                  ORDER BY id_donasi DESC';

    $stmt = $pdo->prepare($sqlviewdonasi);
    $stmt->execute();
    $row = $stmt->fetchAll();

    $sqlhitungtotal = 'SELECT COUNT(t_donasi.id_donasi) AS total_donasi, SUM(t_donasi.nominal) AS total_nominal FROM t_donasi
                  LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi 
                  LEFT JOIN t_status_pengadaan_bibit ON t_donasi.id_status_pengadaan_bibit = t_status_pengadaan_bibit.id_status_pengadaan_bibit
                  WHERE  ' . $extra_query_noand . ' AND t_donasi.id_status_pengadaan_bibit = 4 
                  ORDER BY id_donasi DESC';

    $stmt = $pdo->prepare($sqlhitungtotal);
    $stmt->execute();
    $rowtotal = $stmt->fetch();



    function alertPembayaran($dob)
    {
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $mn = $birthdate->diff($today)->m;
        $dy = $birthdate->diff($today)->d;

        $tglbatas = $birthdate->add(new DateInterval('P3D'));
        $tglbatas_formatted = strftime('%A, %e %B %Y pukul %R', $tglbatas->getTimeStamp());
        $batas_waktu_pesan = '<br><b>Batas pembayaran:</b><br>' . $tglbatas_formatted;
        if ($dy <= 3) {
            //jika masih dalam batas waktu
            return  $batas_waktu_pesan . '<br> <i class="fas fa-exclamation-circle text-primary"></i><small> Menunggu bukti pembayaran donatur</small>';
        } else if ($dy > 3) {
            //overdue
            return $batas_waktu_pesan . '<br><i class="fas fa-exclamation-circle text-danger"></i><small> Sudah lewat batas waktu pembayaran.</small><br>
            ';
        }
    }

?>
    <table>
        <thead>
            <tr>
                <th scope="col">ID Donasi</th>
                <th scope="col">Lokasi</th>
                <th scope="col">Nama Donatur</th>
                <th scope="col">Tanggal Donasi</th>
                <th scope="col">Pesan Donasi</th>
                <th scope="col">Nama Terumbu Karang</th>
                <th scope="col">Jumlah Terumbu Karang</th>
                <th scope="col">Bank Donatur</th>
                <th scope="col">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($row as $rowitem) {
                $truedate = strtotime($rowitem->update_terakhir);
                $donasidate = strtotime($rowitem->tanggal_donasi);
                $sqlviewisi = 'SELECT jumlah_terumbu, nama_terumbu_karang, foto_terumbu_karang FROM t_detail_donasi
                LEFT JOIN t_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
                LEFT JOIN t_terumbu_karang ON t_detail_donasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                WHERE t_detail_donasi.id_donasi = :id_donasi';
                $stmt = $pdo->prepare($sqlviewisi);
                $stmt->execute(['id_donasi' => $rowitem->id_donasi]);
                $rowisi = $stmt->fetchAll();
            ?>
                <tr>
                    <th scope="row"><?= $rowitem->id_donasi ?>
                        <?php echo empty($rowitem->id_batch) ? '' : 'Batch Ke ' . $rowitem->id_batch . ''; ?>
                    </th>
                    <td><?= $rowitem->nama_lokasi ?></td>
                    <td><?= $rowitem->nama_donatur ?></td>
                    <td><?= strftime('%A, %e %B %Y', $donasidate); ?> <br> <?php if ($rowitem->id_status_donasi == 1) {
                                                                                echo alertPembayaran($rowitem->tanggal_donasi);
                                                                            }  ?> </td>
                    <td><?= $rowitem->pesan ?></td>
                    <?php foreach ($rowisi as $isi) : ?>
                        <td><?= $isi->nama_terumbu_karang ?></td>
                        <td><?= $isi->jumlah_terumbu ?></td>
                    <?php endforeach ?>
                    <td><?= $rowitem->bank_donatur ?></td>
                    <td>Rp. <?= number_format($rowitem->nominal, 0) ?></td>
                </tr>
            <?php //$index++;
            } ?>
        </tbody>
    </table>
    <hr class="m-0" />
    <hr class="m-0" />
    <table class="table table-striped table-responsive-sm">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col">Total: <?= $rowtotal->total_donasi ?> Donasi</th>
                <th scope="col">Rp. <?= number_format($rowtotal->total_nominal, 0) ?></th>
            </tr>
        </thead>
    </table>

<?php } ?>