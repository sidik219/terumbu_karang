<?php
include '../../build/config/connection.php';

if(isset($_GET['id_tk'])) 
{		
    $query = mysqli_query($koneksi,"SELECT * FROM t_terumbu_karang 
    	WHERE id_terumbu_karang='".$_GET['id_tk']."'");
    $row = mysqli_fetch_array($query);
    header("Content-type: " . $row["tipe_gambar"]);
    echo $row["foto_terumbu_karang"];
}
else
{
    header('location:h_terumbu_karang.php');
}
?>