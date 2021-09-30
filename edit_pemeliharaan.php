<?php
session_start();
if (!($_SESSION['level_user'] == 3 || $_SESSION['level_user'] == 4)) {
    header('location: login.php?status=restrictedaccess');
}
include 'build/config/connection.php';
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

function alertCabutLabel($dob, $slabel)
{
    if ($slabel == 0) {
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $mn = $birthdate->diff($today)->m;
        if ($mn >= 11) {
            return '<i class="fas fa-exclamation-circle text-danger"></i> Perlu Cabut Label';
        }
    }
}


if (!$_GET['id_pemeliharaan']) {
    header("Location: kelola_pemeliharaan.php?status=accessdenied");
} else {
    $id_pemeliharaan = $_GET['id_pemeliharaan'];
}

$sqlviewlokasi = 'SELECT * FROM t_lokasi
                            ORDER BY id_lokasi';
$stmt = $pdo->prepare($sqlviewlokasi);
$stmt->execute();
$rowlokasi = $stmt->fetchAll();

$sqlviewpemeliharaan = 'SELECT * FROM t_pemeliharaan
                            LEFT JOIN t_lokasi ON t_pemeliharaan.id_lokasi = t_lokasi.id_lokasi
                            LEFT JOIN t_status_pemeliharaan ON t_pemeliharaan.id_status_pemeliharaan = t_status_pemeliharaan.id_status_pemeliharaan
                            WHERE id_pemeliharaan = :id_pemeliharaan';
$stmt = $pdo->prepare($sqlviewpemeliharaan);
$stmt->execute(['id_pemeliharaan' => $id_pemeliharaan]);
$rowpemeliharaan = $stmt->fetchAll();



if (isset($_POST['submit'])) {
    if (isset($_POST['id_batch'])) {
        $id_status_pemeliharaan        = $_POST['id_status_pemeliharaan'];
        $tanggal_pemeliharaan   = $_POST['date_pemeliharaan'];
        $i = 0;

        //UPDATE DATA PEMELIHARAAN
        $sqlupdatepemelihraan = "UPDATE t_pemeliharaan
                                  SET tanggal_pemeliharaan = :tanggal_pemeliharaan, id_status_pemeliharaan = :id_status_pemeliharaan
                                  WHERE id_pemeliharaan = :id_pemeliharaan";

        $stmt = $pdo->prepare($sqlupdatepemelihraan);
        $stmt->execute(['tanggal_pemeliharaan' => $tanggal_pemeliharaan, 'id_status_pemeliharaan' => $id_status_pemeliharaan, 'id_pemeliharaan' => $id_pemeliharaan]);

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            echo "HAHAHAAHA UPDATE FAILED !";
        } else {
            //echo "HAHAHAAHA GREAT SUCCESSS !";
        } // END UPDATE DATA PEMELIHARAAN



        //UPDATE DATA TIAP BATCH
        foreach ($_POST['id_batch'] as $id_batch_value) {
            $id_batch = $id_batch_value;

            $sqlviewbatch = 'SELECT * FROM t_batch
                            WHERE id_batch = :id_batch';
            $stmt = $pdo->prepare($sqlviewbatch);
            $stmt->execute(['id_batch' => $id_batch]);
            $rowbatch = $stmt->fetch();


            if ($id_status_pemeliharaan == 1) {
                $tanggal_pemeliharaan_terakhir = $rowbatch->tanggal_penanaman;
            } else {
                $tanggal_pemeliharaan_terakhir = date('Y-m-d H:i:s', time());
            }


            $sqlinsertdetailpemeliharaan = "UPDATE t_batch
                                                    SET tanggal_pemeliharaan_terakhir = :tanggal_pemeliharaan_terakhir, 
                                                        jumlah_pemeliharaan_batch = jumlah_pemeliharaan_batch + 1
                                                    WHERE id_batch = :id_batch";

            $stmt = $pdo->prepare($sqlinsertdetailpemeliharaan);
            $stmt->execute(['tanggal_pemeliharaan_terakhir' => $tanggal_pemeliharaan_terakhir, 'id_batch' => $id_batch]);


            //Tambah 1 jumlah pemeliharaan donasi dalam detail_batch
            $sqlviewdetailbatch = 'SELECT * FROM t_donasi
                                    LEFT JOIN t_detail_batch ON t_donasi.id_batch = t_detail_batch.id_batch
                                    WHERE t_donasi.id_batch = :id_batch
                                    AND t_donasi.id_donasi = t_detail_batch.id_donasi';
            $stmt = $pdo->prepare($sqlviewdetailbatch);
            $stmt->execute(['id_batch' => $id_batch]);
            $rowdetailbatch = $stmt->fetchAll();

            foreach ($rowdetailbatch as $detailbatch) {
                $jumlah_pemeliharaan = $detailbatch->jumlah_pemeliharaan + 1;

                $sqldonasi = "UPDATE t_donasi
                        SET jumlah_pemeliharaan = jumlah_pemeliharaan + 1
                        WHERE id_donasi = :id_donasi";

                $stmt = $pdo->prepare($sqldonasi);
                $stmt->execute(['id_donasi' => $detailbatch->id_donasi]);

                if($jumlah_pemeliharaan > 4){
                    $sqldonasi = "UPDATE t_donasi
                        SET id_status_donasi = 6
                        WHERE id_donasi = :id_donasi";

                $stmt = $pdo->prepare($sqldonasi);
                $stmt->execute(['id_donasi' => $detailbatch->id_donasi]);
                }
            }
        } //END UPDATE DATA TIAP BATCH

        


        $sqlhapushistorydonasi = 'DELETE FROM t_history_pemeliharaan
                                WHERE id_pemeliharaan = :id_pemeliharaan';

        $stmt = $pdo->prepare($sqlhapushistorydonasi);
        $stmt->execute(['id_pemeliharaan' => $id_pemeliharaan]);




        //UPDATE DATA DETAIL DONASI
        foreach ($_POST['id_detail_donasi'] as $id_detail_donasi_value) {
            $id_detail_donasi = $id_detail_donasi_value;
            $randomstring = substr(md5(rand()), 0, 7);

            $kondisi_terumbu = $_POST['kondisi'][$i];
            $ukuran_terumbu = $_POST['ukuran_terumbu'][$i];
            $punya_foto_lama = ($_POST['oldpic'][$i] != '');


            //Image upload
            if ($punya_foto_lama) {
                if ($_FILES["image_uploads"]["size"][$i] == 0) { //tidak ada pilihan
                    $foto_pemeliharaan = $_POST['oldpic'][$i];
                } else { //ada pilihan
                    $target_dir  = "images/foto_pemeliharaan/";
                    $foto_pemeliharaan = $target_dir . 'PMLH_' . $randomstring . '.jpg';
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"][$i], $foto_pemeliharaan);
                }
            } else { //tidak punya foto lama
                if ($_FILES["image_uploads"]["size"][$i] == 0) { //tidak ada pilihan
                    $foto_pemeliharaan = '';
                } else { //ada pilihan
                    $target_dir  = "images/foto_pemeliharaan/";
                    $foto_pemeliharaan = $target_dir . 'PMLH_' . $randomstring . '.jpg';
                    move_uploaded_file($_FILES["image_uploads"]["tmp_name"][$i], $foto_pemeliharaan);
                }
            } //---image upload end

            //Hapus entry pemeliharaan id_detail_donasi sebelumnya
            $sql = 'DELETE FROM t_history_pemeliharaan
            WHERE id_detail_donasi = :id_detail_donasi';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_detail_donasi' => $id_detail_donasi]);

            //Insert entry pemeliharaan 
            $sqlhistorydonasi = "INSERT INTO t_history_pemeliharaan
                                (id_detail_donasi, kondisi_terumbu, ukuran_terumbu, foto_pemeliharaan , tanggal_pemeliharaan, id_pemeliharaan )
                                VALUES (:id_detail_donasi, :kondisi_terumbu, :ukuran_terumbu, :foto_pemeliharaan, :tanggal_pemeliharaan, :id_pemeliharaan) ";

            $stmt = $pdo->prepare($sqlhistorydonasi);
            $stmt->execute([
                'kondisi_terumbu' => $kondisi_terumbu, 'foto_pemeliharaan' => $foto_pemeliharaan, 'ukuran_terumbu' => $ukuran_terumbu,
                'id_detail_donasi' => $id_detail_donasi, 'tanggal_pemeliharaan' => $tanggal_pemeliharaan, 'id_pemeliharaan' => $id_pemeliharaan
            ]);

            $i++; //index increment

        } // END UPDATE DATA DETAIL DONASI
    }
    header('Location: kelola_pemeliharaan.php?status=updatesuccess&id_status_pemeliharaan=1');
    // header("Location: kelola_pemeliharaan.php?id_status_pemeliharaan=1");
} //submit post end

function ageCalculator($dob)
{
    $birthdate = new DateTime($dob);
    $today   = new DateTime('today');
    $ag = $birthdate->diff($today)->y;
    $mn = $birthdate->diff($today)->m;
    $dy = $birthdate->diff($today)->d;
    if ($mn == 0) {
        return "$dy Hari";
    } elseif ($ag == 0) {
        return "$mn Bulan  $dy Hari";
    } else {
        return "$ag Tahun $mn Bulan $dy Hari";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kelola Pemeliharaan - GoKarang</title>
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
                    <a class="btn btn-outline-primary" href="kelola_pemeliharaan.php">
                        < Kembali</a><br><br>
                            <h4><span class="align-middle font-weight-bold">Masukkan Data Pemeliharaan</h4></span>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- <form action="edit_post_test.php" enctype="multipart/form-data" method="POST"> -->
                    <form action="" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label>Lokasi Pemeliharaan : ID <?= $rowpemeliharaan[0]->id_lokasi ?> <?= $rowpemeliharaan[0]->nama_lokasi ?></label><br>
                            <label>Status :
                                <?php
                                $sqlstatuspemeliharaan = 'SELECT * FROM t_status_pemeliharaan';

                                $stmt = $pdo->prepare($sqlstatuspemeliharaan);
                                $stmt->execute();
                                $rowstatuspemeliharaan = $stmt->fetchAll();
                                $i = 0;
                                foreach ($rowstatuspemeliharaan as $statuspemeliharaan) {
                                ?>
                                    <div class="ml-2 form-check form-check-inline">
                                        <input <?php if ($statuspemeliharaan->id_status_pemeliharaan == $rowpemeliharaan[0]->id_status_pemeliharaan) {
                                                    echo 'checked';
                                                } ?> class="form-check-input" type="radio" name="id_status_pemeliharaan" id="inlineRadio<?= $i ?>" value="<?= $statuspemeliharaan->id_status_pemeliharaan ?>">
                                        <label class="form-check-label" for="inlineRadio<?= $i ?>">
                                            <span class="status-pemeliharaan badge <?php if ($statuspemeliharaan->id_status_pemeliharaan == 1) {
                                                                                        echo 'badge-warning';
                                                                                    } else {
                                                                                        echo 'badge-success';
                                                                                    } ?> p-2"><?= $statuspemeliharaan->nama_status_pemeliharaan ?></span>

                                        </label>
                                    </div>
                                <?php $i++;
                                } ?>




                            </label>

                        </div>
                        <div class="form-group mb-5">
                            <label class="small" for="date_pemeliharaan">Tanggal Pemeliharaan</label>
                            <input type="date" id="date_pemeliharaan" name="date_pemeliharaan" class="form-control small" value="<?= $rowpemeliharaan[0]->tanggal_pemeliharaan ?>" required>
                        </div>
                        <script>
                            var today = new Date().toISOString().split('T')[0];
                            document.getElementsByName("date_pemeliharaan")[0].setAttribute('min', today);
                        </script>

                        <div class="form-group">
                            <h4 class="mb-2 font-weight-bold">Daftar Batch</h4>
                            <div id="daftarbatch">
                                <span class="text-muted mb-4">Harap isi data sesuai keadaan di lapangan</span>

                                <?php
                                foreach ($rowpemeliharaan as $pemeliharaan) {

                                ?>

                                    <div class="col-12">
                                        <?php
                                        $sqlviewdetailpemeliharaan = 'SELECT * FROM t_batch
                                                                    LEFT JOIN t_detail_pemeliharaan ON t_batch.id_batch = t_detail_pemeliharaan.id_batch
                                                                    LEFT JOIN t_titik ON t_batch.id_titik = t_titik.id_titik
                                                            WHERE id_pemeliharaan = :id_pemeliharaan';
                                        $stmt = $pdo->prepare($sqlviewdetailpemeliharaan);
                                        $stmt->execute(['id_pemeliharaan' => $pemeliharaan->id_pemeliharaan]);
                                        $rowdetailpemeliharaan = $stmt->fetchAll();

                                        foreach ($rowdetailpemeliharaan as $detailpemeliharaan) {
                                        ?>
                                            <div class="row mb-2  bg-light rounded p-sm-4 pt-2 shadow-sm border">
                                                <input type="hidden" value="<?= $detailpemeliharaan->id_batch ?>" name="id_batch[]">

                                                <div class="col-12 isi">
                                                    <h4><span class="badge badge-info">ID Batch <?= $detailpemeliharaan->id_batch ?></span></h4>
                                                </div>
                                                <?= '<br><span class="font-weight-bold text-danger">' . alertCabutLabel($detailpemeliharaan->tanggal_penanaman, $detailpemeliharaan->status_cabut_label) . '</span>' ?>
                                                <div class="col-12 isi mb-2 small">
                                                    <span class="font-weight-bold">ID Titik Penanaman : </span><?= $detailpemeliharaan->id_titik ?> <?= $detailpemeliharaan->keterangan_titik ?> <a target="_blank" href="http://maps.google.com/maps/search/?api=1&query=<?= $detailpemeliharaan->latitude ?>,<?= $detailpemeliharaan->longitude ?>&zoom=8" class="btn btn-act"><i class="nav-icon fas fa-map-marked-alt"></i> Lihat di Peta</a>
                                                    <div class="col-12 isi mb-3 pl-0 mt-1">
                                                        <span class="font-weight-bold">Tanggal Penanaman: </span><span class=""><?= strftime('%A, %d %B %Y', strtotime($detailpemeliharaan->tanggal_penanaman)) . '
                                        <span class="text-muted">(' . ageCalculator($detailpemeliharaan->tanggal_penanaman) . ' yang lalu)</span>' ?></span>
                                                    </div>

                                                </div>
                                                <div class="col isi">
                                                    <div class="mb-2 font-weight-bold border-bottom">
                                                        <h5 class="font-weight-bold btn btn-act" onclick="toggleDaftarDonasi()"><i class="icon fas fa-chevron-down"></i> Daftar Donasi</h5>
                                                    </div>
                                                    <?php
                                                    $sqlviewdetailbatch = 'SELECT * FROM t_detail_batch
                                                            LEFT JOIN t_donasi ON t_donasi.id_batch = t_detail_batch.id_batch
                                                            WHERE t_donasi.id_batch = :id_batch
                                                            AND t_donasi.id_donasi = t_detail_batch.id_donasi';
                                                    $stmt = $pdo->prepare($sqlviewdetailbatch);
                                                    $stmt->execute(['id_batch' => $detailpemeliharaan->id_batch]);
                                                    $rowdetailbatch = $stmt->fetchAll();

                                                    foreach ($rowdetailbatch as $detailbatch) {
                                                    ?>
                                                        <div class="row mb-4 daftardonasi">
                                                            <div class="col-auto isi bg-white p-3 rounded border border-primary border-bottom-0">
                                                                <span class="badge badge-pill badge-primary mb-2">ID Donasi <?= $detailbatch->id_donasi ?></span> - <span class="font-weight-bold"><?= $detailbatch->nama_donatur ?></span>
                                                                <br>Label: <span class="font-weight-bold small text-muted"><?= $detailbatch->pesan ?></span>
                                                            </div>
                                                            <div class="col-12">
                                                                <?php
                                                                $sqlviewisi = 'SELECT * FROM t_detail_donasi
                                                LEFT JOIN t_donasi ON t_detail_donasi.id_donasi = t_donasi.id_donasi
                                                LEFT JOIN t_terumbu_karang ON t_detail_donasi.id_terumbu_karang = t_terumbu_karang.id_terumbu_karang
                                                WHERE t_detail_donasi.id_donasi = :id_donasi';
                                                                $stmt = $pdo->prepare($sqlviewisi);
                                                                $stmt->execute(['id_donasi' => $detailbatch->id_donasi]);
                                                                $rowisi = $stmt->fetchAll();
                                                                foreach ($rowisi as $isi) {
                                                                    $sqlviewhistoryitems = 'SELECT * FROM t_history_pemeliharaan
                                                                      WHERE t_history_pemeliharaan.id_pemeliharaan = :id_pemeliharaan
                                                                      AND t_history_pemeliharaan.id_detail_donasi = :id_detail_donasi';

                                                                    $stmt = $pdo->prepare($sqlviewhistoryitems);
                                                                    $stmt->execute(['id_detail_donasi' => $isi->id_detail_donasi, 'id_pemeliharaan' => $id_pemeliharaan]);
                                                                    $rowhistory = $stmt->fetch();

                                                                    //Cek apa ada history mati

                                                                    $sqlcekmati = 'SELECT * FROM t_history_pemeliharaan
                                                                      WHERE t_history_pemeliharaan.id_detail_donasi = :id_detail_donasi AND kondisi_terumbu = "Mati"';

                                                                    $stmt = $pdo->prepare($sqlcekmati);
                                                                    $stmt->execute(['id_detail_donasi' => $isi->id_detail_donasi]);
                                                                    $cekmati = $stmt->fetch();


                                                                ?>
                                                                    <div class="row  mb-3 p-3 border rounded shadow-sm bg-white border-info">
                                                                        <!--DONASI CONTAINER START-->
                                                                        <input type="hidden" value="<?= $isi->id_detail_donasi ?>" name="id_detail_donasi[]">

                                                                        <div class="col-12 col-auto mb-1 mb-md-2">
                                                                            <span class="badge badge-pill badge-info small float-right"> ID Batch <?= $detailpemeliharaan->id_batch ?></span><span class="badge badge-pill badge-primary small float-right  mr-1">ID Donasi <?= $detailbatch->id_donasi ?> </span>
                                                                        </div>

                                                                        <div class="col-sm mb-1">
                                                                            <img class="rounded" height="60px" src="<?= $isi->foto_terumbu_karang ?>?">
                                                                        </div>
                                                                        <div class="col-sm mb-1">
                                                                            <span class="font-weight-bold">Jenis</span><br><span><?= $isi->nama_terumbu_karang ?></span>
                                                                        </div>
                                                                        <div class="col-8">
                                                                            <span class="font-weight-bold">Jumlah</span><br><span><?= $isi->jumlah_terumbu ?></span><br />
                                                                        </div>

                                                                        <div class="col-12 mt-2">
                                                                            <div class="form-group">
                                                                                <label for="tb_nama_jenis">Kondisi / Keterangan</label>
                                                                                <!-- <input type="text" id="tb_kondisi" name="kondisi[]" class="form-control" placeholder="Deskripsi singkat..." value="<?php //echo empty($rowhistory[0]->kondisi_terumbu) ? '' : $rowhistory[0]->kondisi_terumbu; 
                                                                                                                                                                                                        ?>" required> -->
                                                                                <select class="form-control" id="tb_nama_jenis" name="kondisi[]" <?php if($cekmati) echo ' disabled ' ?> required>
                                                                                    <option value="" disabled>--Pilih Kondisi--</option>
                                                                                    <option value="Sangat Baik" <?php if (!empty($rowhistory->kondisi_terumbu)) {
                                                                                                                    if ($rowhistory->kondisi_terumbu == "Sangat Baik") echo ' selected ';
                                                                                                                } ?>>Sangat Baik</option>
                                                                                    <option value="Baik" <?php if (!empty($rowhistory->kondisi_terumbu)) {
                                                                                                                if ($rowhistory->kondisi_terumbu == "Baik") echo ' selected ';
                                                                                                            } ?>>Baik</option>
                                                                                    <option value="Rusak" <?php if (!empty($rowhistory->kondisi_terumbu)) {
                                                                                                                if ($rowhistory->kondisi_terumbu == "Rusak") echo ' selected ';
                                                                                                            } ?>>Rusak</option>
                                                                                    <option value="Mati" <?php if (!empty($rowhistory->kondisi_terumbu)) {
                                                                                                                if ($rowhistory->kondisi_terumbu == "Mati") echo ' selected';
                                                                                                            } ?>>Mati</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col form-group">
                                                                            <label for="tb_nama_jenis">Ukuran Terumbu (meter persegi)</label>
                                                                            <div class="row">
                                                                                <div class="col-6"><input type="number" step="0.01" id="ukuran_terumbu" name="ukuran_terumbu[]" value=<?php if (!empty($rowhistory->ukuran_terumbu)) {
                                                                                                                                                                                            echo $rowhistory->ukuran_terumbu;
                                                                                                                                                                                        } ?> class="form-control number-input" <?php if($cekmati) echo ' disabled ' ?>
                                                                                                            required /></div>
                                                                                <div class="col-2">m²</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-12 mt-1 <?php if($cekmati) echo ' d-none ' ?>">

                                                                            <div class='form-group' id='fototk<?= $isi->id_detail_donasi ?>'>
                                                                                <div>
                                                                                    <label for='image_uploads<?= $isi->id_detail_donasi ?>'>Foto Terumbu Karang</label><span class="small text-muted"> (opsional)</span> <br>

                                                                                    <label class="btn btn-sm btn-primary btn-blue" for='image_uploads<?= $isi->id_detail_donasi ?>'>
                                                                                        <i class="fas fa-camera"></i> Upload Foto</label>
                                                                                    <br><span id="file-input-label<?= $isi->id_detail_donasi ?>" class="small text-muted"><?php echo empty($isi->foto_pemeliharaan) ? 'Belum ada pilihan' : '' ?></span>

                                                                                    <input type='file' class='form-control d-none' id='image_uploads<?= $isi->id_detail_donasi ?>' name='image_uploads[]' accept='.jpg, .jpeg, .png' onchange="readURL<?= $isi->id_detail_donasi ?>(this)">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <img class="preview-images rounded" id="preview<?= $isi->id_detail_donasi ?>" width="100px" src="#" alt="Preview Gambar" />
                                                                                <img id="oldpic<?= $isi->id_detail_donasi ?>" src="<?php echo empty($rowhistory->foto_pemeliharaan) ? '' : $rowhistory->foto_pemeliharaan ?>" width="100px">
                                                                                <input type="hidden" name="oldpic[]" class="form-control" value="<?php echo empty($rowhistory->foto_pemeliharaan) ? '' : $rowhistory->foto_pemeliharaan ?>">

                                                                            </div>
                                                                        </div>

                                                                        <script>
                                                                            const actualBtn<?= $isi->id_detail_donasi ?> = document.getElementById('image_uploads<?= $isi->id_detail_donasi ?>');

                                                                            const fileChosen<?= $isi->id_detail_donasi ?> = document.getElementById('file-input-label<?= $isi->id_detail_donasi ?>');

                                                                            actualBtn<?= $isi->id_detail_donasi ?>.addEventListener('change', function() {
                                                                                fileChosen<?= $isi->id_detail_donasi ?>.innerHTML = '<b>File dipilih :</b> ' + this.files[0].name
                                                                            })


                                                                            function readURL<?= $isi->id_detail_donasi ?>(input) {
                                                                                {
                                                                                    if (input.files[0].size > 2000000) { // ini untuk ukuran 800KB, 2000000 untuk 2MB.
                                                                                        alert("Maaf, Ukuran File Terlalu Besar. !Maksimal Upload 2MB");
                                                                                        input.value = "";
                                                                                    };
                                                                                    if (input.files && input.files[0]) {
                                                                                        var reader = new FileReader();
                                                                                        document.getElementById('oldpic<?= $isi->id_detail_donasi ?>').style.display = 'none';
                                                                                        reader.onload = function(e) {
                                                                                            $('#preview<?= $isi->id_detail_donasi ?>')
                                                                                                .attr('src', e.target.result)
                                                                                                .width(200);
                                                                                            $('#preview<?= $isi->id_detail_donasi ?>').fadeIn()
                                                                                        };

                                                                                        reader.readAsDataURL(input.files[0]);
                                                                                    }
                                                                                };
                                                                            }
                                                                        </script>




                                                                    </div><!-- Batch box thing end -->



                                                                <?php   }
                                                                ?>
                                                            </div>

                                                        </div>

                                                    <?php } ?>
                                                </div>
                                            </div>

                                        <?php } ?>

                                    </div>
                            </div>

                            <!--collapse end -->
                            </td>
                            </tr>
                        <?php } ?>




                        </div>
                </div>


                <br>
                <p align="center">
                    <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button>
                </p>
                </form>
                <br><br>

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

    <br><br>
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

    <script>
        $(document).ready(function() {
            $('.preview-images').hide()

            $('.daftardonasi').hide()
        });


        function toggleDaftarDonasi(e) {
            e = event.target
            $(e).parent().parent().find('.daftardonasi').fadeToggle()
        }
    </script>




</body>

</html>