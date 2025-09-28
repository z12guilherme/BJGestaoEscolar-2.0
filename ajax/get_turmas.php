<<<<<<< HEAD
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';

$pdo = getDBConnection();

$escola_id = intval($_GET['escola_id'] ?? 0);

if ($escola_id > 0) {
    $stmt = $pdo->prepare("SELECT id, name FROM turmas WHERE escola_id = ? ORDER BY name");
    $stmt->execute([$escola_id]);
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($turmas);
} else {
    echo json_encode([]);
}
?>
=======
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';

$pdo = getDBConnection();

$escola_id = intval($_GET['escola_id'] ?? 0);

if ($escola_id > 0) {
    $stmt = $pdo->prepare("SELECT id, name FROM turmas WHERE escola_id = ? ORDER BY name");
    $stmt->execute([$escola_id]);
    $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($turmas);
} else {
    echo json_encode([]);
}
?>
>>>>>>> meu_branch_backup
