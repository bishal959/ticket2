<?php
session_start();
$userid = $_SESSION['user_id'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Add this line to import the SMTP class

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../config.php';

try {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    // Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                 // Enable verbose debug output
    $mail->isSMTP();                                        
    $mail->Host       = 'smtp.gmail.com';                   
    $mail->SMTPAuth   = true;                                
    $mail->Username   = 'bishalluitel6@gmail.com';        
    $mail->Password   = $smtp_pass;              
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      
    $mail->Port       = 587;                               

    // Rest of the code remains unchanged
    // ...
    $mail->setFrom('bishalluitel6@gmail.com', 'Mailer'); 
    $mail->addAddress('bishal.7037707@gpkmc.edu.np');                   // Name is optional
    $mail->addReplyTo('info@bishalluitel.com.np', 'Information');
    $mail->addAttachment('../tempdf/' . $userid . '.pdf');
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');   
    $userName = $_SESSION['username'];
    $template = file_get_contents('templete.html');
    $template = str_replace('{UserName}', $userName, $template);
        $mail->isHTML(true);
        $mail->Subject = 'Congratulations on Your Registration';
        $mail->msgHTML($template);
    // Send the email
    $mail->send();
    echo 'Message has been sent';
    header("Location: ../user/ticket.php");
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
