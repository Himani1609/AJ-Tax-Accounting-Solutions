<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST["username"] ?? '';
    $email   = $_POST["email"] ?? '';
    $phone   = $_POST["phone"] ?? '';
    $subject = $_POST["subject"] ?? 'No subject';
    $message = $_POST["message"] ?? '';
    $date    = date('Y-m-d H:i:s');

    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    // Email Setup
    $to = "himanibansal1691998@gmail.com";
    $email_subject = "New Consultation Request: $subject";
    $email_body = "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\n\nMessage:\n$message";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    //Save to CSV (lead logging)
    $csvFile = __DIR__ . '/leads.csv';
    $leadEntry = [$date, $name, $email, $phone, $subject, $message];

    $fileCreated = !file_exists($csvFile);
    $fp = fopen($csvFile, 'a');

    if ($fp) {
        if ($fileCreated) {
            // Add column headers first time
            fputcsv($fp, ['Date', 'Name', 'Email', 'Phone', 'Subject', 'Message']);
        }
        fputcsv($fp, $leadEntry);
        fclose($fp);
    }

    // Send Email
    if (mail($to, $email_subject, $email_body, $headers)) {
        echo "<script>alert('Message sent successfully!'); window.location.href='../index.html';</script>";
    } else {
        echo "<script>alert('Message saved, but email failed to send.'); window.history.back();</script>";
    }
}
?>
