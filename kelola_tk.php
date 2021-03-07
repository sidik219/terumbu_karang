<?php include 'build/config/connection.php';
session_start();
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewtk = 'SELECT * FROM t_terumbu_karang
                LEFT JOIN t_jenis_terumbu_karang
                ON t_terumbu_karang.id_jenis = t_jenis_terumbu_karang.id_jenis';
$stmt = $pdo->prepare($sqlviewtk);
$stmt->execute();
$row = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Sub-Jenis Terumbu Karang - TKJB</title>
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
                            <h4><span class="align-middle font-weight-bold">Kelola Sub-jenis Terumbu Karang</span></h4>
                        </div>
                        <div class="col">

                        <a class="btn btn-primary float-right" href="input_tk.php" role="button">Input Data Baru (+)</a>

                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                      <?php
                if(!empty($_GET['status'])){
                  if($_GET['status'] == 'updatesuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Update data berhasil
                      </div>';}
                      else if($_GET['status'] == 'addsuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Data baru berhasil ditambahkan
                      </div>';}
                      else if($_GET['status'] == 'deletesuccess'){
                  echo '<div class="alert alert-success" role="alert">
                          Data berhasil dihapus
                      </div>';
                    }
                  }
                ?>

                <?php if($_SESSION['level_user'] == '2') { ?>
                    <table class="table table-striped">
                     <thead>

                            <tr>
                                <th scope="col">ID Terumbu Karang</th>
                                <th scope="col">ID Jenis</th>
                                <th scope="col">Nama Terumbu Karang</th>
                                <!-- <th scope="col">Deskripsi</th>
                                <th scope="col">Foto</th>
                                <th scope="col">Harga</th> -->
                                <th scope="col">Aksi</th>

                            </tr>
                          </thead>
                    <tbody>
                        <?php foreach ($row as $rowitem) {
                        ?>
                          <tr>
                              <th scope="row"><?=$rowitem->id_terumbu_karang?></th>
                              <td>ID <?=$rowitem->id_jenis?> - <?=$rowitem->nama_jenis?></td>
                              <td><?=$rowitem->nama_terumbu_karang?></td>
                              <td>
                              <a href="edit_tk.php?id_terumbu_karang=<?=$rowitem->id_terumbu_karang?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                <a href="hapus.php?type=terumbu_karang&id_terumbu_karang=<?=$rowitem->id_terumbu_karang?>" class="far fa-trash-alt btn btn-act"></a>
                              </td>
                          </tr>
                          <tr>
                                <td colspan="4">
                                    <!--collapse start -->
                            <div class="row  m-0">
                            <div class="col-12 cell detailcollapser<?=$rowitem->id_terumbu_karang?>"
                                data-toggle="collapse"
                                data-target=".cell<?=$rowitem->id_terumbu_karang?>, .contentall<?=$rowitem->id_terumbu_karang?>">
                                <p
                                    class="fielddetail<?=$rowitem->id_terumbu_karang?> btn btn-act">
                                    <i
                                        class="icon fas fa-chevron-down"></i>
                                    Rincian Terumbu Karang</p>
                            </div>
                            <div class="col-12 cell<?=$rowitem->id_terumbu_karang?> collapse contentall<?=$rowitem->id_terumbu_karang?> border rounded shadow-sm p-3">
                                <div class="row mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Deskripsi
                                    </div>
                                    <div class="col isi">
                                        <?=$rowitem->deskripsi_terumbu_karang?>
                                    </div>
                                </div>
                                <div class="row  mb-3">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Foto
                                    </div>
                                    <div class="col isi">
                                        <img src="<?=$rowitem->foto_terumbu_karang?>?<?php if ($status='nochange'){echo time();}?>" width="150px">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 kolom font-weight-bold">
                                        Harga
                                    </div>
                                    <div class="col isi">
                                        Rp. <?=$rowitem->harga_terumbu_karang?>
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

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

</body>
</html>
