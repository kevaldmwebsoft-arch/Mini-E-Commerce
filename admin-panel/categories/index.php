<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$db = (new Database())->connect();
$categoryMdl = new Category($db);

$flash = get_flash();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['category_id'] ?? 0);
    if ($id > 0) {

        $category = $categoryMdl->getById($id);
        
        if ($categoryMdl->delete($id)) {
           
            if ($category && $category['image']) {
                $image_path = __DIR__ . '/../../uploads/' . $category['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            set_flash('success', 'Category deleted successfully!');
        } else {
            set_flash('error', 'Failed to delete category.');
        }
    }
    header("Location: index.php");
    exit;
}

$categories = $categoryMdl->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manage Categories</h1>
            <div>
                <a href="create.php" class="btn btn-primary">+ Create Category</a>
                <a href="../dashboard/index.php" class="btn btn-back">← Back to Dashboard</a>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <div class="table-wrapper">
            <?php if (count($categories) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category['slug']); ?></td>
                                <td>
                                    <?php if ($category['image']): ?>
                                        <img src="../../uploads/<?php echo htmlspecialchars($category['image']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="category-image">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td><code class="code-inline"><?php echo htmlspecialchars($category['slug']); ?></code></td>
                                <td><?php echo substr(htmlspecialchars($category['description'] ?? ''), 0, 50) . (strlen($category['description'] ?? '') > 50 ? '...' : ''); ?></td>
                                <td>
                                    <span class="status-badge <?php echo htmlspecialchars($category['status']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($category['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="view.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-view">View</a>
                                        <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn btn-sm btn-edit">Edit</a>
                                        <button class="btn btn-sm btn-delete" onclick="openDeleteModal(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>')">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">
                    <p>No categories found. <a href="create.php">Create one now</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete the category <strong id="categoryNameDisplay"></strong>? This action cannot be undone.</p>
            <form method="POST" id="deleteForm" class="d-inline">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="category_id" id="categoryIdInput">
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-delete">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(categoryId, categoryName) {
            document.getElementById('deleteModal').classList.add('active');
            document.getElementById('categoryIdInput').value = categoryId;
            document.getElementById('categoryNameDisplay').textContent = categoryName;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            var modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
