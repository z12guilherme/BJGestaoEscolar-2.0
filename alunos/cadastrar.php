<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'Professor'])) {
    header('Location: ../index.php');
    exit;
}

$pdo = getDBConnection();
$errors = [];
$success = false;

// Buscar turmas
$stmt = $pdo->query("SELECT id, name FROM turmas ORDER BY name");
$turmas = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $birth_date = $_POST['birth_date'] ?? '';
    $turma_id = $_POST['turma_id'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validação
    if (empty($name) || strlen($name) < 3) {
        $errors[] = 'Nome é obrigatório e deve ter pelo menos 3 caracteres.';
    }
    if (!empty($username)) {
        if (strlen($username) < 3) {
            $errors[] = 'Nome de usuário deve ter pelo menos 3 caracteres.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
        }
    }

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            // Inserir aluno
            $stmt = $pdo->prepare("INSERT INTO alunos (name, birth_date, turma_id) VALUES (?, ?, ?)");
            $stmt->execute([$name, $birth_date ?: null, $turma_id ?: null]);
            $aluno_id = $pdo->lastInsertId();

            // Se username fornecido, criar usuário
            if (!empty($username)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role) VALUES (?, ?, 'Aluno')");
                $stmt->execute([$username, $hash]);
                $user_id = $pdo->lastInsertId();

                // Vincular user ao aluno
                $stmt = $pdo->prepare("UPDATE alunos SET user_id = ? WHERE id = ?");
                $stmt->execute([$user_id, $aluno_id]);
            }

            $pdo->commit();
            $success = true;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Aluno - <?= SITE_NAME ?></title>
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
        <h1>Cadastrar Aluno</h1>
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
            <div class="alert alert-success">Aluno cadastrado com sucesso! <a href="listar.php">Voltar à lista</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date">
                </div>
                <div class="mb-3">
                    <label for="turma_id" class="form-label">Turma</label>
                    <select class="form-control" id="turma_id" name="turma_id">
                        <option value="">Selecione</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nome de Usuário (opcional, para login)</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha (obrigatória se usuário informado)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
