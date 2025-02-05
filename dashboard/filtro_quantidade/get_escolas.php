<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

$distrito_id = $_GET['distrito_id'] ?? null;

if ($distrito_id) {
    if ($distrito_id === 'todos') {
        $stmt = $mysqli->prepare("SELECT id, nome FROM escola");
    } else {
        $stmt = $mysqli->prepare("SELECT id, nome FROM escola WHERE distrito_id = ?");
        $stmt->bind_param("i", $distrito_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $escolas = [];
    while ($row = $result->fetch_assoc()) {
        $escolas[] = $row;
    }
    echo json_encode($escolas);
}

$mysqli->close();
?>
