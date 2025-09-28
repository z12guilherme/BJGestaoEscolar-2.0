<?php
include 'db.php';
$pdo = getDBConnection();
try {
    $result = $pdo->exec("ALTER TABLE alunos ADD COLUMN email VARCHAR(255)");
    if ($result !== false) {
        echo "Email column added to alunos table successfully.\n";
    } else {
        echo "Failed to add email column.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
