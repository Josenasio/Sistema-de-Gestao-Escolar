<?php 
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verificar se o id_escola está definido na sessão
if (!isset($_SESSION['id_escola'])) {
    die("Erro: ID da escola não definido.");
}

// Recuperar o id_escola da sessão
$id_escola = $_SESSION['id_escola'];
$id_usuario = $_SESSION['id'];

// Receber os dados do formulário
$nome_usuario = $_POST['nome'];
$email_usuario = $_POST['email'];
$senha_usuario = $_POST['senha'];
$nivel_acesso = $_POST['tipo']; // Deverá ser "Professor"
$classe_id = $_POST['classe_id'];
$periodo_dia_id = $_POST['periodo_dia_id'];
$curso_id = $_POST['curso_id'];
$turma_id = strtoupper(trim($_POST['turma_id'])); // Turma com letras maiúsculas e sem espaços


// Verificar se a turma já existe
$query_turma_existente = "SELECT id FROM turma WHERE nome_turma = ?";
$stmt_turma_existente = $mysqli->prepare($query_turma_existente);
$stmt_turma_existente->bind_param('s', $turma_id);
$stmt_turma_existente->execute();
$stmt_turma_existente->store_result();

if ($stmt_turma_existente->num_rows == 0) {
    // Inserir a nova turma na tabela turma
    $query_turma = "INSERT INTO turma (nome_turma) VALUES (?)";
    $stmt_turma = $mysqli->prepare($query_turma);
    $stmt_turma->bind_param('s', $turma_id);
    
    if ($stmt_turma->execute()) {
        $turma_id_novo = $stmt_turma->insert_id; // Obter o ID da nova turma
    } else {
        die("Erro ao criar nova turma.");
    }
} else {
    // Se a turma já existir, recuperar o ID da turma existente
    $stmt_turma_existente->bind_result($turma_id_novo);
    $stmt_turma_existente->fetch();
}

$stmt_turma_existente->close();

// Inserir o diretor de turma na tabela usuarios
$query_diretor = "INSERT INTO usuarios (nome, email, senha, tipo, id_escola, classe_id, periodo_dia_id, curso_id, turma_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_diretor = $mysqli->prepare($query_diretor);
$senha_hash = password_hash($senha_usuario, PASSWORD_DEFAULT); // Criptografar a senha
$stmt_diretor->bind_param('ssssiiiii', $nome_usuario, $email_usuario, $senha_hash, $nivel_acesso, $id_escola, $classe_id, $periodo_dia_id, $curso_id, $turma_id_novo);

if ($stmt_diretor->execute()) {
    // Redirecionar para uma página de sucesso ou mostrar uma mensagem de sucesso
    header("Location: ../formulario/sucesso.php");
    exit;
} else {
    // Caso ocorra um erro durante o cadastro
    echo "Erro ao cadastrar diretor de turma. Tente novamente.";
}

$stmt_diretor->close();
$mysqli->close();
?>
