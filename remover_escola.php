<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        header('Location: dashboard.php?error=invalid_id');
        exit;
    }

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM escolas WHERE id = ?");
    $success = $stmt->execute([$id]);

    if ($success) {
        header('Location: dashboard.php?success=escola_removida');
    } else {
        header('Location: dashboard.php?error=escola_error');
    }
    exit;
}
?>
