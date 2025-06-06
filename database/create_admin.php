<?php
require_once '../config/database.php';

$default_username = 'admin';
$default_password = 'admin123';

$hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$default_username]);
    
    if ($stmt->fetch()) {
        echo "Admin user already exists!\n";
    } else {
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->execute([$default_username, $hashed_password]);
        echo "Admin user created successfully!\n";
        echo "Username: {$default_username}\n";
        echo "Password: {$default_password}\n";
        echo "Please change these credentials after first login.\n";
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
