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
// Busca o item pelo id e pelo usuário logado para garantir que só o dono possa editar
$stmt = $conn->prepare('SELECT * FROM itens_audiovisuais WHERE id = ? AND id_usuario = ?');
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
    <title>Editar Item</title>
    <!-- Importa o Tailwind CSS para estilização rápida -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8">
        <!-- Botão para voltar para a lista -->
        <div class="mb-6 flex justify-center">
            <a href="watchlist.php" class="px-5 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Voltar</a>
        </div>
        <?php 
        // Se existir uma mensagem de erro, ela será exibida em vermelho acima do formulário
        if ($erro) { 
            echo '<div id="feedback-msg" class="mb-4 text-red-600">' . $erro . '</div>'; 
        } 
        ?>
        <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">Editar Item</h1>
        <!--
            Formulário para editar um item da sua lista.
            O formulário já vem preenchido com os dados atuais do item.
            O atributo 'action' indica para onde os dados do formulário serão enviados ao clicar em Salvar.
            O método 'post' envia os dados de forma segura, sem aparecer na URL.
        -->
        <form action="php/crud/processa_editar_item.php" method="POST" class="w-full">
            <!-- Campo oculto para o ID do item (necessário para saber qual item editar) -->
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
            <!-- Campo para o título do item (obrigatório) -->
            <label for="titulo" class="block text-blue-900 font-semibold mb-1">Título <span class="text-red-600">*</span></label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($item['titulo']) ?>" required maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para o ano de lançamento (opcional) -->
            <label for="ano_lancamento" class="block text-blue-900 font-semibold mb-1">Ano de Lançamento</label>
            <input type="number" id="ano_lancamento" name="ano_lancamento" min="1900" max="2100" value="<?= htmlspecialchars($item['ano_lancamento']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para o gênero do item (agora obrigatório e com lista predefinida) -->
            <label for="genero" class="block text-blue-900 font-semibold mb-1">Gênero <span class="text-red-600">*</span></label>
            <select id="genero" name="genero" required class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Selecione um gênero</option>
                <option value="Ação" <?= $item['genero'] === 'Ação' ? 'selected' : '' ?>>Ação</option>
                <option value="Aventura" <?= $item['genero'] === 'Aventura' ? 'selected' : '' ?>>Aventura</option>
                <option value="Animação" <?= $item['genero'] === 'Animação' ? 'selected' : '' ?>>Animação</option>
                <option value="Comédia" <?= $item['genero'] === 'Comédia' ? 'selected' : '' ?>>Comédia</option>
                <option value="Crime" <?= $item['genero'] === 'Crime' ? 'selected' : '' ?>>Crime</option>
                <option value="Documentário" <?= $item['genero'] === 'Documentário' ? 'selected' : '' ?>>Documentário</option>
                <option value="Drama" <?= $item['genero'] === 'Drama' ? 'selected' : '' ?>>Drama</option>
                <option value="Fantasia" <?= $item['genero'] === 'Fantasia' ? 'selected' : '' ?>>Fantasia</option>
                <option value="Ficção Científica" <?= $item['genero'] === 'Ficção Científica' ? 'selected' : '' ?>>Ficção Científica</option>
                <option value="Musical" <?= $item['genero'] === 'Musical' ? 'selected' : '' ?>>Musical</option>
                <option value="Romance" <?= $item['genero'] === 'Romance' ? 'selected' : '' ?>>Romance</option>
                <option value="Suspense" <?= $item['genero'] === 'Suspense' ? 'selected' : '' ?>>Suspense</option>
                <option value="Terror" <?= $item['genero'] === 'Terror' ? 'selected' : '' ?>>Terror</option>
                <option value="Outro" <?= $item['genero'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
            </select>
            <!-- Campo para a sinopse (resumo) do item (opcional) -->
            <label for="sinopse" class="block text-blue-900 font-semibold mb-1">Sinopse</label>
            <textarea id="sinopse" name="sinopse" rows="3" maxlength="500" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($item['sinopse']) ?></textarea>
            <!-- Campo para a plataforma onde o item está disponível (opcional) -->
            <label for="plataforma" class="block text-blue-900 font-semibold mb-1">Plataforma</label>
            <input type="text" id="plataforma" name="plataforma" value="<?= htmlspecialchars($item['plataforma']) ?>" maxlength="40" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para o status do item (obrigatório) -->
            <label for="status" class="block text-blue-900 font-semibold mb-1">Status <span class="text-red-600">*</span></label>
            <!--
                O campo select permite escolher entre três opções:
                - Quero ver: para itens que você ainda não assistiu
                - Assistindo: para itens que está assistindo atualmente
                - Assistido: para itens já concluídos
            -->
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="Quero ver" <?= $item['status'] === 'Quero ver' ? 'selected' : '' ?>>Quero ver</option>
                <option value="Assistindo" <?= $item['status'] === 'Assistindo' ? 'selected' : '' ?>>Assistindo</option>
                <option value="Assistido" <?= $item['status'] === 'Assistido' ? 'selected' : '' ?>>Assistido</option>
            </select>
            <!-- Campo para avaliação pessoal (nota de 0 a 10, opcional) -->
            <label for="avaliacao_pessoal" class="block text-blue-900 font-semibold mb-1">Avaliação Pessoal (0-10)</label>
            <input type="number" id="avaliacao_pessoal" name="avaliacao_pessoal" min="0" max="10" step="0.1" value="<?= htmlspecialchars($item['avaliacao_pessoal']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded mb-6 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ex: 8">
            <!-- Campo para a data que o item foi assistido (opcional) -->
            <label for="data_assistido" class="block text-blue-900 font-semibold mb-1">Data que assistiu</label>
            <input type="date" id="data_assistido" name="data_assistido" value="<?= htmlspecialchars($item['data_assistido']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para o link do trailer (opcional) -->
            <label for="link_trailer" class="block text-blue-900 font-semibold mb-1">Link do Trailer</label>
            <input type="url" id="link_trailer" name="link_trailer" maxlength="255" value="<?= htmlspecialchars($item['link_trailer']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded mb-6 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Checkbox para marcar como favorito (opcional) -->
            <div class="flex items-center mb-6">
                <input type="checkbox" id="favorito" name="favorito" <?= $item['favorito'] ? 'checked' : '' ?> class="mr-2">
                <label for="favorito" class="text-blue-900 font-semibold">Favorito</label>
            </div>
            <!-- Botões de ação: Salvar e Cancelar -->
            <div class="flex gap-3">
                <button type="submit" class="px-5 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Salvar</button>
                <a href="watchlist.php" class="px-5 py-2 rounded bg-gray-200 text-blue-700 font-semibold hover:bg-blue-100 transition">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
<script>
// Esconde a mensagem de feedback após 3 segundos
const feedbackMsg = document.getElementById('feedback-msg');
if (feedbackMsg) {
    setTimeout(() => {
        feedbackMsg.style.display = 'none';
    }, 3000);
}
</script>
