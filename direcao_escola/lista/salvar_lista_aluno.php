<?php
session_start();
if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

if (!isset($_POST['id'], $_POST['campo'], $_POST['valor'])) {
    die("Dados inválidos.");
}

$id = intval($_POST['id']);
$campo = $_POST['campo'];
$valor = $_POST['valor'];

// Lista de campos permitidos para evitar SQL Injection
$campos_permitidos = ['nome', 'genero', 'idade', 'numero_ordem', 'bi', 'numero_frequencia', 'endereco', 'telefone', 'situacao_economica', 'contato_encarregado'];

if (!in_array($campo, $campos_permitidos)) {
    die("Campo inválido.");
}

// Atualiza o banco de dados
$sql = "UPDATE aluno SET $campo = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('si', $valor, $id);

if ($stmt->execute()) {
    echo "Registro atualizado com sucesso.";
} else {
    echo "Erro ao atualizar registro.";
}

$stmt->close();
$mysqli->close();
?>