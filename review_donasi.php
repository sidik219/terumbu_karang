<?php
include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

// var_dump($_SESSION);
// die;

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

$sqldonasiwisata = 'SELECT * FROM t_donasi_wisata WHERE t_donasi_wisata.status_donasi="Terambil"';
$stmt = $pdo->prepare($sqldonasiwisata);
$stmt->execute();
$donasiwisata = $stmt->fetchAll();
$sum_donasi = 0;
foreach ($donasiwisata as $donasi) {
    $sum_donasi += $donasi->donasi;
}

// Untuk User Donatur
if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        $_SESSION['data_donasi'] = $_POST['tb_deskripsi_donasi'];
        // var_dump($_SESSION['data_donasi']);
        // // var_dump($_SESSION['total_pilih']);
        // die;
        header("Location:review_donasi_proses.php?status=addsuccess");
    }
}

// Untuk Donasi Wisata
if (isset($_POST['submitin'])) {
    // if ($_POST['submit'] == 'Simpan') {
    $_SESSION['data_donasi'] = $_POST['tb_deskripsi_donasi'];
    // var_dump($_SESSION['data_donasi']);
    // // var_dump($_SESSION['total_pilih']);
    // die;
    $sisa = ($rowlokasi->saldo_donasi_wisata + $sum_donasi) - $_POST['total_pilih'];
    // var_dump($rowlokasi->saldo_donasi_wisata, $sum_donasi, $_POST['total_pilih'], $sisa);
    // die;
    $_SESSION['prodid'] = $_POST['prodid'];
    // var_dump($_SESSION);
    // die;

    $sqlviewlokasi = 'UPDATE t_lokasi
    SET saldo_donasi_wisata =:saldo_donasi_wisata
    WHERE id_lokasi = :id_lokasi';
    $stmt = $pdo->prepare($sqlviewlokasi);
    $stmt->execute([
        'id_lokasi' => $_SESSION['id_lokasi'],
        'saldo_donasi_wisata' => $sisa
    ]);
    $rowlokasi = $stmt->fetch();
    // die;

    $idpilih = $_POST['prodid'];
    $hitung = count($_POST['prodid']);
    for ($x = 0; $x < $hitung; $x++) {
        $record = $idpilih[$x];
        $sqlreservasi = "UPDATE t_donasi_wisata
            SET status_donasi = 'Terbeli'
            WHERE id_donasi_wisata = $record";
        $stmt = $pdo->prepare($sqlreservasi);
        $stmt->execute();
    }
    header("Location:review_donasi_proses.php?status=addsuccess");
    // $id_user = $_POST['tb_id_user'];
    // $nominal = $_POST['tb_nominal'];
    // $deskripsi_donasi = $_POST['tb_deskripsi_donasi'];
    // $id_lokasi = $_POST['tb_id_lokasi'];
    // $status_donasi = "Menunggu Konfirmasi Pembayaran";



    // $sqlinsertdonasi = "INSERT INTO t_donasi
    //     (id_user, nominal, deskripsi_donasi, id_lokasi, status_donasi)
    //     VALUES (:id_user, :nominal, :deskripsi_donasi, :id_lokasi, :status_donasi)
    // ";

    // $stmt = $pdo->prepare($sqlinsertdonasi);
    // $stmt->execute(['id_user' => $id_user, 'nominal' => $nominal , 'deskripsi_donasi' => $deskripsi_donasi,
    // 'id_lokasi' => $id_lokasi , 'status_donasi' => $status_donasi]);

    // $affectedrows = $stmt->rowCount();
    // if ($affectedrows == '0') {
    // //echo "HAHAHAAHA INSERT FAILED !";
    // } else {
    //     //echo "HAHAHAAHA GREAT SUCCESSS !";
    //     header("Location:donasi_saya.php?status=addsuccess");
    // }
    // }
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
    <!-- Favicon -->
    <?= $favicon ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <script>
        if (sessionStorage.getItem('keranjang_serialised') == undefined) {
            document.location.href = 'map.php';
        }
        var keranjang = JSON.parse(sessionStorage.getItem('keranjang_serialised'))
    </script>


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

            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container">
                    <br>
                    <a class="btn btn-primary btn-sm btn-blue" href="#" onclick="history.back(); sessionStorage.removeItem('keranjang_serialised')"><i class="fas fa-angle-left"></i> Kembali Pilih</a><br>
                    <?php if ($_SESSION['level_user'] == '1') : ?>
                        <h4 class="pt-3 mb-3"><span class="font-weight-bold">Review Informasi Donasi</span></h4>
                    <?php elseif ($_SESSION['level_user'] == '3') : ?>
                        <h4 class="pt-3 mb-3"><span class="font-weight-bold">Review Informasi Donasi Wisata</span></h4>
                    <?php endif ?>
                    <div class="row">
                        <div class="col-md-4 order-md-2 mb-4">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted"><i class="fas fa-shopping-cart"></i> Keranjang Anda</span>
                                <span id="badge-jumlah" class="badge badge-info badge-pill"></span>
                            </h4>

                            <ul class="list-group mb-3" id="keranjangancestor">
                                <!-- listcontentrow cetak di sini -->
                            </ul>
                        </div>
                        <?php if ($_SESSION['level_user'] == '1') : ?>
                            <div class="col-md-8 order-md-1 card">
                                <h4 class="mb-3 card-header pl-0">Data Rekening Donatur</h4>
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="nama_donatur">Nama Pemilik Rekening</label>

                                        <input type="text" class="form-control data_donatur" value="<?= $_SESSION['nama_user'] ?>" id="nama_donatur" name="nama_donatur" required>

                                    </div>
                                    <div class="mb-3">
                                        <label for="no_rekening_donatur">Nomor Rekening</label>
                                        <input type="number" class="form-control data_donatur" id="no_rekening_donatur" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama_bank_donatur">Nama Bank</label>
                                        <input type="text" class="form-control data_donatur" id="nama_bank_donatur" required>
                                    </div>


                                    <!-- Hidden fields for POST data -->

                                    <input type="number" class="d-none" value="1" id="tb_id_user" name="tb_id_user">
                                    <input type="number" class="d-none" value="" id="tb_nominal" name="tb_nominal">
                                    <input type="hidden" value="" id="tb_deskripsi_donasi" name="tb_deskripsi_donasi">
                                    <input type="number" class="d-none" value="" id="tb_id_lokasi" name="tb_id_lokasi">

                                    <script>
                                        var tbnama_donatur = document.getElementById('nama_donatur')
                                        var tbno_rekening_donatur = document.getElementById('no_rekening_donatur')
                                        var tbnama_bank_donatur = document.getElementById('nama_bank_donatur')
                                        var tbdata_donatur = document.getElementsByClassName('data_donatur')

                                        for (i = 0; i < tbdata_donatur.length; i++) {
                                            tbdata_donatur[i].addEventListener('load', updateData);
                                            tbdata_donatur[i].addEventListener('change', updateData);
                                            tbdata_donatur[i].addEventListener('keyup', updateData);
                                        }


                                        function updateData(e) {
                                            keranjang["nama_donatur"] = tbnama_donatur.value
                                            keranjang["no_rekening_donatur"] = tbno_rekening_donatur.value
                                            keranjang["nama_bank_donatur"] = tbnama_bank_donatur.value
                                            keranjang["id_user"] = 1;
                                            keranjang["id_rekening_bersama"] = e
                                            document.getElementById('tb_deskripsi_donasi').value = JSON.stringify(keranjang)
                                        }

                                        document.getElementById('tb_id_lokasi').value = keranjang.id_lokasi
                                        document.getElementById('tb_nominal').value = keranjang.nominal

                                        //console.log(document.getElementById('tb_deskripsi_donasi').value)
                                    </script>

                                    <div class="" style="width:100%;">
                                        <div class="">
                                            <h4 class="card-header mb-2 pl-0">Metode Pembayaran</h4>
                                            <span class="">Pilihan untuk lokasi</span> <span class="text-info font-weight-bolder"><?= $rowlokasi->nama_lokasi ?> : </span>


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
                                            <p class="text-muted"><i class="fas fa-info-circle"></i> Harap upload bukti transfer di halaman "Donasi Saya" setelah menekan tombol Buat Donasi.</p>
                                        </div>

                                        <button name="submit" value="Simpan" class="btn btn-primary btn-lg btn-block mb-4" type="submit">Buat Donasi</button>
                                </form>
                            </div>

                        <?php elseif ($_SESSION['level_user'] == '3') : ?>
                            <div class="col-md-8 order-md-1 card">
                                <h4 class="mb-3 card-header pl-0">Data Rekening Donasi Wisata</h4>
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="nama_donatur">Nama Donasi Wisata Bersama</label>

                                        <input type="hidden" class="form-control data_donatur" value="<?= $_SESSION['username']; ?>" id="nama_donatur" name="nama_donatur" required>
                                        <?php foreach ($donasiwisata as $donasi) { ?>
                                            <input type="hidden" class="form-control data_donatur" value="<?= $donasi->id_donasi_wisata ?>" name="prodid[]" required>
                                        <?php } ?>

                                    </div>

                                    <input type="hidden" name="total_pilih" id="total_pilih">
                                    <div class="mb-3" style="display: none;">
                                        <label for="no_rekening_donatur">Nomor Rekening</label>
                                        <input type="hidden" class="form-control data_donatur" id="no_rekening_donatur" value="0">
                                    </div>
                                    <div class="mb-3" style="display: none;">
                                        <label for="nama_bank_donatur">Nama Bank</label>
                                        <input type="text" class="form-control data_donatur" id="nama_bank_donatur" value="-">
                                    </div>
                                    <input id="id_rekening" name="id_rekening_bersama" onload="updateData(this.value)" value="1" class="custom-control-input" required>

                                    <!-- Hidden fields for POST data -->

                                    <input type="number" class="d-none" value="1" id="tb_id_user" name="tb_id_user">
                                    <input type="number" class="d-none" value="" id="tb_nominal" name="tb_nominal">
                                    <input type="hidden" value="" id="tb_deskripsi_donasi" name="tb_deskripsi_donasi">
                                    <input type="number" class="d-none" value="" id="tb_id_lokasi" name="tb_id_lokasi">

                                    <script>
                                        var tbnama_donatur = document.getElementById('nama_donatur')
                                        var tbno_rekening_donatur = document.getElementById('no_rekening_donatur')
                                        var tbnama_bank_donatur = document.getElementById('nama_bank_donatur')
                                        var tbdata_donatur = document.getElementsByClassName('data_donatur')
                                        console.log(tbnama_donatur.value)
                                        console.log(tbno_rekening_donatur.value)
                                        console.log(tbnama_bank_donatur.value)
                                        console.log(tbdata_donatur)

                                        // for (i = 0; i < tbdata_donatur.length; i++) {
                                        //     tbdata_donatur[i].addEventListener('load', updateData);
                                        //     tbdata_donatur[i].addEventListener('change', updateData);
                                        //     tbdata_donatur[i].addEventListener('keyup', updateData);
                                        // }


                                        // function updateData(e) {
                                        keranjang["nama_donatur"] = tbnama_donatur.value
                                        keranjang["no_rekening_donatur"] = tbno_rekening_donatur.value
                                        keranjang["nama_bank_donatur"] = tbnama_bank_donatur.value
                                        keranjang["id_user"] = 1;
                                        keranjang["id_rekening_bersama"] = 1
                                        document.getElementById('tb_deskripsi_donasi').value = JSON.stringify(keranjang)
                                        // }
                                        // console.log(tb_deskripsi_donasi.value)
                                        console.log(tbdata_donatur.length)
                                        document.getElementById('tb_id_lokasi').value = keranjang.id_lokasi
                                        document.getElementById('tb_nominal').value = keranjang.nominal

                                        console.log(document.getElementById('tb_deskripsi_donasi').value = JSON.stringify(keranjang))
                                    </script>
                                    <button name="submitin" value="Simpan" class="btn btn-primary btn-lg btn-block mb-4" type="submit">Buat Donasi</button>
                                </form>
                            </div>
                        <?php endif ?>
                    </div>
                    <!-- /.container-fluid -->
            </section>

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
    <script src="plugins/popper/popper.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <script src="js\numberformat.js"></script>

    <script>
        var keranjangancestor = document.getElementById("keranjangancestor")
        var total_pilih = document.getElementById("total_pilih")
        var listcontentrow = document.createElement('li')
        listcontentrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed")
        // for (i = 0; i < keranjang.keranjang.length; i++){
        //   var listcontentrow = document.createElement('li')
        //   listcontentrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed")
        //   var listcontent =
        //   `<div>
        //       <h6 class="my-0">${keranjang.keranjang[i].nama_tk}</h6>
        //     </div>
        //     <span class="text-muted">x${keranjang.keranjang[i].jumlah_tk}</span>`
        //   listcontentrow.innerHTML = listcontent
        //   keranjangancestor.prepend(listcontentrow)
        // }


        var jumlahitem = 0;
        for (item in keranjang.keranjang) {

            var listcontentrow = document.createElement('li')
            listcontentrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed")
            var listcontent =
                `<div>
          <img class="rounded" src="${keranjang.keranjang[item].image}" height="30px">
        </div>
          <div>
            <h6 class="my-0">${keranjang.keranjang[item].nama_tk}</h6>
          </div>
          <div>
          <span class="font-weight-bold">x${keranjang.keranjang[item].jumlah_tk}</span>
          </div>
          `
            listcontentrow.innerHTML = listcontent
            keranjangancestor.prepend(listcontentrow)

            jumlahitem += parseInt(keranjang.keranjang[item].jumlah_tk)
        }

        var listpesanrow = document.createElement('li')
        listpesanrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed", "text-break")
        var listpesan =
            `<div class="row">
        <div class="col-12">
            <h6 class="my-0 font-weight-bold">Pesan/Ekspresi</h6>
          </div>
          <div class="col">
          <span><i>${keranjang.pesan}</i></span>
          </div>
        </div>`
        listpesanrow.innerHTML = listpesan
        keranjangancestor.append(listpesanrow)


        //   var listbiayarow = document.createElement('li')
        //   listbiayarow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed", "text-break", "text-sm")
        //   var listbiaya =
        //   `<div class="row">
        //   <div class="col-12">
        //       <h6 class="my-0">Jasa Penanaman <i class="fas fa-question-circle text-info"  data-toggle="tooltip" data-placement="top" title="Biaya transportasi ke titik penanaman bibit, peralatan selam, dan perlengkapan pendukung lainnya"></i></h6>
        //     </div>
        //     <div class="col">
        //     <span>${formatter.format(jumlahitem * <?= $rowlokasi->jasa_penanaman ?>)}</span>
        //     <span class="d-none">${jumlahitem * <?= $rowlokasi->jasa_penanaman ?>}</span>
        //     </div>
        //   </div>`

        //   var jasa_penanaman = jumlahitem * <?= $rowlokasi->jasa_penanaman ?>;
        //   listbiayarow.innerHTML = listbiaya
        //   keranjangancestor.append(listbiayarow)

        //   var listpemeliharaanrow = document.createElement('li')
        //   listpemeliharaanrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed", "text-break", "text-sm")
        //   var listpmlh =
        //   `<div class="row">
        //   <div class="col-12">
        //       <h6 class="my-0">Biaya Pemeliharaan <i class="fas fa-question-circle text-info"  data-toggle="tooltip" data-placement="top" title="Biaya pemeliharaan bibit mulai dari laboratorium hingga pemeliharaan berkala setelah matang di laut"></i></h6>
        //     </div>
        //     <div class="col">
        //     <span>${formatter.format(jumlahitem * <?= $rowlokasi->biaya_pemeliharaan ?>)}</span>
        //     </div>
        //   </div>`

        //   var biaya_pemeliharaan = jumlahitem * <?= $rowlokasi->biaya_pemeliharaan ?>;
        //   listpemeliharaanrow.innerHTML = listpmlh
        //   keranjangancestor.append(listpemeliharaanrow)

        //   keranjang.nominal = keranjang.nominal + biaya_pemeliharaan + jasa_penanaman;

        var listtotalrow = document.createElement('li')
        listtotalrow.classList.add("list-group-item", "d-flex", "justify-content-between", "lh-condensed")
        var listtotal =
            `<div>
            <h6 class="my-0 font-weight-bold">Total</h6>
          </div>
          <span class="font-weight-bold">${formatter.format(keranjang.nominal)}</span>
          `
        listtotalrow.innerHTML = listtotal
        keranjangancestor.append(listtotalrow)
        // var tampiltotal = `<input type="hidden" value="${keranjang.nominal}" name="total_pilih">`
        document.getElementById('total_pilih').value = keranjang.nominal
        // document.getElementById('total_pilih_tampil').value = keranjang.nominal
        // total_pilih.append(tampiltotal)

        // var badgejumlah = document.getElementById("badge-jumlah")
        // badgejumlah.innerText = jumlahitem

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

</body>

</html>