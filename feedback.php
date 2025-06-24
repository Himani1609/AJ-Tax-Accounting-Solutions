<?php
header('Content-Type: application/json'); 

if (isset($_POST['data'])) {
    parse_str($_POST['data'], $parsed);
    $_POST = array_merge($_POST, $parsed);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = htmlspecialchars($_POST["username"] ?? '');
    $email   = filter_var($_POST["email"] ?? '', FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST["message"] ?? '');
    $date    = date('Y-m-d H:i:s');

    // Validate
    if (empty($name) || empty($email)) {
        echo json_encode([
            "success" => false,
            "message" => "Please fill in all required fields."
        ]);
        exit;
    }

    // Email setup
    $to      = "info@himanibansal.com"; 
    $subject = "Website Feedback from $name";
    $body    = "You have received new feedback:\n\n"
             . "Name: $name\n"
             . "Email: $email\n"
             . "Message:\n$message\n"
             . "Submitted on: $date";

    $headers = "From: info@ajtaxsolutions.ca\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Save to CSV
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

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode([
            "success" => true,
            "message" => "Thank you for your feedback!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Feedback saved, but email failed to send."
        ]);
    }
}
?>
