<?php
session_start();
include 'db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!='professor') exit;

if($_SERVER['REQUEST_METHOD']=='POST'){
    $aluno_id = $_POST['aluno_id'];
    $valor = $_POST['valor'];
    $descricao = $_POST['descricao'] ?? '';
    $user_id = $_SESSION['user_id'];

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id FROM professores WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $professor_id = $stmt->fetchColumn();
    if (!$professor_id) {
        die('Professor not found');
    }

    $stmt = $pdo->prepare('INSERT INTO notas (aluno_id, professor_id, valor, descricao) VALUES (?,?,?,?)');
    $stmt->execute([$aluno_id, $professor_id, $valor, $descricao]);

    header('Location: dashboard.php');
}
?>
