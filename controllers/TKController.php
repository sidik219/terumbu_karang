<?php
include '..\build\config\connection.php';
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
            $password      = $_POST['tbpassword'];

            $sql  = 'SELECT username, passwordd, id_user, level_user FROM t_user WHERE username = :username';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username]);
            $row = $stmt->fetch();

            if (!empty($row)) {
                if (password_verify($password, $row->password)) {
                    $_SESSION['id_user']        = $row->id_user;
                    $_SESSION['level_user']     = $row->level_user;

                    header('Location: dashboard.php');
                } else {
                    $msg = "<div class='alert alert-warning' role='alert'>
                        <strong>Akun Saya atau Password salah.</strong>
                        </div>";
                    }
                }
            }
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
        // $isAdmin = $_SESSION['level_user'] == 2;

        // if (!$isLoggedIn) {
        //     header('Location: login.php');
        // }
        // else if (!$isAdmin) {
        //     header('Location: dashboard.php');
        // }
        // else{

    }

    function viewWilayah(){

    }


    function editWilayah(){

    }

    function deleteWilayah(){

    }


    function addLokasi(){

    }

    function viewLokasi(){

    }

    function editLokasi(){

    }

    function deleteLokasi(){

    }


    function addTitik(){

    }

    function viewTitik(){

    }

    function editTitik(){

    }

    function deleteTitik(){

    }

    function addBatch(){

    }

    function viewBatch(){

    }

    function editBatch(){

    }

    function deleteBatch(){

    }


    function addJenis(){

    }

    function viewJenis(){
    }

    function editJenis(){

    }

    function deleteJenis(){

    }



    function addTerumbu(){

    }

    function viewTerumbu(){
        $sqlviewterumbu_karang = 'SELECT * FROM t_terumbu_karang
                        ORDER BY nama_terumbu_karang';
        $stmt = $pdo->prepare($sqlviewterumbu_karang);
        $stmt->execute();
        $row = $stmt->fetchAll();
    }

    function editTerumbu(){

    }

    function deleteTerumbu(){

    }






    function addPerizinan(){
        $isAdmin = $_SESSION['level_user'] == 2;

        if (!$isLoggedIn) {
            header('Location: login.php');
        }
        else if (!$isAdmin) {
            header('Location: dashboard.php');
        }
        else{
        if (isset($_POST['submit'])) {
            $judul_perizinan        = $_POST['tbjudul_perizinan'];
            $id_user        = $_POST['id_user'];
            $id_lokasi        = $_POST['id_lokasi'];
            $biaya_pergantian        = $_POST['tbbiaya_pergantian'];
            $status_perizinan        = 1;
            $deskripsi_perizinan        = $_POST['tbdeskripsi_perizinan'];
            $randomstring = substr(md5(rand()), 0, 7);

            //Image upload
            if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_perizinan/";
            $file_proposal = $target_dir .'FIZIN_'.$randomstring. '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $file_proposal);
            }
            else if($_FILES["file"]["error"] == 4) {
                $file_proposal = "images/fizdefault.png";
            }
            //---image upload end

            $sqlperizinan = "INSERT INTO t_perizinan
                            (judul_perizinan, id_user, id_lokasi, deskripsi_perizinan, file_proposal, biaya_pergantian, status_perizinan)
                            VALUES (:judul_perizinan, :id_user, :id_lokasi, :deskripsi_perizinan, :file_proposal, :biaya_pergantian, :status_perizinan)";

            $stmt = $pdo->prepare($sqlperizinan);
            $stmt->execute(['judul_perizinan' => $judul_perizinan,'id_user' => $id_user, 'id_lokasi' => $id_lokasi, 'deskripsi_perizinan' => $deskripsi_perizinan, 'file_proposal' => $file_proposal
            , 'biaya_pergantian' => $biaya_pergantian, 'status_perizinan' => $status_perizinan]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_perizinan.php?status=addsuccess");
                }
            }
        }
    }

    function viewPerizinan(){
        $sqlviewperizinan = 'SELECT * FROM t_perizinan
                        ORDER BY id_perizinan';
        $stmt = $pdo->prepare($sqlviewperizinan);
        $stmt->execute();
        $row = $stmt->fetchAll();
    }

    function editPerizinan(){
        if (isset($_POST['submit'])) {
            if ($_POST['submit'] == 'Simpan') {
                $judul_perizinan        = $_POST['tbnjudul_perizinan'];
                $id_user        = $_POST['id_user'];
                $id_lokasi        = $_POST['id_lokasi'];
                $biaya_pergantian        = $_POST['tbbiaya_pergantian'];
                $status_perizinan        = $_POST['optstatus_perizinan'];;
                $deskripsi_perizinan        = $_POST['tbdeskripsi_perizinan'];
                $randomstring = substr(md5(rand()), 0, 7);

                //Image upload
                if (isset($_FILES['image_uploads'])) {
                $target_dir  = "images/foto_perizinan/";
                $file_proposal = $target_dir .'FIZIN_'.$randomstring. '.jpg';
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $file_proposal);
                }
                else if($_FILES["file"]["error"] == 4) {
                    $file_proposal = "images/fizdefault.png";
                }
                //---image upload end

                $sqleditperizinan = "UPDATE t_perizinan
                            SET judul_perizinan = :judul_perizinan, id_user = :id_user,
                            deskripsi_perizinan = :deskripsi_perizinan,
                            file_proposal = :file_proposal, biaya_pergantian = :biaya_pergantian, status_perizinan = :status_perizinan
                            WHERE id_perizinan = :id_perizinan";

                $stmt = $pdo->prepare($sqleditperizinan);
                $stmt->execute(['judul_perizinan' => $judul_perizinan,'id_user' => $id_user, 'id_lokasi' => $id_lokasi,
                    'deskripsi_perizinan' => $deskripsi_perizinan, 'file_proposal' => $file_proposal,
                    'biaya_pergantian' => $biaya_pergantian, 'status_perizinan' => $status_perizinan, 'id_perizinan' => $id_perizinan]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                //echo "Update sukses";
                } else {
                header("Location: edit_perizinan.php?id_perizinan=$id_perizinan&status=updatesuccess");
                }
            }
        }
    }

    function deletePerizinan(){
        $sql = 'DELETE FROM t_perizinan
            WHERE id_perizinan = :id_perizinan';

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_perizinan' => $_POST['id_perizinan']]);
            header('Location: kelola_perizinan.php?status=deletesuccess');
    }

    function addInformasi(){

    }

    function viewInformasi(){

    }

    function editInformasi(){

    }

    function deleteInformasi(){

    }

    function viewLaporan(){

    }

    function filterLaporan(){

    }

    function viewUser(){

    }

    function editUser(){

    }

    function deleteUser(){

    }

    function hello(){
        $msg = "Function works, praise KEK";
        return $msg;
    }





?>






