<?php
session_start();
include 'db.php';
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['diretor', 'professor'])) exit;

if($_SERVER['REQUEST_METHOD']=='POST'){
    $aluno_id = $_POST['aluno_id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    $pdo = getDBConnection();

    if($role == 'professor'){
        // Check if aluno is in professor's turma
        $stmt = $pdo->prepare("SELECT a.id FROM alunos a JOIN turmas t ON a.turma_id = t.id JOIN professores p ON t.professor_id = p.id WHERE a.id = ? AND p.user_id = ?");
        $stmt->execute([$aluno_id, $user_id]);
        if(!$stmt->fetch()){
            exit('Unauthorized');
        }
    }
    // Diretor can delete any

    $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = ?");
    $stmt->execute([$aluno_id]);
    header('Location: dashboard.php');
}
?>
