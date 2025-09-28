<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $password = $_POST['password'] ?? '';
    $escola_id = $_POST['escola_id'] ?? '';

    $errors = [];
    if (empty($name) || empty($email) || empty($telefone) || empty($password) || empty($escola_id)) {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email inválido.';
    }

    $pdo = getDBConnection();

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM tutores WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'Email já cadastrado.';
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, escola_id) VALUES (?, ?, 'tutor', ?)");
        $username = strtolower(str_replace(' ', '_', $name)) . '_' . rand(100, 999);
        $stmt->execute([$username, $password_hash, $escola_id]);
        $user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO tutores (user_id, name, email, telefone, escola_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $telefone, $escola_id]);

        header('Location: dashboard.php?tab=tutor&success=1');
        exit;
    } else {
        header('Location: dashboard.php?tab=tutor&error=1');
        exit;
    }
}
?>
