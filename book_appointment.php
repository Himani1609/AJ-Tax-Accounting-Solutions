<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name     = htmlspecialchars($_POST['name'] ?? '');
    $email    = htmlspecialchars($_POST['email'] ?? '');
    $phone    = htmlspecialchars($_POST['phone'] ?? '');
    $date     = $_POST['date'] ?? '';
    $time     = $_POST['time'] ?? '';
    $duration = $_POST['duration'] ?? '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($date) || empty($time) || empty($duration)) {
        echo "<script>alert('All required fields must be filled.'); window.history.back();</script>";
        exit;
    }

    $csvFile = 'appointments.csv';
    $newEntry = [$name, $email, $phone, $date, $time, $duration];

    // Prevent duplicate date/time booking
    $isDuplicate = false;
    if (file_exists($csvFile)) {
        $rows = array_map('str_getcsv', file($csvFile));
        foreach ($rows as $row) {
            if (isset($row[3], $row[4]) && $row[3] === $date && $row[4] === $time) {
                $isDuplicate = true;
                break;
            }
        }
    }

    if ($isDuplicate) {
        echo "<script>alert('This time slot is already booked. Please choose another.'); window.history.back();</script>";
        exit;
    }

    // Save to CSV
    $fileCreated = !file_exists($csvFile);
    $fp = fopen($csvFile, 'a');
    if ($fp) {
        if ($fileCreated) {
            fputcsv($fp, ['Name', 'Email', 'Phone', 'Date', 'Time', 'Duration']);
        }
        fputcsv($fp, $newEntry);
        fclose($fp);
    }

    // Email notification
    $to = "info@himanibansal.com"; // your email
    $subject = "New Appointment Booking";
    $message = "A new appointment has been scheduled:\n\n"
             . "Name: $name\n"
             . "Email: $email\n"
             . "Phone: $phone\n"
             . "Date: $date\n"
             . "Time: $time\n"
             . "Duration: $duration\n";

    $headers = "From: info@ajtaxsolutions.ca\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send the email
    mail($to, $subject, $message, $headers);

    echo "<script>alert('Appointment booked successfully!'); window.location.href='appointment.html';</script>";
}
?>
