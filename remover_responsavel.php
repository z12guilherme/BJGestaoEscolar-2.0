<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $pdo = getDBConnection();

    // Get user_id from responsavel
    $stmt = $pdo->prepare("SELECT user_id FROM responsaveis WHERE id = ?");
    $stmt->execute([$id]);
    $responsavel = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($responsavel) {
        $user_id = $responsavel['user_id'];

        // Delete from responsaveis
        $stmt = $pdo->prepare("DELETE FROM responsaveis WHERE id = ?");
        $stmt->execute([$id]);

        // Delete from usuarios
        if ($user_id) {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$user_id]);
        }
    }

    header('Location: dashboard.php?tab=responsavel&success=1');
    exit;
}
?>
