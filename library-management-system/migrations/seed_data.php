<?php
include '../includes/config.php';

// Add admin user
$adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
$conn->exec("
    INSERT INTO users (username, email, password, role) VALUES
    ('admin', 'admin@library.com', '$adminPassword', 'admin')
");

// Add sample books
$conn->exec("
    INSERT INTO books (title, author, year, identifier, availability) VALUES
    ('Book One', 'Author One', 2001, 'ISBN12345', 1),
    ('Book Two', 'Author Two', 2005, 'ISBN67890', 1),
    ('Book Three', 'Author Three', 2010, 'ISBN11121', 0)
");

echo "Seeder executed successfully.";
?>
