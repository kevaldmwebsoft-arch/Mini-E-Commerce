<?php
require_once '../../config/Database.php';
$db = (new Database())->connect();

$id = $_GET['id'];

// image delete
$stmt = $db->prepare("SELECT image FROM categories WHERE id=?");
$stmt->execute([$id]);
$img = $stmt->fetchColumn();

if ($img) {
    unlink("../../uploads/" . $img);
}

$stmt = $db->prepare("DELETE FROM categories WHERE id=?");
$stmt->execute([$id]);

header("Location: index.php");
?>