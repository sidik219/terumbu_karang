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
            $error ="SMTP Error";
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
    
    $error = smtpmailer($penerima, $pengirim, $nama_pengirim, $subjek, $pesan);
    
?>

<html>
    <head>
        <title>PHPMailer 5.2 Test</title>
    </head>
    <body style="background: black;">
        <center><h2 style="padding-top:70px;color: white;"><?php echo $error; ?></h2></center>
    </body>
    
</html>

<?php
// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

// $mail = new PHPMailer();
//     $mail->IsSMTP(); // enable SMTP

//     $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
//     $mail->SMTPAuth = true; // authentication enabled
//     $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
//     $mail->Host = "mail.tkjb.or.id";
//     $mail->Port = 587; // 465 or 587
//     $mail->IsHTML(true);
//     $mail->Username = "noreply-gokarang@tkjb.or.id";
//     $mail->Password = "&loXl]uR(dh1";
//     $mail->SetFrom("noreply-gokarang@tkjb.or.id");
//     $mail->Subject = "Test";
//     $mail->Body = "hello";
//     $mail->AddAddress("feisalar1@gmail.com", "Feisal AR");

//      if(!$mail->Send()) {
//         echo "Mailer Error: " . $mail->ErrorInfo;
//      } else {
//         echo "Message has been sent";
//      }





    //Nodemailer send
// app.get('/sendemail', (req, res) => {
//     const output = `
//     You have mail!
//     `;

//     // create reusable transporter object using the default SMTP transport
//     let transporter = nodemailer.createTransport({
//         host: "mail.tkjb.or.id",
//         port: 587,
//         secure: false, // true for 465, false for other ports
//         auth: {
//             user: 'dtptkpdev@tkjb.or.id', // generated ethereal user
//             pass: 'DTPtkpemailtest', // generated ethereal password
//         },
//         tls: {
//             rejectUnauthorized: false
//         }
//     });

//     // send mail with defined transport object
//     let info = transporter.sendMail({
//         from: '"DTP TKP Dev nodemail" <dtptkpdev@tkjb.or.id>', // sender address
//         to: "feisalar1@gmail.com", // list of receivers
//         subject: "Nodemailer test1", // Subject line
//         text: "Oy gevalt", // plain text body
//         html: `<b>Oy vey oy gevalt</b>
//                 <br>${output}` // html body
//     });

//     console.log("Message sent: %s", info.messageId);
//     console.log("Preview URL: %s", nodemailer.getTestMessageUrl(info));
// })


// //Whatsapp Twilio API send
// app.get('/sendwhatsapp', (req, res) => {
//     const accountSid = 'AC5f0114a8a710f1340222bae3328bd9f3';
//     const authToken = 'f94c7d250a6ff23774cae60f1208f39a';
//     const client = require('twilio')(accountSid, authToken);

//     client.messages
//         .create({
//             body: 'Permintaan TKP baru diterima',
//             from: 'whatsapp:+14155238886',
//             to: 'whatsapp:+6289618630369'
//         })
//         .then(message => console.log(message.sid))
//         .done();

// })

?>