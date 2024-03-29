<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if (!$_SESSION['level_user']) {
    header('location: ../index?status=akses_terbatas');
} else {
    $id_user    = $_SESSION['id_user'];
    $level      = $_SESSION['level_user'];
}

$id_paket_wisata = $_GET['id_paket_wisata'];
$id_status_reservasi_wisata = 1;
$keterangan = '-';

// Rekening Bersama
$sqlviewlokasi = 'SELECT * FROM t_lokasi
                WHERE id_lokasi = :id_lokasi';

$stmt = $pdo->prepare($sqlviewlokasi);
$stmt->execute(['id_lokasi' => $_SESSION['id_lokasi']]);
$rowlokasi = $stmt->fetch();

$id_wilayah = $rowlokasi->id_wilayah;

$sqlviewrekeningbersama = 'SELECT * FROM t_rekening_bank WHERE id_wilayah = :id_wilayah';

$stmt = $pdo->prepare($sqlviewrekeningbersama);
$stmt->execute(['id_wilayah' => $id_wilayah]);
$rowrekening = $stmt->fetchAll();

// Paket Wisata
$sqldetailpaket = 'SELECT * FROM tb_paket_wisata
                LEFT JOIN t_lokasi ON tb_paket_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata';

$stmt = $pdo->prepare($sqldetailpaket);
$stmt->execute(['id_paket_wisata' => $_GET['id_paket_wisata']]);
$rowwisata = $stmt->fetchAll();

// Fasilitas
// $sqlviewfasilitas = 'SELECT SUM(biaya_kerjasama)+biaya_asuransi+harga_donasi AS total_biaya_fasilitas
//                     FROM tb_fasilitas_wisata
//                     LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
//                     LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
//                     LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
//                     LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
//                     LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
//                     LEFT JOIN t_lokasi ON tb_paket_wisata.id_lokasi = t_lokasi.id_lokasi
//                     WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
//                     AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

// $stmt = $pdo->prepare($sqlviewfasilitas);
// $stmt->execute(['id_paket_wisata' => $id_paket_wisata]);
// $rowfasilitas = $stmt->fetchAll();

// var_dump($rowfasilitas);
// die;
if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $id_lokasi              = $_POST['id_lokasi'];
        $tgl_reservasi          = $_POST['tgl_reservasi'];
        $jumlah_peserta         = $_POST['jumlah_peserta'];
        $jumlah_donasi          = $_POST['jumlah_donasi'];
        $total                  = $_POST['total'];
        $nama_donatur           = $_POST['nama_donatur'];
        $bank_donatur           = $_POST['nama_bank_donatur'];
        $nomor_rekening_donatur = $_POST['no_rekening_donatur'];
        $pesan                  = $_POST['pesan'];
        $id_paket_wisata        = $_POST['id_paket_wisata'];
        $id_rekening_bersama    = $_POST['id_rekening_bersama'];

        //var_dump($jumlah_donasi); exit();
        $tanggal_sekarang = date('Y-m-d H:i:s', time());
        $tanggal_pesan = date('Y-m-d', time());

        $sqlreservasi = "INSERT INTO t_reservasi_wisata (id_user, id_lokasi, tgl_reservasi,tanggal_pesan, jumlah_peserta, 
                                            jumlah_donasi, total, id_status_reservasi_wisata, keterangan,
                                            nama_donatur, bank_donatur, nomor_rekening_donatur, pesan, update_terakhir, id_paket_wisata, id_rekening_bersama)
                                VALUES (:id_user, :id_lokasi, :tgl_reservasi,:tanggal_pesan, :jumlah_peserta, :jumlah_donasi,
                                            :total, :id_status_reservasi_wisata, :keterangan,
                                            :nama_donatur, :bank_donatur, :nomor_rekening_donatur, :pesan, :update_terakhir, :id_paket_wisata, :id_rekening_bersama)";

        $stmt = $pdo->prepare($sqlreservasi);
        $stmt->execute([
            'id_user' => $id_user,
            'id_lokasi' => $id_lokasi,
            'tgl_reservasi' => $tgl_reservasi,
            'tanggal_pesan' => $tanggal_pesan,
            'jumlah_peserta' => $jumlah_peserta,
            'jumlah_donasi' => $jumlah_donasi,
            'total' => $total,
            'id_status_reservasi_wisata' => $id_status_reservasi_wisata,
            'keterangan' => $keterangan,
            'nama_donatur' => $nama_donatur,
            'bank_donatur' => $bank_donatur,
            'nomor_rekening_donatur' => $nomor_rekening_donatur,
            'pesan' => $pesan,
            'update_terakhir' => $tanggal_sekarang,
            'id_paket_wisata' => $id_paket_wisata,
            'id_rekening_bersama' => $id_rekening_bersama
        ]);


        $id_reservasi_terakhir = $pdo->lastInsertId();

        $id_reservasi   = $id_reservasi_terakhir; //ok
        $donasi         = $_POST['donasi']; //ok
        $status_donasi  = 'Belum Terverifikasi'; //ok

        $sqlwisatadonasi = "INSERT INTO t_donasi_wisata
                                    (id_reservasi, donasi, status_donasi)
                                VALUES (:id_reservasi, :donasi, :status_donasi)";

        $stmt = $pdo->prepare($sqlwisatadonasi);
        $stmt->execute([
            'id_reservasi' => $id_reservasi,
            'donasi' => $donasi,
            'status_donasi' => $status_donasi
        ]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            $sqlviewrekeningbersama = 'SELECT * FROM t_rekening_bank WHERE id_rekening_bank = :id_rekening_bank';
            $stmt = $pdo->prepare($sqlviewrekeningbersama);
            $stmt->execute(['id_rekening_bank' => $id_rekening_bersama]);
            $rekening = $stmt->fetch();

            //Kirim email untuk Donatur
            include 'includes/email_handler.php'; //PHPMailer
            $email = $_SESSION['email'];
            $username = $_SESSION['username'];
            $nama_user = $_SESSION['nama_user'];

            $subjek = 'Informasi Pembayaran Reservasi Wisata (ID Reservasi: ' . $id_reservasi_terakhir . ') - GoKarang';
            $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                <br>Yth. ' . $nama_user . '
                <br>Terima kasih telah membuat reservasi wisata di GoKarang!
                <br>Berikut rincian tujuan pembayaran wisata Anda: 
                <br>Paket wisata: ' . $rowwisata[0]->nama_paket_wisata . '
                <br>Lokasi wisata: ' . $rowlokasi->nama_lokasi . '
                <br>Tanggal reservasi: ' . $tgl_reservasi . '
                <br>Jumlah peserta: ' . $jumlah_peserta . '
                <br>Bank Tujuan Pembayaran: ' . $rekening->nama_bank . '
                <br>Nomor Rekening: ' . $rekening->nomor_rekening . '
                <br>Nama Rekening: ' . $rekening->nama_pemilik_rekening . '
                <br>Nominal pembayaran: Rp. ' . number_format($total, 0) . '
                <br>
                <br>Bank Anda: ' . $bank_donatur . '
                <br>Nomor Rekening Anda: ' . $nomor_rekening_donatur . '
                <br>Nama Rekening Anda: ' . $nama_donatur . '
            ';

            smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);

            //Kirim email untuk Pengelola lokasi
            $sqlviewpengelolalokasi = 'SELECT * FROM t_lokasi 
                                        LEFT JOIN t_pengelola_lokasi ON t_pengelola_lokasi.id_lokasi = t_lokasi.id_lokasi
                                        WHERE t_lokasi.id_lokasi = :id_lokasi';
            $stmt = $pdo->prepare($sqlviewpengelolalokasi);
            $stmt->execute(['id_lokasi' => $id_lokasi]);
            $rowpengelola = $stmt->fetchAll();

            // Kirim email untuk Pengelola Wilayah
            // $sqlviewpengelolawilayah = 'SELECT * FROM t_lokasi 
            //                         LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah
            //                         LEFT JOIN t_pengelola_wilayah ON t_pengelola_wilayah.id_wilayah = t_lokasi.id_wilayah
            //                         WHERE id_lokasi = :id_lokasi';
            // $stmt = $pdo->prepare($sqlviewpengelolawilayah);
            // $stmt->execute(['id_lokasi' => $id_lokasi]);
            // $rowpengelola = $stmt->fetchAll();

            foreach ($rowpengelola as $pengelola) {
                $sqlviewdatauser = 'SELECT * FROM t_user 
                                    WHERE id_user = :id_user';
                $stmt = $pdo->prepare($sqlviewdatauser);
                $stmt->execute(['id_user' => $pengelola->id_user]);
                $datauser = $stmt->fetch();

                $email = $datauser->email;
                $username = $datauser->username;
                $nama_user = $datauser->nama_user;

                $subjek = 'Reservasi Wisata Baru (ID Reservasi: ' . $id_reservasi_terakhir . ') - GoKarang';
                $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                <br>Yth. ' . $nama_user . '
                <br>Anda menerima reservasi wisata baru pada lokasi ' . $pengelola->nama_lokasi . '
                <br>Berikut rincian reservasi wisata baru tersebut:
                <br>Paket wisata: ' . $rowwisata[0]->nama_paket_wisata . '
                <br>Lokasi wisata: ' . $rowlokasi->nama_lokasi . '
                <br>Tanggal reservasi: ' . $tgl_reservasi . '
                <br>Jumlah peserta: ' . $jumlah_peserta . '
                <br>Bank Wisatawan: ' . $bank_donatur . '
                <br>Nomor Rekening wisatawan: ' . $nomor_rekening_donatur . '
                <br>Nama Rekening wisatawan: ' . $nama_donatur . '
                <br>          
                <br>Bank Tujuan Pembayaran: ' . $rekening->nama_bank . '
                <br>Nomor Rekening Tujuan: ' . $rekening->nomor_rekening . '
                <br>Nama Rekening Tujuan: ' . $rekening->nama_pemilik_rekening . '
                <br>Nominal pembayaran: Rp. ' . number_format($total, 0) . '
                <br>
                <br>Jika donatur belum mengupload bukti pembayaran wisata dalam ' . $pengelola->batas_hari_pembayaran . ' hari, maka reservasi dapat dibatalkan.
            ';

                smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
            }
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            // $last_id_reservasi = $pdo->lastInsertId();
            header("Location: reservasi_saya.php?status=addsuccess");
        }

        // Jika wisata sekaligus donasi
        // foreach ($_POST['donasi'] as $donasi) {
        //     $id_reservasi   = $last_id_reservasi; //ok
        //     $donasi         = $_POST['donasi']; //ok
        //     $status_donasi  = 'Belum Terverifikasi'; //ok

        //     $sqlwisatadonasi = "INSERT INTO t_donasi_wisata
        //                                 (id_reservasi, donasi, status_donasi)
        //                             VALUES (:id_reservasi, :donasi, :status_donasi)";

        //     $stmt = $pdo->prepare($sqlwisatadonasi);
        //     $stmt->execute([
        //         'id_reservasi' => $id_reservasi,
        //         'donasi' => $donasi,
        //         'status_donasi' => $status_donasi
        //     ]);

        //     $affectedrows = $stmt->rowCount();
        //     if ($affectedrows == '0') {
        //         //echo "HAHAHAAHA INSERT FAILED !";
        //     } else {
        //         //echo "HAHAHAAHA GREAT SUCCESSS !";
        //         header("Location: reservasi_saya.php?status=addsuccess");
        //     }
        // }
    } else {
        echo '<script>alert("Harap pilih paket wisata yang akan ditambahkan")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Review Informasi Donasi - GoKarang</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="css/style-card.css">
    <!-- Favicon -->
    <?= $favicon ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- NAVBAR -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Navbar Toogle -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Akun Saya</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">Edit Profil</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>
        <!-- END OF NAVBAR -->

        <!-- TOP SIDEBAR -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- BRAND LOGO (TOP)-->
            <a href="dashboard_user.php" class="brand-link">
                <?= $logo_website ?>
            </a>
            <!-- END OF TOP SIDEBAR -->

            <!-- SIDEBAR -->
            <div class="sidebar">
                <!-- SIDEBAR MENU -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <?php print_sidebar(basename(__FILE__), $_SESSION['level_user']) ?>
                        <!-- Print sidebar -->
                    </ul>
                </nav>
                <!-- END OF SIDEBAR MENU -->
            </div>
            <!-- SIDEBAR -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php if ($_SESSION['level_user'] == '1') { ?>
                <!-- Main content -->
                <section class="content">
                    <div class="container">
                        <a class="btn btn-warning btn-back mt-3" href="#" onclick="history.back()">
                            <i class="fas fa-angle-left"></i> Kembali Pilih
                        </a>
                        <h4 class="pt-3 mb-3">
                            <span class="font-weight-bold">Review Informasi Reservasi Wisata</span>
                        </h4>

                        <!-- Row -->
                        <div class="row">
                            <!-- Notif -->
                            <?php
                            if (!empty($_GET['status'])) {
                                if ($_GET['status'] == 'review_reservasi') {
                                    echo '<div class="alert alert-success" role="alert">
                                            Cek kembali reservasi wisata Anda, 
                                            agar tidak terjadi kesalahan dalam menginputan data.
                                            </div>';
                                }
                            }
                            ?>

                            <!-- Div-1 -->
                            <div class="col-md-4 order-md-2 mb-4">
                                <h4 class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted"><i class="fas fa-suitcase-rolling"></i> Reservasi Wisata Anda</span>
                                    <span id="badge-jumlah" class="badge badge-secondary badge-pill"></span>
                                </h4>

                                <!-- Form -->
                                <form action="" method="POST">
                                    <?php foreach ($rowwisata as $rowitem) { ?>
                                        <ul class="list-group mb-3" id="keranjangancestor">
                                            <!-- Paket Wisata -->
                                            <div class="card" style="width: 20.5rem; margin-bottom: 20px;">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item card-reservasi">
                                                        <!-- Nama paket wisata -->
                                                        <input type="text" value="<?= $rowitem->nama_paket_wisata ?>" class="list-group-item deskripsi-paket" style="background: transparent;
                                                                border: none;
                                                                color: #fff;
                                                                font-weight: bold;
                                                                width: 100%;
                                                                padding: 1rem;" disabled>
                                                    </li>
                                                    <input type="text" id="deskripsi_wisata" name="deskripsi_wisata" value="Peserta: " class="list-group-item paket-wisata" disabled>
                                                </ul>
                                            </div>

                                            <!-- Paket Donasi -->
                                            <!-- Sementara Dihapus Dulu -->

                                            <!-- Total -->
                                            <div class="card" style="width: 20.5rem;">
                                                <ul class="list-group list-group-flush">
                                                    <label class="list-group-item card-reservasi">Total : </label>
                                                    <input type="hidden" id="total" name="total" value="" class="list-group-item" style="color: gray;" readonly>
                                                    <input type="text" id="total_reservasi" name="total_reservasi" value="" class="list-group-item" style="color: gray;" readonly>
                                                </ul>
                                            </div>

                                            <!-- Link Untuk Ke Halaman Donasi Terumbu Karang -->
                                            <!-- <a class="btn btn-primary btn-lg btn-block mb-4" href="pilih_terumbu_karang.php?id_lokasi=#" style="color: white; width: 20.5rem;">
                                            Ayo Donasi Terumbu Karang
                                        </a> -->
                                        </ul>
                                        <!-- Form extend -->
                            </div>

                            <!-- Div-2 -->
                            <div class="col-md-8 order-md-1 card">
                                <!-- Area Input Data -->
                                <h4 class="card-header pl-0">Data Reservasi Wisata</h4>
                                <div class="form-group">
                                    <input type="hidden" id="id_paket_wisata" name="id_paket_wisata" value="<?= $rowitem->id_paket_wisata ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="id_lokasi">ID Lokasi</label>
                                    <input type="hidden" id="id_lokasi" name="id_lokasi" value="<?= $rowitem->id_lokasi ?>" class="form-control">
                                    <input type="text" id="nama_lokasi" name="nama_lokasi" value="<?= $rowitem->nama_lokasi ?>" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="tgl_reservasi">Tanggal Reservasi</label>
                                    <input type="date" id="tgl_reservasi" name="tgl_reservasi" class="form-control" max="<?= $rowitem->tgl_akhir_pemesanan; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_peserta">Jumlah Peserta</label>
                                    <input type="number" id="jumlah_peserta" name="jumlah_peserta" min="1" max='200' oninput="myFunction()" class="form-control" required>
                                </div>
                                <!-- End Area Input Data -->

                                <!-- Paket Wisata -->
                                <div class="output">
                                    <p class="btn btn-blue btn-primary" onclick="toggleDetail()">
                                        <i class="icon fas fa-chevron-down"></i>
                                        Rincian Data Reservasi Wisata
                                    </p>
                                    <div class="detail-toggle" id="main-toggle">
                                        <div class="" style="width:100%;">
                                            <div class="">
                                                <h4 class="card-header mb-2 pl-0">Rincian <?= $rowitem->nama_paket_wisata ?>,<br> Lokasi
                                                    <span class="text-info font-weight-bolder"> <?= $rowitem->nama_lokasi ?></span>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <!-- Select Wisata -->
                                                <?php
                                                $sqlpaketSelect = 'SELECT * FROM t_wisata
                                                LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata';

                                                $stmt = $pdo->prepare($sqlpaketSelect);
                                                $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                                $rowWisata = $stmt->fetchAll();

                                                foreach ($rowWisata as $wisata) { ?>
                                                    <!-- Deskripsi Wisata -->
                                                    <h5 class="mt-4 mb-4">
                                                        <div class="">
                                                            <span class="badge badge-pill badge-warning">
                                                                <?= $wisata->jadwal_wisata ?>
                                                            </span>
                                                        </div>
                                                    </h5>

                                                    <!-- Judul Wisata -->
                                                    <i class="text-info fas fa-luggage-cart"></i>
                                                    <label>Wisata:</label>
                                                    <span style="font-weight:normal;">
                                                        <?= $wisata->judul_wisata ?>
                                                    </span><br>

                                                    <!-- Select Fasilitas -->
                                                    <?php
                                                    $sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata
                                                                        LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                                                        LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                                        LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                                        LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                                        WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                                        AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata
                                                                        AND t_wisata.id_wisata = :id_wisata';

                                                    $stmt = $pdo->prepare($sqlviewfasilitas);
                                                    $stmt->execute([
                                                        'id_wisata' => $wisata->id_wisata,
                                                        'id_paket_wisata' => $rowitem->id_paket_wisata
                                                    ]);
                                                    $rowfasilitas = $stmt->fetchAll();

                                                    foreach ($rowfasilitas as $allfasilitas) { ?>
                                                        <i class="text-info fas fa-arrow-circle-right"></i>
                                                        <?= $allfasilitas->pengadaan_fasilitas ?><br>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <p>
                                            <!-- Asuransi -->
                                            <hr class="mb-2" />
                                        <div class="row">
                                            <div class="col">
                                                <i class="text-danger fas fa-heartbeat"></i>
                                                <label>Asuransi:</label>
                                                <span style="font-weight:normal;">
                                                    <?= $rowitem->nama_asuransi ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                Rp. <?= number_format($rowitem->biaya_asuransi, 0) ?>
                                            </div>
                                        </div>
                                        <p>
                                            <!-- Asuransi -->
                                            <hr class="mb-2" />
                                        <div class="row">
                                            <div class="col">
                                                <i class="text-success fas fa-donate"></i>
                                                <label>Donasi:</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                Rp. <?= number_format($rowitem->harga_donasi, 0) ?>
                                            </div>
                                        </div>
                                        <p>
                                            <!-- Total Pembayaran -->
                                            <hr class="mb-2" />
                                            <?php
                                            $sqlviewpaket = 'SELECT SUM(biaya_kerjasama) AS total_biaya_fasilitas, pengadaan_fasilitas, biaya_kerjasama, biaya_asuransi
                                        FROM tb_fasilitas_wisata
                                        LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                        LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                        LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                        LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                        LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                                        WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                        AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                            $stmt = $pdo->prepare($sqlviewpaket);
                                            $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                            $rowfasilitas = $stmt->fetchAll();

                                            foreach ($rowfasilitas as $fasilitas) {

                                                // Menjumlahkan biaya asuransi dan biaya paket wisata
                                                $asuransi       = $fasilitas->biaya_asuransi;
                                                $wisata         = $fasilitas->total_biaya_fasilitas;
                                                $total_paket    = $asuransi + $wisata; ?>

                                                <!-- Biaya Paket Kalkulasi Dari Biaya Fasilitas -->
                                        <div class="row p-2 border-bottom">
                                            <p class="">
                                                <i class="text-success fas fa-money-bill-wave"></i>
                                                <label>Total Paket Wisata:</label><br>
                                                <input type="hidden" id="total_paket_wisata" value="<?= $total_paket ?>">
                                                Rp. <input value="<?= number_format($total_paket, 0) ?>" class="paket-wisata" disabled>
                                            </p>
                                        </div>
                                    <?php } ?>
                                    <hr class="mb-2" />
                                    </div>
                                </div>

                                <!-- Form Input Data Rekening Wisatawan -->
                                <?php if ($id_user > 0) { ?>
                                    <h4 class="mb-3 card-header pl-0">Data Rekening Wisatawan</h4>
                                    <div class="mb-3">
                                        <label for="nama_donatur">Nama Pemilik Rekening</label>
                                        <input type="text" class="form-control data_donatur" id="nama_donatur" name="nama_donatur" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_rekening_donatur">Nomor Rekening</label>
                                        <input type="number" class="form-control data_donatur" id="no_rekening_donatur" name="no_rekening_donatur" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama_bank_donatur">Nama Bank</label>
                                        <input type="text" class="form-control data_donatur" id="nama_bank_donatur" name="nama_bank_donatur" required>
                                    </div>
                                    <div class="mb-3">
                                        <!-- Pesan/Ekspresi -->
                                        <!-- <label>
                                            Pesan/Ekspresi di Terumbu Karang
                                            <small style="color: gray;">(Optional)</small>
                                        </label> -->
                                        <input type="hidden" id="pesan" name="pesan" value="-" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <!-- Donasi Wisata -->
                                        <label style="color: blue;">
                                            * Dengan berwisata Anda turut berkontribusi dalam,<br>
                                            konservasi terumbu karang sebesar Rp. <?= number_format($rowlokasi->harga_donasi, 0) ?>
                                        </label>
                                        <!-- Hidden Get ID Untuk Mendapatkan Value -->
                                        <input type="hidden" id="donasi" value="<?= $rowlokasi->harga_donasi; ?>" class="form-control">
                                        <!-- Hidden Input Ke DB Donasi Wisata -->
                                        <input type="hidden" id="donasi_wisata" name="donasi" value="<?= $rowlokasi->harga_donasi; ?>" class="form-control">
                                        <!-- Hidden Input Ke DB Reservasi Wisata -->
                                        <input type="hidden" id="jumlah_donasi" name="jumlah_donasi" value="<?= $rowlokasi->harga_donasi; ?>" class="form-control">
                                    </div>
                                <?php } ?>

                                <!-- Metode Pembayaran -->
                                <div class="" style="width:100%;">
                                    <div class="">
                                        <h4 class="card-header mb-2 pl-0">Metode Pembayaran</h4>
                                        <span class="">Pilihan untuk lokasi :</span>
                                        <span class="text-info font-weight-bolder"> <?= $rowitem->nama_lokasi ?></span>

                                        <?php foreach ($rowrekening as $rekening) { ?>
                                            <div class="d-block my-4">
                                                <div class="rounded p-sm-4 pt-2 shadow-sm border">
                                                    <div class="custom-control custom-radio">
                                                        <input id="id_rekening<?= $rekening->id_rekening_bank ?>" onchange="updateData(this.value)" name="id_rekening_bersama" type="radio" value="<?= $rekening->id_rekening_bank ?>" class="custom-control-input" required>
                                                        <label class="custom-control-label " for="id_rekening<?= $rekening->id_rekening_bank ?>">Bank Transfer - <span class=""><?= $rekening->nama_bank ?> (Konfirmasi Manual)</label>
                                                    </div>
                                                    <hr class="mb-1" />
                                                    <div class="pl-2">
                                                        <div class="row">
                                                            <div class="col">
                                                                <span class="font-weight-bold">Nama Rekening Pengelola
                                                            </div>
                                                            <div class="col-lg-8 mb-2">
                                                                <span class=""><?= $rekening->nama_pemilik_rekening ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <span class="font-weight-bold">Nomor Rekening Pengelola </span>
                                                            </div>
                                                            <div class="col-lg-8  mb-2">
                                                                <span class=""><?= $rekening->nomor_rekening ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col">
                                                                <span class="font-weight-bold">Bank Pengelola </span>
                                                            </div>
                                                            <div class="col-lg-8  mb-2">
                                                                <span class=""><?= $rekening->nama_bank ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <p class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            Harap upload bukti transfer di halaman "Reservasi Saya" setelah menekan tombol Buat Reservasi Wisata.
                                        </p>
                                        <button type="submit" name="submit" value="Simpan" class="btn btn-primary btn-lg btn-block mb-4">Buat Reservasi Wisata</button>
                                    </div>
                                </div>
                                <!-- End Foreach -->
                            <?php } ?>
                            <!-- End Form -->
                            </form>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>
            <!-- /.content -->
        </div>
        <footer class="main-footer">
            <?= $footer ?>
        </footer>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <script src="js\numberformat.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Pembatasan Date Reservasi -->

    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("tgl_reservasi")[0].setAttribute('min', today);
    </script>

    <!--(づ｡◕‿‿◕｡)づ pika pika pikachu (づ｡◕‿‿◕｡)づ-->
    <script>
        function myFunction() {
            var jumlah_peserta = document.getElementById("jumlah_peserta").value;
            var paket_wisata = document.getElementById("total_paket_wisata").value;
            var donasi = document.getElementById("donasi").value;
            //var id_tk = document.getElementById("id_tk").value; //data terumbu karang
            //var nominal = document.getElementById("id_tk").value; //data nominal terumbu karang

            var deskripsi = jumlah_peserta;
            var reservasi = jumlah_peserta * paket_wisata; //5 x 750.000 = 3.750.000
            var total_reservasi = parseInt(reservasi) + parseInt(donasi);
            // var terumbu_karang = id_tk;
            // var hasil_split = nominal.split("-");
            // var split_tk = hasil_split[0];
            // var split_harga_tk = hasil_split[1];

            // if (id_tk == 1) {
            //     var jenis_tk = null;
            //     var split_harga_tk = null;
            //     var hasil = total_reservasi;
            // } else {
            //     var hasil = parseInt(total_reservasi) + parseInt(split_harga_tk);
            // }

            // Format untuk number.
            var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

            document.getElementById("deskripsi_wisata").value = "Peserta: " + deskripsi;
            // document.getElementById("terumbu_karang").value = terumbu_karang;
            // document.getElementById("split_tk").value = split_tk;
            // document.getElementById("split_harga_tk").value = split_harga_tk;
            // document.getElementById("nominal").value = split_harga_tk;
            document.getElementById("donasi_wisata").value = donasi; //Input ke tabel donasi wisata
            document.getElementById("jumlah_donasi").value = donasi; //input ke tabel reservasi wisata field jumlah_donasi
            document.getElementById("total").value = total_reservasi; //total dari total_reservasi * donasi
            document.getElementById("total_reservasi").value = formatter.format(total_reservasi); //total dari total_reservasi * donasi
        }

        // Jenis Terumbu Karang
        // function myFunction2() {
        //     var id_jenis = document.getElementById("dd_id_wilayah").value; //data jenis terumbu karang

        //     if (id_jenis == 1) {
        //         var jenis = null;
        //     } else {
        //         var jenis = id_jenis;
        //     }

        //     document.getElementById("jenis_tk").value = jenis;
        // }

        // function load_detail_lokasi(id_jenis) {
        //     $.ajax({
        //         type: "POST",
        //         url: "list_populate.php",
        //         data: {
        //             id_jenis: id_jenis,
        //             type: 'load_detail_lokasi'
        //         },
        //         beforeSend: function() {
        //             $("#id_tk").addClass("loader");
        //         },
        //         success: function(data) {
        //             $("#id_tk").html(data);
        //             $("#id_tk").removeClass("loader");
        //         }
        //     });
        // }
    </script>

    <!-- Get value selected untuk menampilkan ke input -->
    <script>
        //$(function(){
        //$("#id_tk").change(function(){
        //var tampil = $("#id_tk option:selected").text();
        //$("#nominal").val(tampil);
        //})
        //})
    </script>

    <!-- Hiden/Show Button Inputan Data Donatur -->
    <script>
        $(document).ready(function() {
            $('.preview-images').hide()
            $('.detail-toggle').hide()
        });

        function toggleDetail(e) {
            var e = event.target
            $(e).siblings('.detail-toggle').fadeToggle()
        }
    </script>

</body>

</html>