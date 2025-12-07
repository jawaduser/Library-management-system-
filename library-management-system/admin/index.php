<?php
session_start();
include '../includes/config.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../user/index.php');
    exit();
}

$stmt = $conn->prepare("SELECT COUNT(*) as total_books FROM books");
$stmt->execute();
$bookCount = $stmt->fetch()['total_books'];

$stmt = $conn->prepare("SELECT COUNT(*) as total_users FROM users");
$stmt->execute();
$userCount = $stmt->fetch()['total_users'];

$stmt = $conn->prepare("SELECT COUNT(*) as pending_requests FROM borrow_requests WHERE status = 'pending'");
$stmt->execute();
$pendingCount = $stmt->fetch()['pending_requests'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Total Books: <?php echo $bookCount; ?></p>
    <p>Total Users: <?php echo $userCount; ?></p>
    <p>Pending Borrow Requests: <?php echo $pendingCount; ?></p>
    <a href="manage_books.php">Manage Books</a>
</body>
</html>
