<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

$id_aluno = $_POST['id_aluno'];
$disciplina_id = $_POST['disciplina_id'];
$field = $_POST['field'];
$value = $_POST['value'];

$sql_check = "SELECT * FROM nota WHERE id_aluno = ? AND disciplina_id = ?";
$stmt_check = $mysqli->prepare($sql_check);
$stmt_check->bind_param("ii", $id_aluno, $disciplina_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $sql_update = "UPDATE nota SET $field = ? WHERE id_aluno = ? AND disciplina_id = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param("dii", $value, $id_aluno, $disciplina_id);
    $stmt_update->execute();
    echo "Nota atualizada!";
} else {
    $sql_insert = "INSERT INTO nota (id_aluno, disciplina_id, $field) VALUES (?, ?, ?)";
    $stmt_insert = $mysqli->prepare($sql_insert);
    $stmt_insert->bind_param("iid", $id_aluno, $disciplina_id, $value);
    $stmt_insert->execute();
    echo "Nota inserida!";
}

$mysqli->close();
?>
