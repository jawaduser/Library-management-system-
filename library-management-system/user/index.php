<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <ul>
        <li><a href="search.php">Search Books</a></li>
        <li><a href="borrowed_books.php">View Borrowed Books</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</body>
</html>
