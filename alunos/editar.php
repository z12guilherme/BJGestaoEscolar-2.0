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

$errors = [];
$success = false;

// Buscar aluno
$stmt = $pdo->prepare("SELECT * FROM alunos WHERE id = ?");
$stmt->execute([$id]);
$aluno = $stmt->fetch();
if (!$aluno) {
    header('Location: listar.php');
    exit;
}

// Buscar turmas
$stmt = $pdo->query("SELECT id, name FROM turmas ORDER BY name");
$turmas = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $birth_date = $_POST['birth_date'] ?? '';
    $turma_id = $_POST['turma_id'] ?? '';

    // Validação
    if (empty($name) || strlen($name) < 3) {
        $errors[] = 'Nome é obrigatório e deve ter pelo menos 3 caracteres.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("UPDATE alunos SET name = ?, birth_date = ?, turma_id = ? WHERE id = ?");
            $stmt->execute([$name, $birth_date ?: null, $turma_id ?: null, $id]);
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
    <title>Editar Aluno - <?= SITE_NAME ?></title>
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
        <h1>Editar Aluno</h1>
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
            <div class="alert alert-success">Aluno editado com sucesso! <a href="listar.php">Voltar à lista</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($aluno['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= $aluno['birth_date'] ?>">
                </div>
                <div class="mb-3">
                    <label for="turma_id" class="form-label">Turma</label>
                    <select class="form-control" id="turma_id" name="turma_id">
                        <option value="">Selecione</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?= $turma['id'] ?>" <?= $aluno['turma_id'] == $turma['id'] ? 'selected' : '' ?>><?= htmlspecialchars($turma['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
