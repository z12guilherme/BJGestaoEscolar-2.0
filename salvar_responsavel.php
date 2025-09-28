<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $aluno_id = $_POST['aluno_id'] ?? '';

    $errors = [];
    if (empty($name) || empty($email) || empty($telefone) || empty($aluno_id)) {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email inválido.';
    }

    $pdo = getDBConnection();

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM responsaveis WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'Email já cadastrado.';
    }

    if (empty($errors)) {
        $password_hash = password_hash('default123', PASSWORD_DEFAULT); // Default password, can be changed later
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, escola_id) VALUES (?, ?, 'responsavel', (SELECT turma_id FROM alunos WHERE id = ? LIMIT 1))");
        $username = strtolower(str_replace(' ', '_', $name)) . '_' . rand(100, 999);
        $stmt->execute([$username, $password_hash, $aluno_id]);
        $user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO responsaveis (user_id, name, email, telefone, aluno_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $telefone, $aluno_id]);

        header('Location: dashboard.php?tab=responsavel&success=1');
        exit;
    } else {
        header('Location: dashboard.php?tab=responsavel&error=1');
        exit;
    }
}
?>
