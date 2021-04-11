<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

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
        if ($_POST['submit'] == 'Simpan') {
            $id_lokasi          = $_POST['id_lokasi'];
            $id_wisata          = $_POST['id_wisata'];
            $tgl_reservasi      = $_POST['tgl_reservasi'];
            $jumlah_peserta     = $_POST['jumlah_peserta'];
            $jumlah_donasi      = $_POST['split_harga_tk'];
            $total              = $_POST['total'];
            
            //var_dump($jumlah_donasi); exit();
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
                //header("Location: reservasi_saya.php?status=addsuccess");
                $last_id_reservasi = $pdo->lastInsertId();
            }
            
            $i = 0;
            foreach ($_POST['nominal'] as $nominal) {
                $id_user                = 1; //ok
                $id_status_donasi       = 1; //ok
                $nominal                = $_POST['nominal'][$i]; //ok
                $nama_donatur           = $_POST['nama_donatur'][$i]; //ok
                $nomor_rekening_donatur = $_POST['no_rekening_donatur'][$i]; //ok
                $bank_donatur           = $_POST['nama_bank_donatur'][$i]; //ok
                $id_lokasi              = $_POST['id_lokasi'][$i]; //ok
                $pesan                  = $_POST['pesan'][$i]; //ok
                $tanggal_donasi         = date ('Y-m-d H:i:s', time()); //ok
                $id_reservasi           = $last_id_reservasi; //ok
        
                $sqlinsertdonasi = "INSERT INTO t_donasi
                                        (id_user, nominal, tanggal_donasi, id_status_donasi, 
                                        id_lokasi, nama_donatur, nomor_rekening_donatur, bank_donatur, 
                                        pesan, update_terakhir, id_reservasi)
                                    VALUES (:id_user, :nominal, :tanggal_donasi, :id_status_donasi,
                                            :id_lokasi,  :nama_donatur, :nomor_rekening_donatur, :bank_donatur, 
                                            :pesan, :update_terakhir, :id_reservasi)";
        
                $stmt = $pdo->prepare($sqlinsertdonasi);
                $stmt->execute(['id_user'                   => $id_user, 
                                'nominal'                   => $nominal,
                                'id_lokasi'                 => $id_lokasi,
                                'id_status_donasi'          => $id_status_donasi,
                                'pesan'                     => $pesan,
                                'nama_donatur'              => $nama_donatur,
                                'bank_donatur'              => $bank_donatur,
                                'nomor_rekening_donatur'    => $nomor_rekening_donatur,
                                'tanggal_donasi'            => $tanggal_donasi,
                                'update_terakhir'           => $tanggal_donasi,
                                'id_reservasi'              => $id_reservasi
                                ]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                    //echo "HAHAHAAHA INSERT FAILED !";
                } else {
                    //echo "HAHAHAAHA GREAT SUCCESSS !";
                    header("Location: reservasi_saya.php?status=addsuccess");
                }
                $i++;
            }
        } else {
            echo '<script>alert("Harap pilih paket donasi yang akan ditambahkan")</script>';
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
                        <li class="list-group-item card-reservasi">Wisata :</li>
                        <input type="text" id="deskripsi_wisata" name="deskripsi_wisata" value="Peserta <?=$rowitem->judul_wisata?> : " class="list-group-item paket-wisata" disabled>
                    </ul>
                </div>

                <!-- Paket Donasi -->
                <div class="card" style="width: 20.5rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item card-reservasi">Paket Donasi : </li>

                        <!-- Jenis Terumbu Karang -->
                        <label  class="keterangan-paket-donasi">Jenis Terumbu Karang</label>
                        <select class="form-control" id="dd_id_wilayah" onchange="load_detail_lokasi(this.value); myFunction2();">
                            <option value="1" selected disabled>Pilih Jenis Terumbu Karang:</option>
                            <option value="1">Tidak Donasi</option>

                            <?php
                            $sqlviewjenis = 'SELECT * FROM t_jenis_terumbu_karang';
                            $stmt = $pdo->prepare($sqlviewjenis);
                            $stmt->execute();
                            $rowjenis = $stmt->fetchAll();

                            foreach ($rowjenis as $jenis) { ?>
                            <option value="<?=$jenis->id_jenis?>">
                                ID <?=$jenis->id_jenis?> - <?=$jenis->nama_jenis?>
                            </option>
                            <?php } ?>

                        </select>

                        <!-- Terumbu Karang -->
                        <label  class="keterangan-paket-donasi">Terumbu Karang</label>
                        <select class="form-control" id="id_tk"name="dd_id_tk" onchange="myFunction()">
                            <option value="1" selected disabled>Pilih Terumbu Karang:</option>
                        </select>

                        <!-- Info Paket Donasi -->
                        <span class="keterangan-paket-donasi">
                            *Harap Transfer sesuai dengan nominal tunai <br> *Paket Donasi =
                            Sekalian melakukan donasi terumbu karang
                            di Lokasi <br>
                            <b style="color: #17a2b8;"><?=$rowitem->nama_lokasi?></b>
                        </span>

                        <!-- Hiiden Output Jenis Terumbu Karang [data insert donasi] -->
                        <input type="hidden" id="jenis_tk" name="jenis_tk" value="" class="list-group-item paket-wisata" readonly>
                        <!-- Hiiden Output Terumbu Karang -->
                        <input type="hidden" id="terumbu_karang" name="terumbu_karang" value="" class="list-group-item paket-wisata" disabled>
                        <!-- Hiiden Hasil Split Terumbu Karang [data insert donasi] -->
                        <input type="hidden" id="split_tk" name="split_tk" value="" class="list-group-item paket-wisata" readonly>
                        <!-- Hiiden Hasil Split Harga Patokan Terumbu Karang [data insert donasi] -->
                        <input type="hidden" id="nominal" name="nominal[]" value="" class="list-group-item paket-wisata" readonly>

                        <!-- Output Hasil Split Harga Patokan Terumbu Karang [data insert wisata] -->
                        <input type="text" id="split_harga_tk" name="split_harga_tk" value="" class="list-group-item" style="color: gray;" readonly>

                    </ul>
                </div>

                <!-- Total -->
                <div class="card" style="width: 20.5rem;">
                    <ul class="list-group list-group-flush">
                        <label class="list-group-item card-reservasi">Total : </label>
                        <input type="text" id="total" name="total" value="" class="list-group-item" style="color: gray;" readonly>
                    </ul>
                </div>

                <!-- Link Untuk Ke Halaman Donasi Terumbu Karang -->
                <a class="btn btn-primary btn-lg btn-block mb-4" href="pilih_terumbu_karang.php?id_lokasi=<?=$rowitem->id_lokasi?>" style="color: white; width: 20.5rem;">
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
                    <input type="number" id="jumlah_peserta" name="jumlah_peserta" value="0" min="0" onchange="myFunction()" class="form-control" required>
                </div>
                
                <!-- Paket Wisata -->
                <div class="" style="width:100%;">
                    <div class="">
                        <h4 class="card-header mb-2 pl-0">Rincian Paket Wisata,
                            <span class="text-info font-weight-bolder"> <?=$rowitem->nama_lokasi?></span></h4>
                    </div>
                </div>
                <?php
                $sqlviewpaket = 'SELECT * FROM tb_paket_wisata 
                                    LEFT JOIN t_wisata ON tb_paket_wisata.id_wisata = t_wisata.id_wisata
                                    WHERE t_wisata.id_wisata = :id_wisata
                                    AND t_wisata.id_wisata = tb_paket_wisata.id_wisata';

                $stmt = $pdo->prepare($sqlviewpaket);
                $stmt->execute(['id_wisata' => $rowitem->id_wisata]);
                $rowpaket = $stmt->fetchAll();

                foreach ($rowpaket as $paket) { ?>
                <div class="row">
                    <div class="col">
                        <span class="font-weight-bold">
                        <i class="text-info fas fa-arrow-circle-right"></i> <?=$paket->nama_paket_wisata?></span>
                    </div>
                    <div class="col-lg-8  mb-2">
                        <span class="">
                        <i class="text-success fas fa-money-bill-wave"></i> Rp. <?=number_format($paket->biaya_paket, 0)?></span>
                    </div>
                </div>
                <?php } ?>
                <hr class="mb-2"/>
                <?php
                $sqlviewpaket = 'SELECT SUM(biaya_paket) AS total_biaya_paket, nama_paket_wisata, biaya_paket FROM tb_paket_wisata 
                                    LEFT JOIN t_wisata ON tb_paket_wisata.id_wisata = t_wisata.id_wisata
                                    WHERE t_wisata.id_wisata = :id_wisata
                                    AND t_wisata.id_wisata = tb_paket_wisata.id_wisata';

                $stmt = $pdo->prepare($sqlviewpaket);
                $stmt->execute(['id_wisata' => $rowitem->id_wisata]);
                $rowpaket = $stmt->fetchAll();

                foreach ($rowpaket as $paket) { ?>
                <div class="row">
                    <div class="col">
                        <span class="font-weight-bold">
                        <i class="text-info fas fa-arrow-circle-right"></i> Total Paket Wisata:</span>
                    </div>
                    <div class="col-lg-8  mb-2">
                        <span class="">
                        <i class="text-success fas fa-money-bill-wave"></i>
                        <input type="hidden" id="total_paket_wisata" value="<?=$paket->total_biaya_paket?>">
                        Rp. <input value="<?=number_format($paket->total_biaya_paket, 0)?>" class="paket-wisata" disabled></span>
                    </div>
                </div>
                <?php } ?>
                <hr class="mb-2"/>
                
                <!-- Paket Donasi -->
                <?php if($id_user > 0) {?>
                <div class="output">

                    <p class="btn btn-blue btn-primary" onclick="toggleDetail()">
                        <i class="icon fas fa-chevron-down"></i>
                        Isi Rincian Data Rekening Reservasi Wisata
                    </p>
                    <div class="detail-toggle" id="main-toggle">
                    <h4 class="mb-3 card-header pl-0">Data Rekening Wisatawan</h4>
                        <div class="mb-3">
                            <label for="nama_donatur">Nama Pemilik Rekening</label>
                            <input type="text" class="form-control data_donatur" id="nama_donatur" name="nama_donatur[]">
                        </div>
                        <div class="mb-3">
                            <label for="no_rekening_donatur">Nomor Rekening</label>
                            <input type="number" class="form-control data_donatur" id="no_rekening_donatur" name="no_rekening_donatur[]">
                        </div>
                        <div class="mb-3">
                            <label for="nama_bank_donatur">Nama Bank</label>
                            <input type="text" class="form-control data_donatur" id="nama_bank_donatur" name="nama_bank_donatur[]">
                        </div>
                        <div class="mb-3">
                            <!-- Pesan/Ekspresi -->
                            <label>Pesan/Ekspresi di Terumbu Karang</label>
                            <input type="text" id="pesan" name="pesan[]" class="form-control">
                        </div>
                    </div>

                </div>
                <?php } ?>
                
                <!-- Metode Pembayaran -->
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
                </div>
                <hr class="mb-2"/>

                <div class="row">
                    <div class="col">
                        <span class="font-weight-bold">
                        <i class="fas fa-user-tie"></i> Nama Rekening Pengelola</span>
                    </div>
                    <div class="col-lg-6 mb-2">
                        <span class=""><?=$rowitem->nama_rekening?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <span class="font-weight-bold">
                        <i class="text-warning fas fa-university"></i> Nomor Rekening Pengelola</span>
                    </div>
                    <div class="col-lg-6  mb-2">
                        <span class=""><?=$rowitem->nomor_rekening?></span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <span class="font-weight-bold">
                        <i class="text-info fas fa-hashtag"></i> Bank Pengelola</span>
                    </div>
                    <div class="col-lg-6  mb-2">
                        <span class=""><?=$rowitem->nama_bank?></span>
                    </div>
                </div>

                <button type="submit" name="submit" value="Simpan" class="btn btn-primary btn-lg btn-block mb-4">Buat Reservasi Wisata</button>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!--(づ｡◕‿‿◕｡)づ pika pika pikachu (づ｡◕‿‿◕｡)づ-->
    <script>
        function myFunction() {
            var jumlah_peserta  = document.getElementById("jumlah_peserta").value;
            var paket_wisata    = document.getElementById("total_paket_wisata").value;
            var id_tk           = document.getElementById("id_tk").value; //data terumbu karang
            var nominal         = document.getElementById("id_tk").value; //data nominal terumbu karang

            var deskripsi       = jumlah_peserta;
            var reservasi       = jumlah_peserta * paket_wisata; //5 x 750.000 = 3.750.000
            var total_reservasi = reservasi;
            var terumbu_karang  = id_tk;
            var hasil_split     = nominal.split("-");
            var split_tk        = hasil_split[0];
            var split_harga_tk  = hasil_split[1];

            if(id_tk == 1)
            {
                var jenis_tk        = null;
                var split_harga_tk  = null;
                var hasil           = total_reservasi;
            } else {
                var hasil   = parseInt(total_reservasi) + parseInt(split_harga_tk);
            }

            document.getElementById("deskripsi_wisata").value = "Peserta <?=$rowitem->judul_wisata?> : " + deskripsi;
            document.getElementById("terumbu_karang").value = terumbu_karang;
            document.getElementById("split_tk").value = split_tk;
            document.getElementById("split_harga_tk").value = split_harga_tk;
            document.getElementById("nominal").value = split_harga_tk;
            document.getElementById("total").value = hasil; //total dari total_reservasi * donasi
            //document.write(harga_tk);
        }
        
        //Jenis Terumbu Karang
        function myFunction2() {
            var id_jenis = document.getElementById("dd_id_wilayah").value; //data jenis terumbu karang

            if(id_jenis == 1)
            {
                var jenis = null;
            } else {
                var jenis   = id_jenis;
            }

            document.getElementById("jenis_tk").value = jenis;
        }

        function load_detail_lokasi(id_jenis){
            $.ajax({
                type: "POST",
                url: "list_populate.php",
                data:{
                    id_jenis: id_jenis,
                    type: 'load_detail_lokasi'
                },
                beforeSend: function() {
                $("#id_tk").addClass("loader");
                },
                success: function(data){
                $("#id_tk").html(data);
                $("#id_tk").removeClass("loader");
                }
            });
        }
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

        function toggleDetail(e){
            var e = event.target
            $(e).siblings('.detail-toggle').fadeToggle()
        }
    </script>

</body>
</html>
