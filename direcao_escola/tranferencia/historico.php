<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o id_escola da sessão
if (!isset($_SESSION['id_escola'])) {
    die("Erro: ID da escola não definido.");
}

$id_escola = $_SESSION['id_escola'];

// Consultar transferências enviadas com subconsulta para obter nome do aluno e da escola destino
$query_transferidos = "
    SELECT 
        a.nome AS nome_aluno,
        t1.transferidos, 
        t1.data, 
        (
            SELECT e.nome 
            FROM transferencias t2
            JOIN escola e ON t2.id_escola = e.id
            WHERE t2.id_aluno = t1.id_aluno AND t2.recebidos = 1
            LIMIT 1
        ) AS nome_escola_destino
    FROM transferencias t1
    JOIN aluno a ON t1.id_aluno = a.id
    WHERE t1.id_escola = ? AND t1.transferidos = 1";
$stmt_transferidos = $mysqli->prepare($query_transferidos);
$stmt_transferidos->bind_param("i", $id_escola);
$stmt_transferidos->execute();
$result_transferidos = $stmt_transferidos->get_result();
$transferidos = $result_transferidos->fetch_all(MYSQLI_ASSOC);

// Consultar transferências recebidas com subconsulta para obter nome do aluno e da escola origem
$query_recebidos = "
    SELECT 
        a.nome AS nome_aluno,
        t1.recebidos, 
        t1.data, 
        (
            SELECT e.nome 
            FROM transferencias t2
            JOIN escola e ON t2.id_escola = e.id
            WHERE t2.id_aluno = t1.id_aluno AND t2.transferidos = 1
            LIMIT 1
        ) AS nome_escola_origem
    FROM transferencias t1
    JOIN aluno a ON t1.id_aluno = a.id
    WHERE t1.id_escola = ? AND t1.recebidos = 1";
$stmt_recebidos = $mysqli->prepare($query_recebidos);
$stmt_recebidos->bind_param("i", $id_escola);
$stmt_recebidos->execute();
$result_recebidos = $stmt_recebidos->get_result();
$recebidos = $result_recebidos->fetch_all(MYSQLI_ASSOC);

// Consultar o total de alunos transferidos
$query_total_transferidos = "
    SELECT COUNT(DISTINCT t1.id_aluno) AS total_transferidos
    FROM transferencias t1
    WHERE t1.id_escola = ? AND t1.transferidos = 1";
$stmt_total_transferidos = $mysqli->prepare($query_total_transferidos);
$stmt_total_transferidos->bind_param("i", $id_escola);
$stmt_total_transferidos->execute();
$result_total_transferidos = $stmt_total_transferidos->get_result();
$total_transferidos = $result_total_transferidos->fetch_assoc();

// Consultar o total de alunos recebidos
$query_total_recebidos = "
    SELECT COUNT(DISTINCT t1.id_aluno) AS total_recebidos
    FROM transferencias t1
    WHERE t1.id_escola = ? AND t1.recebidos = 1";
$stmt_total_recebidos = $mysqli->prepare($query_total_recebidos);
$stmt_total_recebidos->bind_param("i", $id_escola);
$stmt_total_recebidos->execute();
$result_total_recebidos = $stmt_total_recebidos->get_result();
$total_recebidos = $result_total_recebidos->fetch_assoc();

$stmt_transferidos->close();
$stmt_recebidos->close();
$stmt_total_transferidos->close();
$stmt_total_recebidos->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Transferências</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Adicione o link para o Font Awesome no cabeçalho -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>


body {
            background-color: #c1efde;;
        } 
         .fixed-top-button {
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
<div style="margin-top: 90px;"></div>

<div class="container my-5">
    <h1 class="text-center mb-4">Histórico de Transferências</h1>

    <!-- Tabela de Transferidos -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Alunos Transferidos</div>
        <div class="card-body">
            <div class="table-responsive"> <!-- Adicione esta linha -->
                <table id="transferidosTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome do Aluno</th>
                            <th>Transferidos</th>
                            <th>Data</th>
                            <th>Escola Destino</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transferidos as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome_aluno']) ?></td>
                                <td><?= htmlspecialchars($row['transferidos']) ?></td>
                                <td><?= htmlspecialchars($row['data']) ?></td>
                                <td><?= htmlspecialchars($row['nome_escola_destino'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> <!-- Feche aqui -->
        </div>
    </div>

    <!-- Tabela de Recebidos -->
    <div class="card">
        <div class="card-header bg-success text-white">Alunos Recebidos</div>
        <div class="card-body">
            <div class="table-responsive"> <!-- Adicione esta linha -->
                <table id="recebidosTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome do Aluno</th>
                            <th>Recebidos</th>
                            <th>Data</th>
                            <th>Escola de Origem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recebidos as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome_aluno']) ?></td>
                                <td><?= htmlspecialchars($row['recebidos']) ?></td>
                                <td><?= htmlspecialchars($row['data']) ?></td>
                                <td><?= htmlspecialchars($row['nome_escola_origem'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> <!-- Feche aqui -->
        </div>
    </div>

    <!-- Tabela de Totais -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Totais de Transferências</div>
        <div class="card-body">
            <div class="table-responsive"> <!-- Adicione esta linha -->
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Total de Alunos Transferidos</th>
                            <th>Total de Alunos Recebidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($total_transferidos['total_transferidos']) ?></td>
                            <td><?= htmlspecialchars($total_recebidos['total_recebidos']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- Feche aqui -->
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#transferidosTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json"
            }
        });
        $('#recebidosTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json"
            }
        });
    });
</script>
</body>
</html>
