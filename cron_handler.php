<?php
include 'build/config/connection.php';
require 'includes/email_handler.php';

$hari_ini = date('Y-m-d', time());
$update_terakhir = date('Y-m-d H:i:s', time());
$tanggal_hari_formatted = strftime("%A, %d %B %Y");

//
//Cek tanggal penanaman batch, jika hari ini, set status_batch ke siap ditanam, kirim email pemberitahuan
$stmt = $pdo->prepare('SELECT t_batch.id_batch, t_batch.id_lokasi, t_batch.id_titik, t_batch.tanggal_penanaman,
                       nama_lokasi, keterangan_titik
                      FROM t_batch
                      LEFT JOIN t_lokasi ON t_batch.id_lokasi = t_lokasi.id_lokasi
                      LEFT JOIN t_titik ON t_batch.id_titik = t_titik.id_titik
                      WHERE t_batch.id_status_batch = 1 '); 
$stmt->execute();
$rowbatch = $stmt->fetchAll();

if(!empty($rowbatch)){    
    foreach($rowbatch as $batch){
        if($hari_ini == $batch->tanggal_penanaman){
            
            //Update status batch ke siap ditanam
            $stmt = $pdo->prepare('UPDATE t_batch SET update_status_batch_terakhir = :update_terakhir, id_status_batch = 2 WHERE id_batch = :id_batch');
            $stmt->execute(['update_terakhir' => $update_terakhir, 'id_batch' => $batch->id_batch]);

            $stmt = $pdo->prepare('SELECT email, nama_user FROM t_pengelola_lokasi
                                    LEFT JOIN t_user ON t_user.id_user  = t_pengelola_lokasi.id_user
                                    WHERE id_lokasi = '.$batch->id_lokasi); 
            $stmt->execute();
            $rowpengelola = $stmt->fetchAll();

            foreach($rowpengelola as $pengelola){
                //kirim email ke pengelola lokasi
                            
                $subjek = 'Batch Siap untuk Ditanam (ID Batch : ' . $batch->id_batch . ' ) - GoKarang';
                $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                        <br>Yth. ' . $pengelola->nama_user . '
                        <br>Bibit terumbu karang pada donasi dalam Batch penanaman ID ' . $batch->id_batch . ' 
                        telah siap untuk ditanam di titik ID '.$batch->id_titik.' ( '.$batch->keterangan_titik.' ), '.$batch->nama_lokasi.', dengan rencana penanaman pada '.$tanggal_hari_formatted.'.
                        <br>
                        <br>Jika bibit dalam batch ini sudah ditanam, selanjutnya harap tentukan tanggal pemeliharaan dengan menginput data pemeliharaan di:
                        <br><a href="https://tkjb.or.id/kelola_pemeliharaan.php">Kelola Pemeliharaan</a>
                ';
                // smtpmailer($pengelola->email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
                echo 'batch siap ditanam '.$batch->id_batch.' mail sent to '.$pengelola->email.'<br>';
            }            
        }
    }
}
echo 'batch update script ran successfully<br>
    ========================================================
    <br>';








//
//Cek tanggal rencana pemeliharaan dengan status pending, kirim email reminder pemeliharaan jika tanggal_pemeliharaan == hari ini
$stmt = $pdo->prepare('SELECT * FROM t_pemeliharaan
                          LEFT JOIN t_lokasi ON t_pemeliharaan.id_lokasi = t_lokasi.id_lokasi
                          LEFT JOIN t_status_pemeliharaan ON t_pemeliharaan.id_status_pemeliharaan = t_status_pemeliharaan.id_status_pemeliharaan
                          WHERE t_pemeliharaan.id_status_pemeliharaan = 1 '); 
$stmt->execute();
$rowpemeliharaan = $stmt->fetchAll();

if(!empty($rowpemeliharaan)){    
    foreach($rowpemeliharaan as $pemeliharaan){
        if($hari_ini == $pemeliharaan->tanggal_pemeliharaan){

            $stmt = $pdo->prepare('SELECT email, nama_user FROM t_pengelola_lokasi
                                    LEFT JOIN t_user ON t_user.id_user  = t_pengelola_lokasi.id_user
                                    WHERE id_lokasi = '.$pemeliharaan->id_lokasi); 
            $stmt->execute();
            $rowpengelola = $stmt->fetchAll();

            foreach($rowpengelola as $pengelola){
                //kirim email ke pengelola lokasi
                            
                $subjek = 'Pemeliharaan Hari ini (ID Pemeliharaan : ' . $pemeliharaan->id_pemeliharaan . ' ) - GoKarang';
                $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                        <br>Yth. ' . $pengelola->nama_user . '
                        <br>Hari ini perlu dilakukan Pemeliharaan terumbu karang sesuai dengan tanggal yang ditentukan pada Pemeliharaan ID ' . $pemeliharaan->id_pemeliharaan . ', di  '.$pemeliharaan->nama_lokasi.', dengan rencana pemeliharaan pada '.$tanggal_hari_formatted.'.
                        <br>
                        <br>Harap mengisi data informasi kondisi, ukuran, dan jika memungkinkan foto terumbu karang dengan menginput data pemeliharaan di:
                        <br><a href="https://tkjb.or.id/edit_pemeliharaan.php?id_pemeliharaan='.$pemeliharaan->id_pemeliharaan.'">Input Data Pemeliharaan</a>
                ';
                // smtpmailer($pengelola->email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
                echo 'pemeliharaan siap dilakukan '.$pemeliharaan->id_pemeliharaan.' mail sent to '.$pengelola->email.'<br>';
            }            
        }
    }
}
echo 'pemeliharaan email script ran successfully<br>
    ========================================================
    <br>';






//
//Cek tanggal pemeliharaan terakhir, jika lebih dari 90 hari / 3 bulan sejak pemeliharaan terakhir, kirim email reminder
$stmt = $pdo->prepare('SELECT t_batch.id_batch, t_batch.id_lokasi, t_batch.id_titik, t_batch.tanggal_penanaman,
                      t_batch.update_status_batch_terakhir, nama_lokasi, keterangan_titik, nama_status_batch, t_titik.latitude, t_titik.longitude, t_status_batch.id_status_batch, tanggal_pemeliharaan_terakhir, status_cabut_label,
                      TIMESTAMPDIFF(DAY, tanggal_pemeliharaan_terakhir, NOW()) AS lama_sejak_pemeliharaan
                      FROM t_batch
                      LEFT JOIN t_lokasi ON t_batch.id_lokasi = t_lokasi.id_lokasi
                      LEFT JOIN t_titik ON t_batch.id_titik = t_titik.id_titik
                      LEFT JOIN t_status_batch ON t_batch.id_status_batch = t_status_batch.id_status_batch  
                      HAVING lama_sejak_pemeliharaan = 90 AND id_status_batch = 3 '); 
$stmt->execute();
$rowbatch = $stmt->fetchAll();

if(!empty($rowbatch)){    
    foreach($rowbatch as $batch){ 
        $stmt = $pdo->prepare('SELECT email, nama_user FROM t_pengelola_lokasi
                                LEFT JOIN t_user ON t_user.id_user  = t_pengelola_lokasi.id_user
                                WHERE id_lokasi = '.$batch->id_lokasi); 
        $stmt->execute();
        $rowpengelola = $stmt->fetchAll();
            foreach($rowpengelola as $pengelola){
                //kirim email ke pengelola lokasi
                            
                $subjek = 'Batch Perlu Pemeliharaan Kembali (ID Batch : ' . $batch->id_batch . ' ) - GoKarang';
                $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                        <br>Yth. ' . $pengelola->nama_user . '
                        <br>Batch penanaman ID ' . $batch->id_batch . ' 
                        yang ditanam di titik ID '.$batch->id_titik.' ( '.$batch->keterangan_titik.' ), '.$batch->nama_lokasi.', sudah 3 bulan sejak pemeliharaan terakhir dan memerlukan pemeliharaan kembali.
                        <br>
                        <br>Harap tentukan tanggal pemeliharaan selanjutnya dengan menginput data pemeliharaan di:
                        <br><a href="https://tkjb.or.id/kelola_pemeliharaan.php">Kelola Pemeliharaan</a>
                ';
                // smtpmailer($pengelola->email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
                echo 'batch pemeliharaan kembali '.$batch->id_batch.' mail sent to '.$pengelola->email.'<br>';         
        }
    }
}
echo 'batch pemeliharaan kembali script ran successfully<br>
    ========================================================
    <br>';







//
//Cek tanggal reservasi wisata, jika sudah H -3 hari sebelum tanggal wisata, kirim email reminder
$stmt = $pdo->prepare('SELECT *, TIMESTAMPDIFF(DAY, tgl_reservasi, NOW()) AS selisih_hari  
                        FROM `t_reservasi_wisata` 
                        LEFT JOIN t_user ON t_user.id_user = t_reservasi_wisata.id_user
                      	LEFT JOIN tb_paket_wisata ON tb_paket_wisata.id_paket_wisata = t_reservasi_wisata.id_paket_wisata 
                        LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_reservasi_wisata.id_lokasi
                        WHERE id_status_reservasi_wisata = 2
                        HAVING selisih_hari = -3'); 
$stmt->execute();
$rowwisata = $stmt->fetchAll();

if(!empty($rowwisata)){    
    foreach($rowwisata as $wisata){
                //kirim email ke wisatawan
                            
                $subjek = '[Reminder] Reservasi Wisata (ID Batch : ' . $wisata->id_reservasi . ' ) - GoKarang';
                $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                        <br>Yth. ' . $wisata->nama_user . '
                        <br>Reservasi wisata Anda dengan ID ' . $wisata->id_reservasi . ' akan dilakukan pada '.strftime("%A, %d %B %Y", strtotime($wisata->tgl_reservasi)).'.                      
                        <br>
                        <br>Paket Wisata: ' . $wisata->nama_paket_wisata . '
                        <br>Jumlah Peserta: ' . $wisata->jumlah_peserta . '
                        <br>Lokasi: ' . $wisata->nama_lokasi . '
                        <br>
                        <br>Email ini bertujuan untuk sekedar mengingatkan kembali tanggal reservasi Anda tersebut.
                        <br>Untuk melihat rincian reservasi wisata Anda, klik link berikut:
                        <br><a href="https://tkjb.or.id/reservasi_saya.php">Reservasi Wisata Saya</a>
                ';
                // smtpmailer($wisata->email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
                echo 'batch reminder wisata '.$wisata->id_reservasi.' mail sent to '.$wisata->email.'<br>';         
    }
}
echo 'Reminder wisata h-3 script ran successfully '.strftime("%A, %d %B %Y", strtotime($wisata->tgl_reservasi)).'<br>
    ========================================================
    <br>';



?>
