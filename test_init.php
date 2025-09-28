<?php
include 'db.php';
$pdo = getDBConnection();
initDatabase();

// Create escola if none
$stmt = $pdo->query("SELECT COUNT(*) FROM escolas");
if ($stmt->fetchColumn() == 0) {
    $stmt = $pdo->prepare("INSERT INTO escolas (nome) VALUES (?)");
    $stmt->execute(['Escola Teste']);
    $escola_id = $pdo->lastInsertId();
} else {
    $escola_id = $pdo->query("SELECT id FROM escolas LIMIT 1")->fetchColumn();
}

// Create diretor if not exists
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
$stmt->execute(['diretor_test']);
if (!$stmt->fetch()) {
    $password_hash = password_hash('diretor123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, email, escola_id) VALUES (?, ?, 'diretor', ?, ?)");
    $stmt->execute(['diretor_test', $password_hash, 'diretor@test.com', $escola_id]);
    $user_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("INSERT INTO diretores (name, user_id) VALUES (?, ?)");
    $stmt->execute(['Diretor Teste', $user_id]);
}

// Create professor if not exists
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
$stmt->execute(['professor_test2']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    $password_hash = password_hash('prof123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, email, escola_id) VALUES (?, ?, 'professor', ?, ?)");
    $stmt->execute(['professor_test2', $password_hash, 'prof@test.com', $escola_id]);
    $user_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("INSERT INTO professores (name, user_id) VALUES (?, ?)");
    $stmt->execute(['Professor Teste 2', $user_id]);
    $prof_id = $pdo->lastInsertId();
} else {
    $user_id = $row['id'];
    $stmt = $pdo->query("SELECT id FROM professores WHERE user_id = $user_id");
    $prof_id = $stmt->fetchColumn();
}

// Create turma if none
$stmt = $pdo->query("SELECT COUNT(*) FROM turmas");
if ($stmt->fetchColumn() == 0) {
    $stmt = $pdo->prepare("INSERT INTO turmas (name, professor_id, escola_id) VALUES (?, ?, ?)");
    $stmt->execute(['Turma 1', $prof_id, $escola_id]);
    $turma_id = $pdo->lastInsertId();
} else {
    $turma_id = $pdo->query("SELECT id FROM turmas LIMIT 1")->fetchColumn();
}

// Create aluno if none
$stmt = $pdo->query("SELECT COUNT(*) FROM alunos");
if ($stmt->fetchColumn() == 0) {
    $password_hash = password_hash('aluno123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, email, escola_id) VALUES (?, ?, 'aluno', ?, ?)");
    $stmt->execute(['aluno_test2', $password_hash, 'aluno@test.com', $escola_id]);
    $user_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("INSERT INTO alunos (name, user_id, turma_id) VALUES (?, ?, ?)");
    $stmt->execute(['Aluno Teste 2', $user_id, $turma_id]);
    $aluno_id = $pdo->lastInsertId();
} else {
    $aluno_id = $pdo->query("SELECT id FROM alunos LIMIT 1")->fetchColumn();
}

// Create responsavel if none
$stmt = $pdo->query("SELECT COUNT(*) FROM responsaveis");
if ($stmt->fetchColumn() == 0) {
    $password_hash = password_hash('resp123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, email, escola_id) VALUES (?, ?, 'responsavel', ?, ?)");
    $stmt->execute(['responsavel_test', $password_hash, 'resp@test.com', $escola_id]);
    $user_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("INSERT INTO responsaveis (name, user_id) VALUES (?, ?)");
    $stmt->execute(['Responsável Teste', $user_id]);
    $responsavel_id = $pdo->lastInsertId();
} else {
    $responsavel_id = $pdo->query("SELECT id FROM responsaveis LIMIT 1")->fetchColumn();
}

// Create tutor if none
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
$stmt->execute(['tutor_test']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    $password_hash = password_hash('tutor123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, email, escola_id) VALUES (?, ?, 'tutor', ?, ?)");
    $stmt->execute(['tutor_test', $password_hash, 'tutor@test.com', $escola_id]);
    $user_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("INSERT INTO tutores (name, user_id) VALUES (?, ?)");
    $stmt->execute(['Tutor Teste', $user_id]);
}

echo "Test data initialized. Aluno ID: $aluno_id, Turma ID: $turma_id, Professor ID: $prof_id\n";
?>
