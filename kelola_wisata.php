<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

if($level_user == 2){
  $id_wilayah = $_SESSION['id_wilayah_dikelola'];
  $extra_query = " AND t_lokasi.id_wilayah = $id_wilayah ";
  $extra_query_noand = " t_lokasi.id_wilayah = $id_wilayah ";

  $join_wilayah = " LEFT JOIN t_wilayah ON t_lokasi.id_wilayah = t_wilayah.id_wilayah ";
}
else if($level_user == 3){
  $id_lokasi = $_SESSION['id_lokasi_dikelola'];
  $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
  $extra_query_noand = " t_lokasi.id_lokasi = $id_lokasi ";

  $join_wilayah = " ";
}
else if($level_user == 4){
  $extra_query = " ";
  $extra_query_noand = " ";
  $join_wilayah = "  ";
}

$sqlviewpaket = 'SELECT * FROM tb_paket_wisata
                LEFT JOIN t_asuransi ON tb_paket_wisata.id_asuransi = t_asuransi.id_asuransi
                ORDER BY id_paket_wisata DESC';
$stmt = $pdo->prepare($sqlviewpaket);
$stmt->execute();
$row = $stmt->fetchAll();

// $results = $stmt->fetchAll();
// $json = json_encode($row);

// LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi '.$join_wilayah.'
// WHERE  '.$extra_query_noand.'

// LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi '.$join_wilayah.'
// WHERE id_paket_wisata = :id_paket_wisata
// AND '.$extra_query_noand.'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Wisata - GoKarang</title>
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
                            <h4><span class="align-middle font-weight-bold">Kelola Wisata</span></h4>
                            
                            <!-- Fitur Next -->
                            <a class="btn btn-success" href="kelola_asuransi.php" style="margin-bottom: 10px;">
                            <i class="fas fa-arrow-left"></i> Kembali</a>

                            <!-- Cetak Laporan Wisata -->
                            <a class="btn btn-success" href="laporan_wisata.php?type=wisata" style="margin-bottom: 10px;">
                            <i class="fas fa-file-excel"></i> Laporan Data Wisata</a>
                        </div>
                        <div class="col">
                            <a class="btn btn-primary float-right" href="kelola_fasilitas_wisata.php" role="button">Input Data Baru (+)</a>
                        </div>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                        if(!empty($_GET['status'])) {
                            if($_GET['status'] == 'updatesuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Update paket wisata berhasil!
                                        </div>'; }
                            else if($_GET['status'] == 'addsuccess') {
                                echo '<div class="alert alert-success" role="alert">
                                        Data paket wisata berhasil ditambahkan!
                                        </div>'; }
                        }
                    ?>
                     <table class="table table-striped table-responsive-sm">
                     <thead>
                            <tr>
                            <th scope="col">ID Paket Wisata</th>
                            <th scope="col">Nama Paket Wisata</th>
                            <th scope="col">Status Paket</th>
                            <th scope="col">Batas Pemesanan</th>
                            <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php foreach ($row as $rowitem) { 
                              
                            $awaldate = strtotime($rowitem->tgl_pemesanan);
                            $akhirdate = strtotime($rowitem->tgl_akhir_pemesanan);
                            ?>
                            <tr>
                                <th scope="row"><?=$rowitem->id_paket_wisata?></th>
                                <td><?=$rowitem->nama_paket_wisata?></td>
                                <td><?=$rowitem->status_aktif?></td>
                                <td>
                                    <h5>
                                        <?php
                                        // tanggal sekarang
                                        $tgl_sekarang = date("Y-m-d");
                                        // tanggal pembuatan batas pemesanan paket wisata
                                        $tgl_awal = $rowitem->tgl_pemesanan;
                                        // tanggal berakhir pembuatan batas pemesanan paket wisata
                                        $tgl_akhir = $rowitem->tgl_akhir_pemesanan;
                                        // jangka waktu + 365 hari
                                        $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                                        //tanggal expired
                                        $tgl_exp = date("Y-m-d",$jangka_waktu);

                                        if ($tgl_sekarang >= $tgl_exp) { ?>
                                            <small>
                                                <span class="badge badge-pill badge-danger">
                                                    <i class="fas fa-tag"></i> Sudah Tidak Berlaku.
                                                </span>
                                            </small>
                                        <?php } else { ?>
                                            <small>
                                                <span class="badge badge-pill badge-success">
                                                    <i class="fas fa-tag"></i> Masih dalam jangka waktu.
                                                </span>
                                            </small>
                                        <?php }?>
                                    </h5>
                                </td>
                                <td>
                                    <a href="edit_wisata.php?id_paket_wisata=<?=$rowitem->id_paket_wisata?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                    <a  onclick="return konfirmasiHapusPaket(event)"
                                        href="hapus.php?type=paket_wisata&id_paket_wisata=<?=$rowitem->id_paket_wisata?>" 
                                        class="far fa-trash-alt btn btn-act"></a>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                <!--collapse start -->
                                <div class="row  m-0">
                                    <table>
                                    <div class="col-12 cell detailcollapser<?=$rowitem->id_paket_wisata?>"
                                        data-toggle="collapse"
                                        data-target=".cell<?=$rowitem->id_paket_wisata?>, .contentall<?=$rowitem->id_paket_wisata?>">
                                        <p class="fielddetail<?=$rowitem->id_paket_wisata?> btn btn-act">
                                            <i class="icon fas fa-chevron-down"></i>
                                            Rincian Wisata</p>
                                    </div>

                                    <!-- Data Untuk Wisata -->
                                    <div class="col-12 cell<?=$rowitem->id_paket_wisata?> collapse contentall<?=$rowitem->id_paket_wisata?> border rounded shadow-sm p-3">

                                        <div class="row  mb-3">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Foto Wisata
                                            </div>
                                            
                                            <div class="col isi">
                                                <img src="<?=$rowitem->foto_wisata?>?<?php if ($status='nochange'){echo time();}?>" width="100px">
                                            </div>
                                        </div>

                                        <!-- Asuransi -->
                                        <div class="row  mb-3">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Asuransi
                                            </div>
                                            
                                            <div class="col isi">
                                                <i class="text-danger fas fa-heartbeat"></i>
                                                Rp. <?=number_format($rowitem->biaya_asuransi, 0)?>
                                            </div>
                                        </div>

                                        <!-- Fasilitas -->
                                        <div class="row  mb-3">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Biaya Wisata
                                            </div>
                                            <?php
                                            $sqlviewpaket = 'SELECT SUM(biaya_kerjasama) AS total_biaya_fasilitas, biaya_asuransi
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
                                            $total_paket    = $asuransi + $wisata;

                                            ?>
                                            <div class="col isi" id="total_biaya">
                                                <i class="text-success fas fa-money-bill-wave"></i>
                                                Rp. <?=number_format($total_paket, 0)?>
                                            </div>
                                            <?php } ?>
                                        </div>

                                        <!-- Wisata -->
                                        <div class="row  mb-3">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Wisata
                                            </div>
                                            <div class="col isi">
                                            <?php
                                            $sqlviewwisata = 'SELECT * FROM t_wisata
                                                            LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                            WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                            AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata
                                                            ORDER BY id_wisata DESC';

                                            $stmt = $pdo->prepare($sqlviewwisata);
                                            $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                            $rowwisata = $stmt->fetchAll();

                                            foreach ($rowwisata as $wisata) { ?>
                                                <i class="text-warning fas fa-luggage-cart"></i>
                                                <?=$wisata->judul_wisata?><br>
                                            <?php } ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Fasilitas -->
                                        <div class="row  mb-3">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Fasilitas Wisata
                                            </div>
                                            <div class="col isi">
                                                <?php
                                                $sqlviewpaket = 'SELECT * FROM tb_fasilitas_wisata
                                                                    LEFT JOIN t_kerjasama ON tb_fasilitas_wisata.id_kerjasama = t_kerjasama.id_kerjasama
                                                                    LEFT JOIN t_pengadaan_fasilitas ON t_kerjasama.id_pengadaan = t_pengadaan_fasilitas.id_pengadaan
                                                                    LEFT JOIN t_wisata ON tb_fasilitas_wisata.id_wisata = t_wisata.id_wisata
                                                                    LEFT JOIN tb_paket_wisata ON t_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                                                                    WHERE tb_paket_wisata.id_paket_wisata = :id_paket_wisata
                                                                    AND tb_paket_wisata.id_paket_wisata = t_wisata.id_paket_wisata';

                                                $stmt = $pdo->prepare($sqlviewpaket);
                                                $stmt->execute(['id_paket_wisata' => $rowitem->id_paket_wisata]);
                                                $rowfasilitas = $stmt->fetchAll();

                                                foreach ($rowfasilitas as $fasilitas) { ?>
                                                <i class="text-info fas fa-arrow-circle-right"></i>
                                                <?=$fasilitas->pengadaan_fasilitas?><br>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <!-- Batas Pemesanan -->
                                        <div class="row  mb-3">
                                            <div class="col-md-3 kolom font-weight-bold">
                                                Batas Pemesanan
                                            </div>
                                            <div class="d-flex flex-column bd-highlight mb-3">
                                                <div class="p-2 bd-highlight">
                                                    <div class="col isi">
                                                        <i class="text-info fas fa-hourglass-half"></i>
                                                        <?=strftime('%A, %d %B %Y', $awaldate);?>
                                                        <strong>s/d</strong> 
                                                        <?=strftime('%A, %d %B %Y', $akhirdate);?>
                                                    </div>
                                                </div>
                                                <div class="p-2 bd-highlight">
                                                    <h5>
                                                        <?php
                                                        // tanggal sekarang
                                                        $tgl_sekarang = date("Y-m-d");
                                                        // tanggal pembuatan batas pemesanan paket wisata
                                                        $tgl_awal = $rowitem->tgl_pemesanan;
                                                        // tanggal berakhir pembuatan batas pemesanan paket wisata
                                                        $tgl_akhir = $rowitem->tgl_akhir_pemesanan;
                                                        // jangka waktu + 365 hari
                                                        $jangka_waktu = strtotime($tgl_akhir, strtotime($tgl_awal));
                                                        //tanggal expired
                                                        $tgl_exp = date("Y-m-d",$jangka_waktu);

                                                        if ($tgl_sekarang >= $tgl_exp) { ?>
                                                            <span class="badge badge-pill badge-danger">
                                                                <i class="fas fa-tag"></i> Sudah Tidak Berlaku.
                                                            </span><br>
                                                            <small class="text-muted">
                                                                Silahkan untuk mengganti status paket wisata ke, Tidak Aktif.
                                                            </small>
                                                        <?php } else { ?>
                                                            <span class="badge badge-pill badge-success">
                                                                <i class="fas fa-tag"></i> Masih dalam jangka waktu.
                                                            </span>
                                                        <?php }?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </table>

                                </div>
                                <!--collapse end -->
                                </td>
                            </tr>
                          <?php } ?>
                          </tbody>
                  </table>

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
    <!-- Konfirmasi Hapus -->
    <script>
        function konfirmasiHapusPaket(event){
        jawab = true
        jawab = confirm('Yakin ingin menghapus? Data Paket Wisata akan hilang permanen!')

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
