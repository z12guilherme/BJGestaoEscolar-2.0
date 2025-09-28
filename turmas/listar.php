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
$sql = "SELECT t.id, t.name, t.year, p.name as professor_name 
        FROM turmas t 
        LEFT JOIN professores p ON t.professor_id = p.id";
$params = [];
$where = [];

if (in_array($role, ['diretor', 'secretario'])) {
    if ($user_escola_id) {
        $where[] = "t.escola_id = ?";
        $params[] = $user_escola_id;
    }
} elseif ($role === 'professor') {
    $prof_id = getProfessorIdByUserId($pdo, $user_id);
    if ($prof_id) {
        $sql .= " JOIN professor_turma pt ON t.id = pt.turma_id";
        $where[] = "pt.professor_id = ?";
        $params[] = $prof_id;
    } else {
        $turmas = []; // No access
        $sql = ""; // Avoid execution
    }
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY t.name";

if (!isset($turmas)) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $turmas = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Turmas - <?= SITE_NAME ?></title>
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
        <h1>Turmas</h1>
        <a href="cadastrar.php" class="btn btn-success mb-3">Cadastrar Nova Turma</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Ano</th>
                    <th>Professor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($turmas as $turma): ?>
                    <tr>
                        <td><?= htmlspecialchars($turma['name']) ?></td>
                        <td><?= htmlspecialchars($turma['year'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($turma['professor_name'] ?? '-') ?></td>
                        <td>
                            <a href="editar.php?id=<?= $turma['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="manage.php?id=<?= $turma['id'] ?>" class="btn btn-sm btn-info">Gerenciar</a>
                            <form method="post" action="deletar.php" class="d-inline" onsubmit="return confirm('Tem certeza? Isso deletará alunos e notas relacionadas.')">
                                <input type="hidden" name="id" value="<?= $turma['id'] ?>">
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
