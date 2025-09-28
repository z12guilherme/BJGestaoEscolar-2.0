<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../index.php');
    exit;
}

$pdo = getDBConnection();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validação
    if (empty($name) || strlen($name) < 3) {
        $errors[] = 'Nome é obrigatório e deve ter pelo menos 3 caracteres.';
    }
    if (empty($username) || strlen($username) < 3) {
        $errors[] = 'Nome de usuário é obrigatório e deve ter pelo menos 3 caracteres.';
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
    }

    // Verificar se username já existe
    if (!empty($username)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = 'Nome de usuário já existe.';
        }
    }

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            // Inserir professor
            $stmt = $pdo->prepare("INSERT INTO professores (name, subject) VALUES (?, ?)");
            $stmt->execute([$name, $subject ?: null]);
            $professor_id = $pdo->lastInsertId();

            // Criar usuário
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role) VALUES (?, ?, 'Professor')");
            $stmt->execute([$username, $hash]);
            $user_id = $pdo->lastInsertId();

            // Vincular user ao professor
            $stmt = $pdo->prepare("UPDATE professores SET user_id = ? WHERE id = ?");
            $stmt->execute([$user_id, $professor_id]);

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
    <title>Cadastrar Professor - <?= SITE_NAME ?></title>
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
        <h1>Cadastrar Professor</h1>
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
            <div class="alert alert-success">Professor cadastrado com sucesso! <a href="listar.php">Voltar à lista</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Disciplina</label>
                    <input type="text" class="form-control" id="subject" name="subject">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nome de Usuário *</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha *</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<<<<<<< HEAD
=======
<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../index.php');
    exit;
}

$pdo = getDBConnection();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validação
    if (empty($name) || strlen($name) < 3) {
        $errors[] = 'Nome é obrigatório e deve ter pelo menos 3 caracteres.';
    }
    if (empty($username) || strlen($username) < 3) {
        $errors[] = 'Nome de usuário é obrigatório e deve ter pelo menos 3 caracteres.';
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
    }

    // Verificar se username já existe
    if (!empty($username)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = 'Nome de usuário já existe.';
        }
    }

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            // Inserir professor
            $stmt = $pdo->prepare("INSERT INTO professores (name, subject) VALUES (?, ?)");
            $stmt->execute([$name, $subject ?: null]);
            $professor_id = $pdo->lastInsertId();

            // Criar usuário
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role) VALUES (?, ?, 'Professor')");
            $stmt->execute([$username, $hash]);
            $user_id = $pdo->lastInsertId();

            // Vincular user ao professor
            $stmt = $pdo->prepare("UPDATE professores SET user_id = ? WHERE id = ?");
            $stmt->execute([$user_id, $professor_id]);

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
    <title>Cadastrar Professor - <?= SITE_NAME ?></title>
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
        <h1>Cadastrar Professor</h1>
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
            <div class="alert alert-success">Professor cadastrado com sucesso! <a href="listar.php">Voltar à lista</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Disciplina</label>
                    <input type="text" class="form-control" id="subject" name="subject">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nome de Usuário *</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha *</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
>>>>>>> meu_branch_backup
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
