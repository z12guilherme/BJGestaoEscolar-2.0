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

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT user_id FROM professores WHERE id = ?");
        $stmt->execute([$id]);
        $prof = $stmt->fetch();
        if (!$prof) {
            $pdo->rollBack();
            header('Location: dashboard.php?error=professor_not_found');
            exit;
        }

        $user_id = $prof['user_id'];

        $stmt = $pdo->prepare("DELETE FROM professores WHERE id = ?");
        $stmt->execute([$id]);

        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$user_id]);

        $pdo->commit();
        header('Location: dashboard.php?success=professor_removido');
    } catch (Exception $e) {
        $pdo->rollBack();
        header('Location: dashboard.php?error=professor_error');
    }
    exit;
}
?>
