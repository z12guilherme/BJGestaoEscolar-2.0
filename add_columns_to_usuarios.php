<?php
include 'db.php';
$pdo = getDBConnection();
try {
    $pdo->exec("ALTER TABLE usuarios ADD COLUMN professor_id INT");
    echo "Professor_id column added to usuarios table.\n";
} catch (PDOException $e) {
    echo "Error adding professor_id: " . $e->getMessage() . "\n";
}
try {
    $pdo->exec("ALTER TABLE usuarios ADD COLUMN aluno_id INT");
    echo "Aluno_id column added to usuarios table.\n";
} catch (PDOException $e) {
    echo "Error adding aluno_id: " . $e->getMessage() . "\n";
}
?>
