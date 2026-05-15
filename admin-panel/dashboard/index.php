<?php
require_once '../../config/Database.php';
$db = (new Database())->connect();

$data = $db->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Admin Dashboard</h2>

<a href="../categories/create.php">+ Create Category</a>

<br><br>
<a href="../auth/logout.php" 
   style="float:right; background:red; color:white; padding:8px 12px; text-decoration:none;">
   Logout
</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Image</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php foreach ($data as $row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['slug'] ?></td>
        <td>
            <img src="../../uploads/<?= $row['image'] ?>" width="60">
        </td>
        <td><?= $row['status'] ?></td>
        <td>
            <a href="../categories/edit.php?id=<?= $row['id'] ?>">Edit</a> |
            <a href="../categories/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>