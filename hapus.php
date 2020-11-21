<?php
include 'build\config\connection.php';
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

 