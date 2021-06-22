<?php
include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}

//Generate arsip laporan baru menggunakan isi database terumbu karang saat ini.
//Proses : Buat entry t_laporan baru, save last inserted id_laporan. Hitung semua data dan insert ke t_arsip_wilayah. INSERT INTO SELECT data t_arsip_lokasi dan t_arsip_titik.

$tahun_sekarang = date("Y") + 1;
$tipe_laporan = "Arsip luas sebaran terumbu karang Tahun ".$tahun_sekarang;

$sqlarsipbaru = 'INSERT INTO t_laporan_sebaran
                  (periode_laporan, tipe_laporan)
                  VALUES (:periode_laporan, :tipe_laporan)';
$stmt = $pdo->prepare($sqlarsipbaru);
$stmt->execute(['periode_laporan' => $tahun_sekarang, 'tipe_laporan' => $tipe_laporan]);

$last_id_laporan = $pdo->lastInsertId(); //id_laporan terbaru

//Hitung luas sebaran per wilayah

$sqlviewwilayah = 'SELECT *, SUM(luas_titik) AS total_titik,
                    COUNT(t_titik.id_titik) AS jumlah_titik,
                    SUM(t_lokasi.luas_lokasi) / (SELECT COUNT(t_titik.id_titik) GROUP BY t_titik.id_titik) AS total_lokasi,
                    (SUM(t_titik.luas_titik) / (SUM(t_lokasi.luas_lokasi) / (SELECT COUNT(t_titik.id_titik) GROUP BY t_titik.id_titik))) * 100 AS persentase_sebaran

                    FROM t_titik, t_lokasi, t_wilayah
					          WHERE t_titik.id_lokasi = t_lokasi.id_lokasi
                    AND t_lokasi.id_wilayah = t_wilayah.id_wilayah
                    GROUP BY t_wilayah.id_wilayah
ORDER BY t_lokasi.id_wilayah ASC';

$stmt = $pdo->prepare($sqlviewwilayah);
$stmt->execute();
$rowwilayah = $stmt->fetchAll();


foreach ($rowwilayah as $rowitem) { //wilayah loop
                  $total_luas_lokasi = 0;
                  $total_persentase_sebaran = 0;

                  $sql_lokasi = 'SELECT *, SUM(luas_titik) AS total_titik,
                  COUNT(id_titik) AS jumlah_titik,
                  SUM(luas_lokasi)  / COUNT(id_titik) AS total_lokasi,
                  (SUM(t_titik.luas_titik) / (SUM(t_lokasi.luas_lokasi) / COUNT(t_titik.id_titik)) ) * 100 AS persentase_sebaran

                  FROM `t_titik`, t_lokasi, t_wilayah
                  WHERE t_titik.id_lokasi = t_lokasi.id_lokasi
                  AND t_lokasi.id_wilayah = t_wilayah.id_wilayah
                  AND t_lokasi.id_wilayah = '.$rowitem->id_wilayah.'
                  GROUP BY t_lokasi.id_lokasi
                  ORDER BY persentase_sebaran DESC';

                  $stmt = $pdo->prepare($sql_lokasi);
                  $stmt->execute();
                  $rowlokasi = $stmt->fetchAll();

                  $kurang = 0; $cukup=0; $baik=0; $sangat_baik=0;
                  $kurang_luas = 0; $cukup_luas = 0; $baik_luas = 0; $sangat_baik_luas = 0;

                  foreach($rowlokasi as $lokasi) { //lokasi loop
                    $ps = $lokasi->persentase_sebaran;
                    if($ps >= 0 && $ps < 25){
                    $kondisi_lokasi = 'Kurang';
                    $kurang_luas += $lokasi->total_titik;
                    }
                    else if($ps >= 25 && $ps < 50){
                    $kondisi_lokasi = 'Cukup';
                    $cukup_luas += $lokasi->total_titik;
                    }
                    else if($ps >= 50 && $ps < 75){
                    $kondisi_lokasi = 'Baik';
                    $baik_luas += $lokasi->total_titik;
                    }
                    else{
                    $kondisi_lokasi = 'Sangat Baik';
                    $sangat_baik_luas += $lokasi->total_titik;
                    }

                    $total_luas_lokasi += $lokasi->total_lokasi;
                    $total_persentase_sebaran += $lokasi->persentase_sebaran ;


                    //INSERT t_arsip_lokasi

                    $tahun_arsip_lokasi = $tahun_sekarang;
                    $id_lokasi = $lokasi->id_lokasi;
                    $id_wilayah = $rowitem->id_wilayah;
                    $id_laporan = $last_id_laporan;
                    $total_titik_l = $lokasi->jumlah_titik;
                    $total_luas_l = round($lokasi->total_lokasi, 0);
                    $luas_sebaran_l = $lokasi->total_titik;
                    $persentase_sebaran_l = round($lokasi->persentase_sebaran,1);
                    $kondisi_l = $kondisi_lokasi;


                    // echo 'Lokasi: '. $lokasi->nama_lokasi
                    //     .'<br>id: '. $id_lokasi
                    //     .'<br>total titik: '. $total_titik_l
                    //     .'<br>total luas: '. $total_luas_l
                    //     .'<br>luas sebaran: '. $luas_sebaran_l
                    //     .'<br>persen sebaran: '. $persentase_sebaran_l
                    //     .'<br>kondisi rata2: '. $kondisi_l
                    //     .'<br>'. $id_laporan
                    //     .'<br>==========================================<br>';

                    $sqlinsertlokasi = 'INSERT INTO t_arsip_lokasi
                    (tahun_arsip_lokasi, id_lokasi, id_wilayah, id_laporan, total_titik_l, total_luas_l, luas_sebaran_l, persentase_sebaran_l, kondisi_l)
                    VALUES (:tahun_arsip_lokasi, :id_lokasi, :id_wilayah, :id_laporan, :total_titik_l, :total_luas_l, :luas_sebaran_l, :persentase_sebaran_l, :kondisi_l)';
                    $stmt = $pdo->prepare($sqlinsertlokasi);
                    $stmt->execute(['tahun_arsip_lokasi' => $tahun_arsip_lokasi, 'id_lokasi' => $id_lokasi, 'id_wilayah' => $id_wilayah, 'id_laporan' => $id_laporan
                          , 'total_titik_l' => $total_titik_l, 'total_luas_l' => $total_luas_l, 'luas_sebaran_l' => $luas_sebaran_l, 'persentase_sebaran_l' => $persentase_sebaran_l,
                          'persentase_sebaran_l' => $persentase_sebaran_l, 'kondisi_l' => $kondisi_l]);

                } //lokasi loop end

                $ps = number_format($rowitem->total_titik / $total_luas_lokasi * 100, 1);
                      if($ps >= 0 && $ps < 25){
                        $kondisi_wilayah = 'Kurang';
                      }
                      else if($ps >= 25 && $ps < 50){
                        $kondisi_wilayah = 'Cukup';
                      }
                      else if($ps >= 50 && $ps < 75){
                        $kondisi_wilayah = 'Baik';
                      }
                      else{
                        $kondisi_wilayah = 'Sangat Baik';
                      }



                        //INSERT t_arsip_wilayah

                        $tahun_arsip_wilayah = $tahun_sekarang;
                        $id_wilayah = $rowitem->id_wilayah;
                        $total_titik_w = $rowitem->jumlah_titik;
                        $total_luas_w = $total_luas_lokasi;
                        $luas_sebaran_w = $rowitem->total_titik;
                        $persentase_sebaran_w = round(($rowitem->total_titik / $total_luas_lokasi) * 100, 1);
                        $kondisi_w = $kondisi_wilayah;
                        $id_laporan = $last_id_laporan;
                        $sisi_pantai = $rowitem->sisi_pantai;
                        $kurang = $kurang_luas;
                        $cukup = $cukup_luas;
                        $baik = $baik_luas;
                        $sangat_baik = $sangat_baik_luas;

                        // echo 'Wilayah: '. $rowitem->nama_wilayah
                        // .'<br>id: '. $id_wilayah
                        // .'<br>total titik: '. $total_titik_w
                        // .'<br>total luas: '. $total_luas_w
                        // .'<br>luas sebaran: '. $luas_sebaran_w
                        // .'<br>persen sebaran: '. $persentase_sebaran_w
                        // .'<br>kondisi rata2: '. $kondisi_w
                        // // .'<br>'. $id_laporan
                        // .'<br>'. $sisi_pantai
                        // .'<br>kurang : '. $kurang
                        // .'<br>cukup: '. $cukup
                        // .'<br>baik: '. $baik
                        // .'<br>sangat baik: '. $sangat_baik
                        // .'<br>==========================================<br>';

              $sql_arsip_wilayah = 'INSERT INTO t_arsip_wilayah
                  (tahun_arsip_wilayah, id_wilayah, total_titik_w, total_luas_w, luas_sebaran_w,
                  persentase_sebaran_w, kondisi_w, id_laporan, sisi_pantai, kurang, cukup, baik, sangat_baik)

                  VALUES (:tahun_arsip_wilayah, :id_wilayah, :total_titik_w, :total_luas_w, :luas_sebaran_w,
                  :persentase_sebaran_w, :kondisi_w, :id_laporan, :sisi_pantai, :kurang, :cukup, :baik, :sangat_baik)';
            $stmt = $pdo->prepare($sql_arsip_wilayah);
            $stmt->execute(['tahun_arsip_wilayah' => $tahun_arsip_wilayah, 'id_wilayah' => $id_wilayah, 'total_titik_w' => $total_titik_w, 'total_luas_w' => $total_luas_w
            , 'luas_sebaran_w' => $luas_sebaran_w, 'persentase_sebaran_w' => $persentase_sebaran_w, 'kondisi_w' => $kondisi_w, 'id_laporan' => $id_laporan, 'sisi_pantai' => $sisi_pantai
            , 'kurang' => $kurang, 'cukup' => $cukup, 'baik' => $baik, 'sangat_baik' => $sangat_baik]);




}//Wilayah loop end

header('location: kelola_arsip_laporan_sebaran.php?status=addsuccess');





