<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$db = (new Database())->connect();
$categoryMdl = new Category($db);
$categories = $categoryMdl->getAll();
$totalCategories = count($categories);

$flash = get_flash();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Categories</h3>
                <div class="stat-number"><?php echo $totalCategories; ?></div>
                <div class="actions">
                    <a href="../categories/index.php" class="btn btn-secondary">Manage</a>
                    <a href="../categories/create.php" class="btn btn-primary">+ Create</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>