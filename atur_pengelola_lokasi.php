<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
if(!isset($_GET['id_lokasi'])){
  header('location: kelola_lokasi.php?status=no_id');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';


    $id_lokasi = $_GET['id_lokasi'];

        $sqlviewkandidat = 'SELECT *, t_user.id_user FROM t_user
                        LEFT JOIN t_pengelola_lokasi ON t_user.id_user =t_pengelola_lokasi.id_user
                    WHERE level_user = 3 AND t_pengelola_lokasi.id_user IS NULL
                    ORDER BY nama_user';
        $stmt = $pdo->prepare($sqlviewkandidat);
        $stmt->execute();
        $rowkandidat = $stmt->fetchAll();


    $sqlviewpwilayah = 'SELECT * FROM t_pengelola_lokasi
                        LEFT JOIN t_user ON t_user.id_user = t_pengelola_lokasi.id_user
                    WHERE id_lokasi = :id_lokasi
                    ORDER BY t_pengelola_lokasi.id_user';
    $stmt = $pdo->prepare($sqlviewpwilayah);
    $stmt->execute(['id_lokasi' => $id_lokasi]);
    $rowpwilayah = $stmt->fetchAll();


    $sql = 'SELECT * FROM t_lokasi WHERE id_lokasi = :id_lokasi';

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_lokasi' => $id_lokasi]);
    $rowitem = $stmt->fetch();

    if (isset($_POST['submit'])) {
        $id_user = $_POST['id_user_pengelola'];

        $sqlinsertkandidat = 'INSERT INTO t_pengelola_lokasi
                            (id_lokasi, id_user)
                            VALUES (:id_lokasi, :id_user)';
        $stmt = $pdo->prepare($sqlinsertkandidat);
        $stmt->execute(['id_lokasi' => $id_lokasi, 'id_user' => $id_user]);
        header("Refresh: 0");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Lokasi - GoKarang</title>
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
                        <a class="btn btn-outline-primary" href="kelola_lokasi.php">< Kembali</a><br><br>
                         <h3>Atur Pengelola Lokasi</h3>
                    </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                  <label for="nama_wilayah" class="text-lg"><?=$rowitem->nama_lokasi?></label>
                <div class="terumbu-karang form-group">


              <form method="POST" action="" enctype="multipart/form-data" onSubmit="window.location.reload()">
                      <label class="text-muted text-sm d-block">Pengelola Lokasi bertugas menangani donasi, wisata, dan memasukkan data luas titik</label>
                      <div class="col">
                                <div class="mt-3">
                        <label for="dd_id_wilayah ">User Kandidat</label>
                                </div>
                        <select id="dd_id_wilayah" name="id_user_pengelola" class="form-control" required>
                          <option value="">-- Pilih Calon Pengelola --</option>
                            <?php foreach ($rowkandidat as $rowitem) {
                            ?>
                            <option value="<?=$rowitem->id_user?>">ID <?=$rowitem->id_user?> - <?=$rowitem->nama_user?> - <?=$rowitem->organisasi_user?></option>

                            <?php } ?>
                        </select>
                   <div class="text-center">
                        <button type="submit" name="submit" value="Simpan" class="btn btn-blue btn btn-primary mt-2 mb-2 text-center"><i class="fas fa-plus"></i> Tambah Pengelola</button>
                   </div>

              </form>



                    <table class="table table-striped table-responsive-sm">
                    <thead>
                            <tr>
                            <th scope="col">ID User</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Organisasi</th>
                            <th class="" scope="col">Aksi</th>
                            </tr>
                        </thead>
                    <tbody id="tbody-append">
                        <?php foreach ($rowpwilayah as $rowuser) { ?>
                            <tr>
                            <th scope="row">
                              <?=$rowuser->id_user?>
                          </th>
                            <td><?=$rowuser->nama_user?></td>
                            <td>-</td>
                            <td><?=$rowuser->organisasi_user?></td>
                            <td class="">
                                <a href="hapus.php?type=user_p_lokasi&id_lokasi=<?=$id_lokasi?>&id_user=<?=$rowuser->id_user?>" class="far fa-trash-alt btn btn-act"></a>
                                </td>
                            </tr>
                           <?php } ?>
                    </tbody>
                  </table>



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
