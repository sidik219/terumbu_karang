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
elseif ($type == 'wisata'){
    $sql = 'DELETE FROM t_wisata
            WHERE id_wisata = :id_wisata';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_wisata' => $_GET['id_wisata']]);
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
elseif ($type == 'fasilitas_wisata'){
    $sql = 'DELETE FROM tb_fasilitas_wisata
            WHERE id_fasilitas_wisata = :id_fasilitas_wisata';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_fasilitas_wisata' => $_GET['id_fasilitas_wisata']]);
            header('Location: kelola_fasilitas_wisata.php?status=deletesuccess');
}
elseif ($type == 'arsip_laporan_sebaran'){
    $id_laporan = $_GET['id_laporan'];
    $sql = 'DELETE FROM t_laporan_sebaran
            WHERE id_laporan = :id_laporan';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_laporan' => $id_laporan]);
            header('Location: kelola_arsip_laporan_sebaran.php?status=deletesuccess');
}
