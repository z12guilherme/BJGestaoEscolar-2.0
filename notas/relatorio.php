<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
<?php
session_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

requireRole(['root', 'secretario', 'diretor', 'admin', 'professor', 'aluno']);

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$pdo = getDBConnection();

// Get user's escola_id if applicable
$user_escola_id = null;
if (in_array($role, ['secretario', 'diretor', 'admin', 'professor', 'aluno'])) {
    $stmt = $pdo->prepare("SELECT escola_id FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_escola_id = $stmt->fetchColumn();
}

$aluno_id = intval($_GET['aluno_id'] ?? 0);
$turma_id = intval($_GET['turma_id'] ?? 0);

$notas = [];

// Base query parts
$base_sql = "SELECT n.id, n.semestre, n.valor, t.name as turma_name, a.name as aluno_name, u.escola_id 
             FROM notas n 
             JOIN alunos a ON n.aluno_id = a.id 
             JOIN turmas t ON n.turma_id = t.id 
             JOIN usuarios u ON a.user_id = u.id";
$params = [];
$where = [];

// Role-based filters
if ($role === 'aluno') {
    // Only own notes
    $own_aluno_id = null;
    $stmt = $pdo->prepare("SELECT id FROM alunos WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $own_aluno_id = $stmt->fetchColumn();
    if ($own_aluno_id) {
        $where[] = "n.aluno_id = ?";
        $params[] = $own_aluno_id;
    } else {
        $notas = []; // No ficha
    }
} elseif (in_array($role, ['diretor', 'secretario'])) {
    if ($user_escola_id) {
        $where[] = "u.escola_id = ?";
        $params[] = $user_escola_id;
    }
} elseif ($role === 'professor') {
    $prof_id = getProfessorIdByUserId($pdo, $user_id);
    if ($prof_id) {
        $base_sql .= " JOIN professor_turma pt ON t.id = pt.turma_id";
        $where[] = "pt.professor_id = ?";
        $params[] = $prof_id;
    } else {
        $notas = []; // No ficha
    }
}

// Specific filters
if ($aluno_id > 0) {
    $where[] = "n.aluno_id = ?";
    $params[] = $aluno_id;
    // Verify access to this aluno
    if ($role === 'professor') {
        $stmt = $pdo->prepare("SELECT a.id FROM alunos a JOIN turmas t ON a.turma_id = t.id JOIN professor_turma pt ON t.id = pt.turma_id WHERE a.id = ? AND pt.professor_id = ?");
        $stmt->execute([$aluno_id, $prof_id]);
        if (!$stmt->fetch()) {
            $notas = [];
        }
    }
} elseif ($turma_id > 0) {
    $where[] = "n.turma_id = ?";
    $params[] = $turma_id;
    // Verify access to this turma
    if ($role === 'professor') {
        $stmt = $pdo->prepare("SELECT t.id FROM turmas t JOIN professor_turma pt ON t.id = pt.turma_id WHERE t.id = ? AND pt.professor_id = ?");
        $stmt->execute([$turma_id, $prof_id]);
        if (!$stmt->fetch()) {
            $notas = [];
        }
    }
}

if (!empty($where)) {
    $base_sql .= " WHERE " . implode(' AND ', $where);
}

$base_sql .= " ORDER BY t.name, a.name, n.semestre";

if (empty($notas)) {
    $stmt = $pdo->prepare($base_sql);
    $stmt->execute($params);
    $notas = $stmt->fetchAll();
}

// Filtered lists for selects
$alunos_list = [];
$turmas_list = [];

if (in_array($role, ['root', 'admin'])) {
    // Full lists
    $stmt = $pdo->query("SELECT id, name FROM alunos ORDER BY name");
    $alunos_list = $stmt->fetchAll();
    $stmt = $pdo->query("SELECT id, name FROM turmas ORDER BY name");
    $turmas_list = $stmt->fetchAll();
} elseif (in_array($role, ['diretor', 'secretario'])) {
    if ($user_escola_id) {
        $stmt = $pdo->prepare("SELECT a.id, a.name FROM alunos a JOIN usuarios u ON a.user_id = u.id WHERE u.escola_id = ? ORDER BY a.name");
        $stmt->execute([$user_escola_id]);
        $alunos_list = $stmt->fetchAll();
        $stmt = $pdo->prepare("SELECT t.id, t.name FROM turmas t WHERE t.escola_id = ? ORDER BY t.name");
        $stmt->execute([$user_escola_id]);
        $turmas_list = $stmt->fetchAll();
    }
} elseif ($role === 'professor') {
    if ($prof_id) {
        $stmt = $pdo->prepare("SELECT a.id, a.name FROM alunos a JOIN turmas t ON a.turma_id = t.id JOIN professor_turma pt ON t.id = pt.turma_id WHERE pt.professor_id = ? ORDER BY a.name");
        $stmt->execute([$prof_id]);
        $alunos_list = $stmt->fetchAll();
        $stmt = $pdo->prepare("SELECT DISTINCT t.id, t.name FROM turmas t JOIN professor_turma pt ON t.id = pt.turma_id WHERE pt.professor_id = ? ORDER BY t.name");
        $stmt->execute([$prof_id]);
        $turmas_list = $stmt->fetchAll();
    }
} elseif ($role === 'aluno') {
    $own_aluno_id = null;
    $stmt = $pdo->prepare("SELECT id FROM alunos WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $own_aluno_id = $stmt->fetchColumn();
    if ($own_aluno_id) {
        $alunos_list = [['id' => $own_aluno_id, 'name' => 'Eu']];
        $stmt = $pdo->prepare("SELECT DISTINCT t.id, t.name FROM turmas t JOIN alunos a ON t.id = a.turma_id WHERE a.id = ? ORDER BY t.name");
        $stmt->execute([$own_aluno_id]);
        $turmas_list = $stmt->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios de Notas - <?= SITE_NAME ?></title>
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
        <h1>Relatórios de Notas</h1>
        <form method="get" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="aluno_id" class="form-label">Filtrar por Aluno</label>
                    <select class="form-control" id="aluno_id" name="aluno_id" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <?php foreach ($alunos_list as $aluno): ?>
                            <option value="<?= $aluno['id'] ?>" <?= ($aluno_id == $aluno['id'] ? 'selected' : '') ?>><?= htmlspecialchars($aluno['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="turma_id" class="form-label">Filtrar por Turma</label>
                    <select class="form-control" id="turma_id" name="turma_id" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <?php foreach ($turmas_list as $turma): ?>
                            <option value="<?= $turma['id'] ?>" <?= ($turma_id == $turma['id'] ? 'selected' : '') ?>><?= htmlspecialchars($turma['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <?php if ($notas): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <?php if (!$aluno_id): ?><th>Aluno</th><?php endif; ?>
                        <?php if (!$turma_id): ?><th>Turma</th><?php endif; ?>
                        <th>Semestre</th>
                        <th>Nota</th>
                        <?php if (in_array($role, ['root', 'secretario', 'diretor', 'admin', 'professor'])): ?><th>Ações</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notas as $nota): ?>
                        <tr>
                            <?php if (!$aluno_id): ?><td><?= htmlspecialchars($nota['aluno_name']) ?></td><?php endif; ?>
                            <?php if (!$turma_id): ?><td><?= htmlspecialchars($nota['turma_name']) ?></td><?php endif; ?>
                            <td><?= htmlspecialchars($nota['semestre']) ?></td>
                            <td><?= htmlspecialchars($nota['valor']) ?></td>
                            <?php if (in_array($role, ['root', 'secretario', 'diretor', 'admin', 'professor'])): ?>
                                <td>
                                    <a href="editar.php?id=<?= $nota['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma nota encontrada.</p>
        <?php endif; ?>
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

requireRole(['root', 'secretario', 'diretor', 'admin', 'professor', 'aluno']);

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$pdo = getDBConnection();

// Get user's escola_id if applicable
$user_escola_id = null;
if (in_array($role, ['secretario', 'diretor', 'admin', 'professor', 'aluno'])) {
    $stmt = $pdo->prepare("SELECT escola_id FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_escola_id = $stmt->fetchColumn();
}

$aluno_id = intval($_GET['aluno_id'] ?? 0);
$turma_id = intval($_GET['turma_id'] ?? 0);

$notas = [];

// Base query parts
$base_sql = "SELECT n.id, n.semestre, n.valor, t.name as turma_name, a.name as aluno_name, u.escola_id 
             FROM notas n 
             JOIN alunos a ON n.aluno_id = a.id 
             JOIN turmas t ON n.turma_id = t.id 
             JOIN usuarios u ON a.user_id = u.id";
$params = [];
$where = [];

// Role-based filters
if ($role === 'aluno') {
    // Only own notes
    $own_aluno_id = null;
    $stmt = $pdo->prepare("SELECT id FROM alunos WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $own_aluno_id = $stmt->fetchColumn();
    if ($own_aluno_id) {
        $where[] = "n.aluno_id = ?";
        $params[] = $own_aluno_id;
    } else {
        $notas = []; // No ficha
    }
} elseif (in_array($role, ['diretor', 'secretario'])) {
    if ($user_escola_id) {
        $where[] = "u.escola_id = ?";
        $params[] = $user_escola_id;
    }
} elseif ($role === 'professor') {
    $prof_id = getProfessorIdByUserId($pdo, $user_id);
    if ($prof_id) {
        $base_sql .= " JOIN professor_turma pt ON t.id = pt.turma_id";
        $where[] = "pt.professor_id = ?";
        $params[] = $prof_id;
    } else {
        $notas = []; // No ficha
    }
}

// Specific filters
if ($aluno_id > 0) {
    $where[] = "n.aluno_id = ?";
    $params[] = $aluno_id;
    // Verify access to this aluno
    if ($role === 'professor') {
        $stmt = $pdo->prepare("SELECT a.id FROM alunos a JOIN turmas t ON a.turma_id = t.id JOIN professor_turma pt ON t.id = pt.turma_id WHERE a.id = ? AND pt.professor_id = ?");
        $stmt->execute([$aluno_id, $prof_id]);
        if (!$stmt->fetch()) {
            $notas = [];
        }
    }
} elseif ($turma_id > 0) {
    $where[] = "n.turma_id = ?";
    $params[] = $turma_id;
    // Verify access to this turma
    if ($role === 'professor') {
        $stmt = $pdo->prepare("SELECT t.id FROM turmas t JOIN professor_turma pt ON t.id = pt.turma_id WHERE t.id = ? AND pt.professor_id = ?");
        $stmt->execute([$turma_id, $prof_id]);
        if (!$stmt->fetch()) {
            $notas = [];
        }
    }
}

if (!empty($where)) {
    $base_sql .= " WHERE " . implode(' AND ', $where);
}

$base_sql .= " ORDER BY t.name, a.name, n.semestre";

if (empty($notas)) {
    $stmt = $pdo->prepare($base_sql);
    $stmt->execute($params);
    $notas = $stmt->fetchAll();
}

// Filtered lists for selects
$alunos_list = [];
$turmas_list = [];

if (in_array($role, ['root', 'admin'])) {
    // Full lists
    $stmt = $pdo->query("SELECT id, name FROM alunos ORDER BY name");
    $alunos_list = $stmt->fetchAll();
    $stmt = $pdo->query("SELECT id, name FROM turmas ORDER BY name");
    $turmas_list = $stmt->fetchAll();
} elseif (in_array($role, ['diretor', 'secretario'])) {
    if ($user_escola_id) {
        $stmt = $pdo->prepare("SELECT a.id, a.name FROM alunos a JOIN usuarios u ON a.user_id = u.id WHERE u.escola_id = ? ORDER BY a.name");
        $stmt->execute([$user_escola_id]);
        $alunos_list = $stmt->fetchAll();
        $stmt = $pdo->prepare("SELECT t.id, t.name FROM turmas t WHERE t.escola_id = ? ORDER BY t.name");
        $stmt->execute([$user_escola_id]);
        $turmas_list = $stmt->fetchAll();
    }
} elseif ($role === 'professor') {
    if ($prof_id) {
        $stmt = $pdo->prepare("SELECT a.id, a.name FROM alunos a JOIN turmas t ON a.turma_id = t.id JOIN professor_turma pt ON t.id = pt.turma_id WHERE pt.professor_id = ? ORDER BY a.name");
        $stmt->execute([$prof_id]);
        $alunos_list = $stmt->fetchAll();
        $stmt = $pdo->prepare("SELECT DISTINCT t.id, t.name FROM turmas t JOIN professor_turma pt ON t.id = pt.turma_id WHERE pt.professor_id = ? ORDER BY t.name");
        $stmt->execute([$prof_id]);
        $turmas_list = $stmt->fetchAll();
    }
} elseif ($role === 'aluno') {
    $own_aluno_id = null;
    $stmt = $pdo->prepare("SELECT id FROM alunos WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $own_aluno_id = $stmt->fetchColumn();
    if ($own_aluno_id) {
        $alunos_list = [['id' => $own_aluno_id, 'name' => 'Eu']];
        $stmt = $pdo->prepare("SELECT DISTINCT t.id, t.name FROM turmas t JOIN alunos a ON t.id = a.turma_id WHERE a.id = ? ORDER BY t.name");
        $stmt->execute([$own_aluno_id]);
        $turmas_list = $stmt->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios de Notas - <?= SITE_NAME ?></title>
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
        <h1>Relatórios de Notas</h1>
        <form method="get" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="aluno_id" class="form-label">Filtrar por Aluno</label>
                    <select class="form-control" id="aluno_id" name="aluno_id" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <?php foreach ($alunos_list as $aluno): ?>
                            <option value="<?= $aluno['id'] ?>" <?= ($aluno_id == $aluno['id'] ? 'selected' : '') ?>><?= htmlspecialchars($aluno['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="turma_id" class="form-label">Filtrar por Turma</label>
                    <select class="form-control" id="turma_id" name="turma_id" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <?php foreach ($turmas_list as $turma): ?>
                            <option value="<?= $turma['id'] ?>" <?= ($turma_id == $turma['id'] ? 'selected' : '') ?>><?= htmlspecialchars($turma['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <?php if ($notas): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <?php if (!$aluno_id): ?><th>Aluno</th><?php endif; ?>
                        <?php if (!$turma_id): ?><th>Turma</th><?php endif; ?>
                        <th>Semestre</th>
                        <th>Nota</th>
                        <?php if (in_array($role, ['root', 'secretario', 'diretor', 'admin', 'professor'])): ?><th>Ações</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notas as $nota): ?>
                        <tr>
                            <?php if (!$aluno_id): ?><td><?= htmlspecialchars($nota['aluno_name']) ?></td><?php endif; ?>
                            <?php if (!$turma_id): ?><td><?= htmlspecialchars($nota['turma_name']) ?></td><?php endif; ?>
                            <td><?= htmlspecialchars($nota['semestre']) ?></td>
                            <td><?= htmlspecialchars($nota['valor']) ?></td>
                            <?php if (in_array($role, ['root', 'secretario', 'diretor', 'admin', 'professor'])): ?>
                                <td>
                                    <a href="editar.php?id=<?= $nota['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma nota encontrada.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
>>>>>>> meu_branch_backup
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
