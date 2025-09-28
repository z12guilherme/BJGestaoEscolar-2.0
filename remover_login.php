<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $pdo = getDBConnection();

    // Delete from usuarios
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: dashboard.php?tab=criar_login&success=1');
    exit;
}
?>
