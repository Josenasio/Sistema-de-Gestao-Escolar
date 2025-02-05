<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o ID do usuário da sessão
$id_usuario = $_SESSION['id'];

// Definir as opções permitidas para situacao_economica
$opcoes_situacao = ['pobre', 'muito pobre', 'médio', 'rico', 'muito rico'];

// Função para validar e limpar entradas
function limparEntrada($entrada) {
    return htmlspecialchars(trim($entrada), ENT_QUOTES, 'UTF-8');
}

// Atualizar dados se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar'])) {
    $id_aluno = $_POST['id_aluno'];
    $nome = limparEntrada($_POST['nome']);
    $idade = (int) $_POST['idade'];
    $bi = limparEntrada($_POST['bi']);
    $motivo_abandono = limparEntrada($_POST['motivo_abandono']);
    $estrategia_recuperacao = limparEntrada($_POST['estrategia_recuperacao']);
    $contato_encarregado = limparEntrada($_POST['contato_encarregado']);
    $endereco = limparEntrada($_POST['endereco']);
    $situacao_economica = $_POST['situacao_economica'];

    if (!in_array($situacao_economica, $opcoes_situacao)) {
        $_SESSION['erro'] = "Situação econômica inválida!";
        header("Location: tabela_abandono.php");
        exit;
    }

    $query_update = "UPDATE aluno SET nome = ?, idade = ?, bi = ?, motivo_abandono = ?, estrategia_recuperacao = ?, contato_encarregado = ?, endereco = ?, situacao_economica = ? WHERE numero_ordem = ?";
    $stmt_update = $mysqli->prepare($query_update);
    if ($stmt_update === false) {
        die('Erro na preparação da consulta: ' . $mysqli->error);
    }
    $stmt_update->bind_param("sissssssi", $nome, $idade, $bi, $motivo_abandono, $estrategia_recuperacao, $contato_encarregado, $endereco, $situacao_economica, $id_aluno);
    if ($stmt_update->execute()) {
        $_SESSION['sucesso'] = "Dados atualizados com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao atualizar os dados!";
    }
    $stmt_update->close();
    header("Location: tabela_abandono.php");
    exit;
}

$query_deficientes = "SELECT * FROM aluno WHERE id_diretor_turma = ? AND motivo_abandono != ''";
$stmt_deficientes = $mysqli->prepare($query_deficientes);
if ($stmt_deficientes === false) {
    die('Erro na preparação da consulta: ' . $mysqli->error);
}
$stmt_deficientes->bind_param("i", $id_usuario);
$stmt_deficientes->execute();
$result_deficientes = $stmt_deficientes->get_result();
$alunos_abandono = $result_deficientes->fetch_all(MYSQLI_ASSOC);
$stmt_deficientes->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Abandonos</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color:rgba(0, 0, 0, 0.19);
        }
        .table-container {
            margin: 20px auto;
            max-width: 100%;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
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
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard/index.php'">
    <i class="fa fa-arrow-left"></i> Voltar a Pagina Inicial
</button>

    <br>
    <br>
    <div class="container table-container mt-5">
        <h2 class="text-center mb-4">Lista de Alunos com Abandono</h2>
        <?php if (count($alunos_abandono) > 0): ?>
            <table class="table table-striped table-bordered table-responsive-md table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Bilhete</th>
                        <th>Motivo</th>
                        <th>Estratégia Recuperação</th>
                        <th>Contato Encarregado</th>
                        <th>Endereço</th>
                        <th>Sit. Econômica</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos_abandono as $aluno): ?>
                        <form method="POST">
                            <tr>
                                <td><?php echo htmlspecialchars($aluno['numero_ordem'] ?? ''); ?></td>
                                <td><input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($aluno['nome'] ?? ''); ?>"></td>
                                <td><input type="number" name="idade" class="form-control" value="<?php echo htmlspecialchars($aluno['idade'] ?? ''); ?>"></td>
                                <td><input type="text" name="bi" class="form-control" value="<?php echo htmlspecialchars($aluno['bi'] ?? ''); ?>"></td>
                                <td><input type="text" name="motivo_abandono" class="form-control" value="<?php echo htmlspecialchars($aluno['motivo_abandono'] ?? ''); ?>"></td>
                                <td><input type="text" name="estrategia_recuperacao" class="form-control" value="<?php echo htmlspecialchars($aluno['estrategia_recuperacao'] ?? ''); ?>"></td>
                                <td><input type="text" name="contato_encarregado" class="form-control" value="<?php echo htmlspecialchars($aluno['contato_encarregado'] ?? ''); ?>"></td>
                                <td><input type="text" name="endereco" class="form-control" value="<?php echo htmlspecialchars($aluno['endereco'] ?? ''); ?>"></td>
                                <td>
                                    <select name="situacao_economica" class="form-control">
                                        <option value="">Selecione</option>
                                        <?php foreach ($opcoes_situacao as $opcao): ?>
                                            <option value="<?php echo $opcao; ?>" <?php echo ($aluno['situacao_economica'] == $opcao) ? 'selected' : ''; ?>><?php echo ucfirst($opcao); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="id_aluno" value="<?php echo $aluno['numero_ordem']; ?>">
                                    <button type="submit" name="atualizar" class="btn btn-success btn-sm">Atualizar</button>
                                </td>
                            </tr>
                        </form>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">Nenhum aluno encontrado.</div>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
