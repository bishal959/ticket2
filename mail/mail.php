<?php
$to = "bishalluitel6@gmail.com";
$subject = "Congratulations on Your Registration";

// Load the HTML template
$htmlTemplate = file_get_contents("templete.html");

// Replace placeholders with actual user data
$userName = "John Doe"; // Replace with the user's name
$eventURL = "Bishalluitel.com.np/event"; // Replace with the event URL
$pageURL = "bishalluitel.com.np"; // Replace with the page URL

$htmlTemplate = str_replace("{{user_name}}", $userName, $htmlTemplate);
$htmlTemplate = str_replace("{{event_url}}", $eventURL, $htmlTemplate);
$htmlTemplate = str_replace("{{page_url}}", $pageURL, $htmlTemplate);

$headers = "From: sender@example.com\r\n";
$headers .= "Reply-To: sender@example.com\r\n";
$headers .= "CC: cc@example.com\r\n";
$headers .= "BCC: bcc@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

// Use mail() function to send the email
$mailSent = mail($to, $subject, $htmlTemplate, $headers);

if ($mailSent) {
    echo "Email sent successfully.";
} else {
    echo "Failed to send email.";
}
?>
