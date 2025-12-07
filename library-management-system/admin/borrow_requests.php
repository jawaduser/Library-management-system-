<?php
session_start();
include '../includes/config.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../user/index.php');
    exit();
}

// Fetch pending borrow requests
$stmt = $conn->prepare("
    SELECT br.id, br.status, b.title, u.username 
    FROM borrow_requests br
    JOIN books b ON br.book_id = b.id
    JOIN users u ON br.user_id = u.id
    WHERE br.status = 'pending'
");
$stmt->execute();
$requests = $stmt->fetchAll();

// Handle approve or deny actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $stmt = $conn->prepare("
            UPDATE borrow_requests 
            SET status = 'approved' 
            WHERE id = :id
        ");
        $stmt->execute(['id' => $requestId]);

        // Mark the book as unavailable
        $stmt = $conn->prepare("
            UPDATE books 
            SET availability = 0 
            WHERE id = (SELECT book_id FROM borrow_requests WHERE id = :id)
        ");
        $stmt->execute(['id' => $requestId]);
    } elseif ($action === 'deny') {
        $stmt = $conn->prepare("
            UPDATE borrow_requests 
            SET status = 'denied' 
            WHERE id = :id
        ");
        $stmt->execute(['id' => $requestId]);
    }

    header('Location: borrow_requests.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Manage Borrow Requests</h1>
    <table>
        <thead>
            <tr>
                <th>Book Title</th>
                <th>User</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['title']); ?></td>
                    <td><?php echo htmlspecialchars($request['username']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="action" value="approve">Approve</button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="action" value="deny">Deny</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
