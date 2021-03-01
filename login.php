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
                header('Location: dashboard_admin.php?pesan=login_berhasil');

            } elseif ($row->level_user == "2") {
                $_SESSION['id_user']        = $row->id_user;
                $_SESSION['username']        = $row->username;
                $_SESSION['level_user']     = $row->level_user;
                header('Location: dashboard_user.php?pesan=login_berhasil');

            } else {
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
    <title>Login - TKJB</title>
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
                            TKJB
              </span>
              <?php
                if(!empty($_GET['pesan'])){
                  if($_GET['pesan'] == 'registrasi_berhasil'){
                  echo '<div class="alert alert-success" role="alert">
                          Pendaftaran berhasil! Silahkan Log In.
                      </div>';}
                  }
                ?>

    					<div class="wrap-input100 validate-input">
    						<input class="input100" type="text" name="tbusername" placeholder="Username">
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
    							Belum punya akun ?
    						</span>

    						<a href="register.php" class="txt2 hov1">
    							Daftar Donatur
                </a><br><br>
                <span class="txt1">
    							Calon pengelola ?
    						</span>
                <a href="register_pengelola.php" class="txt2 hov1">
    							Daftar Pengelola
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
