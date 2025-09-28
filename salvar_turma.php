<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $year = (int)($_POST['year'] ?? 0);
    $escola_id = (int)($_POST['escola_id'] ?? 0);
    $professor_id = (int)($_POST['professor_id'] ?? 0);

    if (empty($name) || $year <= 0 || $escola_id <= 0 || $professor_id <= 0) {
        header('Location: dashboard.php?error=turma_required#turma');
        exit;
    }

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO turmas (name, year, professor_id, escola_id) VALUES (?, ?, ?, ?)");
    $success = $stmt->execute([$name, $year, $professor_id, $escola_id]);

    if ($success) {
        header('Location: dashboard.php?success=turma_criada#turma');
    } else {
        header('Location: dashboard.php?error=turma_error#turma');
    }
    exit;
}
?>
