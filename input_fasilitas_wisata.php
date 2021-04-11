<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';

$sqlviewfasilitas = 'SELECT * FROM tb_fasilitas_wisata
                  ORDER BY id_fasilitas_wisata DESC';
$stmt = $pdo->prepare($sqlviewfasilitas);
$stmt->execute();
$rowfasilitas = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Simpan') {
        //var_dump($_POST['nama_fasilitas']);var_dump($_POST['biaya_fasilitas']);exit();
        $i = 0;
        foreach ($_POST['nama_fasilitas'] as $nama_fasilitas) {
            $nama_fasilitas    = $_POST['nama_fasilitas'][$i];
            $biaya_fasilitas   = $_POST['biaya_fasilitas'][$i];

            $sqlinsertfasilitas = "INSERT INTO tb_fasilitas_wisata (nama_fasilitas, biaya_fasilitas)
                                        VALUES (:nama_fasilitas, :biaya_fasilitas)";

            $stmt = $pdo->prepare($sqlinsertfasilitas);
            $stmt->execute(['nama_fasilitas' => $nama_fasilitas,
                            'biaya_fasilitas' => $biaya_fasilitas
                            ]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
                header("Location: input_fasilitas_wisata.php?status=insertfailed");
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: input_fasilitas_wisata.php?status=addsuccess");
            }
            $i++;
        } //End Foreach
    } else {
        echo '<script>alert("Harap pilih paket wisata yang akan ditambahkan")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Wisata - TKJB</title>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="js/trumbowyg/dist/ui/trumbowyg.min.css">
    <script src="js/trumbowyg/dist/trumbowyg.js"></script>
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
                    <a class="btn btn-outline-primary" href="kelola_wisata.php">< Kembali</a><br><br>
                    <h4><span class="align-middle font-weight-bold">Data Fasilitas Wisata</span></h4>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div align="right">
                    <a class="btn btn-outline-primary" href="input_wisata.php">
                    Selanjutnya Input Wisata <i class="fas fa-angle-right"></i></a>
                    </div>

                    <table class="table table-striped table-responsive-sm">
                     <thead>
                            <tr>
                            <th scope="col">ID Fasilitas</th>
                            <th scope="col">Nama Fasilitas</th>
                            <th scope="col">Biaya Fasilitas</th>
                            <th scope="col">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php foreach ($rowfasilitas as $fasilitas) { ?>
                            <tr>
                              <th scope="row"><?=$fasilitas->id_fasilitas_wisata?></th>
                              <td><?=$fasilitas->nama_fasilitas?></td>
                              <td><?=$fasilitas->biaya_fasilitas?></td>
                              <td>
                                <a href="edit_wisata.php?id_wisata=<?=$fasilitas->id_fasilitas_wisata?>" class="fas fa-edit mr-3 btn btn-act"></a>
                                <a href="hapus.php?type=wisata&id_wisata=<?=$fasilitas->id_fasilitas_wisata?>" class="far fa-trash-alt btn btn-act"></a>
                              </td>
                            </tr>
                          <?php } ?>
                          </tbody>
                  </table>


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
<div>
    <!-- jQuery -->
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <!-- jQuery library -->

    <script>
        $(document).ready(function(){
        //group add limit
        var maxGroup = 50;

        //add more fields group
        $(".addMore").click(function(){
            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="form-group fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);
            }else{
                alert('Maksimal '+maxGroup+' group yang boleh dibuat.');
            }
        });

        //remove fields group
        $("body").on("click",".remove",function(){
            $(this).parents(".fieldGroup").remove();
        });
    });
    </script>
</div>
<!-- Import Trumbowyg font size JS at the end of <body>... -->
<script src="js/trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
</body>
</html>
