<?php
include 'connection.php';
session_start();
$isLoggedIn = isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);

    function registrasi(){
                if ($isLoggedIn) {
            header("Location: dashboard.php");
            } 
            else {
                if (isset($_POST['submit'])) {
                $nama_user    = $_POST['tbnama_user'];
                $jk           = $_POST['optjk'];
                $email        = $_POST['tbemail'];
                $no_hp        = $_POST['tbno_hp'];
                $username     = $_POST['tbusername'];
                $no_ktp     = $_POST['tbno_ktp'];
                $password     = password_hash($_POST['tbpassword'], PASSWORD_DEFAULT);
                $tanggal_lahir = $_POST['tanggal_lahir'];            
                $alamat       = $_POST['txtalamat'];
                $level_user = 1;
                $aktivasi_user = 1;

                //Fotokopi KTP upload
                if (isset($_FILES['image_uploads1'])) {
                    $target_dir  = "images/ktp/";
                    $fotokopi_ktp = $target_dir . 'KTP_' .$id_user . '.jpg';
                    move_uploaded_file($_FILES["image_uploads1"]["tmp_name"], $fotokopi_ktp);
                }
                else if($_FILES["file"]["error"] == 4) {
                    $fotokopi_ktp = "images/ktpdefault.png";
                }
                //---Fotokopi KTP upload end

                 //Foto user upload
                if (isset($_FILES['image_uploads2'])) {
                    $target_dir  = "images/foto_user/";
                    $foto_user = $target_dir . 'FU_' .$id_user . '.jpg';
                    move_uploaded_file($_FILES["image_uploads2"]["tmp_name"], $foto_user);
                }
                else if($_FILES["file"]["error"] == 4) {
                    $foto_user = "images/fudefault.png";
                }
                //---Foto user upload end

                $sql = 'INSERT INTO t_user (nama_user, jk, email, no_hp, alamat, no_ktp, fotokopi_ktp, tanggal_lahir, foto_user, level_user, aktivasi_user, username, password )
                VALUES (:nama_user, :jk, :email, :no_hp, :alamat, :no_ktp, :fotokopi_ktp, :tanggal_lahir, :foto_user, :level_user, :aktivasi_user, :username, :password)';

                $stmt = $pdo->prepare($sql);
                $stmt->execute(['nama_user' => $nama_user, 'jk' => $jk, 'email' => $email, 'no_hp' => $no_hp, 'alamat' => $alamat, 'no_ktp' => $no_ktp, 'fotokopi_ktp' => $fotokopi_ktp, 'tanggal_lahir' => $tanggal_lahir, 'foto_user' => $foto_user, 'level_user' => $level_user, 'aktivasi_user' => $aktivasi_user, 'username' => $username, 'password' => $password]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                    echo "Failed !";
                } else {
                    header('Location: login.php?status=regsuccess');
                }
                } else {
                    echo '';
                }
            }
    }

    function login(){
        if ($isLoggedIn) {
            header("Location: dashboard.php");
            } else {
            $msg = '';
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'regsuccess') {
                $msg = "<div class='alert alert-success' role='alert'>
                        <strong>Registrasi berhasil.</strong> Silahkan Log in.
                        </div>";
                }
            }

            if (isset($_POST['submit'])) {
            $username = $_POST['tbusername'];
            $pwd      = $_POST['tbpassword'];

            $sql  = 'SELECT username, passwordd, id_user, level_user FROM t_user WHERE username = :username';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username]);
            $row = $stmt->fetch();

            if (!empty($row)) { // checks if the user actually exists(true/false returned)
            if (password_verify($pwd, $row->pwd)) {
                $_SESSION['id_user']        = $row->id_user;
                $_SESSION['level_user']     = $row->level_user;

                header('Location: dashboard.php');

                // password_verify success!
            } else {
                $msg = "<div class='alert alert-warning' role='alert'>
                    <strong>Username atau Password salah.</strong>
                    </div>";
            }
    }

    function viewMap(){
        
    }

    function addDonasi(){
        
    }

    function viewDonasi(){
        
    }

    function editDonasi(){
        
    }

    function addWilayah(){
        
    }

    function viewWilayah(){
        
    }

    function editWilayah(){
        
    }

    function addLokasi(){
        
    }

    function viewLokasi(){
        
    }

    function editLokasi(){
        
    }

    
    function addTitik(){
        
    }

    function viewTitik(){
        
    }

    function editTitik(){
        
    }

    
    function addBatch(){
        
    }

    function viewBatch(){
        
    }

    function editBatch(){
        
    }

    
    function addJenis(){
        
    }

    function viewJenis(){
        
    }

    function editJenis(){
        
    }

    
    function addTerumbu(){
        
    }

    function viewTerumbu(){
        
    }

    function editTerumbu(){
        
    }

    function addPerizinan(){
        
    }

    function viewPerizinan(){
        
    }

    function editPerizinan(){
        
    }

    function addInformasi(){
        
    }

    function viewInformasi(){
        
    }

    function editInformasi(){
        
    }

    function viewLaporan(){
        
    }

    function filterLaporan(){
        
    }

    function viewUser(){
        
    }

    function editUser(){
        
    }





?>






