<?php
include 'build/config/connection.php';
session_start();

$type = $_GET['type'];

if(empty($type)){
    header('Location: index.php');
}
elseif ($type == 'wilayah'){
    $sqldelwilayah = 'DELETE FROM t_wilayah
    WHERE id_wilayah = :id_wilayah';

    $stmt = $pdo->prepare($sqldelwilayah);
    $stmt->execute(['id_wilayah' => $_GET['id_wilayah']]);
    header('Location: kelola_wilayah.php?status=deletesuccess');
}
elseif ($type == 'lokasi'){
    $sqldelwilayah = 'DELETE FROM t_lokasi
    WHERE id_lokasi = :id_lokasi';

    $stmt = $pdo->prepare($sqldelwilayah);
    $stmt->execute(['id_lokasi' => $_GET['id_lokasi']]);
    header('Location: kelola_lokasi.php?status=deletesuccess');
}
elseif ($type == 'titik'){
    $sql = 'DELETE FROM t_titik
            WHERE id_titik = :id_titik';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_titik' => $_GET['id_titik']]);
            header('Location: kelola_titik.php?status=deletesuccess');
}
elseif ($type == 'jenis'){
    $sql = 'DELETE FROM t_jenis_terumbu_karang
            WHERE id_jenis = :id_jenis';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_jenis' => $_GET['id_jenis']]);
            header('Location: kelola_jenis_tk.php?status=deletesuccess');
}
elseif ($type == 'terumbu_karang'){
    $sql = 'DELETE FROM t_terumbu_karang
            WHERE id_terumbu_karang = :id_terumbu_karang';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_terumbu_karang' => $_GET['id_terumbu_karang']]);
            header('Location: kelola_tk.php?status=deletesuccess');
}
elseif ($type == 'paket_wisata'){
    $id_paket_wisata = $_GET['id_paket_wisata'];
    // $sql = 'DELETE tb_paket_wisata , t_wisata, tb_fasilitas_wisata  FROM tb_paket_wisata  
    //         INNER JOIN t_wisata
    //         INNER JOIN tb_fasilitas_wisata
    //         WHERE tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata
    //         AND t_wisata.id_wisata = tb_fasilitas_wisata.id_wisata
    //         AND tb_paket_wisata.id_paket_wisata = :id_paket_wisata';

    //         $stmt = $pdo->prepare($sql);
    //         $stmt->execute(['id_paket_wisata' => $_GET['id_paket_wisata']]);

    $sql = 'DELETE FROM tb_paket_wisata
            WHERE id_paket_wisata = :id_paket_wisata';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_paket_wisata' => $_GET['id_paket_wisata']]);
            header('Location: kelola_wisata.php?status=deletesuccess');
}
elseif ($type == 'detail_lokasi'){
    $id_lokasi = $_GET['id_lokasi'];
    $sql = 'DELETE FROM t_detail_lokasi
            WHERE id_detail_lokasi = :id_detail_lokasi';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_detail_lokasi' => $_GET['id_detail_lokasi']]);
            header('Location: kelola_harga_terumbu.php?id_lokasi='.$id_lokasi.'&status=deletesuccess');
}
elseif ($type == 'user_p_wilayah'){
    $id_wilayah = $_GET['id_wilayah'];
    $id_user = $_GET['id_user'];
    $sql = 'DELETE FROM t_pengelola_wilayah
            WHERE id_wilayah = :id_wilayah AND id_user = :id_user';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_wilayah' => $id_wilayah, 'id_user' => $id_user]);
            header('Location: atur_pengelola_wilayah.php?id_wilayah='.$id_wilayah.'&status=deletesuccess');
}
elseif ($type == 'user_p_lokasi'){
    $id_lokasi = $_GET['id_lokasi'];
    $id_user = $_GET['id_user'];
    $sql = 'DELETE FROM t_pengelola_lokasi
            WHERE id_lokasi = :id_lokasi AND id_user = :id_user';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_lokasi' => $id_lokasi, 'id_user' => $id_user]);
            header('Location: atur_pengelola_lokasi.php?id_lokasi='.$id_lokasi.'&status=deletesuccess');
}
elseif ($type == 'rekening_bersama'){
    $sql = 'DELETE FROM t_rekening_bank
            WHERE id_rekening_bank = :id_rekening_bank';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_rekening_bank' => $_GET['id_rekening_bank']]);
            header('Location: kelola_rekening_bersama.php?status=deletesuccess');
}
elseif ($type == 'asuransi'){
    $id_asuransi = $_GET['id_asuransi'];
    $sql = 'DELETE FROM t_asuransi
            WHERE id_asuransi = :id_asuransi';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_asuransi' => $id_asuransi]);
            header('Location: kelola_asuransi.php?status=deletesuccess');
}
elseif ($type == 'arsip_laporan_sebaran'){
    $id_laporan = $_GET['id_laporan'];
    $sql = 'DELETE FROM t_laporan_sebaran
            WHERE id_laporan = :id_laporan';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_laporan' => $id_laporan]);
            header('Location: kelola_arsip_laporan_sebaran.php?status=deletesuccess');
}
elseif ($type == 'batalkan_donasi'){
    $id_donasi = $_GET['id_donasi'];

    //Kembalikan stok terumbu karang

    //Select detail_donasi dengan id_donasi
    $sql = "SELECT * FROM t_detail_donasi WHERE id_donasi = :id_donasi";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_donasi' => $id_donasi]);
    $row = $stmt->fetchAll();

    //Tiap detail_donasi, kembalikan jumlah_tk ke stok_terumbu di t_detail_lokasi
    foreach ($row as $isi_keranjang){
        $sql = "UPDATE t_detail_lokasi 
                SET stok_terumbu = stok_terumbu + :jumlah_terumbu
                WHERE  id_terumbu_karang = :id_terumbu_karang";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_terumbu_karang' => $isi_keranjang->id_terumbu_karang, 'jumlah_terumbu' => $isi_keranjang->jumlah_terumbu]);
    }

    $sql = 'DELETE FROM t_donasi
            WHERE id_donasi = :id_donasi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_donasi' => $id_donasi]);
    header('Location: kelola_donasi.php?status=deletesuccess');
}
elseif ($type == 'batalkan_reservasi'){
    $id_reservasi = $_GET['id_reservasi'];

    $sql = 'DELETE FROM t_reservasi_wisata
            WHERE id_reservasi = :id_reservasi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_reservasi' => $id_reservasi]);
    header('Location: kelola_reservasi_wisata.php?status=deletesuccess');
}
elseif ($type == 'pengadaan_fasilitas'){
    $id_pengadaan = $_GET['id_pengadaan'];

    $sql = 'DELETE FROM t_pengadaan_fasilitas
            WHERE id_pengadaan = :id_pengadaan';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_pengadaan' => $id_pengadaan]);
    header('Location: kelola_pengadaan_fasilitas.php?status=deletesuccess');
}
elseif ($type == 'kerjasama'){
    $id_kerjasama = $_GET['id_kerjasama'];

    $sql = 'DELETE FROM t_kerjasama
            WHERE id_kerjasama = :id_kerjasama';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_kerjasama' => $id_kerjasama]);
    header('Location: kelola_kerjasama.php?status=deletesuccess');
}
elseif ($type == 'pengeluaran'){
    $id_reservasi = $_GET['id_reservasi'];
    $id_pengeluaran = $_GET['id_pengeluaran'];

    $sql = 'DELETE FROM t_laporan_pengeluaran
            WHERE id_pengeluaran = :id_pengeluaran';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_pengeluaran' => $id_pengeluaran]);
    header('Location: kelola_laporan_wisata.php?id_reservasi='.$id_reservasi.'&status=deletesuccess');
}
elseif ($type == 'biaya_operasional'){
    $id_biaya_operasional = $_GET['id_biaya_operasional'];
    $id_lokasi = $_GET['id_lokasi'];


    $sql = 'DELETE FROM t_biaya_operasional
            WHERE id_biaya_operasional = :id_biaya_operasional';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_biaya_operasional' => $id_biaya_operasional]);
    header('Location: kelola_biaya_operasional.php?id_lokasi='.$id_lokasi.'status=deletesuccess');
}
elseif ($type == 'konten_wilayah'){
    $id_konten_wilayah = $_GET['id_konten_wilayah'];
    $sql = 'DELETE FROM t_konten_wilayah
            WHERE id_konten_wilayah = :id_konten_wilayah';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_konten_wilayah' => $id_konten_wilayah]);
    header('Location: kelola_konten.php?status=deletesuccess');
}
elseif ($type == 'konten_lokasi'){
    $id_konten_lokasi = $_GET['id_konten_lokasi'];
    $sql = 'DELETE FROM t_konten_lokasi
            WHERE id_konten_lokasi = :id_konten_lokasi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_konten_lokasi' => $id_konten_lokasi]);
    header('Location: kelola_konten_tangkolak.php?status=deletesuccess');
}