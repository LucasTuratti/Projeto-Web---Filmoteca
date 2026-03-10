<?php
// Processa a edição de um item da watchlist
require_once '../db/conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Validação dos dados recebidos do formulário
$id = (int)($_POST['id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$ano_lancamento = $_POST['ano_lancamento'] ?? null;
$genero = trim($_POST['genero'] ?? '');
$sinopse = trim($_POST['sinopse'] ?? '');
$plataforma = trim($_POST['plataforma'] ?? '');
$status = $_POST['status'] ?? '';
$avaliacao_pessoal = $_POST['avaliacao_pessoal'] !== '' ? $_POST['avaliacao_pessoal'] : null;
$data_assistido = $_POST['data_assistido'] ?? null;
$link_trailer = trim($_POST['link_trailer'] ?? '');
$favorito = isset($_POST['favorito']) ? 1 : 0;

if ($id === 0 || $titulo === '' || $status === '') {
    header('Location: ../../editar_item.php?id=' . $id . '&erro=Preencha todos os campos obrigatórios.');
    exit;
}

// Prepara e executa a atualização do item no banco de dados
$stmt = $conn->prepare('UPDATE itens_audiovisuais SET titulo=?, ano_lancamento=?, genero=?, sinopse=?, plataforma=?, status=?, avaliacao_pessoal=?, data_assistido=?, link_trailer=?, favorito=? WHERE id=? AND id_usuario=?');
$stmt->bind_param('sisssssissii', $titulo, $ano_lancamento, $genero, $sinopse, $plataforma, $status, $avaliacao_pessoal, $data_assistido, $link_trailer, $favorito, $id, $id_usuario);

if ($stmt->execute()) {
    // Redireciona para a watchlist com mensagem de sucesso
    header('Location: ../../watchlist.php?sucesso=Item editado com sucesso!');
    exit;
} else {
    header('Location: ../../editar_item.php?id=' . $id . '&erro=Erro ao atualizar item.');
    exit;
}
$stmt->close();
