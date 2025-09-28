<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
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

// Buscar turmas (para Professor, apenas suas)
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
if ($role === 'Professor') {
    $stmt = $pdo->prepare("SELECT id, name FROM turmas WHERE professor_id = (SELECT id FROM professores WHERE user_id = ?) ORDER BY name");
    $stmt->execute([$user_id]);
} else {
    $stmt = $pdo->query("SELECT id, name FROM turmas ORDER BY name");
}
$turmas = $stmt->fetchAll();

$turma_id = $_GET['turma_id'] ?? null;
$alunos = [];
if ($turma_id) {
    $stmt = $pdo->prepare("SELECT id, name FROM alunos WHERE turma_id = ? ORDER BY name");
    $stmt->execute([$turma_id]);
    $alunos = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_id = $_POST['aluno_id'] ?? '';
    $turma_id_post = $_POST['turma_id'] ?? '';
    $semestre = trim($_POST['semestre'] ?? '');
    $valor = $_POST['valor'] ?? '';

    // Validação
    if (empty($aluno_id) || empty($turma_id_post) || empty($semestre) || !is_numeric($valor)) {
        $errors[] = 'Todos os campos são obrigatórios e valor deve ser numérico.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notas (aluno_id, turma_id, semestre, valor) VALUES (?, ?, ?, ?)");
            $stmt->execute([$aluno_id, $turma_id_post, $semestre, $valor]);
            $success = true;
        } catch (Exception $e) {
            $errors[] = 'Erro ao lançar nota: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançar Nota - <?= SITE_NAME ?></title>
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
        <h1>Lançar Nota</h1>
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
            <div class="alert alert-success">Nota lançada com sucesso! <a href="relatorio.php">Ver Relatórios</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="turma_id" class="form-label">Turma *</label>
                    <select class="form-control" id="turma_id" name="turma_id" onchange="this.form.submit()" required>
                        <option value="">Selecione Turma</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?= $turma['id'] ?>" <?= $turma_id == $turma['id'] ? 'selected' : '' ?>><?= htmlspecialchars($turma['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($alunos): ?>
                    <div class="mb-3">
                        <label for="aluno_id" class="form-label">Aluno *</label>
                        <select class="form-control" id="aluno_id" name="aluno_id" required>
                            <option value="">Selecione Aluno</option>
                            <?php foreach ($alunos as $aluno): ?>
                                <option value="<?= $aluno['id'] ?>"><?= htmlspecialchars($aluno['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semestre" class="form-label">Semestre *</label>
                        <input type="text" class="form-control" id="semestre" name="semestre" placeholder="Ex: 2025/1" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor" class="form-label">Valor *</label>
                        <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Lançar</button>
                <?php else: ?>
                    <p>Selecione uma turma com alunos.</p>
                <?php endif; ?>
                <a href="../turmas/listar.php" class="btn btn-secondary">Voltar</a>
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
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'Professor'])) {
    header('Location: ../index.php');
    exit;
}

$pdo = getDBConnection();
$errors = [];
$success = false;

// Buscar turmas (para Professor, apenas suas)
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
if ($role === 'Professor') {
    $stmt = $pdo->prepare("SELECT id, name FROM turmas WHERE professor_id = (SELECT id FROM professores WHERE user_id = ?) ORDER BY name");
    $stmt->execute([$user_id]);
} else {
    $stmt = $pdo->query("SELECT id, name FROM turmas ORDER BY name");
}
$turmas = $stmt->fetchAll();

$turma_id = $_GET['turma_id'] ?? null;
$alunos = [];
if ($turma_id) {
    $stmt = $pdo->prepare("SELECT id, name FROM alunos WHERE turma_id = ? ORDER BY name");
    $stmt->execute([$turma_id]);
    $alunos = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_id = $_POST['aluno_id'] ?? '';
    $turma_id_post = $_POST['turma_id'] ?? '';
    $semestre = trim($_POST['semestre'] ?? '');
    $valor = $_POST['valor'] ?? '';

    // Validação
    if (empty($aluno_id) || empty($turma_id_post) || empty($semestre) || !is_numeric($valor)) {
        $errors[] = 'Todos os campos são obrigatórios e valor deve ser numérico.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("INSERT INTO notas (aluno_id, turma_id, semestre, valor) VALUES (?, ?, ?, ?)");
            $stmt->execute([$aluno_id, $turma_id_post, $semestre, $valor]);
            $success = true;
        } catch (Exception $e) {
            $errors[] = 'Erro ao lançar nota: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançar Nota - <?= SITE_NAME ?></title>
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
        <h1>Lançar Nota</h1>
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
            <div class="alert alert-success">Nota lançada com sucesso! <a href="relatorio.php">Ver Relatórios</a></div>
        <?php else: ?>
            <form method="post">
                <div class="mb-3">
                    <label for="turma_id" class="form-label">Turma *</label>
                    <select class="form-control" id="turma_id" name="turma_id" onchange="this.form.submit()" required>
                        <option value="">Selecione Turma</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?= $turma['id'] ?>" <?= $turma_id == $turma['id'] ? 'selected' : '' ?>><?= htmlspecialchars($turma['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($alunos): ?>
                    <div class="mb-3">
                        <label for="aluno_id" class="form-label">Aluno *</label>
                        <select class="form-control" id="aluno_id" name="aluno_id" required>
                            <option value="">Selecione Aluno</option>
                            <?php foreach ($alunos as $aluno): ?>
                                <option value="<?= $aluno['id'] ?>"><?= htmlspecialchars($aluno['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semestre" class="form-label">Semestre *</label>
                        <input type="text" class="form-control" id="semestre" name="semestre" placeholder="Ex: 2025/1" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor" class="form-label">Valor *</label>
                        <input type="number" step="0.01" class="form-control" id="valor" name="valor" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Lançar</button>
                <?php else: ?>
                    <p>Selecione uma turma com alunos.</p>
                <?php endif; ?>
                <a href="../turmas/listar.php" class="btn btn-secondary">Voltar</a>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
>>>>>>> meu_branch_backup
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
