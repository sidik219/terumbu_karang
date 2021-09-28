<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

if($level_user == 2){
  $id_wilayah = $_SESSION['id_wilayah_dikelola'];
  $extra_query = " AND t_wilayah.id_wilayah = $id_wilayah ";
  $extra_query_noand = " t_wilayah.id_wilayah = $id_wilayah ";
  $wilayah_join = " LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_donasi.id_lokasi
                    LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_lokasi.id_wilayah ";
  $extra_query_k_lok = " AND t_lokasi.id_wilayah = $id_wilayah ";
  $extra_query_noand_where = " WHERE id_wilayah = $id_wilayah ";
  $extra_query_k_titik = " WHERE t_lokasi.id_wilayah = $id_wilayah ";
  $extra_query_noand_where_k_reservasi = " WHERE t_wilayah.id_wilayah = $id_wilayah ";
}
else if($level_user == 3){
  $id_lokasi = $_SESSION['id_lokasi_dikelola'];
  $extra_query = " AND id_lokasi = $id_lokasi ";
  $extra_query_k_lok = " AND t_lokasi.id_lokasi = $id_lokasi ";
  $extra_query_noand = " id_lokasi = $id_lokasi ";
  $extra_query_noand_where = " WHERE id_lokasi = $id_lokasi ";
  $extra_query_noand_where_k_reservasi = " WHERE t_lokasi.id_lokasi = $id_lokasi ";
  $wilayah_join = " ";
  $extra_query_k_titik = " WHERE t_lokasi.id_lokasi = $id_lokasi ";
}
else if($level_user == 4){
  $extra_query = "  ";
  $extra_query_noand = "  ";
  $wilayah_join = " ";
  $extra_query_k_lok = " ";
  $extra_query_noand_where = " ";
  $extra_query_noand_where_k_reservasi = " ";
  $extra_query_k_titik = "  ";
}

if (isset($_GET['id_status_reservasi_wisata'])) {
    $id_status_reservasi_wisata = $_GET['id_status_reservasi_wisata'];

    if($id_status_reservasi_wisata == 1){ //reservasi baru
        $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                    WHERE t_reservasi_wisata.id_status_reservasi_wisata = 1 '.$extra_query_k_lok.'
                    ORDER BY id_reservasi DESC';
    }
    elseif($id_status_reservasi_wisata == 2){ //reservasi Lama
        $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                    WHERE t_reservasi_wisata.id_status_reservasi_wisata = 2 '.$extra_query_k_lok.'
                    ORDER BY id_reservasi DESC';
    }
    elseif($id_status_reservasi_wisata == 3){ //reservasi bermasalah
        $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                    WHERE t_reservasi_wisata.id_status_reservasi_wisata = 3 '.$extra_query_k_lok.'
                    ORDER BY id_reservasi DESC';
    }
        $stmt = $pdo->prepare($sqlviewreservasi);
        $stmt->execute();
        $row = $stmt->fetchAll();
} else {
    $sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                    LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                    LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                    LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                    LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                    WHERE t_reservasi_wisata.id_status_reservasi_wisata = 1 '.$extra_query_k_lok.'
                  ORDER BY id_reservasi DESC';
    $stmt = $pdo->prepare($sqlviewreservasi);
    $stmt->execute();
    $row = $stmt->fetchAll();
}

function alertPembayaran($dob){ 
    $birthdate = new DateTime($dob);
    $today   = new DateTime('today');
    $mn = $birthdate->diff($today)->m;
    $dy = $birthdate->diff($today)->d;

    $tglbatas = $birthdate->add(new DateInterval('P3D'));
    $tglbatas_formatted = strftime('%A, %e %B %Y pukul %R', $tglbatas->getTimeStamp() );
    $batas_waktu_pesan = '<br><b>Batas pembayaran:</b><br>'. $tglbatas_formatted;
    if ($dy <= 3)
    { 
        //jika masih dalam batas waktu
        return  $batas_waktu_pesan .'<br> <i class="fas fa-exclamation-circle text-primary"></i><small> Menunggu bukti pembayaran wisatawan</small>';
    }
    else if ($dy > 3){
        //overdue
        return $batas_waktu_pesan .'<br><i class="fas fa-exclamation-circle text-danger"></i><small> Sudah lewat batas waktu pembayaran.</small><br>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Reservasi Wisata - GoKarang</title>
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
            <a href="dashboard_admin.php" class="brand-link">
                <?= $logo_website ?>
            </a>
            <!-- END OF TOP SIDEBAR -->

            <!-- SIDEBAR -->
            <div class="sidebar">
                <!-- SIDEBAR MENU -->
                <nav class="mt-2">
                   <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php print_sidebar(basename(__FILE__), $_SESSION['level_user'])?> <!-- Print sidebar -->
                    </ul>
                </nav>
                <!-- END OF SIDEBAR MENU -->
            </div>
            <!-- SIDEBAR -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                <div class="row">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Kelola Reservasi Wisata</span></h4>
                        </div>
                        <div class="col">
                        <!--
                        <a class="btn btn-primary float-right" href="input_reservasi_wisata.php" role="button">Input Data Baru (+)</a>
                        -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col flex">
                        <div class="dropdown show ml-2">
                            <a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Pilih Kategori
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <!-- <a class="dropdown-item" href="kelola_reservasi_wisata.php">Tampilkan Semua</a> -->
                                <a class="dropdown-item" href="kelola_reservasi_wisata.php?id_status_reservasi_wisata=1">Reservasi Wisata Baru</a>
                                <a class="dropdown-item" href="kelola_reservasi_wisata.php?id_status_reservasi_wisata=2">Reservasi Wisata Lama</a>
                                <a class="dropdown-item" href="kelola_reservasi_wisata.php?id_status_reservasi_wisata=3">Reservasi Wisata Bermasalah</a>
                            </div>
                        </div>

                        <div class="dropdown show mt-2 ml-2">
                            <a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Pilih Cetak Laporan
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <label class="ml-2">Laporan Reservasi :</label>
                                <a class="dropdown-item" href="laporan_wisata.php?type=all_reservasi">
                                <i class="far fa-file-excel btn-success"></i> Laporan Reservasi Wisata</a>
                                <hr><label class="ml-2">Laporan Pengeluaran :</label>
                                <a class="dropdown-item" href="laporan_wisata.php?type=all_pengeluaran">
                                <i class="far fa-file-excel btn-success"></i> Laporan Seluruh Pengeluaran</a>
                                <!-- Perpriode -->
                                <a class="dropdown-item" href="laporan_wisata.php?type=laporan_perhari">
                                <i class="far fa-file-excel btn-success"></i> Laporan Pengeluaran Priode</a>
                                <!-- Perhari -->
                                <!-- <a class="dropdown-item" href="laporan_wisata.php?type=laporan_perhari">
                                <i class="far fa-file-excel btn-success"></i> Laporan Pengeluaran Perhari</a> -->
                                <!-- Perminggu -->
                                <!-- <a class="dropdown-item" href="laporan_wisata.php?type=laporan_perminggu">
                                <i class="far fa-file-excel btn-success"></i> Laporan Pengeluaran Perminggu</a> -->
                                <!-- Perbulan -->
                                <!-- <a class="dropdown-item" href="laporan_wisata.php?type=laporan_perbulan">
                                <i class="far fa-file-excel btn-success"></i> Laporan Pengeluaran Perbulan</a> -->
                                <!-- Pertahun-->
                                <!-- <a class="dropdown-item" href="laporan_wisata.php?type=laporan_pertahun">
                                <i class="far fa-file-excel btn-success"></i> Laporan Pengeluaran Pertahun</a> -->
                            </div>
                        </div>

                        <!-- Laporan Pengeluaran Priode -->
                        <div class="mt-4 ml-2">
                            <label for="">Cetak Laporan Pengeluaran Perpriode</label>
                            <div class="input-group">
                                <div class="form-group mb-2">
                                    <input type="date" name="tgl_awal" value="" class="form-control form-control-sm tgl_awal" placeholder="Tanggal Awal">
                                </div>
                                <div class="ml-2 mr-2">
                                    <span class="input-group-addon">s/d</span>
                                </div>
                                <div class="form-group mb-2">
                                    <input type="date" name="tgl_akhir" value="" class="form-control form-control-sm tgl_akhir" placeholder="Tanggal Akhir">
                                </div>
                            </div>
                            <button type="submit" name="filter" value="true" class="btn btn-primary mt-2">Tampilkan</button>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                     <table class="table table-striped table-responsive-sm">
                     <thead>
                            <tr>
                            <th scope="col">ID Reservasi</th>
                            <th scope="col">ID User</th>
                            <th scope="col">ID Lokasi</th>
                            <th scope="col">Tgl Reservasi</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php foreach ($row as $rowitem) {
                                $truedate = strtotime($rowitem->update_terakhir);
                                $reservasidate = strtotime($rowitem->tgl_reservasi);
                            ?>
                            <tr>
                                <th scope="row"><?=$rowitem->id_reservasi?></th>
                                <td><?=$rowitem->id_user?> - <?=$rowitem->nama_user?></td>
                                <td><?=$rowitem->id_lokasi?> - <?=$rowitem->nama_lokasi?></td>
                                <td>
                                    <?=strftime('%A, %d %B %Y', $reservasidate);?><br>
                                    <?php if($rowitem->id_status_reservasi_wisata == 1) {
                                        echo alertPembayaran($rowitem->tgl_reservasi);
                                    } ?>

                                    <div class="mb-3">
                                        <?php
                                        $tglreservasi = new DateTime($rowitem->tgl_reservasi);
                                        $today   = new DateTime('today');

                                        if (($tglreservasi->diff($today))->d > 3 && ($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4) && ($rowitem->id_status_reservasi_wisata == 1)) { ?>
                                            <!--Tombol batalkan donasi -->
                                            <a 
                                                onclick="return konfirmasiBatalReservasi(event)" 
                                                href="hapus.php?type=batalkan_reservasi&id_reservasi=<?=$rowitem->id_reservasi?>" class="btn btn-sm btn-danger userinfo">
                                                <i class="fas fa-times"></i> Batalkan Reservasi
                                            </a>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <?=$rowitem->nama_status_reservasi_wisata?>
                                    <br><small class="text-muted">Update Terakhir:
                                    <br><?=strftime('%A, %d %B %Y', $truedate);?></small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-act">
                                        <a href="edit_reservasi_wisata.php?id_reservasi=<?=$rowitem->id_reservasi?>" class="fas fa-edit"></a>
                                    </button>
                                    <a 
                                    class="btn btn-success" 
                                    href="kelola_laporan_wisata.php?id_reservasi=<?=$rowitem->id_reservasi?>"
                                    style="margin-top: 1rem;">
                                    <i class="fas fa-file-excel"></i> Kelola Laporan</a>
                                    <!-- Status Laporan -->
                                    <?php 
                                    if ($rowitem->id_status_reservasi_wisata == 2) { ?>
                                        <!-- Kelola Laporan Lama -->
                                        <span class="badge badge-pill badge-info mt-2">
                                            Cek Kembali <br> Laporan Pengeluaran
                                        </span>
                                    <?php } elseif ($rowitem->id_status_reservasi_wisata == 3) { ?>
                                        <!-- Kelola Laporan Bermasalah -->
                                        <span class="badge badge-pill badge-danger mt-2">
                                            Laporan Pengeluaran <br> Belum Dibuat. <br> Dikarenakan Reservasi Bermasalah.
                                        </span>
                                    <?php } else { ?>
                                        <!-- Kelola Laporan Baru -->
                                        <span class="badge badge-pill badge-warning mt-2">
                                            Laporan Pengeluaran <br> Belum Dibuat
                                        </span>
                                    <?php } ?>
                                </td>
                          </tr>
                          <tr>
                            <td colspan="6">
                                <!--collapse start -->
                                <div class="row  m-0">

                                    <div class="col-12 cell detailcollapser<?=$rowitem->id_reservasi?>"
                                        data-toggle="collapse"
                                        data-target=".cell<?=$rowitem->id_reservasi?>, .contentall<?=$rowitem->id_reservasi?>">
                                        <p
                                            class="fielddetail<?=$rowitem->id_reservasi?> btn btn-act">
                                            <i
                                                class="icon fas fa-chevron-down"></i>
                                            Rincian Reservasi</p>
                                    </div>
                                    <div class="col-12 cell<?=$rowitem->id_reservasi?> collapse contentall<?=$rowitem->id_reservasi?>    border rounded shadow-sm p-3">

                                        <div class="row mb-3  border-bottom">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Paket Wisata
                                            </div>
                                            <div class="col isi">
                                                <?=$rowitem->nama_paket_wisata?>
                                            </div>
                                        </div>
                                        <div class="row mb-3  border-bottom">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Jumlah Peserta
                                            </div>
                                            <div class="col isi">
                                                <?=$rowitem->jumlah_peserta?>
                                            </div>
                                        </div>
                                        <div class="row mb-3  border-bottom">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Jumlah Donasi
                                            </div>
                                            <div class="col isi">
                                                Rp. <?=number_format($rowitem->jumlah_donasi, 0)?>
                                            </div>
                                        </div>
                                        <div class="row mb-3  border-bottom">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Total (Rp.)
                                            </div>
                                            <div class="col isi">
                                                Rp. <?=number_format($rowitem->total, 0)?>
                                            </div>
                                        </div>
                                        <div class="row mb-3  border-bottom">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Keterangan
                                            </div>
                                            <div class="col isi">
                                                <?=$rowitem->keterangan?>
                                            </div>
                                        </div>
                                        <div class="row mb-3  border-bottom">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                No HP
                                            </div>
                                            <div class="col isi">
                                                <?=$rowitem->no_hp?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--collapse end -->
                            </td>
                          </tr>
                          <?php } ?>
                          </tbody>
                  </table>

            <!-- BUTTON SUBMIT -->

            </section>
            <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->
        </div>
        <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <?= $footer ?>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
<div>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <script>
        function konfirmasiBatalReservasi(event){
        jawab = true
        jawab = confirm('Batalkan Reservasi? Data reservasi akan hilang permanen!')

        if (jawab){
            // alert('Lanjut.')
            return true
        }
        else{
            event.preventDefault()
            return false

        }
    }
    </script>

</div>

</body>
</html>
