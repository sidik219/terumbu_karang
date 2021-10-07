<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];

// Select Tabel Donasi Wisata
$sqldonasiwisata = 'SELECT * FROM t_donasi_wisata
                LEFT JOIN t_reservasi_wisata ON t_donasi_wisata.id_reservasi = t_reservasi_wisata.id_reservasi
                LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                WHERE status_donasi = "Belum Terambil"
                ORDER BY id_donasi_wisata DESC';
$stmt = $pdo->prepare($sqldonasiwisata);
$stmt->execute();
$row = $stmt->fetchAll();

// Setting harga donasi
if (isset($_POST['submit'])) {
    $harga_donasi  = $_POST['harga_donasi'];

    //Update dan set id_paket_wisata ke wisata pilihan
    $sqlupdatewisata = "UPDATE t_lokasi
                        SET harga_donasi = :harga_donasi
                        WHERE id_lokasi = :id_lokasi";

    $stmt = $pdo->prepare($sqlupdatewisata);
    $stmt->execute(['id_lokasi' => $id_lokasi,
                    'harga_donasi' => $harga_donasi]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: kelola_wisata_donasi.php?status=insertfailed");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_wisata_donasi.php?status=addsuccess");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Batch - Terumbu Karang</title>
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
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row pb-2">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Kelola Wisata Donasi</span></h4>
                        </div>
                    </div>
                    <!-- input harga donasi -->
                    <form action="" method="POST">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp.</span>
                        </div>
                        <input type="number" name="harga_donasi" class="form-control" placeholder="Jumlah Donasi Wisata Di Lokasi Anda" aria-label="Jumlah Donasi Wisata Di Lokasi Anda" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" name="submit">Simpan</button>
                        </div>
                    </div>
                    </form>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php
                    if (!empty($_GET['status'])) {
                        if ($_GET['status'] == 'updatesuccess') {
                            echo '<div class="alert alert-success" role="alert">
                          Update data berhasil
                      </div>';
                        } else if ($_GET['status'] == 'addsuccess') {
                            echo '<div class="alert alert-success" role="alert">
                          Data baru berhasil ditambahkan
                      </div>';
                        } else if ($_GET['status'] == 'deletesuccess') {
                            echo '<div class="alert alert-success" role="alert">
                          Data berhasil dihapus
                      </div>';
                        }
                    }
                    ?>
                    <div class="row pb-2">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Tabel Wisata Donasi</span></h4>
                            <p>Tabel ini dibuat dengan tujuan bisa mengambil donasi pada wisata</p>
                        </div>
                    </div>
                    <!-- tabel data belum terambil -->
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">Nama Wisatawan</th>
                                <th scope="col">Paket Wisata</th>
                                <th scope="col">Donasi</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="batch-donasi">
                                <?php
                                $sum_donasi = 0;
                                foreach($row as $donasi) { 
                                $sum_donasi+= $donasi->donasi;
                                ?>
                                <tr class="border rounded p-1 batch-donasi">
                                    <td><?=$donasi->nama_user?></td>
                                    <td><?=$donasi->nama_paket_wisata?></td>
                                    <td><?=$donasi->donasi?></td>
                                    <td><?=$donasi->status_donasi?></td>
                                    <td><button type="button" class="btn donasitambah" onclick="tambahPilihan(this)"><i class="nav-icon fas fa-plus-circle"></i></button></td>
                                </tr>
                                <?php } ?>
                            </div>
                        </tbody>
                    </table>

                    <!-- <div class="row pb-2">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Tabel Wisata Donasi</span></h4>
                            <p>Tabel ini dibuat dengan tujuan mengambil donasi pada wisata</p>
                        </div>
                    </div>
                    tabel yang akan diambil
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">Nama Wisatawan</th>
                                <th scope="col">Paket Wisata</th>
                                <th scope="col">Donasi</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nama Wisatawan</td>
                                <td>Paket Wisata Yang Dipilih</td>
                                <td>Rp 15.000</td>
                                <td>Akan diambil</td>
                                <td id="donasipilihan"></td>
                            </tr>
                        </tbody>
                    </table> -->
                    <div class="d-flex justify-content-between ">
                        <button type="button" class="btn btn-primary">Ambil Donasi</button>
                        <p><b>Total Donasi Yang Diambil : 60.000</b></p>
                        <p><b>Total Donasi : <?=number_format($sum_donasi, 0)?></b></p>
                    </div>
            </section>
            <!-- /.Left col -->
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


    <!-- Modal -->
    <div class="modal fade" id="empModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content  bg-light">
                <div class="modal-header">
                    <h4 class="modal-title">Rincian Donasi</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>





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
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <script>
        $(document).ready(function() {

            $('.userinfo').click(function() {

                var id_donasi = $(this).data('id');

                // AJAX request
                $.ajax({
                    url: 'list_populate.php',
                    type: 'post',
                    data: {
                        id_donasi: id_donasi,
                        type: 'load_rincian_donasi'
                    },
                    success: function(response) {
                        // Add response in Modal body
                        $('.modal-body').html(response);

                        // Display Modal
                        $('#empModal').modal('show');
                    }
                });
            });
        });
    </script>

</body>

</html>