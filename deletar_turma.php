<?php
session_start();
include 'db.php';
if($_SESSION['role'] != 'diretor') exit;

if($_SERVER['REQUEST_METHOD']=='POST'){
    $turma_id = $_POST['turma_id'];
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM turmas WHERE id = ?");
    $stmt->execute([$turma_id]);
    header('Location: dashboard.php');
}
?>
