<?php
require_once '../../config/Database.php';
$db = (new Database())->connect();

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $image = "";

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/" . $image);
    }

    $stmt = $db->prepare("INSERT INTO categories (name, slug, description, image, status, created_at)
                          VALUES (?, ?, ?, ?, ?, NOW())");

    $stmt->execute([$name, $slug, $description, $image, $status]);

    header("Location: index.php");
}
?>

<h2>Create Category</h2>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="name" placeholder="Name"><br><br>
    <input type="text" name="slug" placeholder="Slug"><br><br>
    <textarea name="description"></textarea><br><br>

    <input type="file" name="image"><br><br>

    <select name="status">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select><br><br>

    <button type="submit" name="submit">Save</button>
</form>