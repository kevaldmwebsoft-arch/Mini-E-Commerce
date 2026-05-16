<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$db  = (new Database())->connect();
$cat = new Category($db);

$categories = $cat->getAll();
$pageTitle  = 'Categories';
$activePage = 'categories';
$pageTitle  = 'Add Category';

?>