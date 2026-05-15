<?php
require_once '../../config/Database.php';
$db = (new Database())->connect();

$id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $image = $row['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/" . $image);
    }

    $stmt = $db->prepare("UPDATE categories 
                          SET name=?, slug=?, description=?, image=?, status=? 
                          WHERE id=?");

    $stmt->execute([$name, $slug, $description, $image, $status, $id]);

    header("Location: index.php");
}
?>

<h2>Edit Category</h2>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="name" value="<?= $row['name'] ?>"><br><br>
    <input type="text" name="slug" value="<?= $row['slug'] ?>"><br><br>
    <textarea name="description"><?= $row['description'] ?></textarea><br><br>

    <img src="../../uploads/<?= $row['image'] ?>" width="80"><br><br>

    <input type="file" name="image"><br><br>

    <select name="status">
        <option value="1" <?= $row['status']==1?'selected':'' ?>>Active</option>
        <option value="0" <?= $row['status']==0?'selected':'' ?>>Inactive</option>
    </select><br><br>

    <button type="submit" name="update">Update</button>
</form>