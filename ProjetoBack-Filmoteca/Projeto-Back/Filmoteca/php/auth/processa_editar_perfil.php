<?php
// Script para processar a edição do perfil do usuário
require_once '../db/conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$nome = trim($_POST['nome'] ?? '');

if ($nome === '') {
    header('Location: ../../perfil.php?erro=Preencha o nome.');
    exit;
}

$stmt = $conn->prepare('UPDATE usuarios SET nome = ? WHERE id = ?');
$stmt->bind_param('si', $nome, $id_usuario);
if ($stmt->execute()) {
    $_SESSION['usuario_nome'] = $nome; // Atualiza o nome na sessão
    header('Location: ../../perfil.php?mensagem=Perfil atualizado com sucesso!');
    exit;
} else {
    header('Location: ../../perfil.php?erro=Erro ao atualizar perfil.');
    exit;
}
$stmt->close();
