<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_escola'])) {
    echo json_encode(["new_notifications" => false]);
    exit;
}

// ID da escola do usuário logado
$id_escola = $_SESSION['id_escola'];

// Conta notificações não visualizadas
$query = "SELECT COUNT(*) as total FROM notificacoes WHERE id_escola = ? AND visualizado = 0";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_escola);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$response = [
    "new_notifications" => $row['total'] > 0
];

// Retorna o resultado em JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
