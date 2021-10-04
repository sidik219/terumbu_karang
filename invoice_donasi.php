<?php
require('plugins/fpdf/fpdf182/fpdf.php');
include 'build/config/connection.php';

class myPDF extends FPDF{
    function header(){
        global $pdo;
        $this->Image('images/KKPlogo.png', 10, 3, -2500); //Logo, Kiri-Atas,Kanan-Bawah
        //$this->Image('images/bg-invoice.png', 5, 30, 287, -550); //Kiri-Atas,Kanan-Bawah
        //$this->Image('images/bg-invoice-line.png', 7, 32, 287, -550); //Line-Invoice, Kiri-Atas,Kanan-Bawah
        $this->SetFont('Arial', 'B', 14);
        $this->cell(276, 5, 'INVOICE DONASI TERUMBU KARANG', 0, 0, 'C');
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
            $this->Cell(55, 5, 'ID Donasi', 0, 0);
            $this->Cell(107, 5, ': '.$rowitem->id_donasi, 0, 0);
            $this->Cell(52, 5, 'Tanggal Donasi', 0, 0);
            $this->SetTextColor(255, 255, 255);
            $this->SetFillColor(4, 119, 194);
            $this->Cell(63, 5, ': '.strftime("%A, %d %B %Y", $donasidate), 0, 1, 'C', 1);
            $this->SetTextColor(0, 0, 0);

            $this->Cell(55, 5, 'ID Batch', 0, 0);
            $this->Cell(107, 5, ': '.$rowitem->id_batch, 0, 0);
            $this->Cell(57, 5, 'Lokasi Penanaman', 0, 0);
            $this->Cell(57, 5, ': '.$rowitem->nama_lokasi, 0, 1);

            $this->Cell(55, 5, 'Nama User', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nama_user, 0, 1);

            $this->Line(10, 30, 286, 30); //Line atas

            $this->Cell(55, 5, 'Nominal', 0, 0);
            $this->Cell(117, 5, ': Rp. '.number_format($rowitem->nominal, 0), 0, 1);
            $this->Cell(55, 5, 'Status', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nama_status_donasi, 0, 1);

            $this->Line(10, 65, 286, 65); //Line Tengah

            $this->Ln(10);
            $this->Cell(55, 5, 'Nama Rekening Pembayaran', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nama_pemilik_rekening, 0, 1);
            $this->Cell(55, 5, 'Bank Pembayaran', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nama_bank, 0, 1);
            $this->Cell(55, 5, 'Nomor Rekening Pembayaran ', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nomor_rekening, 0, 1);

            $this->Line(10, 90, 286, 90); //Line Bawah

            $this->Ln(10);
            $this->Cell(55, 5, 'Pesan/Ekspresi', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->pesan, 0, 1);
            $this->Cell(55, 5, 'No HP Pengelola Lokasi', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->kontak_lokasi, 0, 1);

            // TTD Digital
            $this->Image($rowitem->ttd_digital, 245, 129, -450); //Logo, Kiri-Atas,Kanan-Bawah

            //$this->SetTextColor(0, 0, 0);
            $this->Line(234, 170, 286, 170); //Line TTD

            $this->Ln(70);
            $this->Cell(224, 5, '', 0, 0);
            $this->Cell(52, 5, $rowitem->nama_rekening, 0, 1, 'C');
            $this->Cell(224, 5, '', 0, 0);
            $this->Cell(52, 5, 'Pengelola, '.$rowitem->nama_lokasi, 0, 1, 'C');
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
    $fileName = 'Invoice - Donasi Terumbu Karang, ' .$rowitem->nama_user.' - '.strftime('%A, %d %B %Y', $donasidate). '.pdf';
}
$pdf->Output($fileName, 'D');
?>
