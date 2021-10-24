<?php
// require dirname(__FILE__)."/../PHPMailer/PHPMailerAutoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__FILE__).'/../PHPMailer/src/Exception.php';
require dirname(__FILE__).'/../PHPMailer/src/PHPMailer.php';
require dirname(__FILE__).'/../PHPMailer/src/SMTP.php';

function smtpmailer($to, $from, $from_name, $subject, $body)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true; 
 
        $mail->SMTPSecure = 'tls'; 
        $mail->Host = 'mail.tkjb.or.id';
        $mail->Port = 587;  
        $mail->Username = 'noreply-gokarang@tkjb.or.id';
        $mail->Password = '9OJt3l6b)Nb[';   
   
   //   $path = 'reseller.pdf';
   //   $mail->AddAttachment($path);
   
        $mail->IsHTML(true);
        $mail->From="noreply-gokarang@tkjb.or.id";
        $mail->FromName=$from_name;
        $mail->Sender=$from;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        if(!$mail->Send())
        {
            $error = $mail->ErrorInfo;
            return $error; 
        }
        else 
        {
            $error = "Email has been sent.";  
            return $error;
        }
    }
    // $token_aktivasi_user = substr(md5(rand()), 0, 32);
    // $penerima   = 'feisalar1@gmail.com';
    $pengirim = 'noreply-gokarang@tkjb.or.id';
    $nama_pengirim = 'GoKarang Administrator';
    // $subjek = 'PHPMailer Test Email';
    // $pesan = 'Terima kasih telah berdonasi dengan GoKarang.
    // <br><a href="https://tkjb.or.id/aktivasi_user.php?token_aktivasi='.$token_aktivasi_user.'">Konfirmasi Akun Anda</a>';

    // $penerima   = $_SESSION['email'];
    // $pengirim = 'noreply-gokarang@tkjb.or.id';
    // $nama_pengirim = 'GoKarang Administrator';
    // $subjek = $_POST['subjek'];
    // $pesan = $_POST['pesan'];
    
    // $error = smtpmailer($penerima, $pengirim, $nama_pengirim, $subjek, $pesan);
    
?>