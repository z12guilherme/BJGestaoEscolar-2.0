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
$sql = "SELECT p.id, p.name, p.subject, u.username 
        FROM professores p 
        JOIN usuarios u ON p.user_id = u.id";
$params = [];
$where = [];

if (in_array($role, ['diretor', 'secretario'])) {
    if ($user_escola_id) {
        $where[] = "u.escola_id = ?";
        $params[] = $user_escola_id;
    }
} elseif ($role === 'professor') {
    $where[] = "p.user_id = ?";
    $params[] = $user_id; // Only self
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY p.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$professores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Professores - <?= SITE_NAME ?></title>
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
        <h1>Professores</h1>
        <a href="cadastrar.php" class="btn btn-success mb-3">Cadastrar Novo Professor</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Disciplina</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($professores as $professor): ?>
                    <tr>
                        <td><?= htmlspecialchars($professor['name']) ?></td>
                        <td><?= htmlspecialchars($professor['subject'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($professor['username'] ?? '-') ?></td>
                        <td>
                            <a href="editar.php?id=<?= $professor['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <form method="post" action="deletar.php" class="d-inline" onsubmit="return confirm('Tem certeza?')">
                                <input type="hidden" name="id" value="<?= $professor['id'] ?>">
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
