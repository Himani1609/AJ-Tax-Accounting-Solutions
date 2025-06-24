<?php
if (isset($_POST['data'])) {
    parse_str($_POST['data'], $parsed);
    $_POST = array_merge($_POST, $parsed);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST["username"] ?? '';
    $email   = $_POST["email"] ?? '';
    $phone   = $_POST["phone"] ?? '';
    $subject = $_POST["subject"] ?? 'No subject';
    $message = $_POST["message"] ?? '';

    if (empty($name) || empty($email)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    $to = "info@himanibansal.com";
    $email_subject = "New Consultation Request: $subject";
    $email_body = "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\n\nMessage:\n$message";

    // Safer: use a fixed domain email
    $headers = "From: info@ajtaxsolutions.ca\r\n";
    $headers .= "Reply-To: $email\r\n";



    $csvFile = 'leads.csv';
    $fileCreated = !file_exists($csvFile);
    $entry = [date('Y-m-d H:i:s'), $name, $email, $phone, $subject, $message];

    if ($fp = fopen($csvFile, 'a')) {
        if ($fileCreated) {
            fputcsv($fp, ['Date', 'Name', 'Email', 'Phone', 'Subject', 'Message']);
        }
        fputcsv($fp, $entry);
        fclose($fp);
}

    if (mail($to, $email_subject, $email_body, $headers)) {
        echo "<script>alert('Message sent successfully!'); window.location.href='../index.html';</script>";
    } else {
        echo "<script>alert('Message sending failed.'); window.history.back();</script>";
    }
}
?>
