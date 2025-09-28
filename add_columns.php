<?php
include 'db.php';
$pdo = getDBConnection();

try {
    // Add escola_id to professores if not exists
    $pdo->exec("ALTER TABLE professores ADD COLUMN IF NOT EXISTS escola_id INT, ADD FOREIGN KEY (escola_id) REFERENCES escolas(id)");

    // Add escola_id to tutores if not exists
    $pdo->exec("ALTER TABLE tutores ADD COLUMN IF NOT EXISTS escola_id INT, ADD FOREIGN KEY (escola_id) REFERENCES escolas(id)");

    // Ensure turma_id in alunos
    $pdo->exec("ALTER TABLE alunos ADD COLUMN IF NOT EXISTS turma_id INT, ADD FOREIGN KEY (turma_id) REFERENCES turmas(id)");

    // Ensure aluno_id in responsaveis
    $pdo->exec("ALTER TABLE responsaveis ADD COLUMN IF NOT EXISTS aluno_id INT, ADD FOREIGN KEY (aluno_id) REFERENCES alunos(id)");

    // For usuarios, ensure user_id and role columns if needed
    $pdo->exec("ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS user_id INT, ADD COLUMN IF NOT EXISTS role VARCHAR(50), ADD COLUMN IF NOT EXISTS escola_id INT");

    echo "Columns added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
