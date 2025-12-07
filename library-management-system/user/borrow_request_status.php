<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$userId = $_SESSION['user']['id'];
$stmt = $conn->prepare("
    SELECT b.title, br.status 
    FROM borrow_requests br
    JOIN books b ON br.book_id = b.id
    WHERE br.user_id = :user_id
");
$stmt->execute(['user_id' => $userId]);
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Your Borrow Requests</h1>
    <table>
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['title']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
