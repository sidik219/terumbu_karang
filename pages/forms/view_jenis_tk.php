<?php
include '../../build/config/connection.php';

if(isset($_GET['id_jenis'])) 
{
    $query = mysqli_query($koneksi,"SELECT * FROM t_jenis_terumbu_karang WHERE id_jenis='".$_GET['id_jenis']."'");
    $row = mysqli_fetch_array($query);
    header("Content-type: " . $row["tipe_gambar"]);
    echo $row["foto_jenis"];
}
else
{
    header('location:jenis_tk.php');
}
?>