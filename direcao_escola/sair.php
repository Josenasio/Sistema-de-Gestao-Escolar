<?php
// Inicia a sessão
session_start();

// Destrói todas as variáveis de sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona o usuário para a página de login ou inicial
header('Location: ../index.php');
exit();
?>
