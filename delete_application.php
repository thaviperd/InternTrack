<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $app_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM applications WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $app_id, $user_id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();