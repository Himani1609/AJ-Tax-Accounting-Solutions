<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name  = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $date  = $_POST['date'] ?? '';
    $time  = $_POST['time'] ?? '';
    $duration = $_POST['duration'] ?? '';


    // Basic validation
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
    } else {
        $fp = fopen($csvFile, 'a');
        fputcsv($fp, $newEntry);
        fclose($fp);

        echo "<script>alert('Appointment booked successfully!'); window.location.href='appointment.html';</script>";
    }
}
?>
