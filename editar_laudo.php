<?php
session_start();
include 'db.php';
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['diretor', 'professor', 'tutor'])) exit;

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$pdo = getDBConnection();

if($_SERVER['REQUEST_METHOD']=='GET'){
    $laudo_id = $_GET['laudo_id'];
    if($role == 'professor'){
        $stmt = $pdo->prepare("SELECT l.* FROM laudo l JOIN alunos a ON l.aluno_id = a.id JOIN turmas t ON a.turma_id = t.id JOIN professores p ON t.professor_id = p.id WHERE l.id = ? AND p.user_id = ?");
        $stmt->execute([$laudo_id, $user_id]);
    } elseif($role == 'tutor'){
        $stmt = $pdo->prepare("SELECT l.* FROM laudo l JOIN alunos a ON l.aluno_id = a.id JOIN turmas t ON a.turma_id = t.id JOIN usuarios u ON t.escola_id = u.escola_id WHERE l.id = ? AND u.id = ? AND u.role = 'tutor'");
        $stmt->execute([$laudo_id, $user_id]);
    } else { // diretor
        $stmt = $pdo->prepare("SELECT * FROM laudo WHERE id = ?");
        $stmt->execute([$laudo_id]);
    }
    $laudo = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$laudo){
        header('Location: dashboard.php');
        exit;
    }
} elseif($_SERVER['REQUEST_METHOD']=='POST'){
    $laudo_id = $_POST['laudo_id'];
    $descricao = $_POST['descricao'];
    if($role == 'professor'){
        $stmt = $pdo->prepare("UPDATE laudo SET descricao = ? WHERE id = ? AND EXISTS (SELECT 1 FROM alunos a JOIN turmas t ON a.turma_id = t.id JOIN professores p ON t.professor_id = p.id WHERE a.id = laudo.aluno_id AND p.user_id = ?)");
        $stmt->execute([$descricao, $laudo_id, $user_id]);
    } elseif($role == 'tutor'){
        $stmt = $pdo->prepare("UPDATE laudo SET descricao = ? WHERE id = ? AND EXISTS (SELECT 1 FROM alunos a JOIN turmas t ON a.turma_id = t.id JOIN usuarios u ON t.escola_id = u.escola_id WHERE a.id = laudo.aluno_id AND u.id = ? AND u.role = 'tutor')");
        $stmt->execute([$descricao, $laudo_id, $user_id]);
    } else { // diretor
        $stmt = $pdo->prepare("UPDATE laudo SET descricao = ? WHERE id = ?");
        $stmt->execute([$descricao, $laudo_id]);
    }
    if($stmt->rowCount() == 0){
        exit('Unauthorized');
    }
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang='pt-br'>
<head>
<meta charset='UTF-8'>
<title>Editar Laudo</title>
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='p-3'>
<h3>Editar Laudo</h3>
<form method='POST'>
    <input type='hidden' name='laudo_id' value='<?php echo $laudo['id']; ?>'>
    <div class='mb-3'>
        <label>Descrição do Laudo</label>
        <textarea name='descricao' class='form-control' rows='5' required><?php echo htmlspecialchars($laudo['descricao']); ?></textarea>
    </div>
    <button type='submit' class='btn btn-primary'>Salvar Alterações</button>
    <a href='dashboard.php' class='btn btn-secondary'>Cancelar</a>
</form>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>
