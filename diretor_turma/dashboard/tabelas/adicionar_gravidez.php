<?php 
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o ID do usuário da sessão
$id_usuario = $_SESSION['id'];

// Consultar o nome do usuário no banco de dados
$query_usuario = "SELECT nome FROM usuarios WHERE id = ?";
$stmt_usuario = $mysqli->prepare($query_usuario);
if (!$stmt_usuario) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
$nome_usuario = $usuario['nome'] ?? 'Usuário';
$stmt_usuario->close();




// Recuperar os alunos com base no id_diretor_turma e ordenados por numero_ordem
$query_alunos = "SELECT * FROM aluno WHERE id_diretor_turma = ? AND genero = 'Feminino' ORDER BY numero_ordem";
$stmt_alunos = $mysqli->prepare($query_alunos);
$stmt_alunos->bind_param("i", $id_usuario);
$stmt_alunos->execute();
$result_alunos = $stmt_alunos->get_result();
$alunos = $result_alunos->fetch_all(MYSQLI_ASSOC);
$stmt_alunos->close();



// Atualizar os campos de gravidez
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['observacao_gravidez'] as $id_aluno => $tipo) {
        $gravidez = !empty($tipo) ? 1 : NULL;
        $data_conhecimento = $_POST['data_conhecimento_gravidez'][$id_aluno] ?? NULL;

        // Validação do formato da data
        if (!empty($data_conhecimento) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_conhecimento)) {
            $data_conhecimento = date('Y-m-d', strtotime($data_conhecimento));
        } else {
            $data_conhecimento = NULL; // Caso o formato seja inválido
        }

        $query_update = "UPDATE aluno SET observacao_gravidez = ?, data_conhecimento_gravidez = ?, gravidez = ? WHERE id = ?";
        $stmt_update = $mysqli->prepare($query_update);

        if (!$stmt_update) {
            die("Erro na preparação da consulta: " . $mysqli->error);
        }

        $stmt_update->bind_param("ssii", $tipo, $data_conhecimento, $gravidez, $id_aluno);
        $stmt_update->execute();
        $stmt_update->close();
    }

    // Redirecionar após a atualização
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar ou Atualizar Gravidez</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color:rgba(0, 0, 0, 0.19);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
            color: #007bff;
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }

            input[type="submit"] {
                width: 100%;
            }
        }









        .table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Suaviza a rolagem em dispositivos móveis */
}

table {
    width: 100%;
    min-width: 600px; /* Define uma largura mínima para evitar colapsos */
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    white-space: nowrap; /* Evita quebras de linha dentro das células */
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

<br><br>

<div class="container">
    <h1>Registrar ou Atualizar Gravidez</h1>

    <?php if (count($alunos) > 0): ?>
        <form method="POST">
          


        <div class="table-responsive">

        <table>
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Nome</th>
                        <th>Observação</th>
                        <th>Data Conhecimento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aluno['numero_ordem'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($aluno['nome'] ?? ''); ?></td>
                            <td>
                                <input type="text" name="observacao_gravidez[<?php echo $aluno['id']; ?>]" value="<?php echo htmlspecialchars($aluno['observacao_gravidez'] ?? ''); ?>" />
                            </td>
                            <td>
                                <input type="date" name="data_conhecimento_gravidez[<?php echo $aluno['id']; ?>]" value="<?php echo htmlspecialchars($aluno['data_conhecimento_gravidez'] ?? ''); ?>" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


        </div>

            <div style="text-align: center; margin-top: 20px;">
                <input type="submit" value="Atualizar Gravidez">
            </div>
        </form>
    <?php else: ?>
        <p class="message">Não há alunos registrados para este diretor de turma.</p>
    <?php endif; ?>
</div>

</body>
</html>
