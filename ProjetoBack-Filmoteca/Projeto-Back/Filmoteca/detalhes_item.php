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
// Se não houver ID válido, redireciona para a página de não encontrado
if ($id_item === 0) {
    header('Location: nao_encontrado.php');
    exit;
}

// =====================================
// Busca o item no banco de dados
// =====================================
// Busca o item pelo id e pelo usuário logado para garantir que só o dono veja os detalhes
$stmt = $conn->prepare('SELECT * FROM itens_audiovisuais WHERE id = ? AND id_usuario = ?');
$stmt->bind_param('ii', $id_item, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();
$stmt->close();

// Se não encontrar o item, redireciona para a página de não encontrado
if (!$item) {
    header('Location: nao_encontrado.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Item</title>
    <!-- Importa o Tailwind CSS para estilização rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-8">
        <!-- Menu de navegação -->
        <div class="flex flex-wrap justify-center gap-3 mb-8">
            <a href="watchlist.php" class="px-5 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Voltar</a>
            <a href="editar_item.php?id=<?= $item['id'] ?>" class="px-5 py-2 rounded bg-yellow-400 text-gray-900 font-semibold hover:bg-yellow-500 transition">Editar</a>
            <a href="php/auth/logout.php" class="px-5 py-2 rounded bg-red-500 text-white font-semibold hover:bg-red-600 transition">Sair</a>
        </div>
        <?php 
        // Se existir uma mensagem de erro, ela será exibida em vermelho acima dos detalhes
        if ($erro) { 
            echo '<div id="feedback-msg" class="mb-4 text-red-600">' . $erro . '</div>'; 
        } 
        ?>
        <h1 class="text-2xl font-bold text-blue-800 text-center mb-6">Detalhes do Item</h1>
        <!-- Lista com todos os detalhes do item -->
        <ul class="divide-y divide-gray-200">
            <li class="py-2"><span class="font-semibold text-blue-900">Título:</span> <?= htmlspecialchars($item['titulo']) ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Ano de Lançamento:</span> <?= htmlspecialchars($item['ano_lancamento']) ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Gênero:</span> <?= htmlspecialchars($item['genero']) ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Sinopse:</span> <?= nl2br(htmlspecialchars($item['sinopse'])) ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Plataforma:</span> <?= htmlspecialchars($item['plataforma']) ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Status:</span> <?= htmlspecialchars($item['status']) ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Avaliação Pessoal:</span> <?= htmlspecialchars($item['avaliacao_pessoal']) ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Data que assistiu:</span> <?= htmlspecialchars($item['data_assistido']) ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Favorito:</span> <?= $item['favorito'] ? 'Sim' : 'Não' ?></li>
            <li class="py-2"><span class="font-semibold text-blue-900">Link do Trailer:</span> <?php if ($item['link_trailer']): ?><a href="<?= htmlspecialchars($item['link_trailer']) ?>" target="_blank" class="text-blue-600 hover:underline">Assistir Trailer</a><?php else: ?>Não informado<?php endif; ?></li>
        </ul>
    </div>
</body>
<script>
// Esconde a mensagem de feedback após 3 segundos
const feedbackMsg = document.getElementById('feedback-msg');
if (feedbackMsg) {
    setTimeout(() => {
        feedbackMsg.style.display = 'none';
    }, 3000);
}
</script>
</html>
