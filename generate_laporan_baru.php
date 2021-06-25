<?php
include 'build/config/connection.php';
error_reporting(E_ALL ^ E_WARNING);




$sqltahun = 'SELECT * FROM t_arsip_wilayah GROUP BY tahun_arsip_wilayah ORDER BY tahun_arsip_wilayah ASC';
$stmt = $pdo->prepare($sqltahun);
$stmt->execute();
$rowtahunsemua = $stmt->fetchAll();

$sqlviewarsip = 'SELECT * FROM t_arsip_wilayah GROUP BY tahun_arsip_wilayah ORDER BY tahun_arsip_wilayah ASC';
$stmt = $pdo->prepare($sqlviewarsip);
$stmt->execute();
$rowtahun = $stmt->fetchAll();

//Export CSV
if ($_GET['type'] == 'generate_csv_laporan_wilayah'){

  $fileName = 'Laporan_Luas_Sebaran_GoKarang_'.date("Y-m-d").'.csv';



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

fputcsv($fp, array("Laporan Wilayah GoKarang "));
fputcsv($fp, array(date("j F Y, g:i a")));



// fputcsv($fp,array(""));


                          fputcsv($fp,array("Laporan Luas Sebaran Terumbu Karang")); //judul
                          fputcsv($fp,array("*Data dalam satuan hektar (ha)"));

                                fputcsv($fp,array(" "));


                                $array_tahun_doang = array(" ");
                                $array_kondisi = array("Kabupaten");


                                foreach($rowtahun as $tahun){
                                array_push($array_tahun_doang, $tahun->tahun_arsip_wilayah, "", "", "",);
                            }
                            fputcsv($fp,$array_tahun_doang); //tahun


                            foreach($rowtahun as $tahun){
                                array_push($array_kondisi, "Kurang", "Cukup", "Baik", "Sangat Baik");
                            }
                            fputcsv($fp,$array_kondisi); //kondisi header





                            $sqlviewsisi = 'SELECT * FROM t_arsip_wilayah GROUP BY sisi_pantai
                                                ORDER BY sisi_pantai DESC';
                              $stmt = $pdo->prepare($sqlviewsisi);
                              $stmt->execute();
                              $rowsisi = $stmt->fetchAll();

                      foreach($rowsisi as $sisi){
                        $array_data_kabupaten = array();
                        $array_data_sisi = array("Total ".$sisi->sisi_pantai);
                        $array_data_total = array("Total Keseluruhan");

                    $sqlviewluasnama = 'SELECT * FROM t_wilayah
                                    LEFT JOIN t_arsip_wilayah ON t_wilayah.id_wilayah = t_arsip_wilayah.id_wilayah WHERE t_wilayah.sisi_pantai = :sisi_pantai
                                    GROUP BY t_arsip_wilayah.id_wilayah  ORDER BY tahun_arsip_wilayah ASC';
                              $stmt = $pdo->prepare($sqlviewluasnama);
                              $stmt->execute(['sisi_pantai' => $sisi->sisi_pantai]);
                              $rowluasnama = $stmt->fetchAll();



                    foreach ($rowluasnama as $luasnama) {

                        $sqlviewluastahunan = 'SELECT * FROM t_wilayah
                                    LEFT JOIN t_arsip_wilayah ON t_wilayah.id_wilayah = t_arsip_wilayah.id_wilayah
                                   WHERE t_wilayah.id_wilayah = :id_wilayah
                                   ORDER BY tahun_arsip_wilayah ASC';
                              $stmt = $pdo->prepare($sqlviewluastahunan);
                              $stmt->execute(['id_wilayah' => $luasnama->id_wilayah]);
                              $rowluastahunan = $stmt->fetchAll();

                          array_push($array_data_kabupaten, $luasnama->nama_wilayah);

                          foreach ($rowluastahunan as $luastahunan) {
                              array_push($array_data_kabupaten, $luastahunan->kurang, $luastahunan->cukup, $luastahunan->baik, $luastahunan->sangat_baik);

                          }
                          fputcsv($fp, $array_data_kabupaten);
                          $array_data_kabupaten = array();
                    }

                    foreach($rowtahun as $tahun){
                        $sqlhitungluas = 'SELECT id_wilayah, sum(kurang) as total_kurang, sum(cukup) as total_cukup, SUM(baik) as total_baik, SUM(sangat_baik) as total_sangat_baik
                                        FROM t_arsip_wilayah WHERE tahun_arsip_wilayah = :tahun AND sisi_pantai = :sisi_pantai ';
                                $stmt = $pdo->prepare($sqlhitungluas);
                                $stmt->execute(['tahun' => $tahun->tahun_arsip_wilayah, 'sisi_pantai' => $sisi->sisi_pantai]);
                                $rowhitung = $stmt->fetchAll();
                              foreach($rowhitung as $hitungan){
                                array_push($array_data_sisi, $hitungan->total_kurang, $hitungan->total_cukup, $hitungan->total_baik, $hitungan->total_sangat_baik);
                              }

                  }
                  fputcsv($fp, $array_data_sisi);
                  fputcsv($fp, array(" "));
                              // $array_data_sisi = array();
                }

                $sqlhitungluas = 'SELECT id_wilayah, sum(kurang) as total_kurang, sum(cukup) as total_cukup, SUM(baik) as total_baik, SUM(sangat_baik) as total_sangat_baik FROM t_arsip_wilayah GROUP BY tahun_arsip_wilayah';
                                $stmt = $pdo->prepare($sqlhitungluas);
                                $stmt->execute();
                                $rowhitung = $stmt->fetchAll();
                              foreach($rowhitung as $hitungan){
                                array_push($array_data_total, $hitungan->total_kurang, $hitungan->total_cukup, $hitungan->total_baik, $hitungan->total_sangat_baik);
                              }
                  fputcsv($fp, $array_data_total);
                  fputcsv($fp, array(" "));






//Close the file pointer.
fclose($fp);




}


?>
