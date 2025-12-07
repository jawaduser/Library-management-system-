<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

$userId = $_SESSION['user']['id'];
$bookId = $_GET['book_id'];

$stmt = $conn->prepare("SELECT * FROM books WHERE id = :book_id");
$stmt->execute(['book_id' => $bookId]);
$book = $stmt->fetch();

if (!$book || !$book['availability']) {
    die("Book not available for borrowing.");
}

// Insert borrow request
$query = "INSERT INTO borrow_requests (book_id, user_id, status) VALUES (:book_id, :user_id, 'pending')";
$stmt = $conn->prepare($query);
$stmt->execute(['book_id' => $bookId, 'user_id' => $userId]);

echo "Borrow request submitted successfully!";
?>
