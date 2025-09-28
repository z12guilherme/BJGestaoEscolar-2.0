<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: dashboard.php');
exit;
?>
