<?php
require('plugins/fpdf/fpdf182/fpdf.php');
include 'build/config/connection.php';

class myPDF extends FPDF{
    function header(){
        global $pdo;
        $this->Image('images/KKPlogo.png', 10, 3, -2500); //Logo, Kiri-Atas,Kanan-Bawah
        //$this->Image('images/bg-invoice.png', 5, 30, 287, -550); //Bg-Invoice, Kiri-Atas,Kanan-Bawah
        $this->Image('images/bg-invoice-line.png', 8, 34, 158, -2400); //Line-Invoice-kiri, Kiri-Atas,Kanan-Bawah
        $this->Image('images/bg-invoice-line.png', 168, 34, 121, -2400); //Line-Invoice-kanan, Kiri-Atas,Kanan-Bawah
        $this->SetFont('Arial', 'B', 14);
        $this->cell(276, 5, 'INVOICE RESERVASI WISATA', 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', '', 12);

        $id_reservasi = $_GET['id_reservasi'];

        $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                    WHERE id_reservasi = :id_reservasi
                    ORDER BY id_reservasi DESC';
        $stmt = $pdo->prepare($sqlviewreservasi);
        $stmt->execute(['id_reservasi' => $id_reservasi]);
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

        $id_reservasi = $_GET['id_reservasi'];

        $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                    WHERE id_reservasi = :id_reservasi
                    ORDER BY id_reservasi DESC';
        $stmt = $pdo->prepare($sqlviewreservasi);
        $stmt->execute(['id_reservasi' => $id_reservasi]);
        $row = $stmt->fetchAll();

        foreach ($row as $rowitem) {
            $reservasidate = strtotime($rowitem->tgl_reservasi);
            //User
            $this->Cell(55, 5, 'ID Reservasi', 0, 0);
            $this->Cell(107, 5, ': '.$rowitem->id_reservasi, 0, 0);
            $this->Cell(52, 5, 'Tanggal Reservasi', 0, 0,);
            $this->SetTextColor(255, 255, 255);
            $this->SetFillColor(4, 119, 194);
            $this->Cell(49, 5, ': '.strftime("%A, %d %B %Y", $reservasidate), 0, 1, 'C', 1);
            $this->SetTextColor(0, 0, 0);

            $this->Cell(55, 5, 'Nama User', 0, 0);
            $this->Cell(107, 5, ': '.$rowitem->nama_user, 0, 0);
            $this->Cell(52, 5, 'Lokasi Reservasi Wisata', 0, 0);
            $this->Cell(62, 5, ': '.$rowitem->nama_lokasi, 0, 1);
            
            $this->Cell(55, 5, 'Paket Wisata', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nama_paket_wisata, 0, 1);

            $this->Line(10, 30, 286, 30); //Line atas

            $this->Ln(10);
            $this->Cell(55, 5, 'Jumlah Peserta', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->jumlah_peserta, 0, 1);
            $this->Cell(55, 5, 'Jumlah Donasi', 0, 0);
            $this->Cell(117, 5, ': Rp. '.number_format($rowitem->jumlah_donasi, 0), 0, 1);

            $this->Cell(55, 5, 'Total', 0, 0);
            $this->Cell(117, 5, ': Rp. '.number_format($rowitem->total, 0), 0, 1);
            $this->Cell(55, 5, 'Status Reservasi', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nama_status_reservasi_wisata, 0, 1);

            $this->Line(10, 55, 286, 55); //Line Tengah

            $this->Ln(10);
            $this->Cell(55, 5, 'Nama Rekening Pengelola', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nama_rekening, 0, 1);
            $this->Cell(55, 5, 'Bank Pengelola', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nama_bank, 0, 1);
            $this->Cell(55, 5, 'Nomor Rekening Pengelola ', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->nomor_rekening, 0, 1);

            $this->Line(10, 85, 286, 85); //Line Bawah

            $this->Ln(10);
            $this->Cell(55, 5, 'Keterangan', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->keterangan, 0, 1);
            $this->Cell(55, 5, 'No HP Pengelola Lokasi', 0, 0);
            $this->Cell(117, 5, ': '.$rowitem->kontak_lokasi, 0, 1);

            //$this->SetTextColor(0, 0, 0);
            $this->Line(234, 170, 286, 170); //Line TTD

            $this->Ln(50);
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

//Output Invoice Reservasi Wisata
$id_reservasi = $_GET['id_reservasi'];

$sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
            LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
            LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
            LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
            LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
            WHERE id_reservasi = :id_reservasi
            ORDER BY id_reservasi DESC';
$stmt = $pdo->prepare($sqlviewreservasi);
$stmt->execute(['id_reservasi' => $id_reservasi]);
$row = $stmt->fetchAll();


foreach ($row as $rowitem) {
    $reservasidate = strtotime($rowitem->tgl_reservasi);
    $fileName = 'Invoice - Reservasi Wisata, ' .$rowitem->nama_paket_wisata.' - '.$rowitem->nama_user.' - '.strftime('%A, %d %B %Y', $reservasidate). '.pdf';
}
$pdf->Output($fileName, 'D');
?>