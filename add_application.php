<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $company_name = trim($_POST['company_name']);
    $role_title = trim($_POST['role_title']);
    $status = trim($_POST['status']);
    $date_applied = $_POST['date_applied'];
    $notes = trim($_POST['notes']);

    $stmt = $conn->prepare("INSERT INTO applications (user_id, company_name, role_title, status, date_applied, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $company_name, $role_title, $status, $date_applied, $notes);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Error adding application.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Application - InternTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Add Internship Application</h1>
    <p><?php echo $message; ?></p>

    <form method="POST">
        <input type="text" name="company_name" placeholder="Company Name" required>
        <input type="text" name="role_title" placeholder="Role Title" required>

        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Applied">Applied</option>
            <option value="Interview">Interview</option>
            <option value="Offer">Offer</option>
            <option value="Rejected">Rejected</option>
        </select>

        <input type="date" name="date_applied" required>
        <textarea name="notes" placeholder="Notes"></textarea>

        <button type="submit">Save Application</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>