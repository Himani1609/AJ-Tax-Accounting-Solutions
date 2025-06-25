<?php
header('Content-Type: application/json');

if (isset($_POST['data'])) {
    parse_str($_POST['data'], $parsed);
    $_POST = array_merge($_POST, $parsed);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = $_POST["username"] ?? '';
    $email   = $_POST["email"] ?? '';
    $phone   = $_POST["phone"] ?? '';
    $subject = $_POST["subject"] ?? 'No subject';
    $message = $_POST["message"] ?? '';

    if (empty($name) || empty($email)) {
        echo json_encode([
            "success" => false,
            "message" => "Please fill in all required fields."
        ]);
        exit;
    }

    // Save to CSV
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

    // Email
    $to = "info@himanibansal.com";
    $email_subject = "New Consultation Request: $subject";
    $email_body = "Name: $name\nEmail: $email\nPhone: $phone\nSubject: $subject\nMessage:\n$message";
    $headers = "From: info@ajtaxsolutions.ca\r\nReply-To: $email\r\n";

    if (mail($to, $email_subject, $email_body, $headers)) {
        echo json_encode([
            "success" => true,
            "message" => "Your consultation request was sent successfully!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to send email."
        ]);
    }
}
?>
