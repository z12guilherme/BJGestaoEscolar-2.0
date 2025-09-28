<?php
session_start();
include 'db.php';
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['diretor', 'professor', 'tutor'])) exit;
if($_SERVER['REQUEST_METHOD']=='POST'){
    $aluno_id = $_POST['aluno_id'];
    $descricao = $_POST['descricao'];
    $criado_por = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    $pdo = getDBConnection();

    // Verify authorization for professor/tutor
    if($role == 'professor'){
        $stmt = $pdo->prepare("SELECT p.id FROM professores p JOIN turmas t ON p.id = t.professor_id JOIN alunos a ON t.id = a.turma_id WHERE a.id = ? AND p.user_id = ?");
        $stmt->execute([$aluno_id, $criado_por]);
        if(!$stmt->fetch()){
            exit('Unauthorized');
        }
    } elseif($role == 'tutor'){
        // Assuming tutor is assigned via a tutor_id in alunos or similar; adjust if needed
        // For now, limit to alunos in the same escola as tutor's escola (assuming tutor has escola_id)
        $stmt = $pdo->prepare("SELECT t.id FROM tutores t JOIN usuarios u ON t.user_id = u.id JOIN alunos a ON a.turma_id IN (SELECT id FROM turmas WHERE escola_id = u.escola_id) WHERE a.id = ? AND t.user_id = ?");
        $stmt->execute([$aluno_id, $criado_por]);
        if(!$stmt->fetch()){
            exit('Unauthorized');
        }
    }
    // Diretor can access all

    $stmt = $pdo->prepare('INSERT INTO laudo (aluno_id, descricao, criado_por) VALUES (?,?,?)');
    $stmt->execute([$aluno_id, $descricao, $criado_por]);
    header('Location: dashboard.php');
}
?>
