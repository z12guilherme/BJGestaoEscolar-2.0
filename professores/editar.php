<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
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

// Buscar professor
$stmt = $pdo->prepare("SELECT * FROM professores WHERE id = ?");
$stmt->execute([$id]);
$professor = $stmt->fetch();
if (!$professor) {
    header('Location: listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');

    // Validação
    if (empty($name) || strlen($name) < 3) {
        $errors[] = 'Nome é obrigatório e deve ter pelo menos 3 caracteres.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("UPDATE professores SET name = ?, subject = ? WHERE id = ?");
            $stmt->execute([$name, $subject ?: null, $id]);
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
    <title>Editar Professor - <?= SITE_NAME ?></title>
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
        <h1>Editar Professor</h1>
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
            <div class="alert alert-success">Professor editado com sucesso! <a href="listar.php">Voltar à lista</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($professor['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Disciplina</label>
                    <input type="text" class="form-control" id="subject" name="subject" value="<?= htmlspecialchars($professor['subject'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
