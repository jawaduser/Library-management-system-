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
$borrowedBooks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Your Borrowed Books</h1>
    <table>
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($borrowedBooks as $book): ?>
                <tr>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
