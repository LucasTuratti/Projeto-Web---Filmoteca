<?php
// Processa o login do usuário
require_once '../db/conexao.php';
session_start();

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe e valida os dados do formulário
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        header('Location: ../../login.php?erro=Preencha todos os campos.');
        exit;
    }

    // Busca o usuário pelo e-mail
    $stmt = $conn->prepare('SELECT id, nome, senha FROM usuarios WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se encontrou o usuário e se a senha está correta
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nome, $senha_hash);
        $stmt->fetch();
        if (password_verify($senha, $senha_hash)) {
            // Login bem-sucedido, armazena dados na sessão
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome;
            header('Location: ../../index.php');
            exit;
        } else {
            // Senha incorreta
            header('Location: ../../login.php?erro=Senha incorreta.');
            exit;
        }
    } else {
        // Usuário não encontrado
        header('Location: ../../login.php?erro=Usuário não encontrado.');
        exit;
    }
    $stmt->close();
}
?>
