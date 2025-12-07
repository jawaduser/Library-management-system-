<?php
session_start();
include '../includes/config.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../user/index.php');
    exit();
}

$bookId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $availability = isset($_POST['availability']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE books SET title = :title, author = :author, year = :year, availability = :availability WHERE id = :id");
    $stmt->execute([
        'title' => $title,
        'author' => $author,
        'year' => $year,
        'availability' => $availability,
        'id' => $bookId
    ]);

    header('Location: manage_books.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM books WHERE id = :id");
$stmt->execute(['id' => $bookId]);
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
    <h1>Edit Book</h1>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        <label>Author:</label>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        <label>Year:</label>
        <input type="number" name="year" value="<?php echo htmlspecialchars($book['year']); ?>" required>
        <label>Available:</label>
        <input type="checkbox" name="availability" <?php echo $book['availability'] ? 'checked' : ''; ?>>
        <button type="submit">Update</button>
    </form>
</body>
</html>
