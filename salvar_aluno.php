<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $turma_id = (int)($_POST['turma_id'] ?? 0);
    $laudo = trim($_POST['laudo'] ?? '');

    $errors = [];
    if (empty($name) || $turma_id <= 0) {
        $errors[] = 'Nome e turma são obrigatórios.';
    }

    $pdo = getDBConnection();

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO alunos (name, turma_id) VALUES (?, ?)");
        $stmt->execute([$name, $turma_id]);
        $aluno_id = $pdo->lastInsertId();

        if (!empty($laudo)) {
            $user_id = $_SESSION['user_id'] ?? null;
            if ($user_id) {
                $stmt = $pdo->prepare("INSERT INTO laudos (aluno_id, data, descricao, criado_por) VALUES (?, CURDATE(), ?, ?)");
                $stmt->execute([$aluno_id, $laudo, $user_id]);
            }
        }

        header('Location: dashboard.php?success=aluno_criado#aluno');
        exit;
    } else {
        header('Location: dashboard.php?error=aluno_error#aluno');
        exit;
    }
}
?>
