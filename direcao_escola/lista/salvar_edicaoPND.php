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

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados enviados
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $campo = isset($_POST['campo']) ? $_POST['campo'] : '';
    $valor = isset($_POST['valor']) ? $_POST['valor'] : '';

    // Verifica se os dados são válidos
    if ($id > 0 && !empty($campo) && $valor !== '') {
        // Lista de campos permitidos para edição
        $campos_permitidos = [
            'nome', 'contacto', 'endereco', 'idade', 'funcao',
            'ano_servico', 'ano_inicio_servico', 'numero_conta_bancaria'
        ];

        // Verifica se o campo é permitido
        if (in_array($campo, $campos_permitidos)) {
            // Prepara a consulta para atualizar o campo
            $sql = "UPDATE pessoal_nao_docente SET $campo = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("si", $valor, $id);
                if ($stmt->execute()) {
                    echo "Alteração salva com sucesso.";
                } else {
                    echo "Erro ao salvar alteração: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Erro na preparação da consulta: " . $mysqli->error;
            }
        } else {
            echo "Campo inválido.";
        }
    } else {
        echo "Dados incompletos.";
    }
} else {
    echo "Método não permitido.";
}

// Fecha a conexão com o banco de dados
$mysqli->close();
?>
