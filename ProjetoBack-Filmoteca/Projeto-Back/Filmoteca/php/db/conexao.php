<?php
// Script de conexão com o banco de dados MySQL
$host = 'localhost';
$db   = 'minha_watchlist_db';
$user = 'root';
$pass = '';
// Cria a conexão usando a classe mysqli
$conn = new mysqli($host, $user, $pass, $db);
// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die('Falha na conexão: ' . $conn->connect_error);
}
?>
