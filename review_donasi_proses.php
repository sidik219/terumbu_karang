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
  if ($_SESSION['level_user'] == '1') {
    $id_status_donasi = 1;
  } elseif ($_SESSION['level_user'] == '3') {
    $id_status_donasi = 3;
  }
  $tanggal_donasi = date ('Y-m-d H:i:s', time());
  $id_rekening_bersama = $keranjang->id_rekening_bersama;

  $sqlviewrekeningbersama = 'SELECT * FROM t_rekening_bank WHERE id_rekening_bank = :id_rekening_bank';
    $stmt = $pdo->prepare($sqlviewrekeningbersama);
    $stmt->execute(['id_rekening_bank' => $id_rekening_bersama]);
    $rekening = $stmt->fetch();

  $sqlinsertdonasi = "INSERT INTO t_donasi
        (id_user, nominal, deskripsi_donasi, id_lokasi, id_status_donasi, pesan, nama_donatur, bank_donatur, nomor_rekening_donatur, tanggal_donasi, update_terakhir, id_rekening_bersama)
        VALUES (:id_user, :nominal, :deskripsi_donasi, :id_lokasi, :id_status_donasi,
                :pesan, :nama_donatur, :bank_donatur, :nomor_rekening_donatur, :tanggal_donasi, :update_terakhir, :id_rekening_bersama)
    ";

    $stmt = $pdo->prepare($sqlinsertdonasi);
    $stmt->execute(['id_user' => $id_user, 'nominal' => $nominal , 'deskripsi_donasi' => $deskripsi_donasi,
    'id_lokasi' => $id_lokasi , 'id_status_donasi' => $id_status_donasi, 'pesan' => $pesan ,
    'nama_donatur' => $nama_donatur, 'bank_donatur' => $bank_donatur, 'nomor_rekening_donatur' => $nomor_rekening_donatur, 
    'tanggal_donasi' => $tanggal_donasi, 'update_terakhir' => $tanggal_donasi, 'id_rekening_bersama' => $id_rekening_bersama]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
    //echo "HAHAHAAHA INSERT FAILED !";
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        $last_id = $pdo->lastInsertId();

    }

    $list_terumbu = '';

    foreach ($keranjang->keranjang as $isi){
        $id_terumbu_karang = $isi->id_tk;
        $jumlah_terumbu = $isi->jumlah_tk;
        $id_donasi = $last_id;

        $sqlviewrekeningbersama = 'SELECT * FROM t_terumbu_karang WHERE id_terumbu_karang = :id_terumbu_karang';
        $stmt = $pdo->prepare($sqlviewrekeningbersama);
        $stmt->execute(['id_terumbu_karang' => $id_terumbu_karang]);
        $terumbu = $stmt->fetch();

        $list_terumbu .= '<br>'.$terumbu->nama_terumbu_karang.' x'.$jumlah_terumbu;

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
        $stmt->execute(['id_lokasi' => $id_lokasi, 'id_terumbu_karang' => $id_terumbu_karang , 'jumlah_terumbu' => 0]);

      }
      //Kirim email untuk Donatur
      include 'includes/email_handler.php'; //PHPMailer
      $email = $_SESSION['email'];
      $username = $_SESSION['username'];
      $nama_user = $_SESSION['nama_user'];

      $subjek = 'Informasi Pembayaran Donasi Terumbu Karang (ID Donasi: '.$id_donasi.') - GoKarang';
      $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
          <br>Yth. '.$nama_user.'
          <br>Terima kasih telah membuat donasi terumbu karang di GoKarang!
          <br>Berikut rincian tujuan pembayaran donasi anda:          
          <br>Bank Tujuan Pembayaran: '.$rekening->nama_bank.'
          <br>Nomor Rekening: '.$rekening->nomor_rekening.'
          <br>Nama Rekening: '.$rekening->nama_pemilik_rekening.'
          <br>Nominal pembayaran: Rp. '.number_format($nominal, 0).'
          <br>
          <br>Bank Anda: '.$bank_donatur.'
          <br>Nomor Rekening Anda: '.$nomor_rekening_donatur.'
          <br>Nama Rekening Anda: '.$nama_donatur.'
          <br>
          <br>Terumbu karang pilihan:'.$list_terumbu.'
          <br>Harap upload bukti pembayaran donasi (format gambar .JPG) di link berikut:
          <br><a href="https://tkjb.or.id/edit_donasi_saya.php?id_donasi='.$id_donasi.'">Upload Bukti Pembayaran</a>
      ';
      
      smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);

      //Kirim email untuk Pengelola Wilayah
      
      $sqlviewpengelolawilayah = 'SELECT * FROM t_lokasi 
                                  LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah
                                  LEFT JOIN t_pengelola_wilayah ON t_pengelola_wilayah.id_wilayah = t_lokasi.id_wilayah
                                  WHERE id_lokasi = :id_lokasi';
        $stmt = $pdo->prepare($sqlviewpengelolawilayah);
        $stmt->execute(['id_lokasi' => $id_lokasi]);
        $rowpengelola = $stmt->fetchAll();

        foreach($rowpengelola as $pengelola){
          $sqlviewdatauser = 'SELECT * FROM t_user 
                              WHERE id_user = :id_user';
          $stmt = $pdo->prepare($sqlviewdatauser);
          $stmt->execute(['id_user' => $pengelola->id_user]);
          $datauser = $stmt->fetch();

          $email = $datauser->email;
          $username = $datauser->username;
          $nama_user = $datauser->nama_user;

          $subjek = 'Donasi Baru (ID Donasi: '.$id_donasi.') - Terumbu Karang GoKarang';
          $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
          <br>Yth. '.$nama_user.'
          <br>Wilayah anda menerima donasi baru pada lokasi '.$pengelola->nama_lokasi.'
          <br>Berikut rincian donasi baru tersebut:
          <br>Bank Donatur: '.$bank_donatur.'
          <br>Nomor Rekening Donatur: '.$nomor_rekening_donatur.'
          <br>Nama Rekening Donatur: '.$nama_donatur.'
          <br>          
          <br>Bank Tujuan Pembayaran: '.$rekening->nama_bank.'
          <br>Nomor Rekening Tujuan: '.$rekening->nomor_rekening.'
          <br>Nama Rekening Tujuan: '.$rekening->nama_pemilik_rekening.'
          <br>Nominal pembayaran: Rp. '.number_format($nominal, 0).'
          <br>
          <br>Terumbu karang pilihan:'.$list_terumbu.'
          <br>Harap verifikasi bukti donasi di link berikut jika donatur sudah mengupload bukti pembayaran:
          <br><a href="https://tkjb.or.id/edit_donasi.php?id_donasi='.$id_donasi.'">Verifikasi Bukti Pembayaran</a>
          <br>
          <br>Jika donatur belum mengupload bukti donasi dalam '.$pengelola->batas_hari_pembayaran.' hari, maka donasi dapat dibatalkan.
      ';
      
      smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
      } 

      if ($_SESSION['level_user'] == '1') {
        header("Location:donasi_saya.php?status=addsuccess");
      } elseif ($_SESSION['level_user'] == '3') {
        header("Location:kelola_donasi.php?status=addsuccess");
      }
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
