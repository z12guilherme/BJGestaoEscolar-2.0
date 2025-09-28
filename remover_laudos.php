<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role'])) {
    header('Location: dashboard.php?error=unauthorized');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("DELETE FROM laudos WHERE id = ?");
    if ($stmt->execute([$id])) {
        header('Location: dashboard.php#laudos?success=1');
    } else {
        header('Location: dashboard.php#laudos?error=1');
    }
    exit;
}

header('Location: dashboard.php#laudos');
?>
