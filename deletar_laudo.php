<?php
session_start();
include 'db.php';
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['diretor', 'professor', 'tutor'])) exit;

if($_SERVER['REQUEST_METHOD']=='POST'){
    $laudo_id = $_POST['laudo_id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    $pdo = getDBConnection();

    // For professor/tutor, check if they own the laudo or it's for their student
    if($role == 'professor'){
        $stmt = $pdo->prepare("SELECT l.id FROM laudo l JOIN alunos a ON l.aluno_id = a.id JOIN turmas t ON a.turma_id = t.id JOIN professores p ON t.professor_id = p.id WHERE l.id = ? AND p.user_id = ?");
        $stmt->execute([$laudo_id, $user_id]);
        if(!$stmt->fetch()){
            exit('Unauthorized');
        }
    } elseif($role == 'tutor'){
        $stmt = $pdo->prepare("SELECT l.id FROM laudo l JOIN alunos a ON l.aluno_id = a.id JOIN turmas t ON a.turma_id = t.id JOIN usuarios u ON t.escola_id = u.escola_id WHERE l.id = ? AND u.id = ? AND u.role = 'tutor'");
        $stmt->execute([$laudo_id, $user_id]);
        if(!$stmt->fetch()){
            exit('Unauthorized');
        }
    }
    // Diretor can delete any

    $stmt = $pdo->prepare("DELETE FROM laudo WHERE id = ?");
    $stmt->execute([$laudo_id]);
    header('Location: dashboard.php');
}
?>
