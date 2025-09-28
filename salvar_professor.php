<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $escola_id = (int)($_POST['escola_id'] ?? 0);

    if (empty($name) || $escola_id <= 0) {
        header('Location: dashboard.php?error=professor_required');
        exit;
    }

    $pdo = getDBConnection();

    $stmt = $pdo->prepare("INSERT INTO professores (name, escola_id) VALUES (?, ?)");
    $success = $stmt->execute([$name, $escola_id]);

    if ($success) {
        header('Location: dashboard.php?success=professor_criado');
    } else {
        header('Location: dashboard.php?error=professor_error');
    }
    exit;
}
?>
