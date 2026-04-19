<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$app_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM applications WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $app_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: dashboard.php");
    exit();
}

$app = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = trim($_POST['company_name']);
    $role_title = trim($_POST['role_title']);
    $status = trim($_POST['status']);
    $date_applied = $_POST['date_applied'];
    $notes = trim($_POST['notes']);

    $update = $conn->prepare("UPDATE applications SET company_name = ?, role_title = ?, status = ?, date_applied = ?, notes = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("sssssii", $company_name, $role_title, $status, $date_applied, $notes, $app_id, $user_id);
    $update->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Application - InternTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Internship Application</h1>

    <form method="POST">
        <input type="text" name="company_name" value="<?php echo htmlspecialchars($app['company_name']); ?>" required>
        <input type="text" name="role_title" value="<?php echo htmlspecialchars($app['role_title']); ?>" required>

        <select name="status" required>
            <option value="Applied" <?php if ($app['status'] == 'Applied') echo 'selected'; ?>>Applied</option>
            <option value="Interview" <?php if ($app['status'] == 'Interview') echo 'selected'; ?>>Interview</option>
            <option value="Offer" <?php if ($app['status'] == 'Offer') echo 'selected'; ?>>Offer</option>
            <option value="Rejected" <?php if ($app['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
        </select>

        <input type="date" name="date_applied" value="<?php echo htmlspecialchars($app['date_applied']); ?>" required>
        <textarea name="notes"><?php echo htmlspecialchars($app['notes']); ?></textarea>

        <button type="submit">Update Application</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>