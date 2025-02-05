<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: ../../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Consultar transferências com subconsulta para obter nome do aluno, escola origem e escola destino
$query_transferencias_completas = "
    SELECT 
        a.nome AS nome_aluno,
        t1.transferidos, 
        t1.data AS data_transferido,
        e_destino.nome AS nome_escola_destino,
        t2.recebidos,
        t2.data AS data_recebido,
        e_origem.nome AS nome_escola_origem
    FROM transferencias t1
    JOIN aluno a ON t1.id_aluno = a.id
    JOIN escola e_destino ON t1.id_escola = e_destino.id
    LEFT JOIN transferencias t2 ON t2.id_aluno = t1.id_aluno AND t2.recebidos = 1
    LEFT JOIN escola e_origem ON t2.id_escola = e_origem.id
    WHERE t1.transferidos = 1";

// Caso haja filtro por nome da escola
if (isset($_GET['escola']) && !empty($_GET['escola'])) {
    $escola = "%" . $_GET['escola'] . "%";
    $query_transferencias_completas .= " AND (e_destino.nome LIKE ? OR e_origem.nome LIKE ?)";
    $stmt_transferencias_completas = $mysqli->prepare($query_transferencias_completas);
    $stmt_transferencias_completas->bind_param('ss', $escola, $escola);
} else {
    $stmt_transferencias_completas = $mysqli->prepare($query_transferencias_completas);
}

$stmt_transferencias_completas->execute();
$result_transferencias_completas = $stmt_transferencias_completas->get_result();
$transferencias_completas = $result_transferencias_completas->fetch_all(MYSQLI_ASSOC);

// Consultar o total de alunos transferidos e recebidos (ajustado para a junção)
$query_total_transferidos_recebidos = "
    SELECT 
        COUNT(DISTINCT t1.id_aluno) AS total_transferidos,
        (SELECT COUNT(DISTINCT t2.id_aluno) FROM transferencias t2 WHERE t2.recebidos = 1) AS total_recebidos
    FROM transferencias t1
    WHERE t1.transferidos = 1";

$stmt_total_transferidos_recebidos = $mysqli->prepare($query_total_transferidos_recebidos);
$stmt_total_transferidos_recebidos->execute();
$result_total_transferidos_recebidos = $stmt_total_transferidos_recebidos->get_result();
$total_transferidos_recebidos = $result_total_transferidos_recebidos->fetch_assoc();

// Consultar total de alunos transferidos e recebidos por escola
$query_total_por_escola = "
    SELECT 
        e.nome AS nome_escola,
        COUNT(DISTINCT t1.id_aluno) AS total_transferidos,
        (SELECT COUNT(DISTINCT t2.id_aluno) FROM transferencias t2 WHERE t2.id_escola = e.id AND t2.recebidos = 1) AS total_recebidos
    FROM escola e
    LEFT JOIN transferencias t1 ON t1.id_escola = e.id AND t1.transferidos = 1
    GROUP BY e.id";

$stmt_total_por_escola = $mysqli->prepare($query_total_por_escola);
$stmt_total_por_escola->execute();
$result_total_por_escola = $stmt_total_por_escola->get_result();
$total_por_escola = $result_total_por_escola->fetch_all(MYSQLI_ASSOC);

$stmt_transferencias_completas->close();
$stmt_total_transferidos_recebidos->close();
$stmt_total_por_escola->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Transferências - Todas as Escolas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #1B203B;
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
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>

<div style="margin-top: 90px;"></div>

<div class="container my-5">
    <h1 class="text-center mb-4" style="color: #ffffff;">Histórico de Transferências - Todas as Escolas</h1>

    <!-- Filtro de Busca -->
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="escola" class="form-control" placeholder="Buscar por nome da escola" value="<?= isset($_GET['escola']) ? htmlspecialchars($_GET['escola']) : '' ?>">
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </div>
    </form>

    <!-- Tabela Consolidada de Transferências -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Histórico de Transferências (Origem e Destino)</div>
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-striped table-bordered" id="table-results">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome do Aluno</th>
                            <th>Escola Origem</th>
                            <th>Escola Destino</th>
                            <th>Data Transferência</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transferencias_completas as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome_aluno']) ?></td>
                                <td><?= htmlspecialchars($row['nome_escola_destino'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['nome_escola_origem'] ?? 'N/A') ?></td>
                             
                                <td><?= htmlspecialchars($row['data_transferido']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#"  class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download" ></i> Baixar Tabela
    </a>
</div><br>
<hr>

    <!-- Tabela de Totais -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Totais de Transferências</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Total de Alunos Transferidos</th>
                            <th>Total de Alunos Recebidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($total_transferidos_recebidos['total_transferidos']) ?></td>
                            <td><?= htmlspecialchars($total_transferidos_recebidos['total_recebidos']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabela Total de Transferências por Escola -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">Totais por Escola</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome da Escola</th>
                            <th>Total de Alunos Transferidos</th>
                            <th>Total de Alunos Recebidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($total_por_escola as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nome_escola']) ?></td>
                                <td><?= htmlspecialchars($row['total_transferidos']) ?></td>
                                <td><?= htmlspecialchars($row['total_recebidos']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function () {
        $('#transferenciasTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json"
            }
        });
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.querySelectorAll('.field-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        const fieldClass = checkbox.value;
        document.querySelectorAll('.' + fieldClass).forEach(cell => {
            cell.style.display = checkbox.checked ? '' : 'none';
        });
    });
});

function exportToExcel() {
    const table = document.getElementById('table-results');
    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet JS" });
    XLSX.writeFile(wb, 'Transferencias.xlsx');
}
</script>
</body>
</html>
