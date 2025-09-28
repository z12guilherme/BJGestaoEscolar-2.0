<?php
session_start();
include 'db.php';
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['professor', 'tutor'])) exit;

if($_SERVER['REQUEST_METHOD']=='POST'){
    $laudo_id = $_POST['laudo_id'];
    $user_id = $_SESSION['user_id'];
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM laudo WHERE id = ? AND criado_por = ?");
    $stmt->execute([$laudo_id, $user_id]);
    header('Location: dashboard.php');
}
?>
