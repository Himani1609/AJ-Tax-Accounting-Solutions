<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST["username"] ?? '');
    $email   = filter_var($_POST["email"] ?? '', FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST["message"] ?? '');
    $date    = date('Y-m-d H:i:s');

    // Basic validation
    if (empty($name) || empty($email) ) {
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
        exit;
    }

    $to = "himanibansal1691998@gmail.com"; 
    $subject = "Website Feedback from $name";
    $body = "You’ve received new feedback:\n\n"
          . "Name: $name\n"
          . "Email: $email\n"
          . "Message:\n$message\n"
          . "Submitted on: $date";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    //Save to CSV
    $csvFile = 'feedback.csv';
    $entry = [$date, $name, $email, $message];

    $fileCreated = !file_exists($csvFile);
    $fp = fopen($csvFile, 'a');

    if ($fp) {
        if ($fileCreated) {
            fputcsv($fp, ['Date', 'Name', 'Email', 'Message']);
        }
        fputcsv($fp, $entry);
        fclose($fp);
    }

    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href='contacts.html';</script>";
    } else {
        echo "<script>alert('Message saved, but email failed to send.'); window.location.href='contacts.html';</script>";
    }
}
?>
