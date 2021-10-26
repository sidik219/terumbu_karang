<?php include 'build/config/connection.php';

if (isset($_POST['register'])) {

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
    $nama_user    = $_POST['tb_nama_user'];
    $jk           = $_POST['rb_jenis_kelamin'];
    $email        = $_POST['tb_email'];
    $no_hp        = '+62' . substr($_POST['num_nomer_hp'], 1);
    $username     = $_POST['tb_username'];
    $no_ktp         = $_POST['num_ktp_user'];
    $password       = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
    $tanggal_lahir  = $_POST['date_tanggal_lahir'];
    $alamat         = $_POST['tb_alamat_user'];
    $randomstring = substr(md5(rand()), 0, 7);
    $level_user = $_POST['rb_level_user'];
    $aktivasi_user  = 0;
    if ($level_user == 4) {
        $aktivasi_user  = 1;
    }
    $organisasi_user = $_POST['tb_organisasi_user'];
    $token_aktivasi_user = substr(md5(rand()), 0, 32);

    $pasaman = $_POST['pws'];
    $usaman = $_POST['upass'];
    // var_dump($pasaman, $usaman);
    // die;
    //verifikasi pass lebih dari 6 kurang dari 8
    $lenghtpass = strlen($_POST['pwd']);
    // var_dump($lenghtpass);
    // die;
    if ($lenghtpass < 6 || $lenghtpass > 32 && $pasaman == "k") {
        header('location: register.php?pesan=Tidak_valid');
        return false;
    }

    //verivikasi username lebih dari 6 kurang dari 8
    $lenghtuser = strlen($_POST['tb_username']);
    if ($lenghtuser < 6 || $lenghtuser > 16 && $usaman == "k") {
        header('location: register.php?pesan=Tidak_valid');
        return false;
    }

    // Verifikasi Username Sudah terdaftar
    $result = mysqli_query($conn, "SELECT username FROM t_user WHERE username = '$username'");
    $result_email = mysqli_query($conn, "SELECT email FROM t_user WHERE email = '$email'");
    // var_dump($result);
    // die;
    // ini ga tau buat validasi email sama username ada di db malah kelewat
    // if (mysqli_fetch_assoc($result) && mysqli_fetch_assoc($result_email)) {
    //     header('location: register_pengelola.php?pesan=Username_Email_Telah_Terdaftar');
    // } else
    if (mysqli_fetch_assoc($result)) {
        header('location: register_pengelola.php?pesan=Username_Telah_Terdaftar');
    } else  if (mysqli_fetch_assoc($result_email)) {
        header('location: register_pengelola.php?pesan=Email_Telah_Terdaftar');
    } else {
        //Fotokopi KTP upload
        if (isset($_FILES['image_uploads1'])) {
            $target_dir     = "images/ktp/";
            $fotokopi_ktp = $target_dir . 'KTP_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads1"]["tmp_name"], $fotokopi_ktp);
        } else if ($_FILES["file"]["error"] == 4) {
            $fotokopi_ktp = "images/ktpdefault.png";
        }
        //---Fotokopi KTP upload end

        //Foto user upload
        if (isset($_FILES['image_uploads2'])) {
            $target_dir = "images/foto_user/";
            $foto_user = $target_dir . 'FU_' . $randomstring . '.jpg';
            move_uploaded_file($_FILES["image_uploads2"]["tmp_name"], $foto_user);
        } else if ($_FILES["file"]["error"] == 4) {
            $foto_user = "images/fudefault.png";
        }
        //---Foto user upload end

        $sql = 'INSERT INTO t_user (nama_user, organisasi_user, jk, email, no_hp, alamat, 
        no_ktp, fotokopi_ktp, tanggal_lahir, foto_user, level_user, aktivasi_user, username, password, token_aktivasi_user )
        VALUES (:nama_user, :organisasi_user, :jk, :email, :no_hp, :alamat, :no_ktp, :fotokopi_ktp, 
        :tanggal_lahir, :foto_user, :level_user, :aktivasi_user, :username, :password, :token_aktivasi_user)';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nama_user' => $nama_user, 'organisasi_user' => $organisasi_user, 'jk' => $jk, 'email' => $email,
            'no_hp' => $no_hp, 'alamat' => $alamat, 'no_ktp' => $no_ktp, 'fotokopi_ktp' => $fotokopi_ktp, 'tanggal_lahir' => $tanggal_lahir,
            'foto_user' => $foto_user, 'level_user' => $level_user,
            'aktivasi_user' => $aktivasi_user, 'username' => $username, 'password' => $password, 'token_aktivasi_user' => $token_aktivasi_user
        ]);

        $id_user_terakhir = $pdo->lastInsertId();

        $affectedrows = $stmt->rowCount();
        if ($affectedrows == '0') {
            echo "Failed !";
        } else {
            if ($level_user == 2) {
                $tingkat_kelola = 'Wilayah';
            }
            if ($level_user == 3) {
                $tingkat_kelola = 'Lokasi';
            }

            //Email untuk calon pengelola wilayah/lokasi

            include 'includes/email_handler.php'; //PHPMailer
            $subjek = 'Konfirmasi Registrasi Akun Pengelola ' . $tingkat_kelola . ' GoKarang';
            $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                <br>Terima kasih telah mendaftar sebagai Pengelola ' . $tingkat_kelola . ' di GoKarang!
                <br>Username Anda adalah: ' . $username . '
                <br>Harap klik link di bawah agar akun Anda segera diverifikasi dan diberi hak akses oleh Administrator:
                <br><a href="https://tkjb.or.id/aktivasi_user.php?token_aktivasi_user=' . $token_aktivasi_user . '">Konfirmasi Akun Anda</a>
            ';
            smtpmailer($email, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);

            if ($level_user == 2) {
                //Query semua user pengelola pusat
                $sqluserpusat = 'SELECT email FROM t_user WHERE level_user=4';
                $stmt = $pdo->prepare($sqluserpusat);
                $stmt->execute();
                $rowuserpusat = $stmt->fetchAll();

                foreach ($rowuserpusat as $userpusat) {
                    //Email untuk pengelola pusat bahwa ada calon pengelola wilayah baru
                    $email_pusat = $userpusat->email;

                    $subjek = 'Verifikasi Registrasi Akun Pengelola ' . $tingkat_kelola . ' Baru - GoKarang';
                    $pesan = '<img width="150px" src="https://tkjb.or.id/images/gokarang.png"/>
                        <br>Akun Pengelola ' . $tingkat_kelola . ' dengan rincian:
                        <br>Nama: ' . $nama_user . '
                        <br>Organisasi: ' . $organisasi_user . '
                        <br>No. Handphone: ' . $no_hp . '
                        <br>Email: ' . $email . '
                        <br>Alamat: ' . $alamat . '
                        <br>Username: ' . $username . '
                        <br>telah mendaftarkan diri sebagai Pengelola Wilayah. 
                        <br>Jika akun ini dikenal dan valid, harap pilih wilayah yang akan diberikan hak pengelolaan di:
                        <br><a href="https://tkjb.or.id/kelola_wilayah.php">Atur Pengelola Wilayah</a>
                        <br>
                        <br>Jika akun calon pengelola tersebut dipastikan tidak dikenal, klik link berikut untuk menghapus akun tersebut:
                        <br><a href="https://tkjb.or.id/hapus.php?type=hapus_user_pengelola_wilayah_baru&id_user=' . $id_user_terakhir . '">Hapus Akun Pengelola Tidak Dikenal</a>
                    ';
                    smtpmailer($email_pusat, $pengirim, $nama_pengirim, $subjek, $pesan); // smtpmailer($to, $pengirim, $nama_pengirim, $subjek, $pesan);
                }
            }


            header('Location: login.php?pesan=registrasi_berhasil');
        }
    }
}
} else {
    echo '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Styles Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer felis neque, suscipit eget dolor quis, accumsan imperdiet elit. Praesent quis mauris eu quam malesuada auctor. Etiam vitae ante sapien. Sed mauris dui, varius non tempor in, semper fringilla ipsum. Phasellus nec purus enim. Nulla eget fringilla mi, id iaculis ante.">
    <meta name="author" content="">
    <link rel="icon" href="dist/img/KKPlogo.png">

    <title>GoKarang | Terumbu Karang Jawa Barat</title>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&family=Roboto&display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Custom CSS -->
    <link href="css/konten.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Favicon -->
    <link rel="icon" href="dist/img/gokarang_coral_favicon.png" type="image/x-icon" />


</head>


<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light  main-navigation fixed-top">
        <!-- LOGO HOLDER -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php"><img id=logo src="dist/img/gokarang_logo_complete.png"></a>
        </nav>
        <!-- END OF LOGO HOLDER -->

        <!-- MENU NAVBAR -->
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto ">
                <li class="nav-item ">
                    <a class="nav-link" href="index.php">Beranda </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="donasi.php">Donasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="wisata.php">Wisata</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="coralmaps.php">Coralmaps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="coralpedia.php">Coralpedia</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="login.php">Login <span class="sr-only">(current)</span></a>
                </li>
                <!-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Akun Saya</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Edit Profil</a>
                    <a class="dropdown-item" href="#">Logout</a>
                </li>  -->
            </ul>
        </div>
        <!-- END OF MENU NAVBAR -->
    </nav>
    <!-- END OF NAVBAR -->


    <div id="about" class="container reg-container p-5 rounded">

        <div class="starter-template p-0 mt-0">
            <h1><br>REGISTRASI AKUN PENGELOLA</h1><br>
        </div>
        <?php
        if (!empty($_GET['pesan'])) {
            if ($_GET['pesan'] == 'Username_Telah_Terdaftar') {
                echo '<div class="alert alert-warning" role="alert">
                          Username Sudah Terdaftar.
                      </div>';
            } else if ($_GET['pesan'] == 'Email_Telah_Terdaftar') {
                echo '<div class="alert alert-warning" role="alert">
                          Email Sudah Terdaftar. Lupa password? <a href="request_password_reset.php">Reset Password</a>
                      </div>';
            } else if ($_GET['pesan'] == 'Username_Email_Telah_Terdaftar') {
                echo '<div class="alert alert-warning" role="alert">
                          Username dan Email Sudah Terdaftar. Lupa password? <a href="request_password_reset.php">Reset Password</a>
                      </div>';
            } else if ($_GET['pesan'] == 'Tidak_valid') {
                echo '<div class="alert alert-warning" role="alert">
                          Username Atau Password Belum Sesuai</a>
                      </div>';
            }
        }
        ?>
        <form action="" enctype="multipart/form-data" method="POST" name="form1">
            <div class="form-group">
                <div class="form-group">
                    <label for="tb_nama_user" class="font-weight-bold">Nama Lengkap</label>
                    <input type="text" id="tb_nama_user" name="tb_nama_user" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tb_organisasi" class="font-weight-bold">Organisasi</label>
                    <input type="text" id="tb_organisasi" name="tb_organisasi_user" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="rb_jenis_kelamin">Cakupan Pengelolaan</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rb_wilayah" name="rb_level_user" value="2" class="form-check-input" required checked>
                        <label class="form-check-label" for="rb_wilayah">Wilayah (Kabupaten/Dinas)</label>
                    </div><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rb_lokasi" name="rb_level_user" value="3" class="form-check-input">
                        <label class="form-check-label" for="rb_lokasi">Lokasi (Pantai/Organisasi Masyarakat)</label>
                    </div>
                </div><br>
                <div class="form-group">
                    <label for="rb_jenis_kelamin" class="font-weight-bold">Jenis Kelamin</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rb_jenis_kelamin_pria" name="rb_jenis_kelamin" value="pria" class="form-check-input" checked>
                        <label class="form-check-label" for="rb_jenis_kelamin_pria">Pria</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rb_jenis_kelamin_wanita" name="rb_jenis_kelamin" value="wanita" class="form-check-input">
                        <label class="form-check-label" for="rb_jenis_kelamin_wanita">Wanita</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tb_email" class="font-weight-bold">Email</label>
                    <input type="text" id="tb_email" name="tb_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="num_nomer_hp" class="font-weight-bold">Nomor Handphone</label>
                    <input type="number" id="num_nomer_hp" name="num_nomer_hp" class="form-control" required placeholder="Format : 08123456789">
                    <!-- <p class="small">Masukan No Sesuai Format</p> -->
                    <!-- placeholder="Format No : 0812-1234-1234" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}" -->
                </div>
                <div class="form-group">
                    <label for="tb_alamat_user" class="font-weight-bold">Alamat</label>
                    <input type="text" id="tb_email" name="tb_alamat_user" class="form-control" required>
                </div>
                <div class="form-group d-none">
                    <label for="num_ktp_user" class="font-weight-bold">No. KTP</label>
                    <input type="number" id="num_ktp_user" name="num_ktp_user" class="form-control">
                </div>
                <div class="form-group d-none">
                    <label for="image_uploads1">Fotokopi KTP</label>
                    <div class="file-form">
                        <input type="file" id="image_uploads1" name="image_uploads1" class="form-control">
                    </div>
                </div>
                <div class="form-group d-none">
                    <label for="tb_tempat_lahir">Tempat Lahir</label>
                    <input type="text" id="tb_tempat_lahir" name="tb_tempat_lahir" class="form-control">
                </div>
                <div class="form-group">
                    <label for="date_tanggal_lahir" class="font-weight-bold">Tanggal Lahir</label>
                    <div class="file-form">
                        <input type="date" id="date_tanggal_lahir" name="date_tanggal_lahir" class="form-control">
                    </div>
                </div>
                <div class="form-group d-none">
                    <label for="image_uploads2">Foto Diri</label>
                    <div class="file-form">
                        <input type="file" id="image_uploads2" name="image_uploads2" class="form-control">
                    </div>
                </div><br>

                <div class="form-group">
                    <label for="tb_username" class="font-weight-bold">Username</label>
                    <input type="text" id="tb_username" name="tb_username" class="form-control" required onkeyup="allLetter(document.form1.tb_username)">
                    <input type="hidden" id="upass" name="upass">
                    <div class="small text-warning font-weight-bold" id="result" name="upass">Belum Sesuai &#10539;</div>
                    <div class="small" id="hint" name="upass">username berisi 6 hingga 16 karakter yang berisi huruf kecil, angka, atau underscore ( _ )</div>
                </div>
                <script>
                    function allLetter(uname) {
                        var letters = /^[a-z0-9_]{6,16}$/;
                        if (uname.value.match(letters)) {
                            document.getElementById('result').innerHTML = '<span class="text-success font-weight-bold">Sudah Sesuai &#10003;</span>'
                            $('#hint').fadeOut(500);


                            // return true;
                        } else {
                            document.getElementById('result').innerHTML = '<span class="text-warning font-weight-bold">Belum Sesuai &#10539;</span>'
                            document.getElementById('upass').value = "k";
                            $('#hint').fadeIn(500);
                            // return false;
                        }
                    }
                </script>
                <div class="form-group">
                    <label for="pwd" class="font-weight-bold">Password</label>
                    <input type="password" id="pwd" name="pwd" class="form-control" onkeyup="CheckPassword(document.form1.pwd);" required>
                    <input type="hidden" name="pws" id="pws">
                    <div class="small text-warning font-weight-bold" id="cpass" name="cpass">Belum Sesuai &#10539;</div>
                    <div class="small" id="hintpass" name="cpass">kata sandi berisi 6 hingga 32 karakter yang berisi setidaknya satu digit angka, satu huruf besar, dan satu huruf kecil</div>
                </div>
                <script>
                    function CheckPassword(inputtxt) {
                        var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,32}$/;
                        if (inputtxt.value.match(passw)) {
                            document.getElementById('cpass').innerHTML = '<span class="text-success font-weight-bold">Sudah Sesuai &#10003;</span>';
                            $('#hintpass').fadeOut(500);


                            // return true;
                        } else {
                            document.getElementById('cpass').innerHTML = '<span class="text-warning font-weight-bold">Belum Sesuai &#10539;</span>';
                            document.getElementById('pws').value = "k";
                            $('#hintpass').fadeIn(500);
                            // return false;
                        }
                    }
                </script>
                <div class="g-recaptcha m-t-10" data-sitekey="6LdkN-scAAAAACZN1oYKVUoHZQ3JERvzB_sAFs_Q"></div>
                <br>
                <br>
                <br>
                <p align="center">
                    <button type="submit" name="register" class="btn btn-submit">Daftar</button>
                </p>
        </form>

    </div>

    </div>
    <!-- END OF BODY CONTAINER -->

    <br><br>
    <section id="footer">
        <div class="container">
            <div class="row">
                <div class="blogo col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <!-- <a href="#"><img src="dist/img/logo.png" alt="Styles logo"></a> -->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <div class="cpt text-light text-center">
                        <p><a href="about_us.php" style="text-decoration: none !important; color:white; "><strong>Copyright &copy; <?= date("Y") ?> </strong> GoKarang</a></p>
                    </div>
                </div>

            </div>
        </div>
    </section>



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Scrollspy -->
    <script>
        $('body').scrollspy({
            target: '#navbarsExampleDefault',
            offset: 108
        })
    </script>
    <!-- Smooth Scroll -->
    <script src="js/smooth-scroll.js"></script>

    <!-- Number Counter -->
    <script src="js/nsc.js"></script>


</body>

</html>