<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $unique_code = $_POST['unique_code'] ?? '';
    
    if (!empty($name) && !empty($unique_code)) {
        $logo = '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            $fileExtension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $newFileName = uniqid() . '.' . $fileExtension;
            $uploadFile = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadFile)) {
                $logo = $newFileName;
            }
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO restaurants (name, description, logo, unique_code) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$name, $description, $logo, $unique_code]);
        
        header('Location: dashboard.php');
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM restaurants ORDER BY created_at DESC");
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - QR Restaurant Menu Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">QR Menu Tracker</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Manage Restaurants</h2>
            </div>
            <div class="col text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRestaurantModal">
                    Add Restaurant
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($restaurant['name']) ?>
                                <?php if ($restaurant['logo']): ?>
                                    <img src="../uploads/<?= htmlspecialchars($restaurant['logo']) ?>" 
                                         alt="Logo" 
                                         class="img-thumbnail" 
                                         style="max-height: 50px;">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($restaurant['description']) ?></td>
                            <td>
                                <a href="../restaurant.php?code=<?= htmlspecialchars($restaurant['unique_code']) ?>" 
                                   target="_blank">
                                    <?= htmlspecialchars($restaurant['unique_code']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="edit_menu.php?id=<?= $restaurant['id'] ?>" 
                                   class="btn btn-sm btn-primary">
                                    Edit Menu
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Restaurant Modal -->
    <div class="modal fade" id="addRestaurantModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Restaurant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Restaurant Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="unique_code" class="form-label">Unique Code</label>
                            <input type="text" class="form-control" id="unique_code" name="unique_code" required>
                            <div class="form-text">This will be used in the QR code URL</div>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Restaurant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
