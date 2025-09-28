<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
<?php
session_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

requireRole(['root', 'secretario', 'diretor', 'admin', 'professor']);

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$pdo = getDBConnection();

// Get user's escola_id if applicable
$user_escola_id = null;
if (in_array($role, ['secretario', 'diretor', 'admin', 'professor'])) {
    $stmt = $pdo->prepare("SELECT escola_id FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_escola_id = $stmt->fetchColumn();
}

// Build query with filters
$sql = "SELECT a.id, a.name, a.birth_date, t.name as turma_name 
        FROM alunos a 
        LEFT JOIN turmas t ON a.turma_id = t.id 
        JOIN usuarios u ON a.user_id = u.id";
$params = [];
$where = [];

if (in_array($role, ['diretor', 'secretario'])) {
    if ($user_escola_id) {
        $where[] = "u.escola_id = ?";
        $params[] = $user_escola_id;
    }
} elseif ($role === 'professor') {
    $prof_id = getProfessorIdByUserId($pdo, $user_id);
    if ($prof_id) {
        $sql .= " JOIN professor_turma pt ON t.id = pt.turma_id";
        $where[] = "pt.professor_id = ?";
        $params[] = $prof_id;
    } else {
        $alunos = []; // No access
        $sql = ""; // Avoid execution
    }
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY a.name";

if (!isset($alunos)) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $alunos = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Alunos - <?= SITE_NAME ?></title>
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
        <h1>Alunos</h1>
        <a href="cadastrar.php" class="btn btn-success mb-3">Cadastrar Novo Aluno</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Turma</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['name']) ?></td>
                        <td><?= $aluno['birth_date'] ? htmlspecialchars($aluno['birth_date']) : '-' ?></td>
                        <td><?= htmlspecialchars($aluno['turma_name'] ?? '-') ?></td>
                        <td>
                            <a href="editar.php?id=<?= $aluno['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <form method="post" action="deletar.php" class="d-inline" onsubmit="return confirm('Tem certeza?')">
                                <input type="hidden" name="id" value="<?= $aluno['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Deletar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<<<<<<< HEAD
=======
<?php
session_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

requireRole(['root', 'secretario', 'diretor', 'admin', 'professor']);

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$pdo = getDBConnection();

// Get user's escola_id if applicable
$user_escola_id = null;
if (in_array($role, ['secretario', 'diretor', 'admin', 'professor'])) {
    $stmt = $pdo->prepare("SELECT escola_id FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_escola_id = $stmt->fetchColumn();
}

// Build query with filters
$sql = "SELECT a.id, a.name, a.birth_date, t.name as turma_name 
        FROM alunos a 
        LEFT JOIN turmas t ON a.turma_id = t.id 
        JOIN usuarios u ON a.user_id = u.id";
$params = [];
$where = [];

if (in_array($role, ['diretor', 'secretario'])) {
    if ($user_escola_id) {
        $where[] = "u.escola_id = ?";
        $params[] = $user_escola_id;
    }
} elseif ($role === 'professor') {
    $prof_id = getProfessorIdByUserId($pdo, $user_id);
    if ($prof_id) {
        $sql .= " JOIN professor_turma pt ON t.id = pt.turma_id";
        $where[] = "pt.professor_id = ?";
        $params[] = $prof_id;
    } else {
        $alunos = []; // No access
        $sql = ""; // Avoid execution
    }
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY a.name";

if (!isset($alunos)) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $alunos = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Alunos - <?= SITE_NAME ?></title>
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
        <h1>Alunos</h1>
        <a href="cadastrar.php" class="btn btn-success mb-3">Cadastrar Novo Aluno</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Turma</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['name']) ?></td>
                        <td><?= $aluno['birth_date'] ? htmlspecialchars($aluno['birth_date']) : '-' ?></td>
                        <td><?= htmlspecialchars($aluno['turma_name'] ?? '-') ?></td>
                        <td>
                            <a href="editar.php?id=<?= $aluno['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <form method="post" action="deletar.php" class="d-inline" onsubmit="return confirm('Tem certeza?')">
                                <input type="hidden" name="id" value="<?= $aluno['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Deletar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
>>>>>>> meu_branch_backup
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
