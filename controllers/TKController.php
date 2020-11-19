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
                        <strong>Username atau Password salah.</strong>
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
        $sqlviewwilayah = 'SELECT * FROM t_wilayah
                        ORDER BY nama_wilayah';
        $stmt = $pdo->prepare($sqlviewwilayah);
        $stmt->execute();
        $row = $stmt->fetchAll();
    }
    

    function editWilayah(){
        if (isset($_POST['submit'])) {
            if ($_POST['submit'] == 'Simpan') {
                $id_wilayah          = $_POST['id_wilayah'];
                $nama_wilayah          = $_POST['tbnama_wilayah'];
                $deskripsi_wilayah     = $_POST['txtdeskripsi_wilayah'];
                $randomstring = substr(md5(rand()), 0, 7);

                //Image upload
                if (isset($_FILES['image_uploads'])) {
                //Image upload
                $target_dir  = "images/foto_wilayah/";
                $foto_wilayah = $target_dir .'WIL_'.$randomstring. '.jpg';
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_wilayah);  

                }
                else if($_FILES["file"]["error"] == 4) {
                    $foto_wilayah = "images/fwdefault.png";
                }

                //---image upload end

                $sqleditwilayah = "UPDATE t_wilayah
                            SET nama_wilayah = :nama_wilayah, deskripsi_wilayah = :deskripsi_wilayah, foto_wilayah = :foto_wilayah
                            WHERE id_wilayah = :id_wilayah";

                $stmt = $pdo->prepare($sqleditwilayah);
                $stmt->execute(['nama_wilayah' => $nama_wilayah, 'deskripsi_wilayah' => $deskripsi_wilayah, 'foto_wilayah' => $foto_wilayah, 'id_wilayah' => $id_wilayah]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                //echo "Update sukses";
                } else {
                header("Location: edit_wilayah.php?id_wilayah=$id_wilayah&status=updatesuccess");
                }
            }
        }
    }

    function deleteWilayah(){        
            $sql = 'DELETE FROM t_wilayah
            WHERE id_wilayah = :id_wilayah';
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_wilayah' => $_POST['id_wilayah']]);
            header('Location: kelola_wilayah.php?status=deletesuccess');       
    }


    function addLokasi(){
        $isAdmin = $_SESSION['level_user'] == 2;

        if (!$isLoggedIn) {
            header('Location: login.php');
        }
        else if (!$isAdmin) {
            header('Location: dashboard.php');
        }
        else{
        if (isset($_POST['submit'])) {
            $nama_lokasi        = $_POST['tbnama_lokasi'];
            $luas_lokasi        = $_POST['tbluas_lokasi'];
            $id_wilayah        = $_POST['listwilayah'];
            $deskripsi_lokasi     = $_POST['txtdeskripsi_lokasi'];
            $randomstring = substr(md5(rand()), 0, 7);

            //Image upload
            if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_lokasi/";
            $foto_lokasi = $target_dir .'LOK_'.$randomstring. '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_lokasi);  

            }
            else if($_FILES["file"]["error"] == 4) {
                $foto_lokasi = "images/fldefault.png";
            }
            //---image upload end   

            $sqllokasi = "INSERT INTO t_lokasi
                            (id_wilayah, nama_lokasi, deskripsi_lokasi, foto_lokasi, luas_lokasi)
                            VALUES (:id_wilayah, :nama_lokasi, :deskripsi_lokasi, :foto_lokasi, :luas_lokasi)";

            $stmt = $pdo->prepare($sqllokasi);
            $stmt->execute(['id_wilayah' => $id_wilayah, 'nama_lokasi' => $nama_lokasi, 'deskripsi_lokasi' => $deskripsi_lokasi, 'foto_lokasi' => $foto_lokasi, 'luas_lokasi' => $luas_lokasi]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_lokasi.php?status=addsuccess");
                }
            } 
        }
    }

    function viewLokasi(){
        $sqlviewlokasi = 'SELECT * FROM t_lokasi
                        ORDER BY nama_lokasi';
        $stmt = $pdo->prepare($sqlviewlokasi);
        $stmt->execute();
        $row = $stmt->fetchAll();
    }

    function editLokasi(){
        if (isset($_POST['submit'])) {
            if ($_POST['submit'] == 'Simpan') {
                $id_lokasi          = $_POST['id_lokasi'];
                $luas_lokasi        = $_POST['tbluas_lokasi'];
                $id_wilayah        = $_POST['listwilayah'];
                $nama_lokasi          = $_POST['tbnama_lokasi'];
                $deskripsi_lokasi     = $_POST['txtdeskripsi_lokasi'];
                $randomstring = substr(md5(rand()), 0, 7);

                //Image upload
                if (isset($_FILES['image_uploads'])) {
                //Image upload
                $target_dir  = "images/foto_lokasi/";
                $foto_lokasi = $target_dir .'LOK_'.$randomstring. '.jpg';
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_lokasi);  

                }
                else if($_FILES["file"]["error"] == 4) {
                    $foto_lokasi = "images/fldefault.png";
                }

                //---image upload end

                $sqleditlokasi = "UPDATE t_lokasi
                            SET id_wilayah= :id_wilayah, nama_lokasi = :nama_lokasi, deskripsi_lokasi = :deskripsi_lokasi, foto_lokasi = :foto_lokasi, luas_lokasi = :luas_lokasi
                            WHERE id_lokasi = :id_lokasi";

                $stmt = $pdo->prepare($sqleditlokasi);
                $stmt->execute(['nama_lokasi' => $nama_lokasi, 'deskripsi_lokasi' => $deskripsi_lokasi, 'foto_lokasi' => $foto_lokasi, 'id_lokasi' => $id_lokasi, 'id_wilayah' => $id_wilayah, 'luas_lokasi' => $luas_lokasi]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                //echo "Update sukses";
                } else {
                header("Location: edit_lokasi.php?id_lokasi=$id_lokasi&status=updatesuccess");
                }
            }
        }        
    }

    function deleteLokasi(){
        $sql = 'DELETE FROM t_lokasi
            WHERE id_lokasi = :id_lokasi';
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_lokasi' => $_POST['id_lokasi']]);
            header('Location: kelola_wilayah.php?status=deletesuccess');  
    }

    
    function addTitik(){
        $isAdmin = $_SESSION['level_user'] == 2;

        if (!$isLoggedIn) {
            header('Location: login.php');
        }
        else if (!$isAdmin) {
            header('Location: dashboard.php');
        }
        else{
        if (isset($_POST['submit'])) {
            $id_lokasi        = $_POST['listlokasi'];
            $id_wilayah        = $_POST['listwilayah'];
            $luas_titik        = $_POST['tbluas_titik']; 
            $longitude        = $_POST['tblongitude'];
            $latitude        = $_POST['tblatitude'];           

            $sqltitik = "INSERT INTO t_titik
                            (id_wilayah, id_lokasi, luas_titik, longitude, latitude)
                            VALUES (:id_wilayah, :id_lokasi, :luas_titik, :longitude, :latitude)";

            $stmt = $pdo->prepare($sqltitik);
            $stmt->execute(['id_wilayah' => $id_wilayah, 'id_lokasi' => $id_lokasi, 'luas_titik' => $luas_titik, 'longitude' => $longitude, 'latitude' => $latitude]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_titik.php?status=addsuccess");
                }
            } 
        }
    }

    function viewTitik(){
        $sqlviewtitik = 'SELECT * FROM t_titik
                        ORDER BY nama_titik';
        $stmt = $pdo->prepare($sqlviewtitik);
        $stmt->execute();
        $row = $stmt->fetchAll();
    }

    function editTitik(){
        if (isset($_POST['submit'])) {
            if ($_POST['submit'] == 'Simpan') {
                $id_titik        = $_POST['id_titik'];
                $id_lokasi       = $_POST['listlokasi'];
                $id_wilayah      = $_POST['listwilayah'];
                $luas_titik      = $_POST['tbluas_titik']; 
                $longitude       = $_POST['tblongitude'];
                $latitude        = $_POST['tblatitude']; 

                $sqledittitik = "UPDATE t_titik
                            SET id_wilayah= :id_wilayah, id_lokasi = :id_lokasi, luas_titik = :luas_titik, 
                            longitude = :longitude, latitude = :latitude
                            WHERE id_titik = :id_titik";

                $stmt = $pdo->prepare($sqledittitik);
                $stmt->execute(['id_wilayah' => $id_wilayah, 'id_lokasi' => $id_lokasi, 
                                'luas_titik' => $luas_titik, 'longitude' => $longitude, 'latitude' => $latitude, 
                                'id_titik' => $id_titik]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                //echo "Update sukses";
                } else {
                header("Location: edit_titik.php?id_titik=$id_titik&status=updatesuccess");
                }
            }
        }
    }

    function deleteTitik(){
        $sql = 'DELETE FROM t_titik
            WHERE id_titik = :id_titik';
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_titik' => $_POST['id_titik']]);
            header('Location: kelola_titik.php?status=deletesuccess');
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
        $isAdmin = $_SESSION['level_user'] == 2;

        if (!$isLoggedIn) {
            header('Location: login.php');
        }
        else if (!$isAdmin) {
            header('Location: dashboard.php');
        }
        else{
        if (isset($_POST['submit'])) {
            $nama_jenis        = $_POST['tbnama_jenis']; 
            $deskripsi_jenis        = $_POST['tbdeskripsi_jenis'];
            $randomstring = substr(md5(rand()), 0, 7);
            
            //Image upload
            if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_jenis/";
            $foto_jenis = $target_dir .'FJ_'.$randomstring. '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_jenis);
            }
            else if($_FILES["file"]["error"] == 4) {
                $foto_jenis = "images/fjdefault.png";
            }
            //---image upload end   

            $sqljenis = "INSERT INTO t_jenis
                            (nama_jenis, deskripsi_jenis, foto_jenis)
                            VALUES (:nama_jenis, :deskripsi_jenis, :foto_jenis)";

            $stmt = $pdo->prepare($sqljenis);
            $stmt->execute(['nama_jenis' => $nama_jenis, 'deskripsi_jenis' => $deskripsi_jenis, 'foto_jenis' => $foto_jenis]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_jenis.php?status=addsuccess");
                }
            } 
        }
    }

    function viewJenis(){
        $sqlviewjenis = 'SELECT * FROM t_jenis
                        ORDER BY nama_jenis';
        $stmt = $pdo->prepare($sqlviewjenis);
        $stmt->execute();
        $row = $stmt->fetchAll();
    }

    function editJenis(){
        if (isset($_POST['submit'])) {
            if ($_POST['submit'] == 'Simpan') {
                $id_jenis        = $_POST['id_jenis'];
                $nama_jenis        = $_POST['tbnama_jenis']; 
                $deskripsi_jenis        = $_POST['tbdeskripsi_jenis'];
                $randomstring = substr(md5(rand()), 0, 7);
                
                //Image upload
                if (isset($_FILES['image_uploads'])) {
                $target_dir  = "images/foto_jenis/";
                $foto_jenis = $target_dir .'FJ_'.$randomstring. '.jpg';
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_jenis);
                }
                else if($_FILES["file"]["error"] == 4) {
                    $foto_jenis = "images/fjdefault.png";
                }
                //---image upload end  

                $sqleditjenis = "UPDATE t_jenis
                            SET nama_jenis = :nama_jenis, deskripsi_jenis = :deskripsi_jenis, 
                            foto_jenis = :foto_jenis
                            WHERE id_jenis = :id_jenis";

                $stmt = $pdo->prepare($sqleditjenis);
                $stmt->execute(['nama_jenis' => $nama_jenis, 'deskripsi_jenis' => $deskripsi_jenis, 
                                'foto_jenis' => $foto_jenis, 'id_jenis' => $id_jenis]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                //echo "Update sukses";
                } else {
                header("Location: edit_jenis.php?id_jenis=$id_jenis&status=updatesuccess");
                }
            }       
        }
    }        

    function deleteJenis(){
        $sql = 'DELETE FROM t_jenis
            WHERE id_jenis = :id_jenis';
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_jenis' => $_POST['id_jenis']]);
            header('Location: kelola_jenis.php?status=deletesuccess');
    }


    
    function addTerumbu(){
        $isAdmin = $_SESSION['level_user'] == 2;

        if (!$isLoggedIn) {
            header('Location: login.php');
        }
        else if (!$isAdmin) {
            header('Location: dashboard.php');
        }
        else{
        if (isset($_POST['submit'])) {
            $id_jenis        = $_POST['listid_jenis'];
            $nama_terumbu_karang        = $_POST['tbnama_terumbu_karang']; 
            $deskripsi_terumbu_karang        = $_POST['tbdeskripsi_terumbu_karang'];
            $randomstring = substr(md5(rand()), 0, 7);
            
            //Image upload
            if (isset($_FILES['image_uploads'])) {
            $target_dir  = "images/foto_terumbu_karang/";
            $foto_terumbu_karang = $target_dir .'FTK_'.$randomstring. '.jpg';
            move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_terumbu_karang);
            }
            else if($_FILES["file"]["error"] == 4) {
                $foto_terumbu_karang = "images/ftdefault.png";
            }
            //---image upload end   

            $sqlterumbu_karang = "INSERT INTO t_terumbu_karang
                            (id_jenis, nama_terumbu_karang, deskripsi_terumbu_karang, foto_terumbu_karang)
                            VALUES (:id_jenis, :nama_terumbu_karang, :deskripsi_terumbu_karang, :foto_terumbu_karang)";

            $stmt = $pdo->prepare($sqlterumbu_karang);
            $stmt->execute(['id_jenis' => $id_jenis,'nama_terumbu_karang' => $nama_terumbu_karang, 
            'deskripsi_terumbu_karang' => $deskripsi_terumbu_karang, 'foto_terumbu_karang' => $foto_terumbu_karang]);

            $affectedrows = $stmt->rowCount();
            if ($affectedrows == '0') {
            //echo "HAHAHAAHA INSERT FAILED !";
            } else {
                //echo "HAHAHAAHA GREAT SUCCESSS !";
                header("Location: kelola_terumbu_karang.php?status=addsuccess");
                }
            }
        }
    }

    function viewTerumbu(){
        $sqlviewterumbu_karang = 'SELECT * FROM t_terumbu_karang
                        ORDER BY nama_terumbu_karang';
        $stmt = $pdo->prepare($sqlviewterumbu_karang);
        $stmt->execute();
        $row = $stmt->fetchAll();
    }

    function editTerumbu(){
        if (isset($_POST['submit'])) {
            if ($_POST['submit'] == 'Simpan') {
                $id_terumbu_karang        = $_POST['id_terumbu_karang'];
                $id_jenis        = $_POST['listid_jenis'];
                $nama_terumbu_karang        = $_POST['tbnama_terumbu_karang']; 
                $deskripsi_terumbu_karang        = $_POST['tbdeskripsi_terumbu_karang'];
                $randomstring = substr(md5(rand()), 0, 7);
                
                //Image upload
                if (isset($_FILES['image_uploads'])) {
                $target_dir  = "images/foto_terumbu_karang/";
                $foto_terumbu_karang = $target_dir .'FTK_'.$randomstring. '.jpg';
                move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $foto_terumbu_karang);
                }
                else if($_FILES["file"]["error"] == 4) {
                    $foto_terumbu_karang = "images/ftdefault.png";
                }
                //---image upload end  

                $sqleditterumbu_karang = "UPDATE t_terumbu_karang
                            SET id_jenis = :id_jenis, nama_terumbu_karang = :nama_terumbu_karang, 
                            deskripsi_terumbu_karang = :deskripsi_terumbu_karang, 
                            foto_terumbu_karang = :foto_terumbu_karang
                            WHERE id_terumbu_karang = :id_terumbu_karang";

                $stmt = $pdo->prepare($sqleditterumbu_karang);
                $stmt->execute(['id_jenis' => $id_jenis,'nama_terumbu_karang' => $nama_terumbu_karang, 
                'deskripsi_terumbu_karang' => $deskripsi_terumbu_karang, 
                                'foto_terumbu_karang' => $foto_terumbu_karang, 'id_terumbu_karang' => $id_terumbu_karang]);

                $affectedrows = $stmt->rowCount();
                if ($affectedrows == '0') {
                //echo "Update sukses";
                } else {
                header("Location: edit_terumbu_karang.php?id_terumbu_karang=$id_terumbu_karang&status=updatesuccess");
                }
            }       
        }
    }
    
    function deleteTerumbu(){
        $sql = 'DELETE FROM t_terumbu_karang
            WHERE id_terumbu_karang = :id_terumbu_karang';
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_terumbu_karang' => $_POST['id_terumbu_karang']]);
            header('Location: kelola_terumbu_karang.php?status=deletesuccess');
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






