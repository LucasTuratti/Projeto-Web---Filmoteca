<?php
// ===============================
// Proteção de Página com Login
// ===============================
// O require_once inclui o arquivo de verificação de login. Se o usuário não estiver logado, será redirecionado para a página de login.
require_once 'php/auth/verifica_login.php';

// ===============================
// Mensagem de Erro
// ===============================
// Se houver um parâmetro 'erro' na URL (por exemplo, erro ao tentar adicionar um item), ele será exibido na tela para o usuário.
$erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Item - Minha Watchlist</title>
    <!-- Importa o Tailwind CSS, um framework para facilitar a estilização dos elementos HTML -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">Adicionar Novo Item</h1>
        <?php 
        // Se existir uma mensagem de erro, ela será exibida em vermelho acima do formulário
        if ($erro) { 
            echo '<div id="feedback-msg" class="mb-4 text-red-600">' . $erro . '</div>'; 
        } 
        ?>
        <!--
            Formulário para adicionar um novo item à sua lista.
            O atributo 'action' indica para onde os dados do formulário serão enviados ao clicar em Adicionar.
            O método 'post' envia os dados de forma segura, sem aparecer na URL.
        -->
        <form action="php/crud/processa_adicionar_item.php" method="post" class="w-full">
            <!-- Campo para o título do item (obrigatório) -->
            <label for="titulo" class="block text-blue-900 font-semibold mb-1">Título <span class="text-red-600">*</span></label>
            <input type="text" id="titulo" name="titulo" required maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para o ano de lançamento (opcional) -->
            <label for="ano_lancamento" class="block text-blue-900 font-semibold mb-1">Ano de Lançamento</label>
            <input type="number" id="ano_lancamento" name="ano_lancamento" min="1900" max="2100" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para o gênero do item (agora obrigatório e com lista predefinida) -->
            <label for="genero" class="block text-blue-900 font-semibold mb-1">Gênero <span class="text-red-600">*</span></label>
            <select id="genero" name="genero" required class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Selecione um gênero</option>
                <option value="Ação">Ação</option>
                <option value="Aventura">Aventura</option>
                <option value="Animação">Animação</option>
                <option value="Comédia">Comédia</option>
                <option value="Crime">Crime</option>
                <option value="Documentário">Documentário</option>
                <option value="Drama">Drama</option>
                <option value="Fantasia">Fantasia</option>
                <option value="Ficção Científica">Ficção Científica</option>
                <option value="Musical">Musical</option>
                <option value="Romance">Romance</option>
                <option value="Suspense">Suspense</option>
                <option value="Terror">Terror</option>
                <option value="Outro">Outro</option>
            </select>
            <!-- Campo para a sinopse (resumo) do item (opcional) -->
            <label for="sinopse" class="block text-blue-900 font-semibold mb-1">Sinopse</label>
            <textarea id="sinopse" name="sinopse" rows="3" maxlength="500" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
            <!-- Campo para a plataforma onde o item está disponível (opcional) -->
            <label for="plataforma" class="block text-blue-900 font-semibold mb-1">Plataforma</label>
            <input type="text" id="plataforma" name="plataforma" maxlength="40" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para o status do item (obrigatório) -->
            <label for="status" class="block text-blue-900 font-semibold mb-1">Status <span class="text-red-600">*</span></label>
            <!--
                O campo select permite escolher entre três opções:
                - Quero ver: para itens que você ainda não assistiu
                - Assistindo: para itens que está assistindo atualmente
                - Assistido: para itens já concluídos
            -->
            <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="Quero ver">Quero ver</option>
                <option value="Assistindo">Assistindo</option>
                <option value="Assistido">Assistido</option>
            </select>
            <!-- Campo para avaliação pessoal (nota de 0 a 10, opcional) -->
            <label for="avaliacao_pessoal" class="block text-blue-900 font-semibold mb-1">Avaliação Pessoal (0-10)</label>
            <input type="number" id="avaliacao_pessoal" name="avaliacao_pessoal" min="0" max="10" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded mb-6 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ex: 8">
            <!-- Campo para data em que assistiu ao item (opcional) -->
            <label for="data_assistido" class="block text-blue-900 font-semibold mb-1">Data que assistiu</label>
            <input type="date" id="data_assistido" name="data_assistido" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para link do trailer (opcional) -->
            <label for="link_trailer" class="block text-blue-900 font-semibold mb-1">Link do Trailer</label>
            <input type="url" id="link_trailer" name="link_trailer" maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded mb-6 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Checkbox para marcar o item como favorito -->
            <div class="flex items-center mb-6">
                <input type="checkbox" id="favorito" name="favorito" class="mr-2">
                <label for="favorito" class="text-blue-900 font-semibold">Favorito</label>
            </div>
            <!-- Botão para enviar o formulário e botão para cancelar -->
            <div class="flex gap-3">
                <button type="submit" class="px-5 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Adicionar</button>
                <a href="watchlist.php" class="px-5 py-2 rounded bg-gray-200 text-blue-700 font-semibold hover:bg-blue-100 transition">Cancelar</a>
            </div>
        </form>
    </div>
    <script>
    // Esconde a mensagem de feedback após 3 segundos
    const feedbackMsg = document.getElementById('feedback-msg');
    if (feedbackMsg) {
        setTimeout(() => {
            feedbackMsg.style.display = 'none';
        }, 3000);
    }
    </script>
</body>
</html>
