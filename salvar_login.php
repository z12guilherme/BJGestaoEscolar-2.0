<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $user_id = $_POST['user_id'] ?? '';

    $errors = [];
    if (empty($username) || empty($password) || empty($role) || empty($user_id)) {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    $pdo = getDBConnection();

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $errors[] = 'Username já existe.';
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $escola_id = null; // Can be derived from user_id if needed, e.g., for professor/aluno etc.
        if (in_array($role, ['professor', 'tutor'])) {
            $stmt = $pdo->prepare("SELECT escola_id FROM professores WHERE id = ? UNION SELECT escola_id FROM tutores WHERE id = ? LIMIT 1");
            $stmt->execute([$user_id, $user_id]);
            $escola = $stmt->fetch(PDO::FETCH_ASSOC);
            $escola_id = $escola ? $escola['escola_id'] : null;
        } elseif (in_array($role, ['aluno', 'responsavel'])) {
            $stmt = $pdo->prepare("SELECT e.id as escola_id FROM alunos a JOIN turmas t ON a.turma_id = t.id JOIN escolas e ON t.escola_id = e.id WHERE a.id = ? LIMIT 1");
            $stmt->execute([$user_id]);
            $escola = $stmt->fetch(PDO::FETCH_ASSOC);
            $escola_id = $escola ? $escola['escola_id'] : null;
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, user_id, escola_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password_hash, $role, $user_id, $escola_id]);

        header('Location: dashboard.php?tab=criar_login&success=1');
        exit;
    } else {
        header('Location: dashboard.php?tab=criar_login&error=1');
        exit;
    }
}
?>
