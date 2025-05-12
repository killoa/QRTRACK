<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $restaurant_id = $_POST['restaurant_id'] ?? 0;
    
    if ($id && $restaurant_id) {
        $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ? AND restaurant_id = ?");
        $stmt->execute([$id, $restaurant_id]);
    }
    
    header("Location: edit_menu.php?id=$restaurant_id");
    exit;
} else {
    header('Location: dashboard.php');
    exit;
}
