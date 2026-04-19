<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all applications for this user
$stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? ORDER BY date_applied DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Get total applications count
$countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM applications WHERE user_id = ?");
$countStmt->bind_param("i", $user_id);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$totalApplications = $countRow['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - InternTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Your Internship Applications</h1>

    <p>
        <a href="add_application.php" class="btn">Add Application</a>
        <a href="logout.php" class="btn secondary">Logout</a>
    </p>

    <p><strong>Total Applications:</strong> <?php echo $totalApplications; ?></p>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Company</th>
                <th>Role</th>
                <th>Status</th>
                <th>Date Applied</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['role_title']); ?></td>
                    <td>
                        <?php
                        $status = $row['status'];
                        $color = "";

                        if ($status == "Applied") {
                            $color = "blue";
                        } elseif ($status == "Interview") {
                            $color = "orange";
                        } elseif ($status == "Offer") {
                            $color = "green";
                        } elseif ($status == "Rejected") {
                            $color = "red";
                        }
                        ?>

                        <span style="color: <?php echo $color; ?>; font-weight: bold;">
                            <?php echo htmlspecialchars($status); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($row['date_applied']); ?></td>
                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    <td class="actions">
                        <a href="edit_application.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete_application.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this application?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No applications yet.</p>
    <?php endif; ?>
</div>
</body>
</html>