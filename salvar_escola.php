<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    if (empty($nome)) {
        header('Location: dashboard.php?error=escola_nome_required');
        exit;
    }

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO escolas (nome, endereco) VALUES (?, ?)");
    $success = $stmt->execute([$nome, $endereco]);

    if ($success) {
        header('Location: dashboard.php?success=escola_criada');
    } else {
        header('Location: dashboard.php?error=escola_error');
    }
    exit;
}
?>
