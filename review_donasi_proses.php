<?php

include 'build/config/connection.php';
session_start();
if(isset($_SESSION['data_donasi'])){

  $keranjang =  json_decode($_SESSION['data_donasi']);

  $id_user = $_SESSION['id_user'];
  $nominal = $keranjang->nominal;
  $id_lokasi = $keranjang->id_lokasi;
  $pesan = $keranjang->pesan;
  $nama_donatur = $keranjang->nama_donatur;
  $nomor_rekening_donatur = $keranjang->no_rekening_donatur;
  $bank_donatur = $keranjang->nama_bank_donatur;
  $deskripsi_donasi = $_SESSION["data_donasi"];
  $id_status_donasi = 1;
  $tanggal_donasi = date ('Y-m-d H:i:s', time());

  $sqlinsertdonasi = "INSERT INTO t_donasi
        (id_user, nominal, deskripsi_donasi, id_lokasi, id_status_donasi, pesan, nama_donatur, bank_donatur, nomor_rekening_donatur, tanggal_donasi, update_terakhir)
        VALUES (:id_user, :nominal, :deskripsi_donasi, :id_lokasi, :id_status_donasi,
                :pesan, :nama_donatur, :bank_donatur, :nomor_rekening_donatur, :tanggal_donasi, :update_terakhir)
    ";

    $stmt = $pdo->prepare($sqlinsertdonasi);
    $stmt->execute(['id_user' => $id_user, 'nominal' => $nominal , 'deskripsi_donasi' => $deskripsi_donasi,
    'id_lokasi' => $id_lokasi , 'id_status_donasi' => $id_status_donasi, 'pesan' => $pesan ,
    'nama_donatur' => $nama_donatur, 'bank_donatur' => $bank_donatur, 'nomor_rekening_donatur' => $nomor_rekening_donatur, 'tanggal_donasi' => $tanggal_donasi, 'update_terakhir' => $tanggal_donasi]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
    //echo "HAHAHAAHA INSERT FAILED !";
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        $last_id = $pdo->lastInsertId();

    }

    foreach ($keranjang->keranjang as $isi){
        $id_terumbu_karang = $isi->id_tk;
        $jumlah_terumbu = $isi->jumlah_tk;
        $id_donasi = $last_id;

        $sqlinsertdetaildonasi = "INSERT INTO t_detail_donasi
        (id_donasi, id_terumbu_karang, jumlah_terumbu)
        VALUES (:id_donasi, :id_terumbu_karang, :jumlah_terumbu)
        ";

        $stmt = $pdo->prepare($sqlinsertdetaildonasi);
        $stmt->execute(['id_donasi' => $id_donasi, 'id_terumbu_karang' => $id_terumbu_karang , 'jumlah_terumbu' => $jumlah_terumbu]);


        $sqlupdatestoktk = 'UPDATE t_detail_lokasi
                            SET stok_terumbu = (stok_terumbu - :jumlah_terumbu)
                            WHERE id_lokasi = :id_lokasi AND id_terumbu_karang = :id_terumbu_karang';

        $stmt = $pdo->prepare($sqlupdatestoktk);
        $stmt->execute(['id_lokasi' => $id_lokasi, 'id_terumbu_karang' => $id_terumbu_karang , 'jumlah_terumbu' => $jumlah_terumbu]);

      }
      header("Location:donasi_saya.php?status=addsuccess");






}
else{
  header("Location:map.php?status=nodata");
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Memproses Donasi...</title>
</head>
<body>
</body>
</html>
