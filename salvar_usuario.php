<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $categoria = $_POST['categoria'] ?? '';

    if (empty($username) || empty($password) || empty($categoria)) {
        header('Location: dashboard.php?error=user_required');
        exit;
    }

    $pdo = getDBConnection();
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role) VALUES (?, ?, ?)");
    $success = $stmt->execute([$username, $hash, $categoria]);

    if ($success) {
        header('Location: dashboard.php?success=user_criado');
    } else {
        header('Location: dashboard.php?error=user_error');
    }
    exit;
}
?>
