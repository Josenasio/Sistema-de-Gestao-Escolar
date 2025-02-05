<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../index.php");
    exit;
}

// Incluir a conexão com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar e receber os dados do formulário
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome']);
    $idade = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
    $endereco = trim($_POST['endereco']);
    $genero = $_POST['genero'];
    $numero_frequencia = filter_input(INPUT_POST, 'numero_frequencia', FILTER_VALIDATE_INT);
    $numero_ordem = filter_input(INPUT_POST, 'numero_ordem', FILTER_VALIDATE_INT);
    $nome_encarregado = trim($_POST['nome_encarregado']);
    $contato_encarregado = trim($_POST['contato_encarregado']);
    $bi = trim($_POST['bi']);
    $naturalidade = trim($_POST['naturalidade']);
    $data_emissao_bi = $_POST['data_emissao_bi'];
    $situacao_economica = $_POST['situacao_economica'];
    $religiao_id = filter_input(INPUT_POST, 'religiao_id', FILTER_VALIDATE_INT);
    $id_distrito = filter_input(INPUT_POST, 'id_distrito', FILTER_VALIDATE_INT);

    // Verificar se todos os campos obrigatórios foram preenchidos
    if ($id && $nome && $idade && $endereco && $genero &&
        $numero_frequencia && $numero_ordem && $nome_encarregado &&
        $contato_encarregado && $bi && $naturalidade && $data_emissao_bi &&
        $situacao_economica && $religiao_id && $id_distrito) {
        
        // Preparar a consulta para atualizar o aluno
        $query = "UPDATE aluno SET 
                    nome = ?, 
                    idade = ?, 
    
                    endereco = ?, 
                    genero = ?, 
                    numero_frequencia = ?, 
                    numero_ordem = ?, 
                
                    nome_encarregado = ?, 
                    contato_encarregado = ?, 
                    bi = ?, 
                    naturalidade = ?, 
                    data_emissao_bi = ?, 
                    situacao_economica = ?, 
                    religiao_id = ?, 
                    id_distrito = ?
                  WHERE id = ?";

        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param(
                "sissiississsiii",
                $nome, $idade, $endereco, $genero,
                $numero_frequencia, $numero_ordem, $nome_encarregado,
                $contato_encarregado, $bi, $naturalidade, $data_emissao_bi,
                $situacao_economica, $religiao_id, $id_distrito, $id
            );

            if ($stmt->execute()) {
                // Sucesso
                $_SESSION['success_message'] = "Dados do aluno atualizados com sucesso!";
                header("Location: index.php"); // Redirecionar para a lista de alunos
                exit;
            } else {
                $_SESSION['error_message'] = "Erro ao atualizar os dados do aluno.";
            }

            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Erro na preparação da consulta: " . $mysqli->error;
        }
    } else {
        $_SESSION['error_message'] = "Por favor, preencha todos os campos obrigatórios.";
    }
} else {
    $_SESSION['error_message'] = "Método de requisição inválido.";
}

// Redirecionar de volta em caso de erro
header("Location: index.php");
exit;
?>
