<?php
session_start();
include '../includes/config.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../user/index.php');
    exit();
}

$bookId = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM books WHERE id = :id");
$stmt->execute(['id' => $bookId]);

header('Location: manage_books.php');
exit();
?>
