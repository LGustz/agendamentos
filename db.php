<?php
$host = 'localhost'; // ou o IP do seu servidor de banco de dados
$dbname = 'agendamentos';
$username = 'root';
$password = ''; // substitua com a senha, se houver

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>
