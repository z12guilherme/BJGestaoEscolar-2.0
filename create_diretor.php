<?php
require_once 'db.php';

$pdo = getDBConnection();

// Create escola if none
$stmt = $pdo->query("SELECT COUNT(*) FROM escolas");
if ($stmt->fetchColumn() == 0) {
    $stmt = $pdo->prepare("INSERT INTO escolas (nome) VALUES (?)");
    $stmt->execute(['Escola Teste']);
    $escola_id = $pdo->lastInsertId();
} else {
    $escola_id = $pdo->query("SELECT id FROM escolas LIMIT 1")->fetchColumn();
}

// Create diretor user
$username = 'diretor_test';
$password_hash = password_hash('diretor123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role, escola_id) VALUES (?, ?, 'diretor', ?)");
$stmt->execute([$username, $password_hash, $escola_id]);

echo "Diretor user created: $username / diretor123\n";
?>
