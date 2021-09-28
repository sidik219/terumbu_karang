<?php
require('plugins/fpdf/fpdf182/fpdf.php');
include 'build/config/connection.php';

class myPDF extends FPDF{
    function header(){
        global $pdo;
        $this->Image('images/area-gunting.png', 10, 25, -5000); //Logo, Kiri-Atas,Kanan-Bawah
        // $this->Image('images/bg-invoice.png', 5, 30, 287, -550); //Kiri-Atas,Kanan-Bawah
        // $this->Image('images/bg-invoice-line.png', 7, 32, 287, -550); //Line-Invoice, Kiri-Atas,Kanan-Bawah
        $this->SetFont('Arial', 'B', 14);
        $this->cell(276, 5, 'EKSPRESI/PESAN DONASI TERUMBU KARANG', 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', '', 12);

        $id_donasi = $_GET['id_donasi'];

        $sqlviewreservasi = 'SELECT * FROM t_donasi
                        LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                        LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                        LEFT JOIN t_user ON t_donasi.id_user = t_user.id_user
                        LEFT JOIN t_rekening_bank ON t_donasi.id_rekening_bersama = t_rekening_bank.id_rekening_bank
                        WHERE id_donasi = :id_donasi
                        ORDER BY id_donasi DESC';
        $stmt = $pdo->prepare($sqlviewreservasi);
        $stmt->execute(['id_donasi' => $id_donasi]);
        $row = $stmt->fetchAll();

        foreach ($row as $rowitem) {
        $this->Cell(276, 10, $rowitem->deskripsi_lokasi, 0, 0, 'C');
        }
        $this->Ln(20);
    }

    function footer(){
        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);
    }
    /*
    function headerTable(){
        $this->SetFont('Times', 'B', 8);
        $this->Cell(20, 10, 'ID Reservasi', 1, 0, 'C');
        $this->Cell(40, 10, 'Nama User', 1, 0, 'C');
        $this->Cell(40, 10, 'Nama Lokasi', 1, 0, 'C');
        $this->Cell(60, 10, 'Tanggal Reservasi', 1, 0, 'C');
        $this->Cell(36, 10, 'Jumlah Peserta', 1, 0, 'C');
        $this->Cell(30, 10, 'Jumlah Donasi', 1, 0, 'C');
        $this->Cell(50, 10, 'Total', 1, 0, 'C');
        $this->Cell(50, 10, 'Status Reservasi', 1, 0, 'C');
        $this->Cell(50, 10, 'Keterangan', 1, 0, 'C');
        $this->Cell(50, 10, 'Judul Wisata', 1, 0, 'C');
        $this->Ln();
    }*/

    Function viewTable($pdo){
        $this->SetFont('Times', '', 13);

        $id_donasi = $_GET['id_donasi'];

        $sqlviewreservasi = 'SELECT * FROM t_donasi
                        LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                        LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                        LEFT JOIN t_user ON t_donasi.id_user = t_user.id_user
                        LEFT JOIN t_rekening_bank ON t_donasi.id_rekening_bersama = t_rekening_bank.id_rekening_bank
                        WHERE id_donasi = :id_donasi
                        ORDER BY id_donasi DESC';
        $stmt = $pdo->prepare($sqlviewreservasi);
        $stmt->execute(['id_donasi' => $id_donasi]);
        $row = $stmt->fetchAll();

        foreach ($row as $rowitem) {
            $donasidate = strtotime($rowitem->tanggal_donasi);
            //User
            $this->Line(10, 90, 10, 30); //Line Tengah Kanan luar
            $this->Line(10, 30, 287, 30); //Line atas

            $this->Cell(55, 5, 'ID Donasi', 0, 0);
            $this->Cell(107, 5, ': '.$rowitem->id_donasi, 0, 0);
            $this->Cell(52, 5, 'Tanggal Donasi', 0, 0);
            // $this->SetTextColor(55, 255, 255);
            $this->SetFillColor(255, 155, 71);
            $this->Cell(63, 5, ': '.strftime("%A, %d %B %Y", $donasidate), 0, 1, 'C', 1);
            $this->SetTextColor(0, 0, 0);

            $this->Cell(55, 5, 'Nama User', 0, 0);
            $this->Cell(107, 5, ': '.$rowitem->nama_user, 0, 0);
            $this->Cell(57, 5, 'Lokasi Penanaman', 0, 0);
            $this->Cell(57, 5, ': '.$rowitem->nama_lokasi, 0, 1);

            $this->Line(20, 85, 20, 50); //Line Tengah Kanan Dalam
            $this->Line(20, 50, 280, 50); //Line Tengah Atas

            $this->Ln(10);
            $this->Cell(118, 5, '', 0, 0);
            $this->SetFont('Arial', '', 16);
            $this->Cell(55, 5, 'Pesan/Ekspresi', 0, 0);

            $this->Ln(10);
            $this->Cell(10, 10, '', 0, 0);
            $this->SetFont('Arial', 'B', 20);
            $this->Cell(55, 10, '"'.$rowitem->pesan.'"', 0, 1);

            $this->Line(20, 85, 280, 85); //Line Tengah bawah
            $this->Line(280, 50, 280, 85); //Line Tengah Kiri Dalam

            $this->Line(10, 90, 287, 90); //Line Bawah
            $this->Line(287, 30, 287, 90); //Line Tengah Kiri Luar

        }
    }
}

//PDF
$pdf    = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4', 0);
//$pdf->headerTable();
$pdf->viewTable($pdo);

//Output Invoice Donasi
$id_donasi = $_GET['id_donasi'];

$sqlviewreservasi = 'SELECT * FROM t_donasi
                LEFT JOIN t_lokasi ON t_donasi.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_status_donasi ON t_donasi.id_status_donasi = t_status_donasi.id_status_donasi
                LEFT JOIN t_user ON t_donasi.id_user = t_user.id_user
                WHERE id_donasi = :id_donasi
                ORDER BY id_donasi DESC';
$stmt = $pdo->prepare($sqlviewreservasi);
$stmt->execute(['id_donasi' => $id_donasi]);
$row = $stmt->fetchAll();

foreach ($row as $rowitem) {
    $donasidate = strtotime($rowitem->tanggal_donasi);
    $fileName = 'Ekspresi/Pesan - Donasi Terumbu Karang, ' .$rowitem->nama_user.' - '.strftime('%A, %d %B %Y', $donasidate). '.pdf';
}
$pdf->Output($fileName, 'D');
?>

?>