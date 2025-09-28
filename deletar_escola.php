<?php
session_start();
include 'db.php';
if($_SESSION['role'] != 'diretor') exit;

if($_SERVER['REQUEST_METHOD']=='POST'){
    $escola_id = $_POST['escola_id'];
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM escolas WHERE id = ?");
    $stmt->execute([$escola_id]);
    header('Location: dashboard.php');
}
?>
