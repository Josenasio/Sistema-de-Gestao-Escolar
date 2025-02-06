<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar os dados do formulário
$aluno_id = $_POST['aluno_id'];
$numero_ordem = $_POST['numero_ordem'];
$diretor_turma_id = $_POST['diretor_turma_id'];

// Validar campos obrigatórios
if (empty($aluno_id) || empty($numero_ordem) || empty($diretor_turma_id)) {
    die("Erro: Todos os campos são obrigatórios.");
}

// Obter turma_id e periodo_dia_id do diretor de turma
$query_diretor = "SELECT turma_id, periodo_dia_id FROM usuarios WHERE id = ?";
$stmt_diretor = $mysqli->prepare($query_diretor);
$stmt_diretor->bind_param("i", $diretor_turma_id);
$stmt_diretor->execute();
$result_diretor = $stmt_diretor->get_result();

if ($result_diretor->num_rows === 0) {
    die("Erro: Diretor de turma não encontrado.");
}

$diretor = $result_diretor->fetch_assoc();
$turma_id = $diretor['turma_id'];
$periodo_id = $diretor['periodo_dia_id'];
$stmt_diretor->close();

// Atualizar os dados do aluno
$query_update = "UPDATE aluno 
                SET turma_id = ?, 
                    periododia_id = ?, 
                    numero_ordem = ?, 
                    id_diretor_turma = ? 
                WHERE id = ?";

$stmt_update = $mysqli->prepare($query_update);
if (!$stmt_update) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_update->bind_param("iiiii", $turma_id, $periodo_id, $numero_ordem, $diretor_turma_id, $aluno_id);

if ($stmt_update->execute()) {
    header("Location: sucesso.php");
    exit;
} else {
    echo "Erro ao atualizar aluno: " . $stmt_update->error;
}

$stmt_update->close();
$mysqli->close();
?>