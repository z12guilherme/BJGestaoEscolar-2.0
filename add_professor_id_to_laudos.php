<?php
include 'db.php';
$pdo = getDBConnection();
try {
    $result = $pdo->exec("ALTER TABLE laudos ADD COLUMN professor_id INT");
    if ($result !== false) {
        echo "Professor_id column added to laudos table successfully.\n";
    } else {
        echo "Failed to add professor_id column.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
