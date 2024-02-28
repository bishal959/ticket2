<?php
$to = "bishalluitel6@gmail.com";
$subject = "Congratulations on Your Registration";


// Load the HTML template
$htmlTemplatePath = "templete.html"; // Adjust the path based on your setup

if (file_exists($htmlTemplatePath)) {
    $htmlTemplate = file_get_contents($htmlTemplatePath);

    // Replace placeholders with actual user data
    $userName = "John Doe"; // Replace with the user's name
    $eventURL = "https://Bishalluitel.com.np/event"; // Replace with the event URL
    $pageURL = "https://bishalluitel.com.np"; // Replace with the page URL

    $htmlTemplate = str_replace("{{user_name}}", $userName, $htmlTemplate);
    $htmlTemplate = str_replace("{{event_url}}", $eventURL, $htmlTemplate);
    $htmlTemplate = str_replace("{{page_url}}", $pageURL, $htmlTemplate);

    $headers = "From: alert@project.bishalluitel.com.np\\r\n";
    $headers .= "Reply-To: reply@project.bishalluitel.com.np\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    // Use mail() function to send the email
    $mailSent = mail($to, $subject, $htmlTemplate, $headers);

    if ($mailSent) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email.";
    }
} else {
    echo "HTML template file not found.";
}
?>
