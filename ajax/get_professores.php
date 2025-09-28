<?php
include '../db.php';
$pdo = getDBConnection();

if (isset($_POST['escola_id'])) {
    $escola_id = $_POST['escola_id'];
    $stmt = $pdo->prepare("SELECT id, name FROM professores WHERE escola_id = ?");
    $stmt->execute([$escola_id]);
    $options = '<option value="">Selecione um professor</option>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $options .= "<option value='{$row['id']}'>{$row['name']}</option>";
    }
    echo $options;
}
?>
