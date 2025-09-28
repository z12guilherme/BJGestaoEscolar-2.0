<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'Professor'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $pdo = getDBConnection();
        try {
            $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: listar.php');
            exit;
        } catch (Exception $e) {
            // Handle error, e.g., foreign key constraint
            header('Location: listar.php?error=1');
            exit;
        }
    }
}
header('Location: listar.php');
exit;
?>
<<<<<<< HEAD
=======
<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'Professor'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $pdo = getDBConnection();
        try {
            $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: listar.php');
            exit;
        } catch (Exception $e) {
            // Handle error, e.g., foreign key constraint
            header('Location: listar.php?error=1');
            exit;
        }
    }
}
header('Location: listar.php');
exit;
?>
>>>>>>> meu_branch_backup
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
