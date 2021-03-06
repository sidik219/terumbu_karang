<?php include 'build/config/connection.php';
session_start();

if (isset($_POST['login'])) {
    $username   = $_POST['tbusername'];
    $password   = $_POST['tbpassword'];

    $sql  = "SELECT username, password, id_user, level_user FROM t_user WHERE username=:username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $row = $stmt->fetch();

    if (!empty($row)) {
        if (password_verify($password, $row->password)) {
            if ($row->level_user == "1") {
                $_SESSION['id_user']        = $row->id_user;
                $_SESSION['username']        = $row->username;
                $_SESSION['level_user']     = $row->level_user;
                header('Location: dashboard_user.php?pesan=login_berhasil');

            } elseif ($row->level_user == "2") {
              $sql  = "SELECT id_wilayah FROM t_pengelola_wilayah WHERE id_user=:id_user";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(['id_user' => $row->id_user]);
              $rowkelolawilayah = $stmt->fetch();
              if($stmt->rowCount() != 0){

                $_SESSION['id_wilayah_dikelola']     = $rowkelolawilayah->id_wilayah;
                $_SESSION['id_user']        = $row->id_user;
                $_SESSION['username']        = $row->username;
                $_SESSION['level_user']     = $row->level_user;
                header('Location: dashboard_admin.php?pesan=login_berhasil_w&id_wilayah='.$rowkelolawilayah->id_wilayah);
              }
              else{
                header('Location: login.php?pesan=akun_belum_diberi_akses');
              }
            }
            elseif ($row->level_user == "3") {
              $sql  = "SELECT id_lokasi FROM t_pengelola_lokasi WHERE id_user=:id_user";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(['id_user' => $row->id_user]);
              $rowkelolalokasi = $stmt->fetch();
              if($stmt->rowCount() != 0){
                $_SESSION['id_user']        = $row->id_user;
                $_SESSION['username']        = $row->username;
                $_SESSION['level_user']     = $row->level_user;
                $_SESSION['id_lokasi_dikelola']     = $rowkelolalokasi->id_lokasi;
                header('Location: dashboard_admin.php?pesan=login_berhasil_l&id_lokasi='.$rowkelolalokasi->id_lokasi);
              }
              else{
                header('Location: login.php?pesan=akun_belum_diberi_akses');
              }

            }
            elseif ($row->level_user == "4") {
                $_SESSION['id_user']        = $row->id_user;
                $_SESSION['username']        = $row->username;
                $_SESSION['level_user']     = $row->level_user;
                header('Location: dashboard_admin_pusat.php?pesan=login_berhasil_p');

            }
            else {
                header('location: login.php?pesan=gagal_login_session');
            }
        } else {
            header('location: login.php?pesan=gagal_login');
        }
    } else {
        header('location: login.php?pesan=username_atau_password_salah');
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/konten.css">
    <!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <!--===============================================================================================-->
        <!-- <link rel="stylesheet" type="text/css" href="css/login.css"> -->
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <title>Login - GoKarang</title>
    <!-- Favicon -->
    <link rel="icon" href="dist/img/gokarang_coral_favicon.png" type="image/x-icon" />
</head>
<body>

    <!-- END OF NAVBAR -->
    <div class="login-jumbotron">
        <div class="limiter">
    		<div class="container-login100">

        		<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50">
                    <div class="login100-form-back"> <a href="index.php">< Kembali   </a></div>
    				<form action="" method="POST" class="login100-form validate-form">

    					<span class="login100-form-title p-b-33">
                            <img class="logo-img" src="dist/img/gokarang_logo_complete.png">
              </span>
              <?php
                if(!empty($_GET['pesan'])){
                  if($_GET['pesan'] == 'registrasi_berhasil'){
                  echo '<div class="alert alert-success" role="alert">
                          Pendaftaran berhasil! Silahkan Log In.
                      </div>';
                    }
                  else if($_GET['pesan'] == 'akun_belum_diberi_akses'){
                  echo '<div class="alert alert-primary" role="alert">
                          Akun anda dalam tahap verifikasi oleh Pengelola Pusat. Harap tunggu beberapa saat. Terima kasih.
                      </div>';
                    }
                  else{
                    {
                  echo '<div class="alert alert-warning" role="alert">
                          Username atau password salah.
                      </div>';}
                  }
                  }
                ?>

    					<div class="wrap-input100 validate-input">
    						<input class="input100" type="text" name="tbusername" placeholder="Akun Saya">
    						<span class="focus-input100-1"></span>
    						<span class="focus-input100-2"></span>
    					</div>

    					<div class="wrap-input100 rs1 validate-input" data-validate="Password is required">
    						<input class="input100" type="password" name="tbpassword" placeholder="Password">
    						<span class="focus-input100-1"></span>
    						<span class="focus-input100-2"></span>
    					</div>

    					<div class="container-login100-form-btn m-t-20">
    						<button type="submit" name="login" class="login100-form-btn">
    							Login
    						</button>
              </div>


                        <br>
    					<div class="text-center">
    						<span class="txt1">
    							Belum punya akun donatur?
    						</span>

    						<a href="register.php" class="txt2 hov1">
    							Daftar Donatur
                </a><br><br>
                <span class="txt1">
    							Calon pengelola Wilayah/Lokasi?
    						</span>
                <a href="register_pengelola.php" class="txt2 hov1">
    							Daftar Pengelola
                </a><br>
                <span class="txt1">
    							Pengelola Pusat/Provinsi ?
    						</span>
                <a href="register_pusat.php" class="txt2 hov1">
    							Daftar Pusat
    						</a>
    					</div>
    				</form>
        		</div>

            </div>
        </div>
    </div>

    <!-- JS Bootstrap -->


    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
