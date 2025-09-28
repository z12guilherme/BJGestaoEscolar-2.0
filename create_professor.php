<?php
require_once 'db.php';

$pdo = getDBConnection();

// Assume escola exists, get first one
$escola_id = $pdo->query("SELECT id FROM escolas LIMIT 1")->fetchColumn();

// Create professor user
$username = 'professor_test2';
$password_hash = password_hash('prof123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, escola_id) VALUES (?, ?, 'professor', ?)");
$stmt->execute([$username, $password_hash, $escola_id]);
$user_id = $pdo->lastInsertId();

// Create professor record
$stmt = $pdo->prepare("INSERT INTO professores (name, user_id) VALUES (?, ?)");
$stmt->execute(['Professor Teste', $user_id]);
$professor_id = $pdo->lastInsertId();

echo "Professor created: $username / prof123\n";

// Create turma for this professor
$stmt = $pdo->prepare("INSERT INTO turmas (name, escola_id, professor_id) VALUES (?, ?, ?)");
$stmt->execute(['Turma Teste', $escola_id, $professor_id]);
$turma_id = $pdo->lastInsertId();

echo "Turma created: ID $turma_id\n";

// Create aluno in this turma
$aluno_username = 'aluno_test2';
$aluno_password_hash = password_hash('aluno123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, escola_id) VALUES (?, ?, 'aluno', ?)");
$stmt->execute([$aluno_username, $aluno_password_hash, $escola_id]);
$aluno_user_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("INSERT INTO alunos (name, user_id, turma_id) VALUES (?, ?, ?)");
$stmt->execute(['Aluno Teste', $aluno_user_id, $turma_id]);

echo "Aluno created: $aluno_username / aluno123\n";
?>
