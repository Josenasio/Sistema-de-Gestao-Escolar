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

// Atualizar dados se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar'])) {
    $id_aluno = $_POST['id_aluno'];
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $bi = $_POST['bi'];
    $tipo_deficiencia = $_POST['tipo_deficiencia'];
    $contato_encarregado = $_POST['contato_encarregado'];
    $endereco = $_POST['endereco'];
    $situacao_economica = $_POST['situacao_economica'];

    // Validar situacao_economica
    if (!in_array($situacao_economica, $opcoes_situacao)) {
        echo "<script>alert('Situação econômica inválida!');</script>";
    } else {
        // Preparar e executar a atualização
        $query_update = "UPDATE aluno SET nome = ?, idade = ?, bi = ?, tipo_deficiencia = ?, contato_encarregado = ?, endereco = ?, situacao_economica = ? WHERE numero_ordem = ?";
        $stmt_update = $mysqli->prepare($query_update);
        $stmt_update->bind_param("sisssssi", $nome, $idade, $bi, $tipo_deficiencia, $contato_encarregado, $endereco, $situacao_economica, $id_aluno);
        $stmt_update->execute();
        $stmt_update->close();
    }
}

// Consultar alunos deficientes
$query_deficientes = "SELECT * FROM aluno WHERE id_diretor_turma = ? AND deficiente = 1";
$stmt_deficientes = $mysqli->prepare($query_deficientes);
$stmt_deficientes->bind_param("i", $id_usuario);
$stmt_deficientes->execute();
$result_deficientes = $stmt_deficientes->get_result();
$alunos_deficientes = $result_deficientes->fetch_all(MYSQLI_ASSOC);
$stmt_deficientes->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos Deficientes</title>
    <!-- Incluindo o CSS do Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color:rgba(0, 0, 0, 0.19);
        }
        .table-container {
            margin-top: 40px;
            padding: 20px;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        .fixed-top-button {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
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


<div class="container table-container">
    <h2 class="text-center mb-4">Lista de Alunos Deficientes</h2>
    <?php if (count($alunos_deficientes) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Bilhete</th>
                        <th>Deficiência</th>
                        <th>Contato Encarregado</th>
                        <th>Endereço</th>
                        <th>Sit.Económica</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos_deficientes as $aluno): ?>
                        <form method="POST">
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($aluno['numero_ordem']); ?></td>
                                <td><input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($aluno['nome']); ?>"></td>
                                <td><input type="number" name="idade" class="form-control" value="<?php echo htmlspecialchars($aluno['idade']); ?>"></td>
                                <td><input type="text" name="bi" class="form-control" value="<?php echo htmlspecialchars($aluno['bi']); ?>"></td>
                                <td><input type="text" name="tipo_deficiencia" class="form-control" value="<?php echo htmlspecialchars($aluno['tipo_deficiencia']); ?>"></td>
                                <td><input type="text" name="contato_encarregado" class="form-control" value="<?php echo htmlspecialchars($aluno['contato_encarregado']); ?>"></td>
                                <td><input type="text" name="endereco" class="form-control" value="<?php echo htmlspecialchars($aluno['endereco']); ?>"></td>
                                <td>
                                    <select name="situacao_economica" class="form-control">
                                        <option value="">Selecione a situação econômica</option>
                                        <?php 
                                            foreach ($opcoes_situacao as $opcao) {
                                                $selected = ($aluno['situacao_economica'] == $opcao) ? 'selected' : ''; 
                                                echo "<option value='$opcao' $selected>$opcao</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="id_aluno" value="<?php echo $aluno['numero_ordem']; ?>">
                                    <button type="submit" name="atualizar" class="btn btn-success btn-sm">Atualizar</button>
                                </td>
                            </tr>
                        </form>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center" role="alert">
            Nenhum aluno deficiente encontrado.
        </div>
    <?php endif; ?>
</div>

<!-- Incluindo o JavaScript do Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
