<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $pdo = getDBConnection();

    // Delete from alunos
    $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: dashboard.php?tab=aluno&success=1');
    exit;
}
?>
