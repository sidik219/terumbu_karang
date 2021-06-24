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
                                $array_kondisi = array("", "Kurang", "Cukup", "Baik", "Sangat Baik");

                                foreach($rowtahun as $tahun){
                                array_push($array_tahun_doang, $tahun->tahun_arsip_wilayah, "", "", "",);
                            }
                            fputcsv($fp,$array_tahun_doang); //tahun


                            foreach($rowtahun as $tahun){
                                array_push($array_kondisi, "Kurang", "Cukup", "Baik", "Sangat Baik");
                            }
                            fputcsv($fp,$array_kondisi); //kondisi header

                            fputcsv($fp,array(" Kabupaten "));






//Close the file pointer.
fclose($fp);




}


?>
