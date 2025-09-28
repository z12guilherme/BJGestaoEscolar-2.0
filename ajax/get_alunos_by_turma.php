<?php
include '../db.php';
$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $turma_id = (int)($_POST['turma_id'] ?? 0);
    
    if ($turma_id > 0) {
        $stmt = $pdo->prepare("SELECT id, name FROM alunos WHERE turma_id = ? ORDER BY name");
        $stmt->execute([$turma_id]);
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $options = '<option value="">Selecione o Aluno</option>';
        foreach ($alunos as $aluno) {
            $options .= '<option value="' . $aluno['id'] . '">' . htmlspecialchars($aluno['name']) . '</option>';
        }
        
        echo $options;
    } else {
        echo '<option value="">Selecione o Aluno</option>';
    }
} else {
    echo '<option value="">Método inválido</option>';
}
?>
