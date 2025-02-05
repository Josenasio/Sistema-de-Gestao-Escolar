<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verificar se o ID do aluno foi passado na URL
if (!isset($_GET['id'])) {
    die("Erro: ID do aluno não fornecido.");
}

// Recuperar o ID do aluno da URL
$id_aluno = $_GET['id'];

// Consultar as informações do aluno no banco de dados
$query_aluno = "SELECT * FROM aluno WHERE id = ?";
$stmt_aluno = $mysqli->prepare($query_aluno);
if (!$stmt_aluno) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_aluno->bind_param("i", $id_aluno);
$stmt_aluno->execute();
$result_aluno = $stmt_aluno->get_result();
$aluno = $result_aluno->fetch_assoc();

if (!$aluno) {
    die("Erro: Aluno não encontrado.");
}

// Fechar a consulta
$stmt_aluno->close();

// Verificar se o formulário foi enviado para atualizar o aluno
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar os dados do formulário
    $bi = $mysqli->real_escape_string($_POST['bi']);
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $idade = $mysqli->real_escape_string($_POST['idade']);
    $numero_ordem = $mysqli->real_escape_string($_POST['numero_ordem']);
    $endereco = $mysqli->real_escape_string($_POST['endereco']);
    $genero = $mysqli->real_escape_string($_POST['genero']);
    $numero_frequencia = $mysqli->real_escape_string($_POST['numero_frequencia']);
    $telefone = $mysqli->real_escape_string($_POST['telefone']);
    $nome_encarregado = $mysqli->real_escape_string($_POST['nome_encarregado']);
    $contato_encarregado = $mysqli->real_escape_string($_POST['contato_encarregado']);
    $deficiente = isset($_POST['deficiente']) ? 1 : 0;
    $gravidez = isset($_POST['gravidez']) ? 1 : 0;
    

    // Atualizar as informações do aluno
    $query_update = "UPDATE aluno SET bi = ?, nome = ?, idade = ?, numero_ordem = ?, endereco = ?, genero = ?, numero_frequencia = ?, telefone = ?, nome_encarregado = ?, contato_encarregado = ?, deficiente = ?, gravidez = ? WHERE id = ?";
    $stmt_update = $mysqli->prepare($query_update);
    if (!$stmt_update) {
        die("Erro na preparação da consulta de atualização: " . $mysqli->error);
    }

    $stmt_update->bind_param("ssissssissssi", $bi, $nome, $idade, $numero_ordem, $endereco, $genero, $numero_frequencia, $telefone, $nome_encarregado, $contato_encarregado, $deficiente, $gravidez, $id_aluno);
    $stmt_update->execute();

    if ($stmt_update->affected_rows > 0) {
        // Redirecionar para a lista de alunos
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Erro ao atualizar os dados do aluno.";
    }
    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Adicione o link para o Font Awesome no cabeçalho -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #baf3d7;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        h1 {
            color: #007bff;
            font-weight: 500;
        }

        .form-control {
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .checkbox-label {
            font-weight: 400;
            color: #333;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #007bff;
        }

        .fixed-top-button {
            margin-top: -2px;
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 100%;
            z-index: 1000;
            background-color: black;
            border: none;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            letter-spacing: 2px;
         
     
        }

        .fixed-top-button:hover {
            background-color: red;
        }
    </style>
</head>
<body>

<!-- Botão com ícone -->
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
</button>
<br><br>
    <div class="container mt-4">
        <h1 class="mb-4">Editar Aluno</h1>
        <form method="POST">
            <!-- Campo para o BI -->
            <div class="mb-3">
                <label for="bi" class="form-label">BI</label>
                <input type="text" class="form-control" id="bi" name="bi" value="<?= htmlspecialchars($aluno['bi']) ?>" required>
            </div>
            
            <!-- Campo para o Nome -->
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($aluno['nome']) ?>" required>
            </div>
            
            <!-- Campo para a Idade -->
            <div class="mb-3">
                <label for="idade" class="form-label">Idade</label>
                <input type="number" class="form-control" id="idade" name="idade" value="<?= htmlspecialchars($aluno['idade']) ?>" required>
            </div>
            
            <!-- Campo para o Número de Ordem -->
            <div class="mb-3">
                <label for="numero_ordem" class="form-label">Número de Ordem</label>
                <input type="number" class="form-control" id="numero_ordem" name="numero_ordem" value="<?= htmlspecialchars($aluno['numero_ordem'] ?? '') ?>" required>

            </div>
            
            <!-- Campo para o Endereço -->
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($aluno['endereco']) ?>" required>
            </div>
            
            <!-- Campo para o Gênero -->
            <div class="mb-3">
                <label for="genero" class="form-label">Gênero</label>
                <select class="form-control" id="genero" name="genero">
                    <option value="Masculino" <?= ($aluno['genero'] == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                    <option value="Feminino" <?= ($aluno['genero'] == 'Feminino') ? 'selected' : '' ?>>Feminino</option>
                    <option value="Outro" <?= ($aluno['genero'] == 'Outro') ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>
            
            <!-- Campo para o Número de Frequência -->
            <div class="mb-3">
                <label for="numero_frequencia" class="form-label">Número de Frequência</label>
                <input type="number" class="form-control" id="numero_frequencia" name="numero_frequencia" value="<?= htmlspecialchars($aluno['numero_frequencia']) ?>" required>
            </div>
            
            <!-- Campo para o Telefone -->
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($aluno['telefone']) ?>" required>
            </div>
            
            <!-- Campo para o Nome do Encarregado -->
            <div class="mb-3">
                <label for="nome_encarregado" class="form-label">Nome do Encarregado</label>
                <input type="text" class="form-control" id="nome_encarregado" name="nome_encarregado" value="<?= htmlspecialchars($aluno['nome_encarregado']) ?>" required>
            </div>
            
            <!-- Campo para o Contato do Encarregado -->
            <div class="mb-3">
                <label for="contato_encarregado" class="form-label">Contato do Encarregado</label>
                <input type="text" class="form-control" id="contato_encarregado" name="contato_encarregado" value="<?= htmlspecialchars($aluno['contato_encarregado']) ?>" required>
            </div>

            <!-- Deficiente e Gravidez -->
            <div class="mb-3">
                <label for="deficiente" class="form-label">Deficiente</label>
                <input type="checkbox" id="deficiente" name="deficiente" <?= $aluno['deficiente'] ? 'checked' : '' ?>>
            </div>
            
            <div class="mb-3">
                <label for="gravidez" class="form-label">Grávida</label>
                <input type="checkbox" id="gravidez" name="gravidez" <?= $aluno['gravidez'] ? 'checked' : '' ?>>
            </div>

           

            <!-- Botão de Enviar -->
            <button type="submit" class="btn btn-primary">Atualizar Aluno</button>
        </form>
    </div>
</body>
</html>
