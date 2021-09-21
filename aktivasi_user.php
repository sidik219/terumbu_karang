<?php include 'build/config/connection.php';
session_start();

if (isset($_GET['token_aktivasi_user'])) {
    $token_aktivasi_user   = $_GET['token_aktivasi_user'];

    $sql  = "SELECT * FROM t_user WHERE token_aktivasi_user = :token_aktivasi_user";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['token_aktivasi_user' => $token_aktivasi_user]);
    $row = $stmt->fetch();

    if (!empty($row)) {
        if($row->aktivasi_user == 1){
          header('location: login.php?pesan=sudah_aktivasi');
        }
        else{
          $sql  = "UPDATE t_user SET aktivasi_user = 1 WHERE id_user = :id_user";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(['id_user' => $row->id_user]);
          header('location: login.php?pesan=aktivasi_berhasil');
        }
    } else {
        header('location: login.php?pesan=token_aktivasi_invalid');
    }
}
?>

