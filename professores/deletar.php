<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
<?php
session_start();
require_once '../db.php';

// Verificar login e role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $pdo = getDBConnection();
        try {
            $stmt = $pdo->prepare("DELETE FROM professores WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: listar.php');
            exit;
        } catch (Exception $e) {
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
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $pdo = getDBConnection();
        try {
            $stmt = $pdo->prepare("DELETE FROM professores WHERE id = ?");
            $stmt->execute([$id]);
            header('Location: listar.php');
            exit;
        } catch (Exception $e) {
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
