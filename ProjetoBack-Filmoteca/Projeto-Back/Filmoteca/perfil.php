<?php
// =====================================
// Proteção de Página com Login
// =====================================
require_once 'php/auth/verifica_login.php';
require_once 'php/db/conexao.php';

// Recupera o ID do usuário logado
$id_usuario = $_SESSION['usuario_id'];

// Mensagem de sucesso ou erro
$mensagem = isset($_GET['mensagem']) ? htmlspecialchars($_GET['mensagem']) : '';
$erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';

// Busca os dados atuais do usuário
$stmt = $conn->prepare('SELECT nome, email FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $id_usuario);
$stmt->execute();
$stmt->bind_result($nome, $email);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">Perfil do Usuário</h1>
        <?php if ($mensagem) { echo '<div class="mb-4 text-green-700">' . $mensagem . '</div>'; } ?>
        <?php if ($erro) { echo '<div class="mb-4 text-red-600">' . $erro . '</div>'; } ?>
        <!-- Formulário para editar o nome do usuário -->
        <form action="php/auth/processa_editar_perfil.php" method="post" class="w-full">
            <label for="nome" class="block text-blue-900 font-semibold mb-1">Nome</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <label class="block text-blue-900 font-semibold mb-1">E-mail</label>
            <input type="email" value="<?= htmlspecialchars($email) ?>" disabled class="w-full px-3 py-2 border border-gray-300 rounded mb-6 bg-gray-100">
            <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700 transition">Salvar Alterações</button>
        </form>
        <a href="watchlist.php" class="mt-4 text-blue-600 hover:underline">Voltar para a Watchlist</a>
    </div>
</body>
</html>
