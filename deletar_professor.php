<?php
session_start();
include 'db.php';
if($_SESSION['role'] != 'diretor') exit;

if($_SERVER['REQUEST_METHOD']=='POST'){
    $professor_id = $_POST['professor_id'];
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM professores WHERE id = ?");
    $stmt->execute([$professor_id]);
    header('Location: dashboard.php');
}
?>
