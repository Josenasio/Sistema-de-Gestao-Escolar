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

// Atualizar os campos motivo_abandono e estrategia_recuperacao
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['motivo_abandono'] as $id_aluno => $motivo) {
        $estrategia = $_POST['estrategia_recuperacao'][$id_aluno] ?? null;

        $query_update = "UPDATE aluno SET motivo_abandono = ?, estrategia_recuperacao = ? WHERE id = ?";
        $stmt_update = $mysqli->prepare($query_update);
        $stmt_update->bind_param("ssi", $motivo, $estrategia, $id_aluno);
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
    <title>Registrar ou Atualizar Casos de Abandono</title>

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

<div class="container">
    <h1>Registrar ou Atualizar Casos de Abandono</h1>

    <?php if (count($alunos) > 0): ?>
        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Nome</th>
                        <th>Motivo de Abandono</th>
                        <th>Estratégia de Recuperação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($aluno['numero_ordem'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($aluno['nome'] ?? ''); ?></td>
                            <td>
                                <textarea name="motivo_abandono[<?php echo $aluno['id']; ?>]" rows="3"><?php echo htmlspecialchars($aluno['motivo_abandono'] ?? ''); ?></textarea>
                            </td>
                            <td>
                                <textarea name="estrategia_recuperacao[<?php echo $aluno['id']; ?>]" rows="3"><?php echo htmlspecialchars($aluno['estrategia_recuperacao'] ?? ''); ?></textarea>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 20px;">
                <input type="submit" value="Atualizar Casos de Abandono">
            </div>
        </form>
    <?php else: ?>
        <p class="message">Não há alunos registrados para este diretor de turma.</p>
    <?php endif; ?>
</div>

</body>
</html>
