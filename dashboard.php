<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
<?php
session_start();
require_once 'auth.php';
require_once 'db.php';
requireLogin();
$pdo = getDBConnection();
$role = currentRole();

// =======================
// Funções de CRUD
// =======================
function getEscolas($pdo) {
    return $pdo->query("SELECT * FROM escolas ORDER BY id DESC")->fetchAll();
}

function getProfessores($pdo) {
    return $pdo->query("
        SELECT p.*, e.nome AS escola_nome
        FROM professores p
        LEFT JOIN escolas e ON p.escola_id = e.id
        ORDER BY p.id DESC
    ")->fetchAll();
}

function getTurmas($pdo) {
    return $pdo->query("
        SELECT t.*, p.name AS professor_nome, e.nome AS escola_nome
        FROM turmas t
        LEFT JOIN professores p ON t.professor_id = p.id
        LEFT JOIN escolas e ON t.escola_id = e.id
        ORDER BY t.id DESC
    ")->fetchAll();
}

function getAlunos($pdo) {
    return $pdo->query("
        SELECT a.*, t.name AS turma_nome
        FROM alunos a
        LEFT JOIN turmas t ON a.turma_id = t.id
        ORDER BY a.id DESC
    ")->fetchAll();
}

function getLaudos($pdo) {
    $stmt = $pdo->query("
        SELECT l.*, a.name AS aluno_nome, u.username AS criado_por
        FROM laudos l
        JOIN alunos a ON l.aluno_id = a.id
        JOIN usuarios u ON l.criado_por = u.id
        ORDER BY l.id DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// =======================
// Remoção
// =======================
if (isset($_GET['remove'])) {
    $table = $_GET['table'];
    $id = (int)$_GET['id'];
    $allowed = ['escolas', 'professores', 'turmas', 'alunos', 'laudos'];
    if (in_array($table, $allowed)) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: dashboard.php");
        exit;
    }
}

// =======================
// Dados iniciais
// =======================
$escolas = getEscolas($pdo);
$professores = getProfessores($pdo);
$turmas = getTurmas($pdo);
$alunos = getAlunos($pdo);
$user_id = $_SESSION['user_id'];
$laudos = getLaudos($pdo);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Dashboard - <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h1>Dashboard</h1>

<!-- Nav tabs -->
<ul class="nav nav-tabs" id="cadastroTabs" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#escola">Escolas</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#professor">Professores</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#turma">Turmas</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#aluno">Alunos</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#laudos">Laudos</button></li>
</ul>

<!-- Tab panes -->
<div class="tab-content mt-3">

  <!-- Escola -->
  <div class="tab-pane fade show active" id="escola">
    <form method="POST" action="salvar_escola.php" class="mb-3">
      <input type="text" name="nome" placeholder="Nome da Escola" class="form-control mb-2" required>
      <input type="text" name="endereco" placeholder="Endereço" class="form-control mb-2">
      <button class="btn btn-primary">Cadastrar Escola</button>
    </form>

    <h5>Escolas cadastradas:</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nome</th><th>Endereço</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach($escolas as $e): ?>
        <tr>
          <td><?= $e['id'] ?></td>
          <td><?= htmlspecialchars($e['nome']) ?></td>
          <td><?= htmlspecialchars($e['endereco']) ?></td>
          <td><a href="?remove=1&table=escolas&id=<?= $e['id'] ?>" class="btn btn-sm btn-danger">Remover</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Professor -->
  <div class="tab-pane fade" id="professor">
    <form method="POST" action="salvar_professor.php" class="mb-3">
      <input type="text" name="nome" placeholder="Nome" class="form-control mb-2" required>
      <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
      <select name="escola_id" class="form-control mb-2">
        <option value="">Escolha a Escola</option>
        <?php foreach($escolas as $e): ?>
        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary">Cadastrar Professor</button>
    </form>

    <h5>Professores cadastrados:</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Escola</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach($professores as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['email'] ?? '-') ?></td>
          <td><?= htmlspecialchars($p['escola_nome'] ?? '-') ?></td>
          <td><a href="?remove=1&table=professores&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger">Remover</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Turma -->
  <div class="tab-pane fade" id="turma">
    <form method="POST" action="salvar_turma.php" class="mb-3">
      <input type="text" name="nome" placeholder="Nome da Turma" class="form-control mb-2" required>
      <input type="number" name="ano" placeholder="Ano" class="form-control mb-2" required>
      <select name="professor_id" class="form-control mb-2">
        <option value="">Escolha o Professor</option>
        <?php foreach($professores as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <select name="escola_id" class="form-control mb-2">
        <option value="">Escolha a Escola</option>
        <?php foreach($escolas as $e): ?>
        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary">Cadastrar Turma</button>
    </form>

    <h5>Turmas cadastradas:</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nome</th><th>Ano</th><th>Professor</th><th>Escola</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach($turmas as $t): ?>
        <tr>
          <td><?= $t['id'] ?></td>
          <td><?= htmlspecialchars($t['name']) ?></td>
          <td><?= htmlspecialchars($t['year']) ?></td>
          <td><?= htmlspecialchars($t['professor_nome'] ?? '-') ?></td>
          <td><?= htmlspecialchars($t['escola_nome'] ?? '-') ?></td>
          <td><a href="?remove=1&table=turmas&id=<?= $t['id'] ?>" class="btn btn-sm btn-danger">Remover</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Aluno -->
  <div class="tab-pane fade" id="aluno">
    <form method="POST" action="salvar_aluno.php" class="mb-3">
      <input type="text" name="nome" placeholder="Nome" class="form-control mb-2" required>
      <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
      <select name="turma_id" class="form-control mb-2">
        <option value="">Escolha a Turma</option>
        <?php foreach($turmas as $t): ?>
        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary">Cadastrar Aluno</button>
    </form>

    <h5>Alunos cadastrados:</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Turma</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach($alunos as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= htmlspecialchars($a['name']) ?></td>
          <td><?= htmlspecialchars($a['email'] ?? '-') ?></td>
          <td><?= htmlspecialchars($a['turma_nome'] ?? '-') ?></td>
          <td><a href="?remove=1&table=alunos&id=<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmar remoção?')">Remover</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Laudos -->
  <div class="tab-pane fade" id="laudos">
    <?php if (in_array($role, ['professor', 'tutor', 'diretor'])): ?>
    <form method="POST" action="salvar_laudos.php" class="mb-3">
      <select name="aluno_id" class="form-control mb-2" required>
        <option value="">Selecione o Aluno</option>
        <?php foreach($alunos as $a): ?>
        <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <textarea name="descricao" placeholder="Descrição do Laudo" class="form-control mb-2" rows="4" required></textarea>
      <button class="btn btn-primary">Salvar Laudo</button>
    </form>
    <?php endif; ?>

    <h5>Laudos Cadastrados:</h5>
    <table class="table table-striped">
      <thead><tr><th>Aluno</th><th>Data</th><th>Descrição</th><th>Criado Por</th><th>Ações</th></tr></thead>
      <tbody>
        <?php if(!empty($laudos)): ?>
            <?php foreach($laudos as $l): ?>
            <tr>
              <td><?= htmlspecialchars($l['aluno_nome']) ?></td>
              <td><?= htmlspecialchars($l['data']) ?></td>
              <td><?= htmlspecialchars($l['descricao']) ?></td>
              <td><?= htmlspecialchars($l['criado_por']) ?></td>
              <td>
                <?php if($role === 'diretor' || $role === 'admin' || $user_id == $l['criado_por']): ?>
                    <a href="remover_laudos.php?id=<?= $l['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmar remoção?')">Remover</a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Nenhum laudo cadastrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<<<<<<< HEAD
=======
<?php
session_start();
require_once 'auth.php';
require_once 'db.php';
requireLogin();
$pdo = getDBConnection();
$role = currentRole();

// =======================
// Funções de CRUD
// =======================
function getEscolas($pdo) {
    return $pdo->query("SELECT * FROM escolas ORDER BY id DESC")->fetchAll();
}

function getProfessores($pdo) {
    return $pdo->query("
        SELECT p.*, e.nome AS escola_nome
        FROM professores p
        LEFT JOIN escolas e ON p.escola_id = e.id
        ORDER BY p.id DESC
    ")->fetchAll();
}

function getTurmas($pdo) {
    return $pdo->query("
        SELECT t.*, p.name AS professor_nome, e.nome AS escola_nome
        FROM turmas t
        LEFT JOIN professores p ON t.professor_id = p.id
        LEFT JOIN escolas e ON t.escola_id = e.id
        ORDER BY t.id DESC
    ")->fetchAll();
}

function getAlunos($pdo) {
    return $pdo->query("
        SELECT a.*, t.name AS turma_nome
        FROM alunos a
        LEFT JOIN turmas t ON a.turma_id = t.id
        ORDER BY a.id DESC
    ")->fetchAll();
}

// =======================
// Remoção
// =======================
if (isset($_GET['remove'])) {
    $table = $_GET['table'];
    $id = (int)$_GET['id'];
    $allowed = ['escolas', 'professores', 'turmas', 'alunos'];
    if (in_array($table, $allowed)) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: dashboard.php");
        exit;
    }
}

// =======================
// Dados iniciais
// =======================
$escolas = getEscolas($pdo);
$professores = getProfessores($pdo);
$turmas = getTurmas($pdo);
$alunos = getAlunos($pdo);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Dashboard - <?= SITE_NAME ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h1>Dashboard</h1>

<!-- Nav tabs -->
<ul class="nav nav-tabs" id="cadastroTabs" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#escola">Escolas</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#professor">Professores</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#turma">Turmas</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#aluno">Alunos</button></li>
</ul>

<!-- Tab panes -->
<div class="tab-content mt-3">

  <!-- Escola -->
  <div class="tab-pane fade show active" id="escola">
    <form method="POST" action="salvar_escola.php" class="mb-3">
      <input type="text" name="nome" placeholder="Nome da Escola" class="form-control mb-2" required>
      <input type="text" name="endereco" placeholder="Endereço" class="form-control mb-2">
      <button class="btn btn-primary">Cadastrar Escola</button>
    </form>

    <h5>Escolas cadastradas:</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nome</th><th>Endereço</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach($escolas as $e): ?>
        <tr>
          <td><?= $e['id'] ?></td>
          <td><?= htmlspecialchars($e['nome']) ?></td>
          <td><?= htmlspecialchars($e['endereco']) ?></td>
          <td><a href="?remove=1&table=escolas&id=<?= $e['id'] ?>" class="btn btn-sm btn-danger">Remover</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Professor -->
  <div class="tab-pane fade" id="professor">
    <form method="POST" action="salvar_professor.php" class="mb-3">
      <input type="text" name="nome" placeholder="Nome" class="form-control mb-2" required>
      <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
      <select name="escola_id" class="form-control mb-2">
        <option value="">Escolha a Escola</option>
        <?php foreach($escolas as $e): ?>
        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary">Cadastrar Professor</button>
    </form>

    <h5>Professores cadastrados:</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Escola</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach($professores as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['email'] ?? '-') ?></td>
          <td><?= htmlspecialchars($p['escola_nome'] ?? '-') ?></td>
          <td><a href="?remove=1&table=professores&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger">Remover</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Turma -->
  <div class="tab-pane fade" id="turma">
    <form method="POST" action="salvar_turma.php" class="mb-3">
      <input type="text" name="nome" placeholder="Nome da Turma" class="form-control mb-2" required>
      <input type="number" name="ano" placeholder="Ano" class="form-control mb-2" required>
      <select name="professor_id" class="form-control mb-2">
        <option value="">Escolha o Professor</option>
        <?php foreach($professores as $p): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <select name="escola_id" class="form-control mb-2">
        <option value="">Escolha a Escola</option>
        <?php foreach($escolas as $e): ?>
        <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary">Cadastrar Turma</button>
    </form>

    <h5>Turmas cadastradas:</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nome</th><th>Ano</th><th>Professor</th><th>Escola</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach($turmas as $t): ?>
        <tr>
          <td><?= $t['id'] ?></td>
          <td><?= htmlspecialchars($t['name']) ?></td>
          <td><?= htmlspecialchars($t['year']) ?></td>
          <td><?= htmlspecialchars($t['professor_nome'] ?? '-') ?></td>
          <td><?= htmlspecialchars($t['escola_nome'] ?? '-') ?></td>
          <td><a href="?remove=1&table=turmas&id=<?= $t['id'] ?>" class="btn btn-sm btn-danger">Remover</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Aluno -->
  <div class="tab-pane fade" id="aluno">
    <form method="POST" action="salvar_aluno.php" class="mb-3">
      <input type="text" name="nome" placeholder="Nome" class="form-control mb-2" required>
      <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
      <select name="turma_id" class="form-control mb-2">
        <option value="">Escolha a Turma</option>
        <?php foreach($turmas as $t): ?>
        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary">Cadastrar Aluno</button>
    </form>

    <h5>Alunos cadastrados:</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Turma</th><th>Ações</th></tr></thead>
      <tbody>
        <?php foreach($alunos as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= htmlspecialchars($a['name']) ?></td>
          <td><?= htmlspecialchars($a['email'] ?? '-') ?></td>
          <td><?= htmlspecialchars($a['turma_nome'] ?? '-') ?></td>
          <td><a href="?remove=1&table=alunos&id=<?= $a['id'] ?>" class="btn btn-sm btn-danger">Remover</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
>>>>>>> meu_branch_backup
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
