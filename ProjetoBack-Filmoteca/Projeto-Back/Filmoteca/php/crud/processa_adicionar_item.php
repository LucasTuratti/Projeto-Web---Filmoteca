<?php
// Processa a adição de um novo item à watchlist
require_once '../db/conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

// Validação básica
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

if ($titulo === '' || $status === '') {
    header('Location: ../../adicionar_item.php?erro=Preencha todos os campos obrigatórios.');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Prepara e executa a inserção do novo item no banco de dados
$stmt = $conn->prepare('INSERT INTO itens_audiovisuais (id_usuario, titulo, ano_lancamento, genero, sinopse, plataforma, status, avaliacao_pessoal, data_assistido, link_trailer, favorito) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('isissssisss', $id_usuario, $titulo, $ano_lancamento, $genero, $sinopse, $plataforma, $status, $avaliacao_pessoal, $data_assistido, $link_trailer, $favorito);

if ($stmt->execute()) {
    // Redireciona para a watchlist com mensagem de sucesso
    header('Location: ../../watchlist.php?sucesso=Filme adicionado com sucesso!');
    exit;
} else {
    // Exibe erro ao adicionar item
    header('Location: ../../adicionar_item.php?erro=Erro ao adicionar item.');
    exit;
}
$stmt->close();
