<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Add this line to import the SMTP class

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

try {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    // Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;                 // Enable verbose debug output
    $mail->isSMTP();                                         // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                   // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                // Enable SMTP authentication
    $mail->Username   = 'bishalluitel6@gmail.com';          // SMTP username
    $mail->Password   = 'xohd mckk susb dsls';              // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption
    $mail->Port       = 587;                                // TCP port to connect to

    // Rest of the code remains unchanged
    // ...
    $mail->setFrom('bishalluitel6@gmail.com', 'Mailer'); 
    $mail->addAddress('bishal.7037707@gpkmc.edu.np');                   // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');
    // $mail->addAttachment('/var/tmp/file.tar.gz');             // Add attachments
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
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
