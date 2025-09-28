<?php
// Configurações do banco de dados MySQL
define('DB_HOST', '127.0.0.1:3307');
define('DB_NAME', 'gestao_escolar');
define('DB_USER', 'root');
define('DB_PASS', ''); // Coloque a senha do seu MySQL se houver

// Opções PDO
define('PDO_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
]);

// Outras configurações
define('SITE_NAME', 'Sistema de Gestão Escolar');
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');
?>
