<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$errors = [];
$name        = '';
$description = '';  
$status      = 'active';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status      = trim($_POST['status'] ?? 'active');
    $image       = '';

    // Validation
    if (empty($name)) {
        $errors['name'] = 'Category name is required.';
    } elseif (strlen($name) < 2) {
        $errors['name'] = 'Name must be at least 2 characters.';
    } elseif (strlen($name) > 50) {
        $errors['name'] = 'Name must not exceed 50 characters.';
    }

      if (empty($description)) {
        $errors['description'] = 'Category description is required.';
      }
   elseif (strlen($description) > 500) {
        $errors['description'] = 'Description must not exceed 500 characters.';
    }


    
    if (!in_array($status, ['active', 'inactive'])) {
        $errors['status'] = 'Invalid status selected.';
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 2 * 1024 * 1024;

        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $errors['image'] = 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.';
        } elseif ($_FILES['image']['size'] > $max_size) {
            $errors['image'] = 'File size must not exceed 2MB.';
        } else {
            $upload_dir = __DIR__ . '/../../uploads/categories/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_ext;
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                $image = 'categories/' . $file_name;
            } else {
                $errors['image'] = 'Failed to upload image.';
            }
        }
    }

    if (empty($errors)) {
        $db  = (new Database())->connect();
        $cat = new Category($db);
        if ($cat->create($name, $description, $image, $status)) {
            set_flash('success', "Category \"$name\" created successfully!");
            header("Location: index.php");
            exit;
        } else {
            $errors['general'] = 'Failed to create category. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Category</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Create Category</h1>

            <?php if (!empty($errors['general'])): ?>
                <div class="form-error">
                    <?php echo htmlspecialchars($errors['general']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Category Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="Enter category name"
                        value="<?php echo htmlspecialchars($name); ?>"
                        required
                    >
                    <?php if (isset($errors['name'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['name']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="Enter category description"
                        rows="4"
                    ><?php echo htmlspecialchars($description); ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['description']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="image">Category Image</label>
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/jpeg,image/png,image/gif,image/webp"
                    >
                    <small>Max file size: 2MB. Allowed formats: JPEG, PNG, GIF, WebP</small>
                    <?php if (isset($errors['image'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['image']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                    <?php if (isset($errors['status'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['status']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" name="create_category" class="btn btn-primary">Create Category</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>