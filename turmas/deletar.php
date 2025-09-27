<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'Professor'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $pdo = getDBConnection();
        try {
            $stmt = $pdo->prepare("DELETE FROM turmas WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: listar.php');
            exit;
        } catch (Exception $e) {
            header('Location: listar.php?error=1');
            exit;
        }
    }
}
header('Location: listar.php');
exit;
?>
