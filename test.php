<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1:3307;dbname=gestao_escolar","root","");
    echo "Conexão OK!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
<<<<<<< HEAD
=======
<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1:3307;dbname=gestao_escolar","root","");
    echo "Conexão OK!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
>>>>>>> meu_branch_backup
=======
>>>>>>> bdb7a67 (Adiciona Laravel sem repositório interno)
?>