<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $pdo = getDBConnection();

    // Get user_id from tutor
    $stmt = $pdo->prepare("SELECT user_id FROM tutores WHERE id = ?");
    $stmt->execute([$id]);
    $tutor = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($tutor) {
        $user_id = $tutor['user_id'];

        // Delete from tutores
        $stmt = $pdo->prepare("DELETE FROM tutores WHERE id = ?");
        $stmt->execute([$id]);

        // Delete from usuarios
        if ($user_id) {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$user_id]);
        }
    }

    header('Location: dashboard.php?tab=tutor&success=1');
    exit;
}
?>
