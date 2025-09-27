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
    header('Location: relatorio.php');
    exit;
}

$errors = [];
$success = false;

// Buscar nota
$stmt = $pdo->prepare("SELECT n.*, a.name as aluno_name, t.name as turma_name FROM notas n JOIN alunos a ON n.aluno_id = a.id JOIN turmas t ON n.turma_id = t.id WHERE n.id = ?");
$stmt->execute([$id]);
$nota = $stmt->fetch();
if (!$nota) {
    header('Location: relatorio.php');
    exit;
}

// Verificar acesso para Professor
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
if ($role === 'Professor') {
    $stmt = $pdo->prepare("SELECT id FROM turmas WHERE id = ? AND professor_id = (SELECT id FROM professores WHERE user_id = ?)");
    $stmt->execute([$nota['turma_id'], $user_id]);
    if (!$stmt->fetch()) {
        header('Location: relatorio.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semestre = trim($_POST['semestre'] ?? '');
    $valor = $_POST['valor'] ?? '';

    // Validação
    if (empty($semestre) || !is_numeric($valor)) {
        $errors[] = 'Semestre obrigatório e valor numérico.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("UPDATE notas SET semestre = ?, valor = ? WHERE id = ?");
            $stmt->execute([$semestre, $valor, $id]);
            $success = true;
        } catch (Exception $e) {
            $errors[] = 'Erro ao editar: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Nota - <?= SITE_NAME ?></title>
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
        <h1>Editar Nota</h1>
        <p><strong>Aluno:</strong> <?= htmlspecialchars($nota['aluno_name']) ?> | <strong>Turma:</strong> <?= htmlspecialchars($nota['turma_name']) ?></p>
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">Nota editada com sucesso! <a href="relatorio.php">Voltar aos relatórios</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="semestre" class="form-label">Semestre *</label>
                    <input type="text" class="form-control" id="semestre" name="semestre" value="<?= htmlspecialchars($nota['semestre']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="valor" class="form-label">Valor *</label>
                    <input type="number" step="0.01" class="form-control" id="valor" name="valor" value="<?= htmlspecialchars($nota['valor']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="relatorio.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
