<?php
session_start();

if (!isset($_POST['id']) || !isset($_POST['campo']) || !isset($_POST['valor'])) {
    die("Dados incompletos.");
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

if (!isset($mysqli)) {
    die("Erro na conexão com o banco de dados.");
}

$id = $_POST['id'];
$campo = $_POST['campo'];
$valor = $_POST['valor'];

// Proteção contra SQL Injection
$campo_permitido = in_array($campo, ['nome', 'idade', 'data_nascimento', 'endereco', 'motivo_abandono', 'contato_encarregado', 'nome_encarregado', 'bi', 'naturalidade', 'estrategia_recuperacao']);
if (!$campo_permitido) {
    die("Campo inválido.");
}

$sql = "UPDATE aluno SET $campo = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt->bind_param("si", $valor, $id);

if ($stmt->execute()) {
    echo "Alteração salva com sucesso.";
} else {
    echo "Erro ao salvar alteração: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
