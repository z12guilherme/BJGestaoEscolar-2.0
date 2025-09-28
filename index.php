<<<<<<< HEAD
<?php
session_start();
require_once 'db.php';

// Inicializar banco se necessário
initDatabase();

// Mantém a sessão, mas não redireciona mais automaticamente.
// O login será sempre carregado ao acessar index.php

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errors[] = 'Usuário e senha são obrigatórios.';
    } else {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, password_hash, role, professor_id, aluno_id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            $_SESSION['professor_id'] = $user['professor_id'];
            $_SESSION['aluno_id'] = $user['aluno_id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Credenciais inválidas.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BJ GESTÃO ESCOLAR - Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Fundo dark gradient */
body {
    background: linear-gradient(-45deg, #0f0f0f, #1a1a2e, #16213e, #0f3460);
    background-size: 400% 400%;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: gradientBG 15s ease infinite;
    font-family: 'Segoe UI', sans-serif;
    color: #ddd;
}

/* Animação do background */
@keyframes gradientBG {
    0% {background-position:0% 50%;}
    50% {background-position:100% 50%;}
    100% {background-position:0% 50%;}
}

/* Card do login */
.card {
    border-radius: 1.5rem;
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    opacity: 0;
    transform: translateY(50px);
    animation: fadeInUp 1s forwards;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: #1e1e2f;
    border: 1px solid #333;
}
.card:hover {
    transform: translateY(0) scale(1.02);
    box-shadow: 0 25px 50px rgba(0,0,0,0.6);
}

/* Logo do sistema com chapeuzinho animado */
.logo {
    font-size: 2rem;
    text-align: center;
    margin-bottom: 1rem;
    color: #fff;
}
.logo span.cap {
    font-size: 3rem;
    display: inline-block;
    animation: swing 1s ease-in-out infinite alternate;
}

/* Swing do chapeuzinho */
@keyframes swing {
    0% { transform: rotate(-20deg); }
    50% { transform: rotate(20deg); }
    100% { transform: rotate(-20deg); }
}

/* Inputs com efeito de foco */
.form-control {
    border-radius: 50px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    border: 2px solid #555;
    background-color: #2a2a3f;
    color: #ddd;
}
.form-control:focus {
    border-color: #0f3460;
    box-shadow: 0 0 10px rgba(15,52,96,0.4);
    background-color: #2a2a3f;
}
.form-control::placeholder {
    color: #888;
}

/* Botão com hover animado */
.btn-primary {
    background: linear-gradient(90deg, #16213e, #0f3460);
    border: none;
    border-radius: 50px;
    padding: 0.75rem;
    font-weight: bold;
    transition: all 0.3s ease;
    color: #fff;
}
.btn-primary:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    background: linear-gradient(90deg, #0f3460, #16213e);
}

/* Fade-in animation do card */
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Alertas */
.alert {
    border-radius: 50px;
    font-weight: 500;
    text-align: center;
    background-color: #3a3a5a;
    color: #fff;
    border: 1px solid #555;
}
.alert-danger {
    background-color: #4a1a1a;
    border-color: #aa0000;
}
</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <div class="logo">
                    <span class="cap">🎓</span> BJ GESTÃO ESCOLAR
                </div>
                <div class="card-body">
                    <?php if ($errors): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Usuário" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
=======
<?php
session_start();
session_unset();
session_destroy();
session_start();
require_once 'db.php';

// Inicializar banco se necessário
initDatabase();

// Se já logado, redirecionar para dashboard
// Temporariamente comentado para sempre mostrar a página de login primeiro
// if (isset($_SESSION['user_id'])) {
//     header('Location: dashboard.php');
//     exit;
// }

// Processar login
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errors[] = 'Usuário e senha são obrigatórios.';
    } else {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, password_hash, role FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Credenciais inválidas.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= SITE_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($errors): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuário</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
>>>>>>> meu_branch_backup
