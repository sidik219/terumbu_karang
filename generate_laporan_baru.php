<?php
include 'build/config/connection.php';
error_reporting(E_ALL ^ E_WARNING);


//Export CSV
if ($_GET['type'] == 'generate_csv_laporan_wilayah'){

  $fileName = 'Laporan_Luas_Sebaran_GoKarang_'.date("Y-m-d").'.csv';

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

//Get the column names.
$columnNames = array();
if(!empty($rows)){
    //We only need to loop through the first row of our result
    //in order to collate the column names.
    $firstRow = $rows[0];
    foreach($firstRow as $colName => $val){
        $columnNames[] = $colName;
    }
}

//Set the Content-Type and Content-Disposition headers to force the download.
header('Content-Type: application/excel');
header('Content-Disposition: attachment; filename="' . $fileName . '"');

//Open up a file pointer
$fp = fopen('php://output', 'w');

fputcsv($fp, array("Laporan Wilayah GoKarang ",date("j F Y, g:i a")," tkjb.or.id"));



foreach ($rowwilayah as $rowitem) {
                        $total_luas_lokasi = 0;
                        $total_persentase_sebaran = 0;

                        fputcsv($fp, array($rowitem->nama_wilayah));
                            fputcsv($fp, array('Nama Lokasi', 'Jumlah Titik', 'Luas Sebaran (m2)', 'Luas Total (m2)', 'Persentase Sebaran', 'Kondisi Lokasi'));


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

                                    foreach($rowlokasi as $lokasi) {
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

                                    fputcsv($fp, array($lokasi->nama_lokasi,$lokasi->jumlah_titik,$lokasi->total_titik,$lokasi->total_lokasi,number_format($lokasi->persentase_sebaran, 1),$kondisi_lokasi));

                    $total_luas_lokasi += $lokasi->total_lokasi;
                    $total_persentase_sebaran += $lokasi->persentase_sebaran ;

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


                      fputcsv($fp,array("Total",$rowitem->jumlah_titik,$rowitem->total_titik,$total_luas_lokasi,$ps,$kondisi_wilayah));
                      fputcsv($fp,array(" "));
                      fputcsv($fp,array("Kondisi Kurang","Cukup","Baik","Sangat Baik"));
                      fputcsv($fp,array($kurang_luas,$cukup_luas,$baik_luas,$sangat_baik_luas));
                      fputcsv($fp,array(" "));
                      fputcsv($fp,array(" "));


                    }



//Close the file pointer.
fclose($fp);




}


?>
