<?php 
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o ID do usuário da sessão
$id_usuario = $_SESSION['id']; // ID do usuário armazenado na sessão

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

// Armazenar o nome do usuário em uma variável
$nome_usuario = $usuario['nome'] ?? 'Usuário';
$stmt_usuario->close();





// Recuperar os alunos com base no id_diretor_turma e ordenados por numero_ordem
$query_alunos = "SELECT * FROM aluno WHERE id_diretor_turma = ? ORDER BY numero_ordem";
$stmt_alunos = $mysqli->prepare($query_alunos);
$stmt_alunos->bind_param("i", $id_usuario);
$stmt_alunos->execute();
$result_alunos = $stmt_alunos->get_result();
$alunos = $result_alunos->fetch_all(MYSQLI_ASSOC);
$stmt_alunos->close();




// Atualizar os campos de deficiência
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['tipo_deficiencia'] as $id_aluno => $tipo) {
        // Se o campo 'tipo_deficiencia' for preenchido, definimos o valor de 'deficiente' como 1
        $deficiente = !empty($tipo) ? 1 : 0;

        $query_update = "UPDATE aluno SET tipo_deficiencia = ?, deficiente = ? WHERE id = ?";
        $stmt_update = $mysqli->prepare($query_update);
        $stmt_update->bind_param("sii", $tipo, $deficiente, $id_aluno);
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
    <title>Registrar ou Atualizar Deficiência</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color:rgba(0, 0, 0, 0.19);
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h1 {
            color: #343a40;
            margin-bottom: 30px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            background-color: #f1f1f1;
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
            width: 100%;
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

        .fixed-top-button {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background-color: black;
            border: none;
            color: white;
            padding: 15px;
            font-size: 16px;
            text-align: center;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .fixed-top-button:hover {
            background-color: #e74c3c;
        }

        @media (max-width: 768px) {
            input[type="submit"] {
                width: 100%;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard/index.php'">
    <i class="fa fa-arrow-left"></i> Voltar a Pagina Inicial
</button>


<div class="container">
<h2 class="text-center mb-4">Registar ou atualizar Deficiência</h2>

    <?php if (count($alunos) > 0): ?>
        <form method="POST">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Nome</th>
                        <th>Tipo de Deficiência</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aluno['numero_ordem'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($aluno['nome'] ?? ''); ?></td>
                            <td>
                                <input type="text" class="form-control" name="tipo_deficiencia[<?php echo $aluno['id']; ?>]" value="<?php echo htmlspecialchars($aluno['tipo_deficiencia'] ?? ''); ?>" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-center">
                <input type="submit" value="Atualizar Deficiência">
            </div>
        </form>
    <?php else: ?>
        <p class="message">Não há alunos registrados para este diretor de turma.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
