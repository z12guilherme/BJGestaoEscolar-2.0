<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'Professor'])) {
    header('Location: ../index.php');
    exit;
}

$pdo = getDBConnection();
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: listar.php');
    exit;
}

// Verificar se professor pode acessar apenas suas turmas
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
if ($role === 'Professor') {
    $stmt = $pdo->prepare("SELECT id FROM turmas t JOIN professores p ON t.professor_id = p.id WHERE t.id = ? AND p.user_id = ?");
    $stmt->execute([$id, $user_id]);
    if (!$stmt->fetch()) {
        header('Location: listar.php');
        exit;
    }
}

// Buscar turma
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$id]);
$turma = $stmt->fetch();
if (!$turma) {
    header('Location: listar.php');
    exit;
}

// Buscar alunos da turma
$stmt = $pdo->prepare("SELECT id, name FROM alunos WHERE turma_id = ? ORDER BY name");
$stmt->execute([$id]);
$alunos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Turma - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../dashboard.php"><?= SITE_NAME ?></a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../dashboard.php">Dashboard</a>
                <a class="nav-link" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Gerenciar Turma: <?= htmlspecialchars($turma['name']) ?></h1>
        <a href="../notas/lancar.php?turma_id=<?= $id ?>" class="btn btn-success mb-3">Lançar Notas</a>
        <h2>Alunos</h2>
        <?php if ($alunos): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?= htmlspecialchars($aluno['name']) ?></td>
                            <td>
                                <a href="../notas/relatorio.php?aluno_id=<?= $aluno['id'] ?>" class="btn btn-sm btn-info">Ver Notas</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum aluno nesta turma.</p>
        <?php endif; ?>
        <a href="listar.php" class="btn btn-secondary">Voltar</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
