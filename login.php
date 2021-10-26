<?php include 'build/config/connection.php';
session_start();

if (isset($_POST['login'])) {
    $username   = $_POST['tbusername'];
    $password   = $_POST['tbpassword'];

    $_SESSION['tbpassword']   = $_POST['tbpassword'];

    // Development Key
    // $private_key = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; 

    // Production Key
    $private_key = '6LdkN-scAAAAAJe6U4jxES3zPr1oEm9-cldHM2XD'; 

    $g_recaptcha_response = $_POST['g-recaptcha-response'];
    $user_ip =  $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$private_key&response=$g_recaptcha_response&remoteip=$user_ip";

    // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 

    $response = json_decode($output);

    if($response->success == "true"){
            $sql  = "SELECT username, password, id_user, level_user, email, nama_user, aktivasi_user, organisasi_user FROM t_user WHERE username=:username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username]);
            $row = $stmt->fetch();

            if (!empty($row)) {        
                if (password_verify($password, $row->password)) {          
                  if($row->aktivasi_user != 1){
                      header('Location: login.php?pesan=belum_konfirmasi_email');
                      return 0;
                }
                    if ($row->level_user == "1") {
                        $_SESSION['id_user']        = $row->id_user;
                        $_SESSION['username']        = $row->username;
                        $_SESSION['level_user']     = $row->level_user;
                        $_SESSION['email']     = $row->email;
                        $_SESSION['nama_user']     = $row->nama_user;

                        if (isset($_SESSION['id_paket_wisata_pilihan_redireksi'])){
                          header('Location: detail_lokasi_wisata.php?id_paket_wisata='.$_SESSION['id_paket_wisata_pilihan_redireksi']);                             
                        }else{
                          header('Location: dashboard_user.php?pesan=login_berhasil');
                        }
                        

                    } elseif ($row->level_user == "2") {
                      $sql  = "SELECT t_pengelola_wilayah.id_wilayah, nama_wilayah FROM t_pengelola_wilayah 
                                LEFT JOIN t_wilayah ON t_wilayah.id_wilayah = t_pengelola_wilayah.id_wilayah
                                WHERE id_user=:id_user";
                      $stmt = $pdo->prepare($sql);
                      $stmt->execute(['id_user' => $row->id_user]);
                      $rowkelolawilayah = $stmt->fetch();
                      if($stmt->rowCount() != 0){

                        $_SESSION['id_wilayah_dikelola']     = $rowkelolawilayah->id_wilayah;
                        $_SESSION['id_user']        = $row->id_user;
                        $_SESSION['username']        = $row->username;

                        $_SESSION['nama_wilayah_dikelola']        = $rowkelolawilayah->nama_wilayah;    
                        $_SESSION['nama_user']        = $row->nama_user;
                        $_SESSION['organisasi_user']        = $row->organisasi_user;


                        $_SESSION['level_user']     = $row->level_user;
                        header('Location: dashboard_admin.php?pesan=login_berhasil_w&id_wilayah='.$rowkelolawilayah->id_wilayah);
                      }
                      else{
                        header('Location: login.php?pesan=akun_belum_diberi_akses');
                      }
                    }
                    elseif ($row->level_user == "3") {
                      $sql  = "SELECT t_pengelola_lokasi.id_lokasi, nama_lokasi FROM t_pengelola_lokasi 
                              LEFT JOIN t_lokasi ON t_lokasi.id_lokasi = t_pengelola_lokasi.id_lokasi
                              WHERE id_user=:id_user";
                      $stmt = $pdo->prepare($sql);
                      $stmt->execute(['id_user' => $row->id_user]);
                      $rowkelolalokasi = $stmt->fetch();
                      if($stmt->rowCount() != 0){
                        $_SESSION['id_user']        = $row->id_user;
                        $_SESSION['username']        = $row->username;

                        $_SESSION['nama_lokasi_dikelola']        = $rowkelolalokasi->nama_lokasi;                
                        $_SESSION['nama_user']        = $row->nama_user;
                        $_SESSION['organisasi_user']        = $row->organisasi_user;


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
                        $_SESSION['nama_user']        = $row->nama_user;
                        $_SESSION['organisasi_user']        = $row->organisasi_user;
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
                header('location: login.php?pesan=username_atau_password_salah&username='.$_POST['tbusername']);
            }
        } else {
                header('location: login.php?pesan=recaptcha_invalid&username='.$_POST['tbusername']);
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
                          Pendaftaran berhasil! Harap konfirmasi email Anda untuk melanjutkan.
                      </div>';
                    }
                  else if($_GET['pesan'] == 'akun_belum_diberi_akses'){
                  echo '<div class="alert alert-primary" role="alert">
                          Akun Anda dalam tahap verifikasi oleh Pengelola Pusat. Harap tunggu beberapa saat. Terima kasih.
                      </div>';
                    }
                    else if ($_GET['pesan'] == 'belum_konfirmasi_email'){
                      echo '<div class="alert alert-primary" role="alert">
                          Harap konfirmasi email Anda terlebih dahulu. Terima kasih.
                      </div>';
                    }
                    else if ($_GET['pesan'] == 'aktivasi_berhasil'){
                      echo '<div class="alert alert-success" role="alert">
                          Konfirmasi Email berhasil! Silahkan Log In. Terima kasih.
                      </div>';
                    }
                    else if ($_GET['pesan'] == 'request_password_reset_berhasil'){
                      echo '<div class="alert alert-success" role="alert">
                          Email konfirmasi Reset Password telah dikirim. Terima kasih.
                      </div>';
                    }
                    else if ($_GET['pesan'] == 'reset_password_berhasil'){
                      echo '<div class="alert alert-success" role="alert">
                          Password Anda berhasil diperbarui. Silahkan Log In.
                      </div>';
                    }else if ($_GET['pesan'] == 'recaptcha_invalid'){
                      echo '<div class="alert alert-warning" role="alert">
                          Harap verifikasi reCAPTCHA dahulu.
                      </div>';
                    }                    
                    else{                    
                      echo '<div class="alert alert-warning" role="alert">
                          Username atau password salah.
                      </div>';
                  }
                }

                if (isset($_SESSION['id_paket_wisata_pilihan_redireksi']))  {
                      echo '<div class="alert alert-info" role="alert">
                          Untuk melanjutkan reservasi wisata anda, harap melakukan <u><a href="register.php" class="txt2 hov1">Pendaftaran Akun</a></u> atau Log In
                      </div>';
                    }
                ?>

    					<div class="wrap-input100 validate-input">
    						<input class="input100" type="text" name="tbusername" value="<?= isset($_GET['tbusername']) ? $_GET['tbusername'] : '' ?>" placeholder="Akun Saya">
    						<span class="focus-input100-1"></span>
    						<span class="focus-input100-2"></span>
    					</div>

    					<div class="wrap-input100 rs1 validate-input" data-validate="Password is required">
    						<input class="input100" type="password" value="<?= isset($_SESSION['tbpassword']) ? $_SESSION['tbpassword'] : '' ?>" name="tbpassword" placeholder="Password">
    						<span class="focus-input100-1"></span>
    						<span class="focus-input100-2"></span>
    					</div>

                <!-- Development -->
                 <!-- <div class="g-recaptcha m-t-10" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div> -->


                 <!-- Production/Deployment -->
                 <div class="g-recaptcha m-t-10" data-sitekey="6LdkN-scAAAAACZN1oYKVUoHZQ3JERvzB_sAFs_Q"></div>
            
 
    					<div class="container-login100-form-btn m-t-10">
    						<button type="submit" name="login" class="login100-form-btn">
    							Log In
    						</button>
              </div>


                        <br>
    					<div class="text-center">
              <span class="txt1">
    							Lupa password?
    						</span>
                <a href="request_password_reset.php" class="txt2 hov1">
    							Reset Password
                </a><br><br>

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
