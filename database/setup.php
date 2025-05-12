<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS qr_tracker";
    $pdo->exec($sql);
    echo "Database created successfully\n";
    
    // Select the database
    $pdo->exec("USE qr_tracker");
    
    // Create tables
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($sql);
    echo "Tables created successfully\n";
    
    // Create uploads directory
    $uploadsDir = __DIR__ . '/../uploads';
    if (!file_exists($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
        echo "Uploads directory created successfully\n";
    }
    
    echo "\nDatabase setup completed! Now you can run create_admin.php to create an admin user.\n";
    
} catch(PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
