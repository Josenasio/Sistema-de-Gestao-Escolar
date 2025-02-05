<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar os dados do formulário
$aluno_id = $_POST['aluno_id'];
$turma_id = $_POST['turma_id'];
$periodo_id = $_POST['periodo_id'];
$numero_ordem = $_POST['numero_ordem'];
$diretor_turma_id = $_POST['diretor_turma_id'];

// Validar se os campos obrigatórios estão preenchidos
if (empty($aluno_id) || empty($turma_id) || empty($periodo_id) || empty($numero_ordem) || empty($diretor_turma_id)) {
    echo "Erro: Todos os campos são obrigatórios.";
    exit;
}

// Atualizar os dados do aluno no banco de dados
$query = "
    UPDATE aluno 
    SET turma_id = ?, periododia_id = ?, numero_ordem = ?, id_diretor_turma = ? 
    WHERE id = ?
";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt->bind_param("iiiii", $turma_id, $periodo_id, $numero_ordem, $diretor_turma_id, $aluno_id);

if ($stmt->execute()) {
    echo "Aluno atualizado com sucesso!";
} else {
    echo "Erro ao atualizar aluno: " . $stmt->error;
}

$stmt->close();

// Redirecionar de volta para a página de listagem de alunos
header("Location: recebido.php");
exit;
?>
