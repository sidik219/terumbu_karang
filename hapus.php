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