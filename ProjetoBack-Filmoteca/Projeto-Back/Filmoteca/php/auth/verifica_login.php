<?php
// Verifica se o usuário está logado antes de acessar páginas protegidas
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: ../../login.php');
    exit;
}
