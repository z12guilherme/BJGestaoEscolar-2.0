<?php
include 'db.php';
$pdo = getDBConnection();

// Drop if exists to recreate
$pdo->exec("DROP TABLE IF EXISTS laudos");

// Create without FK first
$sql = "CREATE TABLE laudos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    data DATE NOT NULL,
    descricao TEXT NOT NULL,
    criado_por INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

try {
    $pdo->exec($sql);
    echo "Table 'laudos' created successfully without foreign keys.";
    
    // Try to add FK after
    try {
        $pdo->exec("ALTER TABLE laudos ADD CONSTRAINT fk_laudo_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE");
        echo " FK for aluno_id added.";
    } catch (PDOException $e) {
        echo " FK for aluno_id failed: " . $e->getMessage();
    }
    
    try {
        $pdo->exec("ALTER TABLE laudos ADD CONSTRAINT fk_laudo_usuario FOREIGN KEY (criado_por) REFERENCES usuarios(id) ON DELETE SET NULL");
        echo " FK for criado_por added.";
    } catch (PDOException $e) {
        echo " FK for criado_por failed: " . $e->getMessage();
    }
    
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
