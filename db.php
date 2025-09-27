<?php
require_once 'config.php';

/**
 * Conecta ao banco de dados MySQL usando PDO
 * @return PDO
 */
function getDBConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            // Primeiro, conectar sem especificar o banco para verificar/criar
            $dsn_no_db = 'mysql:host=' . DB_HOST . ';charset=utf8';
            $pdo_temp = new PDO($dsn_no_db, DB_USER, DB_PASS, PDO_OPTIONS);

            // Verificar se o banco existe
            $stmt = $pdo_temp->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
            if (!$stmt->fetch()) {
                // Criar o banco se não existir
                $pdo_temp->exec("CREATE DATABASE `" . DB_NAME . "` CHARACTER SET utf8 COLLATE utf8_general_ci");
            }

            // Agora conectar ao banco específico
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, PDO_OPTIONS);
        } catch (PDOException $e) {
            die('Erro de conexão: ' . $e->getMessage());
        }
    }
    return $pdo;
}

/**
 * Inicializa as tabelas do banco de dados e cria usuário root se não existir
 */
function initDatabase() {
    $pdo = getDBConnection();

    // Criar tabelas
    $sql = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(80) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('root', 'secretario', 'diretor', 'admin', 'professor', 'aluno') NOT NULL,
        escola_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (escola_id) REFERENCES escolas(id) ON DELETE SET NULL
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS escolas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(150) NOT NULL,
        endereco VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS professores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        subject VARCHAR(100),
        user_id INT UNIQUE,
        FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS turmas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        year INT,
        professor_id INT,
        escola_id INT NULL,
        FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE SET NULL,
        FOREIGN KEY (escola_id) REFERENCES escolas(id) ON DELETE SET NULL
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS alunos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        birth_date DATE,
        turma_id INT,
        user_id INT UNIQUE,
        FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE SET NULL,
        FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS notas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        aluno_id INT NOT NULL,
        turma_id INT NOT NULL,
        semestre VARCHAR(20) NOT NULL,
        valor DECIMAL(5,2) NOT NULL,
        FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
        FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;

    CREATE TABLE IF NOT EXISTS professor_turma (
        id INT AUTO_INCREMENT PRIMARY KEY,
        professor_id INT NOT NULL,
        turma_id INT NOT NULL,
        UNIQUE KEY unique_prof_turma (professor_id, turma_id),
        FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE,
        FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;
    ";

    $pdo->exec($sql);

    // Migrações para colunas existentes se necessário (exemplo para escola_id em usuarios e turmas)
    // Verificar e adicionar escola_id se não existir
    $check_col = $pdo->query("SHOW COLUMNS FROM usuarios LIKE 'escola_id'")->fetch();
    if (!$check_col) {
        $pdo->exec("ALTER TABLE usuarios ADD COLUMN escola_id INT NULL AFTER role, ADD FOREIGN KEY (escola_id) REFERENCES escolas(id) ON DELETE SET NULL");
    }

    $check_col_tur = $pdo->query("SHOW COLUMNS FROM turmas LIKE 'escola_id'")->fetch();
    if (!$check_col_tur) {
        $pdo->exec("ALTER TABLE turmas ADD COLUMN escola_id INT NULL AFTER professor_id, ADD FOREIGN KEY (escola_id) REFERENCES escolas(id) ON DELETE SET NULL");
    }

    // Atualizar enum role se necessário (mais complexo, assumir recriação ou manual)
    // Para simplicidade, assumimos que o enum é atualizado na CREATE IF NOT EXISTS

    // Criar ou atualizar usuário root
    $root_hash = password_hash('rootpass', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role) VALUES ('root', ?, 'root') ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)");
    $stmt->execute([$root_hash]);

    // Manter admin se quiser, mas root é principal
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmt->execute([ADMIN_USERNAME]);
    if (!$stmt->fetch()) {
        $hash = password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password_hash, role) VALUES (?, ?, 'admin')");
        $stmt->execute([ADMIN_USERNAME, $hash]);
    }

    // Criar escola de exemplo se nenhuma existir
    $stmt = $pdo->query("SELECT COUNT(*) FROM escolas");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO escolas (nome) VALUES ('Escola Exemplo')");
    }
}
