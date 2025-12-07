<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = "%" . $_GET['query'] . "%";
    $stmt = $conn->prepare("
        SELECT * FROM books 
        WHERE title LIKE :query 
        OR author LIKE :query 
        OR year LIKE :query 
        OR identifier LIKE :query
    ");
    $stmt->execute(['query' => $query]);
    $searchResults = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Search Books</h1>
    <form method="GET">
        <input type="text" name="query" placeholder="Search by title, author, year, or identifier" required>
        <button type="submit">Search</button>
    </form>
    <?php if (!empty($searchResults)): ?>
        <h2>Search Results</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Availability</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['year']); ?></td>
                        <td><?php echo $book['availability'] ? 'Available' : 'Not Available'; ?></td>
                        <td>
                            <?php if ($book['availability']): ?>
                                <a href="borrow.php?book_id=<?php echo $book['id']; ?>">Borrow</a>
                            <?php else: ?>
                                Unavailable
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
        <p>No results found.</p>
    <?php endif; ?>
</body>
</html>

