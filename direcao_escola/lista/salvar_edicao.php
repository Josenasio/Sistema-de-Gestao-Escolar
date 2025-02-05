<?php
// Inicia a sessão
session_start();

// Verifica se a sessão da escola está ativa
if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
}

// Inclui a conexão com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verifica se a variável de conexão foi definida corretamente
if (!isset($mysqli)) {
    die("Erro na conexão com o banco de dados.");
}

// Recupera o ID da escola da sessão
$escola_id = $_SESSION['id_escola'];

// Verifica se os parâmetros necessários foram passados
if (isset($_POST['id'], $_POST['campo'], $_POST['valor'])) {
    $id = intval($_POST['id']); // ID do professor
    $campo = mysqli_real_escape_string($mysqli, $_POST['campo']); // Campo a ser atualizado
    $valor = mysqli_real_escape_string($mysqli, $_POST['valor']); // Novo valor

    // Valida o campo
    $campos_validos = ['nome', 'email', 'genero', 'idade', 'endereco', 'distrito_id', 'telefone', 'nome_facebook', 'data_contrato', 'nivel_academico', 'area_formacao1', 'funcao', 'categoria_salarial', 'id_classe', 'id_turma', 'id_disciplina'];

    if (in_array($campo, $campos_validos)) {
        // Prepara a consulta para atualizar o campo do professor
        $sql = "UPDATE professor SET $campo = ? WHERE id = ? AND id_escola = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $mysqli->error);
        }

        // Faz o bind e executa a atualização
        if ($campo == 'data_contrato') {
            // Se for data, precisamos garantir o formato correto
            $stmt->bind_param("ssi", $valor, $id, $escola_id);
        } else {
            $stmt->bind_param("ssi", $valor, $id, $escola_id);
        }

        if ($stmt->execute()) {
            echo "Edição salva com sucesso!";
        } else {
            echo "Erro ao salvar a edição: " . $stmt->error;
        }

        // Fecha o statement
        $stmt->close();
    } else {
        echo "Campo inválido!";
    }
} else {
    echo "Dados incompletos!";
}

// Fecha a conexão com o banco de dados
$mysqli->close();
?>
