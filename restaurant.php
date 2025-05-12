<?php
require_once 'config/database.php';

$code = isset($_GET['code']) ? $_GET['code'] : '';

if (empty($code)) {
    die("No restaurant code provided");
}

$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE unique_code = ?");
$stmt->execute([$code]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    die("Restaurant not found");
}

$stmt = $pdo->prepare("
    SELECT * FROM menu_items 
    WHERE restaurant_id = ? 
    ORDER BY RAND() 
    LIMIT 5
");
$stmt->execute([$restaurant['id']]);
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($restaurant['name']) ?> - Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-4">
            <?php if ($restaurant['logo']): ?>
                <img src="uploads/<?= htmlspecialchars($restaurant['logo']) ?>" 
                     alt="<?= htmlspecialchars($restaurant['name']) ?>" 
                     class="img-fluid mb-3" 
                     style="max-height: 200px;">
            <?php endif; ?>
            <h1><?= htmlspecialchars($restaurant['name']) ?></h1>
            <p class="lead"><?= htmlspecialchars($restaurant['description']) ?></p>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <h2 class="mb-4">Today's Special Menu Items</h2>
                <?php if (empty($menu_items)): ?>
                    <p class="alert alert-info">No menu items available at the moment.</p>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($menu_items as $item): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?= htmlspecialchars($item['item_name']) ?></h5>
                                    <strong>$<?= number_format($item['price'], 2) ?></strong>
                                </div>
                                <?php if (!empty($item['description'])): ?>
                                    <p class="mb-1"><?= htmlspecialchars($item['description']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
