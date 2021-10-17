<?php include 'build/config/connection.php';
session_start();
if (!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$level_user = $_SESSION['level_user'];
$id_lokasi = $_SESSION['id_lokasi_dikelola'];
// var_dump($_SESSION);
// die;
$sqldonasiwisata = 'SELECT saldo_donasi_wisata FROM t_lokasi
                WHERE t_lokasi.id_lokasi=' . $id_lokasi . '';
$stmt = $pdo->prepare($sqldonasiwisata);
$stmt->execute();
$rowsisa = $stmt->fetch();
// var_dump($rowsisa);
// die;

if ($level_user == 2) {
    $id_wilayah = $_SESSION['id_wilayah_dikelola'];
    $extra_query = " AND t_lokasi.id_wilayah = $id_wilayah ";
    $extra_query_noand = " t_lokasi.id_wilayah = $id_wilayah ";
} else if ($level_user == 3) {
    $id_lokasi = $_SESSION['id_lokasi_dikelola'];
    $extra_query = " AND t_lokasi.id_lokasi = $id_lokasi ";
    $extra_query_noand = " t_lokasi.id_lokasi = $id_lokasi ";
} else if ($level_user == 4) {
    $extra_query = "  ";
    $extra_query_noand = " 1 ";
}

// Select Tabel Donasi Wisata
$sqldonasiwisata = 'SELECT * FROM t_lokasi
                WHERE  ' . $extra_query_noand . '
                ORDER BY id_lokasi DESC';
$stmt = $pdo->prepare($sqldonasiwisata);
$stmt->execute();
$rowLokasi = $stmt->fetch();


$sqlambilharga = 'SELECT * FROM `t_detail_lokasi`  
                LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_detail_lokasi.id_lokasi
                WHERE t_detail_lokasi.id_lokasi=:id_lokasi
                ORDER BY `t_detail_lokasi`.`harga_patokan_lokasi` ASC';
// cek lagi buat lokasi lain dengan user lokasi yang lain kayanya salah
$stmt = $pdo->prepare($sqlambilharga);
$stmt->execute(['id_lokasi' => $id_lokasi]);
$rowharga = $stmt->fetch();
// var_dump($rowharga);
// die;

// Select Tabel Donasi Wisata
$sqldonasiwisata = 'SELECT * FROM t_donasi_wisata
                LEFT JOIN t_reservasi_wisata ON t_donasi_wisata.id_reservasi = t_reservasi_wisata.id_reservasi
                LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                WHERE status_donasi = "Belum Terambil"
                AND  ' . $extra_query_noand . '
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
    $stmt->execute([
        'id_lokasi' => $id_lokasi,
        'harga_donasi' => $harga_donasi
    ]);

    $affectedrows = $stmt->rowCount();
    if ($affectedrows == '0') {
        header("Location: kelola_wisata_donasi.php?status=insertfailed");
    } else {
        //echo "HAHAHAAHA GREAT SUCCESSS !";
        header("Location: kelola_wisata_donasi.php?status=addsuccess&status=donasisuccess");
    }
}
if (isset($_POST['submitin'])) {
    // var_dump($_POST);
    // die;
    if (!$_POST['prodid'] == null) {
        $idpilih = $_POST['prodid'];
        $hitung = count($_POST['prodid']);
        for ($x = 0; $x < $hitung; $x++) {
            $record = $idpilih[$x];
            $sqlreservasi = "UPDATE t_donasi_wisata
        SET status_donasi = 'Terambil'
        WHERE id_donasi_wisata = $record";
            $stmt = $pdo->prepare($sqlreservasi);
            $stmt->execute();
        }
    }
    // header("Refresh:0;");
    header("location: pilih_terumbu_karang.php?status=terambil&id_lokasi=$id_lokasi");

    /*ini bisa loncat ke donasi, nanti total donasi tinggal sorting dari status terambil, 
    nanti kalau udah di checkout ganti lagi status jadi terbeli atau bagusnya seperti apa tergantung bobi*/
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Wisata Donasi - Terumbu Karang</title>
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
                            <h4><span class="align-middle font-weight-bold">Kelola Wisata Donasi Di <?= $rowLokasi->nama_lokasi ?></span></h4>
                        </div>
                    </div>
                    <!-- input harga donasi -->
                    <form action="" method="POST">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp.</span>
                            </div>
                            <input type="hidden" name="id_lokasi" value="<?= $rowLokasi->id_lokasi ?>">
                            <input type="number" name="harga_donasi" required value="<?= $rowLokasi->harga_donasi ?>" class="form-control" placeholder="Jumlah Donasi Wisata Di <?= $rowLokasi->nama_lokasi ?>" aria-label="Jumlah Donasi Wisata Di <?= $rowLokasi->nama_lokasi ?>" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit" name="submit">Simpan</button>
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
                        } else if ($_GET['status'] == 'donasisuccess') {
                            echo '<div class="alert alert-success" role="alert">
                          Wisata Donasi Berhasil!
                      </div>';
                        }
                    }
                    ?>
                    <div class="row pb-2 d-flex justify-content-between">
                        <div class="col">
                            <h4><span class="align-middle font-weight-bold">Tabel Wisata Donasi</span></h4>
                            <p>Tabel ini dibuat dengan tujuan bisa mengambil donasi pada wisata</p>
                        </div>
                        <!-- <div class="col">
                            <button onclick="takeshot()">
                                Take Screenshot
                            </button>
                        </div> -->
                    </div>
                    <!-- tabel data belum terambil -->
                    <div id="photo">
                        <form action="" method="POST" id="ok">
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
                                        foreach ($row as $donasi) {
                                            $sum_donasi += $donasi->donasi;
                                        ?>
                                            <tr class="border rounded p-1 batch-donasi ">
                                                <td><?= $donasi->nama_user ?><input type="hidden" name="user" value="<?= $donasi->nama_user ?>"></td>
                                                <td><?= $donasi->nama_paket_wisata ?><input type="hidden" name="namapaket" value="<?= $donasi->nama_paket_wisata ?>"></td>
                                                <td><?= $donasi->donasi ?><input type="hidden" name="donasi" value="<?= $donasi->donasi ?>"></td>
                                                <td><?= $donasi->status_donasi ?><input type="hidden" name="statusdonasi" value="<?= $donasi->status_donasi ?>"></td>
                                                <!-- <td><button type="button" class="btn donasitambah" onclick="tambahPilihan(this)"><i class="nav-icon fas fa-plus-circle"></i></button></td> -->
                                                <!-- <td class="pl-4"><input type="checkbox" name="prodid[]" onchange="keklik()" value=""></td> -->
                                                <td>
                                                    <label class="w-checkbox">
                                                        <div class="w-checkbox-input w-checkbox-input--inputType-custom hack42-checkbox"></div>
                                                        <input type="checkbox" id="checkbox" name="prodid[]" data-name="Checkbox" add-value="<?= $donasi->donasi; ?>" value="<?= $donasi->id_donasi_wisata; ?>">
                                                        <span class="pilihdonasi w-form-label">
                                                            Pilih
                                                        </span>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </div>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center pb-4">
                                <?php if ($_GET['status'] == 'kurang') : ?>
                                    <input type="submit" name="submitin" value="Ambil Donasi" class="btn btn-primary">
                                <?php elseif ($_GET['status'] == 'baru') : ?>
                                    <input onclick="return ver()" type="submit" name="submitin" value="Ambil Donasi" class="btn btn-primary">
                                <?php endif ?>
                                <div class="hack42-45-added-value-row">
                                    <?php if ($rowsisa->saldo_donasi_wisata == null || $rowsisa->saldo_donasi_wisata == 0) : ?>
                                        <b>Total Donasi Yang Diambil : Rp. <span class="totalpilih">0</span></b>
                                        <div class="w-embed"><input type="hidden" name="hasil_donasi" id="hasil_donasi" class="hasil_donasi" val="" readonly></div>
                                    <?php else : ?>
                                        <b>Total Donasi Yang Diambil : Rp. <span class="totalpilih"><?= number_format($rowsisa->saldo_donasi_wisata, 0); ?></span></b>
                                        <div class="w-embed"><input type="hidden" name="hasil_donasi" id="hasil_donasi" class="hasil_donasi" val="" value="<?= $rowsisa->saldo_donasi_wisata; ?>" readonly></div>

                                    <?php endif ?>
                                    <!-- name "hasil_donasi" ini yang akan diambil untuk validasi harga terumbu yang paling murah dan setting donasi -->
                                </div>
                                <div class="text-left">
                                    <b>Total Donasi : Rp. <?= number_format($sum_donasi, 0) ?></b>
                                    <div class="small">
                                        Terdapat Sisa Saldo Sebesar Rp. <?= number_format($rowsisa->saldo_donasi_wisata, 0); ?>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function ver() {
                                    let hargaterumbu = <?= $rowharga->harga_patokan_lokasi + $rowharga->biaya_pemeliharaan; ?>;
                                    const b = document.getElementById('hasil_donasi').value;
                                    // alert(hargaterumbu);
                                    if (b < hargaterumbu) {
                                        alert('Donasi Yang Diambil Tidak Mencukupi, Minimal Mengambil <?= $rowharga->harga_patokan_lokasi + $rowharga->biaya_pemeliharaan; ?>');
                                        return false
                                    } else {
                                        // event.preventDefault()
                                        // alert('gas');
                                        return true
                                    }
                                }
                            </script>
                        </form>
                    </div>
                    <div id="output"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js">
    </script>
    <!-- checkbox calculator -->
    <script>
        $('.pilihdonasi').click(function() {
            const $totalVal = $('.totalpilih'),
                $checkbox = $(this).prev();
            let sum;
            let sisa = <?= $rowsisa->saldo_donasi_wisata; ?>;
            if (!$checkbox.is(':checked')) {
                sum = Number($totalVal.text().replace(/[\$,]/g, '')) + Number($checkbox.attr('add-value'));
            } else {
                sum = Number($totalVal.text().replace(/[\$,]/g, '')) - Number($checkbox.attr('add-value'));
            }
            const formattedSum = new Intl.NumberFormat().format(sum);
            $totalVal.text(formattedSum);
            $('.hasil_donasi').val(sum);
        });
    </script>

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
    <script type="text/javascript">
        // Define the function 
        // to screenshot the div
        function takeshot() {
            let div = document.getElementById('photo');

            // Use the html2canvas
            // function to take a screenshot
            // and append it
            // to the output div
            html2canvas(div).then(
                function(canvas) {
                    document.getElementById('output').appendChild(canvas);
                })
        }
    </script>
</body>

</html>