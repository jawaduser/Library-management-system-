<?php
include '../includes/config.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

if (!$book) {
    die("Book not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($book['title']); ?></h1>
    <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
    <p>Year: <?php echo htmlspecialchars($book['year']); ?></p>
    <p>Availability: <?php echo $book['availability'] ? 'Available' : 'Not Available'; ?></p>
</body>
</html>
