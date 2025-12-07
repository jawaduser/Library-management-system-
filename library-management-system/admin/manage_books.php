<?php
session_start();
include '../includes/config.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../user/index.php');
    exit();
}

// Handle adding a book
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $identifier = $_POST['identifier'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, year, identifier, availability) VALUES (:title, :author, :year, :identifier, 1)");
    $stmt->execute([
        'title' => $title,
        'author' => $author,
        'year' => $year,
        'identifier' => $identifier
    ]);

    echo "Book added successfully!";
}

// Fetch all books
$stmt = $conn->prepare("SELECT * FROM books");
$stmt->execute();
$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Manage Books</h1>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" required>
        <label>Author:</label>
        <input type="text" name="author" required>
        <label>Year:</label>
        <input type="number" name="year" required>
        <label>Identifier:</label>
        <input type="text" name="identifier" required>
        <button type="submit">Add Book</button>
    </form>
    <h2>Book List</h2>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']); ?>
                - <a href="edit_book.php?id=<?php echo $book['id']; ?>">Edit</a>
                - <a href="delete_book.php?id=<?php echo $book['id']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
