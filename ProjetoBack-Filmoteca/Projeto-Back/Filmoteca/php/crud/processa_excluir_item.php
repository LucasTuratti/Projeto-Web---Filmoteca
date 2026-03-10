<?php
// Processa a exclusão de um item da watchlist
require_once '../db/conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
// Validação do id recebido do formulário
$id = (int)($_POST['id'] ?? 0);
if ($id === 0) {
    header('Location: ../../watchlist.php?erro=ID inválido.');
    exit;
}

// Prepara e executa a exclusão do item no banco de dados
$stmt = $conn->prepare('DELETE FROM itens_audiovisuais WHERE id = ? AND id_usuario = ?');
$stmt->bind_param('ii', $id, $id_usuario);
if ($stmt->execute()) {
    // Redireciona para a watchlist com mensagem de sucesso
    header('Location: ../../watchlist.php?sucesso=Item excluído com sucesso!');
    exit;
} else {
    header('Location: ../../watchlist.php?erro=Erro ao excluir item.');
    exit;
}
$stmt->close();
