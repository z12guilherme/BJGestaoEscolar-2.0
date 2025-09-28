<<<<<<< HEAD
<?php
session_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

requireRole(['root']);

$pdo = getDBConnection();

// Fetch escolas
$escolas = $pdo->query("SELECT id, nome FROM escolas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Roles
$roles = ['root', 'secretario', 'diretor', 'admin', 'professor', 'aluno'];

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $escola_id = intval($_POST['escola_id'] ?? 0);
    $turma_id = intval($_POST['turma_id'] ?? 0);
    $name = trim($_POST['name'] ?? ''); // For professor/aluno

    // Validations
    if (empty($username) || empty($password) || empty($role)) {
        $error = 'Campos obrigatórios ausentes.';
    } elseif (strlen($password) < 6) {
        $error = 'Senha deve ter pelo menos 6 caracteres.';
    } elseif ($role === 'diretor' && !$escola_id) {
        $error = 'Escola obrigatória para diretor.';
    } elseif (in_array($role, ['professor', 'aluno']) && empty($name)) {
        $error = 'Nome obrigatório para professor ou aluno.';
    } else {
        // Check username unique
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username já existe.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, escola_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hash, $role, ($role === 'diretor' ? $escola_id : null)]);
            $user_id = $pdo->lastInsertId();

            // Create ficha for professor or aluno
            if ($role === 'professor') {
                $stmt = $pdo->prepare("INSERT INTO professores (name, user_id) VALUES (?, ?)");
                $stmt->execute([$name, $user_id]);
                $prof_id = $pdo->lastInsertId();
                if ($turma_id > 0) {
                    $stmt = $pdo->prepare("INSERT INTO professor_turma (professor_id, turma_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE id=id");
                    $stmt->execute([$prof_id, $turma_id]);
                }
            } elseif ($role === 'aluno') {
                $stmt = $pdo->prepare("INSERT INTO alunos (name, user_id, turma_id) VALUES (?, ?, ?)");
                $stmt->execute([$name, $user_id, $turma_id]);
            }

            $success = 'Usuário criado com sucesso!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../dashboard.php"><?= SITE_NAME ?></a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Criar Novo Usuário (Root)</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" id="createUserForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required onchange="updateForm()">
                    <option value="">Selecione uma role</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r ?>"><?= ucfirst($r) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3" id="nameBlock" style="display:none;">
                <label for="name" class="form-label">Nome Completo (para Professor/Aluno)</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3" id="escolaBlock" style="display:none;">
                <label for="escola_id" class="form-label">Escola</label>
                <select id="escola_id" name="escola_id" class="form-select" onchange="loadTurmas()">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($escolas as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3" id="turmaBlock" style="display:none;">
                <label for="turma_id" class="form-label">Turma</label>
                <select id="turma_id" name="turma_id" class="form-select">
                    <option value="">-- Selecione --</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Criar Usuário</button>
            <a href="../dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateForm() {
            const role = document.getElementById('role').value;
            const nameBlock = document.getElementById('nameBlock');
            const escolaBlock = document.getElementById('escolaBlock');
            const turmaBlock = document.getElementById('turmaBlock');

            nameBlock.style.display = (role === 'professor' || role === 'aluno') ? 'block' : 'none';
            escolaBlock.style.display = (role === 'diretor' || role === 'professor' || role === 'aluno') ? 'block' : 'none';
            turmaBlock.style.display = (role === 'professor' || role === 'aluno') ? 'block' : 'none';

            if (role !== 'diretor' && role !== 'professor' && role !== 'aluno') {
                document.getElementById('escola_id').value = '';
                document.getElementById('turma_id').innerHTML = '<option value="">-- Selecione --</option>';
            }
        }

        function loadTurmas() {
            const escolaId = document.getElementById('escola_id').value;
            const turmaSelect = document.getElementById('turma_id');
            if (escolaId) {
                fetch(`../ajax/get_turmas.php?escola_id=${escolaId}`)
                    .then(response => response.json())
                    .then(data => {
                        turmaSelect.innerHTML = '<option value="">-- Selecione --</option>';
                        data.forEach(turma => {
                            const option = document.createElement('option');
                            option.value = turma.id;
                            option.textContent = turma.name;
                            turmaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                turmaSelect.innerHTML = '<option value="">-- Selecione --</option>';
            }
        }

        // Initial call
        updateForm();
    </script>
</body>
</html>
=======
<?php
session_start();
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

requireRole(['root']);

$pdo = getDBConnection();

// Fetch escolas
$escolas = $pdo->query("SELECT id, nome FROM escolas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Roles
$roles = ['root', 'secretario', 'diretor', 'admin', 'professor', 'aluno'];

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $escola_id = intval($_POST['escola_id'] ?? 0);
    $turma_id = intval($_POST['turma_id'] ?? 0);
    $name = trim($_POST['name'] ?? ''); // For professor/aluno

    // Validations
    if (empty($username) || empty($password) || empty($role)) {
        $error = 'Campos obrigatórios ausentes.';
    } elseif (strlen($password) < 6) {
        $error = 'Senha deve ter pelo menos 6 caracteres.';
    } elseif ($role === 'diretor' && !$escola_id) {
        $error = 'Escola obrigatória para diretor.';
    } elseif (in_array($role, ['professor', 'aluno']) && empty($name)) {
        $error = 'Nome obrigatório para professor ou aluno.';
    } else {
        // Check username unique
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username já existe.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, escola_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hash, $role, ($role === 'diretor' ? $escola_id : null)]);
            $user_id = $pdo->lastInsertId();

            // Create ficha for professor or aluno
            if ($role === 'professor') {
                $stmt = $pdo->prepare("INSERT INTO professores (name, user_id) VALUES (?, ?)");
                $stmt->execute([$name, $user_id]);
                $prof_id = $pdo->lastInsertId();
                if ($turma_id > 0) {
                    $stmt = $pdo->prepare("INSERT INTO professor_turma (professor_id, turma_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE id=id");
                    $stmt->execute([$prof_id, $turma_id]);
                }
            } elseif ($role === 'aluno') {
                $stmt = $pdo->prepare("INSERT INTO alunos (name, user_id, turma_id) VALUES (?, ?, ?)");
                $stmt->execute([$name, $user_id, $turma_id]);
            }

            $success = 'Usuário criado com sucesso!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../dashboard.php"><?= SITE_NAME ?></a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Criar Novo Usuário (Root)</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" id="createUserForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required onchange="updateForm()">
                    <option value="">Selecione uma role</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r ?>"><?= ucfirst($r) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3" id="nameBlock" style="display:none;">
                <label for="name" class="form-label">Nome Completo (para Professor/Aluno)</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3" id="escolaBlock" style="display:none;">
                <label for="escola_id" class="form-label">Escola</label>
                <select id="escola_id" name="escola_id" class="form-select" onchange="loadTurmas()">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($escolas as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3" id="turmaBlock" style="display:none;">
                <label for="turma_id" class="form-label">Turma</label>
                <select id="turma_id" name="turma_id" class="form-select">
                    <option value="">-- Selecione --</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Criar Usuário</button>
            <a href="../dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateForm() {
            const role = document.getElementById('role').value;
            const nameBlock = document.getElementById('nameBlock');
            const escolaBlock = document.getElementById('escolaBlock');
            const turmaBlock = document.getElementById('turmaBlock');

            nameBlock.style.display = (role === 'professor' || role === 'aluno') ? 'block' : 'none';
            escolaBlock.style.display = (role === 'diretor' || role === 'professor' || role === 'aluno') ? 'block' : 'none';
            turmaBlock.style.display = (role === 'professor' || role === 'aluno') ? 'block' : 'none';

            if (role !== 'diretor' && role !== 'professor' && role !== 'aluno') {
                document.getElementById('escola_id').value = '';
                document.getElementById('turma_id').innerHTML = '<option value="">-- Selecione --</option>';
            }
        }

        function loadTurmas() {
            const escolaId = document.getElementById('escola_id').value;
            const turmaSelect = document.getElementById('turma_id');
            if (escolaId) {
                fetch(`../ajax/get_turmas.php?escola_id=${escolaId}`)
                    .then(response => response.json())
                    .then(data => {
                        turmaSelect.innerHTML = '<option value="">-- Selecione --</option>';
                        data.forEach(turma => {
                            const option = document.createElement('option');
                            option.value = turma.id;
                            option.textContent = turma.name;
                            turmaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                turmaSelect.innerHTML = '<option value="">-- Selecione --</option>';
            }
        }

        // Initial call
        updateForm();
    </script>
</body>
</html>
>>>>>>> meu_branch_backup
