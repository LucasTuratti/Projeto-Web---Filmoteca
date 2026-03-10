<?php
// Encerra a sessão do usuário e faz logout
session_start();
session_destroy();

// Redireciona para a página inicial após logout
header("Location: ../../index.php");
exit();