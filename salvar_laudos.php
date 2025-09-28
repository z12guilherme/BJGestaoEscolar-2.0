<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['professor', 'tutor', 'diretor'])) {
    header('Location: dashboard.php?error=unauthorized');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_id = (int)($_POST['aluno_id'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');

    $errors = [];
    if (empty($aluno_id) || empty($descricao)) {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    $pdo = getDBConnection();

    // Verify aluno exists
    $stmt = $pdo->prepare("SELECT id FROM alunos WHERE id = ?");
    $stmt->execute([$aluno_id]);
    if (!$stmt->fetch()) {
        $errors[] = 'Aluno inválido.';
    }

    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("INSERT INTO laudos (aluno_id, descricao, criado_por) VALUES (?, ?, ?)");
        $stmt->execute([$aluno_id, $descricao, $user_id]);

        header('Location: dashboard.php#laudos?success=1');
        exit;
    } else {
        header('Location: dashboard.php#laudos?error=1');
        exit;
    }
}
?>
