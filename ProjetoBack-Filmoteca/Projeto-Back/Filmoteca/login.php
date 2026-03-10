<?php
// =====================================
// Início da sessão PHP
// =====================================
// A função session_start() é usada para iniciar uma sessão. Sessões permitem guardar informações do usuário enquanto ele navega entre as páginas.
session_start();

// =====================================
// Mensagem de erro vinda da URL
// =====================================
// Se houver um parâmetro 'erro' na URL (por exemplo, se o login falhar), ele será exibido para o usuário.
$erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Minha Watchlist Inteligente</title>
    <!-- Importa o Tailwind CSS, um framework para facilitar a estilização dos elementos HTML -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">Login</h1>
        <?php 
        // Se existir uma mensagem de erro, ela será exibida em vermelho acima do formulário
        if ($erro) { 
            echo '<div class="mb-4 text-red-600">' . $erro . '</div>'; 
        } 
        ?>
        <!--
            Formulário de login do usuário.
            O atributo 'action' indica para onde os dados do formulário serão enviados ao clicar em Entrar.
            O método 'post' envia os dados de forma segura, sem aparecer na URL.
        -->
        <form action="php/auth/processa_login.php" method="post" class="w-full">
            <!-- Campo para o e-mail do usuário (obrigatório) -->
            <label for="email" class="block text-blue-900 font-semibold mb-1">E-mail</label>
            <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Campo para a senha do usuário (obrigatório) -->
            <label for="senha" class="block text-blue-900 font-semibold mb-1">Senha</label>
            <input type="password" id="senha" name="senha" required class="w-full px-3 py-2 border border-gray-300 rounded mb-6 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <!-- Botão para enviar o formulário -->
            <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">Entrar</button>
        </form>
        <!-- Link para a página de cadastro, caso o usuário ainda não tenha conta -->
        <a href="cadastro.php" class="mt-4 text-blue-600 hover:underline">Não tem uma conta? Cadastre-se</a>
    </div>
</body>
</html>
