<?php
// Iniciar a sessão
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

// Conexão com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// ID do diretor turma logado
$id_usuario = $_SESSION['id'];

// Consulta para obter os dados
$sql = "
    SELECT 
        a.numero_ordem, 
        a.nome AS aluno_nome, 
        d.nome_disciplina, 
        n.periodo, 
        n.nota1, n.nota2, n.nota_final
    FROM aluno a
    INNER JOIN usuarios u ON a.id_diretor_turma = u.id
    LEFT JOIN nota n ON a.id = n.id_aluno
    LEFT JOIN disciplina d ON n.disciplina_id = d.id
    WHERE a.id_diretor_turma = ?
    ORDER BY a.numero_ordem ASC, d.nome_disciplina ASC, n.periodo ASC
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$alunos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Agrupar os dados por aluno e disciplina
$alunos_agrupados = [];
foreach ($alunos as $aluno) {
    $numero = $aluno['numero_ordem'];
    $disciplina = $aluno['nome_disciplina'];
    $periodo = $aluno['periodo'];

    if (!isset($alunos_agrupados[$numero])) {
        $alunos_agrupados[$numero] = [
            'nome' => $aluno['aluno_nome'],
            'disciplinas' => []
        ];
    }

    if (!isset($alunos_agrupados[$numero]['disciplinas'][$disciplina])) {
        $alunos_agrupados[$numero]['disciplinas'][$disciplina] = [];
    }

    $alunos_agrupados[$numero]['disciplinas'][$disciplina][$periodo] = [
        'nota1' => $aluno['nota1'],
        'nota2' => $aluno['nota2'],
        'nota_final' => $aluno['nota_final']
    ];
}

// Funções auxiliares
function aplicarNotaBaixa($nota) {
    return $nota < 10 ? 'nota-baixa' : '';
}

function safe_htmlspecialchars($value) {
    return htmlspecialchars($value ?? '');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas dos Alunos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .student-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .table-responsive {
            margin-bottom: 30px;
        }
        th {
            background-color: #6c757d;
            color: white;
            text-align: center;
        }
        .nota-baixa {
            color: red !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center mb-5">Lista das Notas dos Alunos</h1>

    <?php foreach ($alunos_agrupados as $numero => $dados_aluno): ?>
        <div class="student-header">
            <strong>Número:</strong> <?= safe_htmlspecialchars($numero) ?> - 
            <strong>Nome:</strong> <?= safe_htmlspecialchars($dados_aluno['nome']) ?>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Período</th>
                        <th>Avaliação 1</th>
                        <th>Avaliação 2</th>
                        <th>Pauta Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados_aluno['disciplinas'] as $disciplina => $periodos): ?>
                        <?php foreach ($periodos as $periodo => $notas): ?>
                            <tr>
                                <td><?= safe_htmlspecialchars($disciplina) ?></td>
                                <td><?= safe_htmlspecialchars($periodo) ?></td>
                                <td class="<?= aplicarNotaBaixa($notas['nota1']) ?>">
                                    <?= safe_htmlspecialchars($notas['nota1']) ?>
                                </td>
                                <td class="<?= aplicarNotaBaixa($notas['nota2']) ?>">
                                    <?= safe_htmlspecialchars($notas['nota2']) ?>
                                </td>
                                <td class="<?= aplicarNotaBaixa($notas['nota_final']) ?>">
                                    <?= safe_htmlspecialchars($notas['nota_final']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
