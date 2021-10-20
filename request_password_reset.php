<?php include 'build/config/connection.php';
session_start();

if (isset($_POST['reset'])) {
    $email   = $_POST['email'];

    $sql  = "SELECT id_user, username, nama_user FROM t_user WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch();

    if (!empty($row)) {        
        $token_reset_password = substr(md5(rand()), 0, 32);
        $sql  = "UPDATE t_user SET token_reset_password = :token_reset_password WHERE id_user = :id_user";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(['token_reset_password' => $token_reset_password, 'id_user' => $row->id_user]);

          include 'includes/email_handler.php'; //PHPMailer
            $subjek = 'Reset Password Akun GoKarang';
            $pesan = '
                <img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                <br>Anda telah meminta reset password akun GoKarang Anda. Jika Anda tidak meminta ganti password, hiraukan email ini.
                <br>Username Anda: '.$row->username.'
                <br>Untuk melakukan reset password, klik link di bawah ini:
                <br><a href="https://tkjb.or.id/form_reset_password.php?token_reset_password='.$token_reset_password.'">Reset Password Anda</a>
            ';
            smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
            header('Location: login.php?pesan=request_password_reset_berhasil');
    } else {
        header('Location: request_password_reset.php?pesan=email_invalid');
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
                }
                ?>
              <h4 class="font-weight-bold text-center">Permintaan Reset Password</h4>
              <br><span class="text-sm text-muted">Konfirmasi reset password akan dikirimkan melalui email.</span>
    					<form action="" enctype="multipart/form-data" method="POST">              
                <div class="form-group mt-4">
                    <label for="tb_email" class="font-weight-bold">Email</label>
                    <input type="text" id="tb_email" name="email" class="form-control" required>
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
