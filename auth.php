<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function currentUser() {
    return $_SESSION['user_id'] ?? null;
}

function currentRole() {
    return $_SESSION['role'] ?? null;
}

function requireLogin() {
    if (!currentUser()) {
        header('Location: index.php');
        exit;
    }
}

function requireRole($roles = []) {
    requireLogin(); // Ensure logged in first
    $userRole = currentRole();
    if (!in_array($userRole, (array)$roles)) {
        header('HTTP/1.1 403 Forbidden');
        echo "Acesso negado";
        exit;
    }
}

function ownsSchool($pdo, $userId, $escolaId) {
    $stmt = $pdo->prepare("SELECT escola_id FROM usuarios WHERE id = ?");
    $stmt->execute([$userId]);
    $userEscola = $stmt->fetchColumn();
    return $userEscola == $escolaId;
}

function getProfessorIdByUserId($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT id FROM professores WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}
?>
