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

// Verificar acesso
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

$errors = [];
$success = false;

// Buscar turma
$stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->execute([$id]);
$turma = $stmt->fetch();
if (!$turma) {
    header('Location: listar.php');
    exit;
}

// Buscar professores
$stmt = $pdo->query("SELECT id, name FROM professores ORDER BY name");
$professores = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $year = $_POST['year'] ?? '';
    $professor_id = $_POST['professor_id'] ?? '';

    // Validação
    if (empty($name) || strlen($name) < 3) {
        $errors[] = 'Nome da turma é obrigatório e deve ter pelo menos 3 caracteres.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("UPDATE turmas SET name = ?, year = ?, professor_id = ? WHERE id = ?");
            $stmt->execute([$name, $year ?: null, $professor_id ?: null, $id]);
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
    <title>Editar Turma - <?= SITE_NAME ?></title>
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
        <h1>Editar Turma</h1>
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
            <div class="alert alert-success">Turma editada com sucesso! <a href="listar.php">Voltar à lista</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($turma['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="year" class="form-label">Ano</label>
                    <input type="number" class="form-control" id="year" name="year" value="<?= htmlspecialchars($turma['year'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="professor_id" class="form-label">Professor</label>
                    <select class="form-control" id="professor_id" name="professor_id">
                        <option value="">Selecione</option>
                        <?php foreach ($professores as $professor): ?>
                            <option value="<?= $professor['id'] ?>" <?= $turma['professor_id'] == $professor['id'] ? 'selected' : '' ?>><?= htmlspecialchars($professor['name']) ?></option>
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
