<?php
session_start();
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();


    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: ../user/index.php');
        exit();
    } else {
        $error = "Invalid credentials.";
    }
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <label>Username</label>
        <input type="text" name="username" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <p>email</p>
        <input type="email" name="email" required>
        
        <button type="submit">Login</button>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    </form>
</body>
</html>
