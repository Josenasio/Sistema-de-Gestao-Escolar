<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');
session_start();

if (!isset($_SESSION['id_escola'])) {
    echo json_encode([]);
    exit;
}

$id_escola = $_SESSION['id_escola'];
$type = $_GET['type'] ?? '';

$query = '';
if ($type === 'professores') {
    $query = "SELECT nome FROM professor WHERE id_escola = ?";
} elseif ($type === 'pnd') {
    $query = "SELECT nome FROM pessoal_nao_docente WHERE escola_id = ?";
} else {
    echo json_encode([]);
    exit;
}

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_escola);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
