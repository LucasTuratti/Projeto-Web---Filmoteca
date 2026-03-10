<?php
// =====================================
// Proteção de Página com Login
// =====================================
// O require_once inclui o arquivo de verificação de login. Se o usuário não estiver logado, será redirecionado para a página de login.
require_once 'php/auth/verifica_login.php';
// Inclui o arquivo de conexão com o banco de dados
require_once 'php/db/conexao.php';

// =====================================
// Recuperando o ID do item e do usuário
// =====================================
// O ID do item é recebido pela URL (GET). O ID do usuário está salvo na sessão.
$id_item = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_usuario = $_SESSION['usuario_id'];
// Mensagem de erro, se houver
$erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';

// =====================================
// Validação do ID do item
// =====================================
// Se não houver ID válido, exibe mensagem de erro e encerra o script
if ($id_item === 0) {
    header('Location: nao_encontrado.php');
    exit;
}

// =====================================
// Busca o item no banco de dados
// =====================================
// Busca o item pelo id e pelo usuário logado para garantir que só o dono possa excluir
$stmt = $conn->prepare('SELECT titulo FROM itens_audiovisuais WHERE id = ? AND id_usuario = ?');
$stmt->bind_param('ii', $id_item, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();
$stmt->close();

// Se não encontrar o item, exibe mensagem de erro
if (!$item) {
    header('Location: nao_encontrado.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Item</title>
    <!-- Importa o Tailwind CSS para estilização rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-red-700 mb-4 text-center">Excluir Item</h1>
        <?php 
        // Se existir uma mensagem de erro, ela será exibida em vermelho acima do formulário
        if ($erro) { 
            echo '<div class="mb-4 text-red-600">' . $erro . '</div>'; 
        } 
        ?>
        <!--
            Mensagem de confirmação para o usuário ter certeza que deseja excluir o item.
            O nome do item é exibido em destaque.
        -->
        <p class="mb-6 text-gray-700 text-center">Tem certeza que deseja excluir o item <strong><?= htmlspecialchars($item['titulo']) ?></strong>?</p>
        <!--
            Formulário para confirmar a exclusão do item.
            O atributo 'action' indica para onde os dados do formulário serão enviados ao clicar em Sim, excluir.
            O método 'post' envia os dados de forma segura, sem aparecer na URL.
        -->
        <form action="php/crud/processa_excluir_item.php" method="POST" class="w-full flex flex-col items-center gap-3">
            <!-- Campo oculto para o ID do item (necessário para saber qual item excluir) -->
            <input type="hidden" name="id" value="<?= $id_item ?>">
            <!-- Botão para confirmar a exclusão -->
            <button type="submit" class="w-full py-2 rounded bg-red-600 text-white font-semibold hover:bg-red-700 transition">Sim, excluir</button>
            <!-- Botão para cancelar e voltar para a lista -->
            <a href="watchlist.php" class="w-full py-2 rounded bg-gray-200 text-blue-700 font-semibold text-center hover:bg-blue-100 transition">Cancelar</a>
        </form>
    </div>
</body>
</html>
