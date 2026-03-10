<?php
// Processa o cadastro de um novo usuário
require_once '../db/conexao.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe e valida os dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        header('Location: ../../cadastro.php?erro=Preencha todos os campos.');
        exit;
    }

    // Verifica se o e-mail já está cadastrado
    $stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header('Location: ../../cadastro.php?erro=Email já cadastrado.');
        exit;
    }
    $stmt->close();

    // Insere o novo usuário no banco de dados
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $nome, $email, $senha_hash);
    if ($stmt->execute()) {
        // Redireciona para login ou exibe erro
        header('Location: ../../login.php?cadastro=sucesso');
        exit;
    } else {
        header('Location: ../../cadastro.php?erro=Erro ao cadastrar usuário.');
        exit;
    }
    $stmt->close();
}
?>
