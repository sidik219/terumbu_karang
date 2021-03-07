<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewreservasi = 'SELECT * FROM t_reservasi_wisata
                  LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                  LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                  LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                  LEFT JOIN t_wisata ON t_reservasi_wisata.id_wisata = t_wisata.id_wisata
                  ORDER BY id_reservasi DESC';
$stmt = $pdo->prepare($sqlviewreservasi);
$stmt->execute();
$row = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Reservasi Wisata - TKJB</title>
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
    <link rel="icon" href="dist/img/KKPlogo.png" type="image/x-icon" />
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
                <img src="dist/img/KKPlogo.png"  class="brand-image img-circle elevation-3" style="opacity: .8">
                <!-- BRAND TEXT (TOP) -->
                <span class="brand-text font-weight-bold">TKJB</span>
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
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                <?php if($_SESSION['level_user'] == '3') { ?>
                     <table class="table table-striped">
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
                              <td><?=strftime('%A, %d %B %Y', $reservasidate);?></td>
                              <td><?=$rowitem->nama_status_reservasi_wisata?><br><small class="text-muted">Update Terakhir:
                                <br><?=strftime('%A, %d %B %Y', $truedate);?></small></td>
                              <td>
                                <button type="button" class="btn btn-act">
                                    <a href="edit_reservasi_wisata.php?id_reservasi=<?=$rowitem->id_reservasi?>" class="fas fa-edit"></a>
                                </button>
                                <button type="button" class="btn btn-act">
                                    <i class="far fa-trash-alt"></i>
                                </button>
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
                                                Wisata
                                            </div>
                                            <div class="col isi">
                                                <?=$rowitem->judul_wisata?>
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
                <?php } ?>

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
        <strong>Copyright &copy; 2020 .</strong> Terumbu Karang Jawa Barat
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

</div>

</body>
</html>
