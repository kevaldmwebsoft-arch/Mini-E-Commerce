<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

if (!isset($_GET['id'])) {
    set_flash('error', 'Category not found.');
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$db = (new Database())->connect();
$categoryMdl = new Category($db);

if ($categoryMdl->delete($id)) {
    set_flash('success', 'Category deleted successfully!');
} else {
    set_flash('error', 'Failed to delete category.');
}

header("Location: index.php");
exit;
?>