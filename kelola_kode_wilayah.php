<?php include 'build/config/connection.php';
session_start();
if(!($_SESSION['level_user'] == 2 || $_SESSION['level_user'] == 4)){
  header('location: login.php?status=restrictedaccess');
}
$url_sekarang = basename(__FILE__);
include 'hak_akses.php';



    $sqlwilayah = 'SELECT * FROM t_kode_wilayah
                   ';
    $stmt = $pdo->prepare($sqlwilayah);
    $stmt->execute();
    $rowwilayah = $stmt->fetchAll();


    if (isset($_POST['submit'])) {
            if($_POST['submit'] == 'Simpan'){
                if(count(array_unique($_POST['kode_lokasi'])) != count($_POST['kode_lokasi']))
                    {
                        header("Location: kelola_kode_wilayah.php?status=kode_duplikat");
                        return 0;   
                }else{
                        $pdo->prepare('TRUNCATE TABLE t_kode_wilayah')->execute(); //Kosongin t_kode_wilayah
                        $pdo->prepare('TRUNCATE TABLE t_kode_lokasi')->execute(); //Kosongin t_kode_lokasi

                $i = 0;
                $x = 0;
                $j = 0;
                foreach($_POST['kode_wilayah'] as $kode_wilayah){ 
                  $nama_wilayah = $_POST['nama_wilayah'][$i]; 
                  $kode_wilayah = $_POST['kode_wilayah'][$i]; 
                  $jumlahlokasiwilayah = $_POST['jumlahlokasiwilayah'][$i]; 

                  $sqlinsertwilayah = "INSERT INTO t_kode_wilayah 
                            (kode_wilayah, nama_wilayah)
                            VALUES (:kode_wilayah, :nama_wilayah)";

                  $stmt = $pdo->prepare($sqlinsertwilayah);
                  $stmt->execute(['kode_wilayah' => $kode_wilayah, 'nama_wilayah' => $nama_wilayah]);

                  foreach($_POST['kode_lokasi'] as $kode_lokasi){

                    $nama_lokasi = $_POST['nama_lokasi'][$x]; 
                    $kode_lokasi = $_POST['kode_lokasi'][$x]; 
                    

                    $sqlinsertlokasi = "INSERT INTO t_kode_lokasi 
                                (kode_lokasi, kode_wilayah, nama_lokasi)
                                VALUES (:kode_lokasi, :kode_wilayah, :nama_lokasi)";

                    $stmt = $pdo->prepare($sqlinsertlokasi);
                    $stmt->execute(['kode_lokasi' => $kode_lokasi, 'kode_wilayah' => $kode_wilayah, 'nama_lokasi' => $nama_lokasi]);
                    $x++; 
                    $j++;

                    if($j == $jumlahlokasiwilayah){ 
                        $j = 0;
                        break;
                    }
                }

                  $i++; 
                }

                header("Location: kelola_kode_wilayah.php?status=updatesuccess");
            }
                }

                
                
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Kode Wilayah & Lokasi - GoKarang</title>
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
                        
                         <h4>Kelola Kode Wilayah & Lokasi</h4>
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
            } else if ($_GET['status'] == 'kode_duplikat') {
              echo '<div class="alert alert-warning" role="alert">
                          Data Kode tidak boleh ada duplikat! Harap input data kembali.
                      </div>';
            
          }
        }
          ?>
                        
                        <p align="center">
                        <button onclick="event.preventDefault(); tambahWilayah();" class="btn btn-blue btn-primary mb-4"><i class="fas fa-plus"></i> Tambah Wilayah</button>
                        </p>
                  
                    <form action="" onsubmit="return cekDuplikat()" id="container-table" enctype="multipart/form-data" method="POST" name="updateWilayah">
                        <?php 
                            foreach($rowwilayah as $wilayah){
                                $sqllokasi = 'SELECT * FROM t_kode_lokasi WHERE kode_wilayah = :kode_wilayah';
                                $stmt = $pdo->prepare($sqllokasi);
                                $stmt->execute(['kode_wilayah' => $wilayah->kode_wilayah]);
                                $rowlokasi = $stmt->fetchAll();
                        ?>
            <div id="rowwilayah<?=$wilayah->kode_wilayah?>">
             <table class="table table-striped table-bordered DataWilayah">
                    <thead>
                            <tr class="table-active">
                                <th class="text-center align-middle" >Nama Wilayah: <input class="cek form-control-sm" type="text" value="<?= $wilayah->nama_wilayah ?>" name="nama_wilayah[]" required></th>
                                <th class="text-center">Kode Wilayah: <input class="cek form-control-sm" type="text" value="<?=$wilayah->kode_wilayah?>" name="kode_wilayah[]" required>
                            <button onclick="konfirmasiHapusWilayah(event, this)" class="ml-2 btn text-cyan"><i class="fas fa-times"></i></button></th>
                              <input type="hidden" id="jumlah<?=$wilayah->kode_wilayah?>" value="<?= count($rowlokasi) ?>" name="jumlahlokasiwilayah[]" ><tr>
                                <th class="text-center" scope="col">Nama Lokasi</th><th class="text-center" scope="col">Kode Lokasi</th>
                              </tr>
                            </tr>
                      </thead>

                <tbody  id="rowlokasi<?=$wilayah->kode_wilayah?>" class="table-hover">

                <?php                

                    foreach ($rowlokasi as $lokasi) {                  
                ?>
                        <tr>
                            <td class="text-center"><input class="cek form-control-sm text-center" type="text" value="<?=$lokasi->nama_lokasi?>" name="nama_lokasi[]" required></td>
                            <td class="text-center"><input class=" cek form-control-sm text-center" type="text" value="<?=$lokasi->kode_lokasi?>" name="kode_lokasi[]" required> 
                                                    <button onclick="konfirmasiHapusLokasi(event, this)" class="ml-2 btn text-cyan"><i class="fas fa-times"></i></button></td>
                        </tr>
                <?php }
                    
                ?>
                        
                </tbody>
                
                </table>
                    
                <?php
                        echo '<tr>
                            <p align="center">
                            <button onclick="event.preventDefault(); tambahLokasi(\'rowlokasi'.$wilayah->kode_wilayah.'\', \'jumlah'.$wilayah->kode_wilayah.'\');" class="btn btn-sm btn-outline-primary mb-5"><i class="fas fa-plus"></i> Tambah Lokasi</button>
                            </p>
                            </tr>
                        </div>';
            
                } ?>

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

    <script>
        function tambahLokasi(id_target, id_jumlah){        
            var lokasibaru = `<tr>
                            <td class="text-center"><input class="cek form-control-sm text-center" type="text" value="" name="nama_lokasi[]" required></td>
                            <td class="text-center"><input class="cek form-control-sm text-center" type="text" value="" name="kode_lokasi[]" required>
                            <button onclick="event.preventDefault(); (()=>$(this).parent().parent().remove())(); $('#${id_jumlah}').val(parseInt($('#${id_jumlah}').val()) - 1);" class="ml-2 btn text-cyan"><i class="fas fa-times"></i></button></td>
                        </tr>`;
            $(lokasibaru).appendTo('#'+id_target);
            $('#'+id_jumlah).val(parseInt($('#'+id_jumlah).val()) + 1)
        }



        function tambahWilayah(){
            var random_id = Math.random().toString(36).substr(2, 5);        
            var wilayahbaru = `
            <div id="rowwilayah${random_id}">
             <table class="table table-striped table-bordered DataWilayah">
                    <thead>
                            <tr class="table-active">
                                <th class="text-center align-middle" >Nama Wilayah: <input class="cek form-control-sm" type="text"  name="nama_wilayah[]" required></th>
                                <th class="text-center">Kode Wilayah: <input class="cek form-control-sm" type="text"  name="kode_wilayah[]" required><input type="hidden" id="jumlah${random_id}" value="1" name="jumlahlokasiwilayah[]" >
                            <button onclick="event.preventDefault(); (()=>$('#rowwilayah${random_id}').remove())();" class="ml-2 btn text-cyan"><i class="fas fa-times"></i></button></th>
                              <tr>
                                <th class="text-center" scope="col">Nama Lokasi</th><th class="text-center" scope="col">Kode Lokasi</th>
                              </tr>
                            </tr>
                      </thead>

                <tbody  id="rowlokasi${random_id}" class="table-hover">

                        <tr>
                            <td class="text-center"><input class="cek form-control-sm text-center" type="text"  name="nama_lokasi[]" required></td>
                            <td class="text-center"><input class="cek form-control-sm text-center" type="text"  name="kode_lokasi[]" required> 
                                                    <button onclick="event.preventDefault(); (()=>$(this).parent().parent().remove())(); $('#${random_id}').val(parseInt($('#${random_id}').val()) - 1);" class="ml-2 btn text-cyan"><i class="fas fa-times"></i></button></td>
                        </tr>
                        
                </tbody>
                
                </table>
                    
                
                            <p align="center">
                                <button onclick="event.preventDefault(); tambahLokasi('rowlokasi${random_id}', 'jumlah${random_id}');" class="btn btn-sm btn-outline-primary mb-5"><i class="fas fa-plus"></i> Tambah Lokasi</button>
                            </p>
                            
                        </div>;
            
            
            
            `;
            $(wilayahbaru).prependTo('#container-table');
        }


        function cekDuplikat(){
            var inputs=[], flag=false;
            $('.cek').each(function(){
                if ($.inArray(this.value, inputs) != -1) flag=true ;
                inputs.push(this.value);
            });

            if (flag==true) {
                alert('Kode tidak boleh ada duplikat!')
                console.log(inputs)
                return false;
            }
        }


        function konfirmasiHapusWilayah(event, elementTarget) {
        jawab = true
        jawab = confirm('Anda yakin ingin menghapus Wilayah?')

        if (jawab) {
            event.preventDefault()
            // alert('Lanjut.')
            elementTarget.parentNode.parentNode.parentNode.parentNode.parentNode.remove();
            return true
        } else {
            event.preventDefault()
            return false

        }
    }

    function konfirmasiHapusLokasi(event, elementTarget) {
        jawab = true
        jawab = confirm('Anda yakin ingin menghapus Lokasi?')

        if (jawab) {
            event.preventDefault()
            // alert('Lanjut.')
            $(elementTarget).parent().parent().remove(); $('#jumlah<?=$wilayah->kode_wilayah?>').val(parseInt($('#jumlah<?=$wilayah->kode_wilayah?>').val()) - 1);
            return true
        } else {
            event.preventDefault()
            return false

        }
    }
    </script>

</body>
</html>