<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/destp_pro/conexao/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notas = $_POST['notas']; // Array associativo com id_aluno e id_disciplina
    $fase = 1;

    foreach ($notas as $id_aluno => $disciplinas) {
        $id_aluno = (int)$id_aluno;
        $fase = 1; // Ajuste conforme necessário
    
        foreach ($disciplinas as $id_disciplina => $nota) {
            $id_disciplina = (int)$id_disciplina;
            $nota = trim($nota); // Remove espaços em branco
    
            if ($nota !== '') {
                $nota = (float)$nota;
    
                // Insere ou atualiza a nota
                $sql = "INSERT INTO notas_exame (id_aluno, id_disciplina, fase, nota) 
                        VALUES (?, ?, ?, ?) 
                        ON DUPLICATE KEY UPDATE nota = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("iiidd", $id_aluno, $id_disciplina, $fase, $nota, $nota);
                $stmt->execute();
            }
        }
    }
    

 
    header("Location: exame.php");
    exit;
}
?>



