<?php
// =====================================
// Início da sessão PHP
// =====================================
// A função session_start() é usada para iniciar uma sessão. Sessões permitem guardar informações do usuário enquanto ele navega entre as páginas.
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minha Watchlist Inteligente</title>
    <!-- Importa o Tailwind CSS, um framework para facilitar a estilização dos elementos HTML -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">Bem-vindo à Minha Watchlist Inteligente</h1>
        <?php 
        // =====================================
        // Verificação de login
        // =====================================
        // Se o usuário estiver logado (ou seja, existe o id do usuário na sessão), mostra as opções para usuário logado.
        // Caso contrário, mostra as opções para visitante.
        if (isset($_SESSION['usuario_id'])): ?>
            <!-- Exibe mensagem de boas-vindas com o nome do usuário -->
            <p class="mb-4 text-gray-700">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</p>
            <div class="flex gap-3">
                <!-- Link para a página da watchlist do usuário -->
                <a href="watchlist.php" class="px-5 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Minha Watchlist</a>
                <!-- Link para sair (logout) -->
                <a href="php/auth/logout.php" class="px-5 py-2 rounded bg-red-500 text-white font-semibold hover:bg-red-600 transition">Sair</a>
            </div>
        <?php else: ?>
            <!-- Exibe opções de login e cadastro para quem ainda não está logado -->
            <div class="flex gap-3">
                <a href="login.php" class="px-5 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">Login</a>
                <a href="cadastro.php" class="px-5 py-2 rounded bg-gray-200 text-blue-700 font-semibold hover:bg-blue-100 transition">Cadastro</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
