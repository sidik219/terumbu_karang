<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';



    if(!$_GET['id_laporan']){
      header("Location: kelola_arsip_laporan_sebaran.php?status=accessdenied");
    }else{
      $id_laporan = $_GET['id_laporan'];
    }

    $sqllaporan = 'SELECT * FROM t_arsip_wilayah
                   LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_arsip_wilayah.id_wilayah
                   WHERE id_laporan = :id_laporan GROUP BY t_wilayah.id_wilayah';
    $stmt = $pdo->prepare($sqllaporan);
    $stmt->execute(['id_laporan' => $id_laporan]);
    $rowlaporan = $stmt->fetchAll();

    $sqllaporan1 = 'SELECT * FROM t_laporan_sebaran WHERE id_laporan = :id_laporan';
    $stmt = $pdo->prepare($sqllaporan1);
    $stmt->execute(['id_laporan' => $id_laporan]);
    $laporansebaran = $stmt->fetch();



    if (isset($_POST['submit'])) {
            if($_POST['submit'] == 'Simpan'){
                $i = 0;
                
                $update_terakhir = date ('Y-m-d H:i:s', time());
                $tipe_laporan = $_POST['tipe_laporan'];
                $periode_laporan = $_POST['periode_laporan'];

                $sqlupdatearsip = 'UPDATE t_laporan_sebaran SET update_terakhir = :update_terakhir, tipe_laporan = :tipe_laporan, periode_laporan = :periode_laporan WHERE id_laporan = :id_laporan';
                $stmt = $pdo->prepare($sqlupdatearsip);
                $stmt->execute(['update_terakhir' => $update_terakhir, 'id_laporan' => $id_laporan, 'tipe_laporan' => $tipe_laporan, 'periode_laporan' => $periode_laporan]);

                foreach($_POST['id_arsip_wilayah'] as $id_arsip){
                  $kurang = $_POST['kurang'][$i];
                  $cukup = $_POST['cukup'][$i];
                  $baik = $_POST['baik'][$i];
                  $sangat_baik = $_POST['sangat_baik'][$i];

                  $sqleditarsipwilayah = "UPDATE t_arsip_wilayah
                            SET kurang = :kurang, cukup = :cukup, baik = :baik, sangat_baik = :sangat_baik, tahun_arsip_wilayah = :tahun_arsip_wilayah
                            WHERE id_arsip_wilayah = :id_arsip_wilayah";

                  $stmt = $pdo->prepare($sqleditarsipwilayah);
                  $stmt->execute(['kurang' => $kurang, 'cukup' => $cukup, 'baik' => $baik, 'sangat_baik' => $sangat_baik, 'id_arsip_wilayah' => $id_arsip, 'tahun_arsip_wilayah' => $periode_laporan]);

                  $i++;
                }

                header("Location: kelola_arsip_laporan_sebaran.php?status=updatesuccess");
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Arsip Luas Sebaran - GoKarang</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
        <link rel="stylesheet" href= "plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
        <link rel="stylesheet" href= "dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
        <link rel="stylesheet" href= "plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href= "css/style.css">

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
            <a href= "dashboard_admin.php" class="brand-link">
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
                        <a class="btn btn-outline-primary" href="kelola_arsip_laporan_sebaran.php">< Kembali</a><br><br>
                         <h3>Edit Data Arsip Luas Sebaran</h3>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <form action="" enctype="multipart/form-data" method="POST" name="updateWilayah">

                          <table class="table table-striped table-bordered DataWilayah">
                    <div class="row">
                      <div class="col">
                          <span class="align-middle mt-2">*Data dalam satuan hektar (ha)</span>
                      </div>
                    </div>

                    <thead>
                            <tr>
                                <th class="text-center align-middle" rowspan="2" scope="col">Kabupaten</th>
                                <th class="text-center" colspan="4" scope="col">Tahun: <input type="number" value="<?=$rowlaporan[0]->tahun_arsip_wilayah?>" name="periode_laporan"></th>
                              <tr>
                                <th class="text-center" scope="col">Kurang</th><th class="text-center" scope="col">Cukup</th><th class="text-center" scope="col">Baik</th> <th class="text-center" scope="col">Sangat Baik</th>
                              </tr>
                            </tr>
                      </thead>

                <tbody class="table-hover">
                <?php


                    $sqlviewluasnama = 'SELECT * FROM t_wilayah
                                    LEFT JOIN t_arsip_wilayah ON t_wilayah.id_wilayah = t_arsip_wilayah.id_wilayah
                                    GROUP BY t_arsip_wilayah.id_wilayah  ORDER BY tahun_arsip_wilayah ASC';
                              $stmt = $pdo->prepare($sqlviewluasnama);
                              $stmt->execute();
                              $rowluasnama = $stmt->fetchAll();

                    foreach ($rowluasnama as $luasnama) {
                        $sqlviewluastahunan = 'SELECT * FROM t_wilayah
                                    LEFT JOIN t_arsip_wilayah ON t_wilayah.id_wilayah = t_arsip_wilayah.id_wilayah
                                    WHERE t_wilayah.id_wilayah = :id_wilayah  AND tahun_arsip_wilayah = :tahun_arsip_wilayah  GROUP BY t_wilayah.id_wilayah
                                    ORDER BY tahun_arsip_wilayah ASC';
                              $stmt = $pdo->prepare($sqlviewluastahunan);
                              $stmt->execute(['id_wilayah' => $luasnama->id_wilayah, 'tahun_arsip_wilayah' => $rowlaporan[0]->tahun_arsip_wilayah]);
                              $rowluastahunan = $stmt->fetchAll();
                ?>
                        <tr>
                            <td><?=$luasnama->nama_wilayah?></td>

                            <?php foreach ($rowluastahunan as $luastahunan) {

                            ?>
                            <input type="hidden" value="<?=$luastahunan->id_arsip_wilayah?>" name="id_arsip_wilayah[]">
                            <td class="text-center border"><input type="number" value="<?=$luastahunan->kurang?>" name="kurang[]"></td>
                            <td class="text-center border"><input type="number" value="<?=$luastahunan->cukup?>" name="cukup[]"></td>
                            <td class="text-center border"><input type="number" value="<?=$luastahunan->baik?>" name="baik[]"></td>
                            <td class="text-center border"><input type="number" value="<?=$luastahunan->sangat_baik?>" name="sangat_baik[]"></td>

                            <?php }?>
                        </tr>
                <?php }
                echo '</tr>';
                ?>

                </tbody>
                </table>
                          <label class="mt-2">Keterangan: </label>
                          <input type="text" value="<?=$laporansebaran->tipe_laporan?>" name="tipe_laporan" class="form-control mb-4">
                         <p align="center">
                        <button type="submit" name="submit" value="Simpan" class="btn btn-submit">Simpan</button></p>
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
    <script src= "plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <!-- Bootstrap 4 -->
    <script src= "plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src= "plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src= "dist/js/adminlte.js"></script>

</body>
</html>
