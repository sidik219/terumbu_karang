<?php include 'build/config/connection.php';
session_start();

if (isset($_GET['token_reset_password'])) {
    $token_reset_password   = $_GET['token_reset_password'];

    $sql  = "SELECT * FROM t_user WHERE token_reset_password = :token_reset_password";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['token_reset_password' => $token_reset_password]);
    $rowuser = $stmt->fetch();

    if (empty($rowuser)){
        header('location: login.php?pesan=token_reset_invalid');
    }
}

if (isset($_POST['reset'])) {
    if($_POST['password'] != $_POST['password2']){
        header('location: form_reset_password.php?token_reset_password='.$token_reset_password.'&pesan=password_tidak_sama');
        return 0;
    }else{
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $token_reset_password = substr(md5(rand()), 0, 32);

        $sql  = "UPDATE t_user SET password = :password, token_reset_password = :token_reset_password  WHERE id_user = :id_user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['password' => $password, 'id_user' => $rowuser->id_user, 'token_reset_password' => $token_reset_password]);
        
        include 'includes/email_handler.php'; //PHPMailer
        $subjek = 'Password Akun Anda telah Diperbarui - GoKarang';
        $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
            <br>Password akun GoKarang Anda telah berhasil diperbarui.
            <br>Username Anda: '.$row->username.'
            <br>Anda dapat menggunakan password baru untuk log in:
            <br><a href="https://tkjb.or.id/login.php">Masuk ke GoKarang</a>
        ';
        smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
        header('Location: login.php?pesan=reset_password_berhasil');
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
    <title>Permintaan Reset Password - GoKarang</title>
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
                            <img width="50%" class="" src="dist/img/gokarang_logo_complete.png">
              </span>
              <?php
                if(!empty($_GET['pesan'])){
                  if($_GET['pesan'] == 'email_invalid')
                  {                    
                      echo '<div class="alert alert-warning" role="alert">
                          Email tidak terdaftar. Buat Akun baru <a href="register.php">Di sini</a>
                      </div>';
                  }
                  if($_GET['pesan'] == 'password_tidak_sama')
                  {                    
                      echo '<div class="alert alert-warning" role="alert">
                          Field Password Baru dan Ulang Password Baru tidak sama
                      </div>';
                  }
                }
                ?>
              <h4 class="font-weight-bold text-center">Form Reset Password</h4>
              <br><span class="text-sm text-muted">Harap simpan password baru Anda di tempat yang aman.</span>
    		<form action="" enctype="multipart/form-data" method="POST">              
                <div class="form-group mt-4">
                    <label for="tb_password" class="font-weight-bold">Password Baru</label>
                    <input type="password" id="tb_password" name="password" class="form-control" required>
                </div>
                <div class="form-group mt-4">
                    <label for="tb_password2" class="font-weight-bold">Ulang Password Baru</label>
                    <input type="password" id="tb_password2" name="password2" class="form-control" required>
                </div>
                <br>
                <p align="center">
                    <button type="submit" name="reset" class="btn login100-form-btn btn-submit">Reset Password</button>
                </p>
        </form>

    <!-- JS Bootstrap -->


    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
