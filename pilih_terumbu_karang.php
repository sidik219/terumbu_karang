<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

if ($_GET['id_lokasi']) {
    $_SESSION['id_lokasi'] = $_GET['id_lokasi'];
} else if (!$_GET['id_lokasi' && !$_SESSION['id_lokasi']]) {
    header("Location: map.php");
}

// cek session
if (isset($_SESSION['level_user'])) {
    //   echo "Your session is running " . $_SESSION['level_user'];
    $cek_SESSION = $_SESSION['level_user'];
}

// SIsa donasi dari periode sebelumnya
if (isset($_SESSION['id_lokasi_dikelola'])) {
    $id_lokasi = $_SESSION['id_lokasi_dikelola'];
    $sqldonasiwisata = 'SELECT saldo_donasi_wisata FROM t_lokasi
                    WHERE t_lokasi.id_lokasi=' . $id_lokasi . '';
    $stmt = $pdo->prepare($sqldonasiwisata);
    $stmt->execute();
    $rowsisa = $stmt->fetch();
}

if (isset($_GET['id_jenis']) && ((!$_GET['id_jenis']) == "")) {
    $id_jenis = $_GET['id_jenis'];

    $sqlviewtk = 'SELECT * FROM t_detail_lokasi
                LEFT JOIN t_terumbu_karang ON t_terumbu_karang.id_terumbu_karang = t_detail_lokasi.id_terumbu_karang
                LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_detail_lokasi.id_lokasi
                WHERE t_terumbu_karang.id_jenis = :id_jenis
                AND  t_detail_lokasi.id_lokasi = :id_lokasi'; //AND stok_terumbu > 0

    $stmt = $pdo->prepare($sqlviewtk);
    $stmt->execute(['id_jenis' => $_GET['id_jenis'], 'id_lokasi' => $_SESSION['id_lokasi']]);
    $row = $stmt->fetchAll();
    // var_dump($row);
    // die;
} elseif (isset($_GET['id_jenis']) && (($_GET['id_jenis']) == "")) {
    $sqlviewtk = 'SELECT * FROM t_detail_lokasi
                LEFT JOIN t_terumbu_karang ON t_terumbu_karang.id_terumbu_karang = t_detail_lokasi.id_terumbu_karang
                LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_detail_lokasi.id_lokasi
                WHERE  t_detail_lokasi.id_lokasi = :id_lokasi';

    $stmt = $pdo->prepare($sqlviewtk);
    $stmt->execute(['id_lokasi' => $_SESSION['id_lokasi']]);
    $row = $stmt->fetchAll();
    // var_dump($row);
    // die;
} else {
    $sqlviewtk = 'SELECT * FROM t_detail_lokasi
                LEFT JOIN t_terumbu_karang ON t_terumbu_karang.id_terumbu_karang = t_detail_lokasi.id_terumbu_karang
                LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_detail_lokasi.id_lokasi
                WHERE  t_detail_lokasi.id_lokasi = :id_lokasi';

    $stmt = $pdo->prepare($sqlviewtk);
    $stmt->execute(['id_lokasi' => $_SESSION['id_lokasi']]);
    $row = $stmt->fetchAll();

    $sqldonasiwisata = 'SELECT*FROM t_donasi_wisata WHERE t_donasi_wisata.status_donasi="Terambil"';
    'SELECT * FROM t_donasi_wisata
                LEFT JOIN t_reservasi_wisata ON t_donasi_wisata.id_reservasi = t_reservasi_wisata.id_reservasi
                LEFT JOIN t_lokasi ON t_reservasi_wisata.id_lokasi = t_lokasi.id_lokasi
                LEFT JOIN t_user ON t_reservasi_wisata.id_user = t_user.id_user
                LEFT JOIN tb_status_reservasi_wisata ON t_reservasi_wisata.id_status_reservasi_wisata = tb_status_reservasi_wisata.id_status_reservasi_wisata
                LEFT JOIN tb_paket_wisata ON t_reservasi_wisata.id_paket_wisata = tb_paket_wisata.id_paket_wisata
                WHERE status_donasi = "Belum Terambil"
                
                ORDER BY id_donasi_wisata DESC';
    $stmt = $pdo->prepare($sqldonasiwisata);
    $stmt->execute();
    $rowharga = $stmt->fetchAll();
    // var_dump($rowharga);
    // die;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pilih Jenis Terumbu Karang - GoKarang</title>
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
<script src="js\numberformat.js"></script>

<body class="hold-transition sidebar-mini layout-fixed">
    <a href="#" class="scrollup"><img class="scrollup" src="images/cart.png"></a>
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
        <div class="content-wrapper bg-light">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <?php if ($_SESSION['level_user'] == '1' || $_SESSION['level_user'] == '3') { ?>
                        <?php
                        if (!empty($_GET['status'])) {
                            if ($_GET['status'] == 'terambil') {
                                echo '<div class="alert alert-success" role="alert">Donasi Wisata Berhasil Diambil! Silahkan Pilih Bibit</div>';
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-between pb-2">
                                    <?php if ($_SESSION['level_user'] == '1') : ?>
                                        <a href="map.php" class="btn btn-primary btn-sm btn-blue"><i class="fas fa-angle-left"></i> Ganti Lokasi Penanaman</button></a>
                                    <?php elseif ($_SESSION['level_user'] == '3') : ?>
                                        <a href="kelola_wisata_donasi.php?status=kurang" class="btn btn-primary btn-sm btn-blue"><i class="fas fa-angle-left"></i> Tambah Total Donasi Wisata</button></a>
                                        <?php
                                        $sum_donasi = 0 + $rowsisa->saldo_donasi_wisata;
                                        foreach ($rowharga as $donasi) {
                                            $sum_donasi += $donasi->donasi;
                                        } ?>
                                        <input id="sum_donasi" type="hidden" value="<?= $sum_donasi ?>">
                                        <b>Total Donasi Yang Diambil : <?= number_format($sum_donasi, 0) ?> </b>
                                    <?php endif ?>
                                </div>
                                <!-- <button class="btn btn-warning btn-back" type="button"><i class="fas fa-angle-left"></i> Jenis Lainnya</button> -->

                                <div class="row">
                                    <div class="col">
                                        <div class="dropdown show d-flex justify-content-between">
                                            <h4 class="font-weight-bold mt-2">Pilih Terumbu Karang</h4>
                                            <?php if ($_SESSION['level_user'] == '1') : ?>
                                                <a class="btn btn-warning mb-2 dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Pilih Jenis
                                                </a>
                                            <?php endif ?>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="pilih_terumbu_karang.php?id_lokasi=<?= $_SESSION['id_lokasi']; ?>">Tampilkan Semua</a>

                                                <?php

                                                $sqlviewjenis = 'SELECT * FROM t_detail_lokasi
                                              LEFT JOIN t_terumbu_karang ON t_terumbu_karang.id_terumbu_karang = t_detail_lokasi.id_terumbu_karang
                                              LEFT JOIN t_jenis_terumbu_karang ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis
                                              AND id_lokasi = :id_lokasi AND t_jenis_terumbu_karang.id_jenis IS NOT NULL
                                              GROUP BY t_jenis_terumbu_karang.id_jenis'; //AND stok_terumbu > 0 

                                                $stmt = $pdo->prepare($sqlviewjenis);
                                                $stmt->execute(['id_lokasi' => $_SESSION['id_lokasi']]);
                                                $rowjenis = $stmt->fetchAll();

                                                foreach ($rowjenis as $jenis) {
                                                ?>
                                                    <a class="dropdown-item" href="pilih_terumbu_karang.php?id_lokasi=<?= $_SESSION['id_lokasi'] ?>&id_jenis=<?= $jenis->id_jenis ?>"><?= $jenis->nama_jenis ?></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row shop-items">
                                    <!-- <div class="card-columns"> -->
                                    <div class="row row-pilihan">

                                        <?php
                                        foreach ($row as $rowitem) {
                                            $harga_tk = $rowitem->harga_patokan_lokasi + $rowitem->biaya_pemeliharaan;
                                        ?>
                                            <div class="col-sm-4 card-container">
                                                <div class="card p-0 col-4 card-pilihan rounded mb-4 shadow-sm shop-item text-sm">
                                                <a href="#">
                                                    <img class="card-img-top rounded shop-item-image" height="150px" width="150px" src="<?= $rowitem->foto_terumbu_karang ?>"></a>
                                                <div class="card-body pt-2">
                                                    <h5 class="shop-item-title mb-0 card-title"><?= $rowitem->nama_terumbu_karang ?></h5>
                                                    <p class="card-text text-muted deskripsi_pilih_tk text-sm"><?php echo $rowitem->deskripsi_terumbu_karang; ?></p>
                                                    <?php //echo strlen($rowitem->deskripsi_terumbu_karang) > 50 ? substr($rowitem->deskripsi_terumbu_karang,0,40)."..." :$rowitem->deskripsi_terumbu_karang;
                                                    ?>
                                                    <span class="font-weight-bold" id="harga<?= $rowitem->id_terumbu_karang ?>">
                                                        <script>
                                                            var hargaformat = formatter.format(<?= $harga_tk ?>);
                                                            var hargap = document.createElement('p')
                                                            hargap.classList.add("mb-0", "mt-0")
                                                            hargap.textContent = hargaformat
                                                            document.getElementById("harga<?= $rowitem->id_terumbu_karang ?>").appendChild(hargap)
                                                        </script>
                                                    </span>
                                                    <span class="shop-item-price d-none">Rp. <?= $harga_tk ?></span>
                                                    <input type="hidden" class="shop-item-id" value="<?= $rowitem->id_terumbu_karang ?>">
                                                    <div class="row">
                                                        <!-- <div class="col-2">
                        <input type="number" min="1" id="tbqty" style="width: 100%; height:100%;">
                    </div> -->
                                                        <div class="col">
                                                            <a data-nama_tk="<?= $rowitem->id_terumbu_karang ?>" data-harga_tk="<?= $harga_tk ?>" data-id_tk="<?= $rowitem->id_terumbu_karang ?>" data-stok_tk="9999" class="add-to-cart btn btn-warning shop-item-button"><i class="nav-icon fas fa-cart-plus"></i> Tambahkan Keranjang</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <input type="text" class="d-none" id="id-lokasi" value="<?= $_SESSION['id_lokasi'] ?>">
                                    </div>
                                </div>
                            </div>
                            </section>
                            <!-- /.Left col -->
                        </div>
                        <!-- /.row (main row) -->
                </div>
            <?php } ?>
            <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <footer class="main-footer bg-light p-0 border-top-0">
            <?php if ($cek_SESSION == 3 || $cek_SESSION == 1) : ?>
                <section class="container bg-white content-section p-3 shadow-lg pb-0 rounded">
                    <h4 class="section-header font-weight-bold" id="keranjang"><i class="fas fa-cart-arrow-down"></i> Keranjang Anda</h4>
                    <div class="cart-items">
                    </div>
                    <div class="cart-total">
                        <strong class="cart-total-title">Subtotal</strong>
                        <span id="total_pilihan" class="cart-total-price font-weight-bold">0</span>
                        <!-- <input type="text" id="totalpilihan" value=""> -->
                        <!-- <div id="totalpilihan"></div> -->
                    </div>
                    <div class="mb-3 text-center mt-2">
                        <h5 class="font-weight-bold">Pesan / Ekspresi</h5><label for="pesan" class="font-weight-normal">
                            (Opsional. Pesan akan disertakan dalam label khusus pada terumbu karang )</label>
                        <?php if ($_SESSION['level_user'] == '3') : ?>
                            <input type="text" maxlength="64" class="form-control success" id="pesan" value="Donasi Wisata Bersama" placeholder="Isi pesan Anda di sini...">
                        <?php else : ?>
                            <input type="text" maxlength="64" class="form-control success" id="pesan" placeholder="Isi pesan Anda di sini...">
                        <?php endif ?>
                    </div>
                    <!-- <button class="btn btn-warning btn-back" type="button"><i class="fas fa-angle-left"></i> Jenis Lainnya</button> -->
                    <?php if ($_SESSION['level_user'] == '3') : ?>
                        <button class="btn btn-primary btn-purchase btn-blue" onclick="event.preventDefault(); ver()" type="button">Selesai Pilih<i class="fas fa-angle-double-right"></i></button>
                    <?php else : ?>
                        <button class="btn btn-primary btn-purchase btn-purchase-donator btn-blue" onclick="updateCartTotal()" type="button">Selesai Pilih <i class="fas fa-angle-double-right"></i></button>
                    <?php endif ?>
                </section>
            <?php endif ?>
            <script>
                function ver() {
                    // event.preventDefault()
                    let totalterumbu = $('#sum_donasi').val();
                    // const b = parseFloat(document.getElementsById('total_pilihan'));
                    var keranjang_deserialised = JSON.parse(sessionStorage.getItem('keranjang_serialised'))
                    subtotal = keranjang_deserialised.nominal
                    if (subtotal > totalterumbu) {
                        alert('Donasi Yang Diambil Tidak Mencukupi Dengan Bibit Diambil, Jika Kurang Klik Tombol Tambah Total Donasi Wisata');
                    } else {
                        purchaseClicked();
                    }
                }
                // }
            </script>
        </footer>
        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.js"></script>
        <!-- Shopping Cart -->
        <script src="js\shopping_cart.js" async></script>
        <script src="js\numberformat.js"></script>

        <script>
            //pre fill pesan text
            var keranjang_deserialised = JSON.parse(sessionStorage.getItem('keranjang_serialised'))
            var isipesan = keranjang_deserialised["pesan"]

            document.getElementById('pesan').value = isipesan

            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('.scrollup').fadeIn();
                } else {
                    $('.scrollup').fadeOut();
                }
            });

            $('.scrollup').click(function() {
                // $("html, body").animate({
                //     scrollTop: 0
                // }, 600);
                // return false;
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#keranjang").offset().top
                }, 1000);
            });

            $("#pesan").val(null);
        </script>

</body>

</html>