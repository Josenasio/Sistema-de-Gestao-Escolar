<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

if (isset($_GET['classe_id'])) {
    $classe_id = $_GET['classe_id'];

    // Buscar cursos relacionados à classe
    $query = "SELECT c.id, CONCAT(c.sigla, ' - ', c.nome_area) AS nome_curso 
              FROM curso c
              INNER JOIN classe_curso cc ON c.id = cc.curso_id
              WHERE cc.classe_id = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $classe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Gerar opções para o select
    echo '<option value="">Selecione o Curso</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['nome_curso'] . '</option>';
    }

    $stmt->close();
}
?>
