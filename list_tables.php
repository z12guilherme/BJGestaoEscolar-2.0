<?php
require_once 'config.php';
require_once 'db.php';

try {
    $pdo = getDBConnection();
    initDatabase(); // Ensure init runs
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    // Also check columns in usuarios for role enum and escola_id
    $stmt = $pdo->query("DESCRIBE usuarios");
    echo "\nUsuarios table columns:\n";
    while ($row = $stmt->fetch()) {
        echo "- {$row['Field']}: {$row['Type']}\n";
    }
    // Check escolas
    $stmt = $pdo->query("SELECT COUNT(*) FROM escolas");
    echo "\nNumber of escolas: " . $stmt->fetchColumn() . "\n";
    // Check users
    $stmt = $pdo->query("SELECT username, role FROM usuarios");
    echo "\nUsers:\n";
    while ($row = $stmt->fetch()) {
        echo "- {$row['username']} ({$row['role']})\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
