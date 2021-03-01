<?php include 'build/config/connection.php';
session_start();

if (isset($_SESSION['level_user']) == 0) {
    header('location: login.php');
}

    $id_wisata = $_GET['id_wisata'];
    $id_user = 1;
    $id_status_reservasi_wisata = 1;
    $keterangan = '-';

    $sqllokasi = 'SELECT * FROM t_wisata
                    LEFT JOIN t_lokasi ON t_wisata.id_lokasi = t_lokasi.id_lokasi
                    WHERE id_wisata = :id_wisata';

    $stmt = $pdo->prepare($sqllokasi);
    $stmt->execute(['id_wisata' => $id_wisata]);
    $row = $stmt->fetchAll();

    if (isset($_POST['submit'])) {
        $id_lokasi          = $_POST['id_lokasi'];
        $id_wisata          = $_POST['id_wisata'];
        $tgl_reservasi      = $_POST['tgl_reservasi'];
        $jumlah_peserta     = $_POST['jumlah_peserta'];
        $jumlah_donasi      = $_POST['jumlah_donasi'];
        $total              = $_POST['total'];

        $tanggal_sekarang = date ('Y-m-d H:i:s', time());

        $sqlreservasi = "INSERT INTO t_reservasi_wisata (id_user, id_lokasi, id_wisata,
                                        tgl_reservasi, jumlah_peserta, jumlah_donasi, total,
                                        id_status_reservasi_wisata, keterangan, update_terakhir)
                            VALUES (:id_user, :id_lokasi, :id_wisata,
                                        :tgl_reservasi, :jumlah_peserta, :jumlah_donasi, :total,
                                        :id_status_reservasi_wisata, :keterangan, :update_terakhir)";

        $stmt = $pdo->prepare($sqlreservasi);
        $stmt->execute(['id_user' => $id_user, 'id_lokasi' => $id_lokasi,
                        'id_wisata' => $id_wisata, 'tgl_reservasi' => $tgl_reservasi,
                        'jumlah_peserta' => $jumlah_peserta, 'jumlah_donasi' => $jumlah_donasi, 'total' => $total,
                        'id_status_reservasi_wisata' => $id_status_reservasi_wisata, 'keterangan' => $keterangan,
                        'update_terakhir' => $tanggal_sekarang]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
            header("Location: reservasi_saya.php?status=addsuccess");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Review Informasi Donasi - TKJB</title>
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Username</a>
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
                <?php if($_SESSION['level_user'] == '1') { ?>
                        <li class="nav-item  ">
                           <a href="dashboard_user.php" class="nav-link ">
                                <i class="nav-icon fas fa-home"></i>
                                <p> Home </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="donasi_saya.php" class="nav-link">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p> Donasi Saya </p>
                           </a>
                        </li>
                        <li class="nav-item menu-open">
                           <a href="reservasi_saya.php" class="nav-link active">
                                <i class="nav-icon fas fa-suitcase"></i>
                                <p> Reservasi Saya  </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="profil_saya.php" class="nav-link">
                                <i class="nav-icon fas fas fa-user"></i>
                                <p> Profil Saya  </p>
                           </a>
                        </li>
                    <?php } ?>
                    </ul>
                </nav>
                <!-- END OF SIDEBAR MENU -->
            </div>
            <!-- SIDEBAR -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->
        <?php if($_SESSION['level_user'] == '1') { ?>
            <!-- Main content -->
            <section class="content">
                <div class="container">
                  <br>
                  <a class="btn btn-warning btn-back" href="#" onclick="history.back()"><i class="fas fa-angle-left"></i>   Kembali Pilih</a><br>
                            <h4 class="pt-3 mb-3"><span class="font-weight-bold">Review Informasi Reservasi Wisata</span></h4>
            <div class="row">

        <?php
            if(!empty($_GET['status'])) {
                if($_GET['status'] == 'review_reservasi') {
                    echo '<div class="alert alert-success" role="alert">
                            Cek kembali reservasi wisata anda, supaya tidak terjadi kesalahan dalam menginputan data
                            </div>'; }
            }
        ?>

        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted"><i class="fas fa-dollar-sign"></i> Total Reservasi Wisata Anda</span>
                <span id="badge-jumlah" class="badge badge-secondary badge-pill"></span>
            </h4>

        <form action="" method="POST">
        <?php foreach ($row as $rowitem) { ?>
            <ul class="list-group mb-3" id="keranjangancestor">
                <!-- Paket Wisata -->
                <div class="card" style="width: 20.5rem; margin-bottom: 20px;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item card-reservasi">Paket Wisata :</li>
                        <input type="text" id="deskripsi_wisata" name="deskripsi_wisata" value="<?=$rowitem->judul_wisata?> : " class="list-group-item reservasi-input" readonly>
                        <input type="text" id="subtotal" name="subtotal" value="Subtotal : Rp " class="list-group-item reservasi-input" readonly>
                    </ul>
                </div>

                <!-- Paket Donasi -->
                <div class="card" style="width: 20.5rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item card-reservasi">Paket Donasi : </li>
                        <select class="custom-select" id="donasi_saya" onchange="myFunction()">
                            <option value="1" selected disabled>Pilih Donasi:</option>
                            <option value="1">Tidak Donasi</option>

                            <?php
                            $sqlviewpaket = 'SELECT * FROM tb_paket_donasi
                                                LEFT JOIN t_wisata ON tb_paket_donasi.id_wisata = t_wisata.id_wisata
                                                WHERE t_wisata.id_wisata = :id_wisata
                                                AND t_wisata.id_wisata = tb_paket_donasi.id_wisata';

                            $stmt = $pdo->prepare($sqlviewpaket);
                            $stmt->execute(['id_wisata' => $rowitem->id_wisata]);
                            $rowpersentase = $stmt->fetchAll();

                            foreach ($rowpersentase as $rowpaket) { ?>
                            <option value="<?=$rowpaket->persentase_paket_donasi?>"><?=$rowpaket->persentase_paket_donasi?>%</option>
                            <?php } ?>

                        </select>
                        <span class="keterangan-paket-donasi">
                            *Harap Transfer sesuai dengan nominal tunai <br> *Paket Donasi =
                            <?php
                            $sqlviewpaket = 'SELECT * FROM tb_paket_donasi
                                                LEFT JOIN t_wisata ON tb_paket_donasi.id_wisata = t_wisata.id_wisata
                                                WHERE t_wisata.id_wisata = :id_wisata
                                                AND t_wisata.id_wisata = tb_paket_donasi.id_wisata';

                            $stmt = $pdo->prepare($sqlviewpaket);
                            $stmt->execute(['id_wisata' => $rowitem->id_wisata]);
                            $rowpersentase = $stmt->fetchAll();

                            foreach ($rowpersentase as $rowpaket) { ?>
                            <span class="badge badge-pill badge-success mr-2">
                                <?=$rowpaket->persentase_paket_donasi?>%
                            </span>
                            <?php } ?>
                            Harga Wisata di Lokasi
                            <b style="color: #17a2b8;"><?=$rowitem->nama_lokasi?></b>
                        </span>
                        <input type="hidden" id="jumlah_donasi" name="jumlah_donasi" value="" class="list-group-item reservasi-input" readonly>
                    </ul>
                </div>

                <!-- Pesan / Ekspresi
                <div class="card" style="width: 20.5rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item card-reservasi">Pesan / Ekspresi : </li>
                        <input type="text" id="jumlah_donasi" name="jumlah_donasi" value="" class="list-group-item reservasi-input">
                    </ul>
                </div>-->

                <!-- Total -->
                <div class="card" style="width: 20.5rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item card-reservasi">Total : </li>
                        <input type="text" id="total" name="total" value="" class="list-group-item reservasi-input" readonly>
                    </ul>
                </div>

                <!-- Link Untuk Ke Halaman Donasi Terumbu Karang -->
                <a class="btn btn-primary btn-lg btn-block mb-4" href="pilih_jenis_tk.php?id_lokasi=<?=$rowitem->id_lokasi?>" style="color: white; width: 20.5rem;">
                    Ayo Donasi Terumbu Karang
                </a>
            </ul>
        </div>

        <div class="col-md-8 order-md-1 card">
            <h4 class="mb-3 card-header pl-0">Data Reservasi Wisata</h4>

                <div class="form-group">
                    <label for="id_user"></label>
                    <input type="hidden" id="id_wisata" name="id_wisata" value="<?=$rowitem->id_wisata?>" class="form-control">
                </div>

                <div class="form-group">
                    <label for="id_lokasi">ID Lokasi</label>
                    <input type="hidden" id="id_lokasi" name="id_lokasi" value="<?=$rowitem->id_lokasi?>" class="form-control">
                    <input type="text" id="nama_lokasi" name="nama_lokasi" value="<?=$rowitem->nama_lokasi?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="tgl_reservasi">Tanggal Reservasi</label>
                    <input type="date" id="tgl_reservasi" name="tgl_reservasi" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="jumlah_peserta">Jumlah Peserta</label>
                    <input type="number" id="jumlah_peserta" name="jumlah_peserta" value="1" min="1" onchange="myFunction()" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="jumlah_peserta"></label>
                    <input type="hidden" id="biaya_wisata" name="biaya_wisata" value="<?=$rowitem->biaya_wisata?>" class="form-control" readonly>
                </div>

                <div class="" style="width:100%;">
                    <div class="">
                        <h4 class="card-header mb-2 pl-0">Metode Pembayaran</h4>
                        <span class="">Pilihan untuk lokasi :</span>  <span class="text-info font-weight-bolder"> <?=$rowitem->nama_lokasi?></span>
                    <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                        <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                        <label class="custom-control-label  mb-2" for="credit">Bank Transfer (Konfirmasi Manual)</label>
                        <p class="text-muted">Harap upload bukti transfer agar reservasi wisata segera diproses pengelola lokasi.</p>
                    </div>
                <hr class="mb-2"/>

                <div class="row">
                    <div class="col">
                        <span class="font-weight-bold">Nama Rekening Pengelola
                    </div>
                    <div class="col-lg-8 mb-2">
                        <span class=""><?=$rowitem->nama_rekening?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <span class="font-weight-bold">Nomor Rekening Pengelola  </span>
                    </div>
                    <div class="col-lg-8  mb-2">
                        <span class=""><?=$rowitem->nomor_rekening?></span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <span class="font-weight-bold">Bank Pengelola  </span>
                    </div>
                    <div class="col-lg-8  mb-2">
                        <span class=""><?=$rowitem->nama_bank?></span>
                    </div>
                </div>

                <button name="submit" value="Simpan" class="btn btn-primary btn-lg btn-block mb-4" type="submit">Buat Reservasi Wisata</button>
            <?php } ?>
          </form>
        </div>
      </div>
        <!-- /.container-fluid -->
        </section>
      <?php } ?>
        <!-- /.content -->
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2020 .</strong> Terumbu Karang Jawa Barat
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

    <!--(づ｡◕‿‿◕｡)づ pika pika pikachu (づ｡◕‿‿◕｡)づ-->
    <script>
        function myFunction() {
            var jumlah_peserta  = document.getElementById("jumlah_peserta").value;
            var biaya_wisata    = document.getElementById("biaya_wisata").value;
            var donasi_saya     = document.getElementById("donasi_saya").value;

            var deskripsi   = jumlah_peserta;
            var reservasi   = jumlah_peserta * biaya_wisata; //5 x 750.000 = 3.750.000
            var donasi      = donasi_saya * (biaya_wisata / 100); //0.4 x 750.000 = 300.000

            if(donasi_saya == 1)
            {
                //3.750.000 tidak pakai donasi
                var donasi = 0;
                var hasil   = reservasi;
            } else {
                //3.750.000 + 300.000 = 4.050.000 pakai donasi
                var hasil   = reservasi + donasi ;
            }

            //Reservasi Wisata subtotal
            //var	reverse1     = reservasi.toString().split('').reverse().join(''),
                //ribuan1      = reverse1.match(/\d{1,3}/g);
                //subtotal     = ribuan1.join('.').split('').reverse().join('');

            //Reservasi Wisata total
            //var	reverse2     = hasil.toString().split('').reverse().join(''),
                //ribuan2      = reverse2.match(/\d{1,3}/g);
                //total        = ribuan2.join('.').split('').reverse().join('');

            document.getElementById("deskripsi_wisata").value = "<?=$rowitem->judul_wisata?> : " + deskripsi;
            document.getElementById("subtotal").value = "Subtotal : Rp " + reservasi; //subtotal dari jumlah_peserta * biaya_wisata
            document.getElementById("jumlah_donasi").value = donasi; //jumlah donasi dari donasi_saya * biaya_wisata
            document.getElementById("total").value = hasil; //total dari reservasi * donasi

        }
    </script>

</body>
</html>
