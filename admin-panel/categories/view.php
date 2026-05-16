<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$db = (new Database())->connect();
$categoryMdl = new Category($db);

if (!isset($_GET['id'])) {
    set_flash('error', 'Category not found.');
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$category = $categoryMdl->getById($id);

if (!$category) {
    set_flash('error', 'Category not found.');
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Category</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="view-container">
            <h1>Category Details</h1>

            <?php if ($category['image']): ?>
                <div>
                    <img src="../../uploads/<?php echo htmlspecialchars($category['image']); ?>" alt="Category">
                </div>
            <?php endif; ?>

            <div class="view-group">
                <span class="view-label">ID</span>
                <div class="view-value"><?php echo htmlspecialchars($category['id']); ?></div>
            </div>

            <div class="view-group">
                <span class="view-label">Name</span>
                <div class="view-value"><?php echo htmlspecialchars($category['name']); ?></div>
            </div>

            <div class="view-group">
                <span class="view-label">Slug</span>
                <div class="view-value"><?php echo htmlspecialchars($category['slug'] ?? 'N/A'); ?></div>
            </div>

            <div class="view-group">
                <span class="view-label">Description</span>
                <div class="view-value"><?php echo nl2br(htmlspecialchars($category['description'] ?? 'No description')); ?></div>
            </div>

            <div class="view-group">
                <span class="view-label">Status</span>
                <div class="view-value">
                    <span class="status-badge <?php echo htmlspecialchars($category['status'] ?? 'active'); ?>">
                        <?php echo ucfirst(htmlspecialchars($category['status'] ?? 'active')); ?>
                    </span>
                </div>
            </div>

            <div class="actions">
                <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="index.php" class="btn btn-back">Back to Categories</a>
            </div>
        </div>
    </div>
</body>
</html>
