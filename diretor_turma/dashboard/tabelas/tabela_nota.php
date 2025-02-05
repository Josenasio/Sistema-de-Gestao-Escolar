<?php
// Iniciar a sessão
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

// Conexão com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// ID do professor logado
$id_usuario = $_SESSION['id'];

// Consulta para obter os dados
$sql = "
    SELECT 
        a.numero_ordem, 
        a.nome AS aluno_nome, 
        d.nome_disciplina, 
        n1.nota1, n2.nota2, n1.nota_final1, 
        n3.nota3, n4.nota4, n2.nota_final2, 
        n5.nota5, n6.nota6, n3.nota_final3 
    FROM aluno a
    LEFT JOIN nota n1 ON a.id = n1.id_aluno
    LEFT JOIN disciplina d ON n1.disciplina_id = d.id
    LEFT JOIN nota n2 ON a.id = n2.id_aluno AND n2.disciplina_id = d.id
    LEFT JOIN nota n3 ON a.id = n3.id_aluno AND n3.disciplina_id = d.id
    LEFT JOIN nota n4 ON a.id = n4.id_aluno AND n4.disciplina_id = d.id
    LEFT JOIN nota n5 ON a.id = n5.id_aluno AND n5.disciplina_id = d.id
    LEFT JOIN nota n6 ON a.id = n6.id_aluno AND n6.disciplina_id = d.id
    WHERE a.id_diretor_turma = ?
    ORDER BY a.numero_ordem ASC, d.nome_disciplina ASC
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$alunos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Função para aplicar a classe "nota-baixa" em notas menores que 10
if (!function_exists('aplicarNotaBaixa')) {
    function aplicarNotaBaixa($nota) {
        return $nota < 10 ? 'nota-baixa' : '';
    }
}

// Função para garantir que o valor não seja NULL
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <!-- JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        table {
            margin-bottom: 30px;
        }
        th {
            background-color: #6c757d;
            color: white;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-responsive {
            overflow-x: auto;
        }
        /* Estilos para a diferenciação dos períodos */
        .periodo-1 {
            background-color: #d1ecf1;
        }
        .periodo-2 {
            background-color: #f8d7da;
        }
        .periodo-3 {
            background-color: #c3e6cb;
        }
        /* Estilo para as notas menores que 10 */
        .nota-baixa {
            color: red !important;
            font-weight: bold;
        }
        @media (max-width: 767px) {
            .table th, .table td {
                font-size: 12px;
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
        <h1 class="text-center mb-5">Lista das Notas dos Alunos</h1>

        <?php 
        // Agrupar as disciplinas por aluno
        $alunos_agrupados = [];
        foreach ($alunos as $aluno) {
            $alunos_agrupados[$aluno['numero_ordem']][] = $aluno;
        }

        // Exibir as tabelas por aluno
        foreach ($alunos_agrupados as $numero => $dados_aluno): 
            // Pegar o nome do aluno
            $aluno_nome = $dados_aluno[0]['aluno_nome']; 
        ?>
            <div class="student-header">
                <strong>Número:</strong> <?= safe_htmlspecialchars($numero) ?> - 
                <strong>Nome:</strong> <?= safe_htmlspecialchars($aluno_nome) ?>
            </div>

            <!-- Tabela com as disciplinas e as avaliações por período -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="table-results">
                    <thead>
                        <tr>
                            <th rowspan="2">Disciplina</th>
                            <th colspan="3" style="background-color: #ADD8E6;">1º Período</th>
                            <th colspan="3" style="background-color: #90EE90;">2º Período</th>
                            <th colspan="3" style="background-color: #FFFFE0;">3º Período</th>
                        </tr>
                        <tr>
                            <th style="background-color: #ADD8E6;">Avaliação 1</th>
                            <th style="background-color: #ADD8E6;">Avaliação 2</th>
                            <th style="background-color: YELLOW;">Pauta Final</th>
                            <th style="background-color: #90EE90;">Avaliação 1</th>
                            <th style="background-color: #90EE90;">Avaliação 2</th>
                            <th style="background-color: YELLOW;">Pauta Final</th>
                            <th style="background-color: #FFFFE0;">Avaliação 1</th>
                            <th style="background-color: #FFFFE0;">Avaliação 2</th>
                            <th style="background-color: YELLOW;">Pauta Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Exibir as disciplinas e notas do aluno
                        foreach ($dados_aluno as $aluno) {
                            $disciplina = $aluno['nome_disciplina'];
                            $nota1_1 = $aluno['nota1'] ?? '';
                            $nota2_1 = $aluno['nota2'] ?? '';
                            $nota_final_1 = $aluno['nota_final1'] ?? '';

                            $nota1_2 = $aluno['nota3'] ?? '';
                            $nota2_2 = $aluno['nota4'] ?? '';
                            $nota_final_2 = $aluno['nota_final2'] ?? '';

                            $nota1_3 = $aluno['nota5'] ?? '';
                            $nota2_3 = $aluno['nota6'] ?? '';
                            $nota_final_3 = $aluno['nota_final3'] ?? '';
                        ?>
                            <tr>
                                <td><?= safe_htmlspecialchars($disciplina) ?></td>
                                <td style="background-color: #ADD8E6;" class="periodo-1 <?= aplicarNotaBaixa($nota1_1) ?>"><?= safe_htmlspecialchars($nota1_1) ?></td>
                                <td style="background-color: #ADD8E6;" class="periodo-1 <?= aplicarNotaBaixa($nota2_1) ?>"><?= safe_htmlspecialchars($nota2_1) ?></td>
                                <td style="background-color: YELLOW;" class="periodo-1 <?= aplicarNotaBaixa($nota_final_1) ?>"><?= safe_htmlspecialchars($nota_final_1) ?></td>
                                <td style="background-color: #90EE90;" class="periodo-2 <?= aplicarNotaBaixa($nota1_2) ?>"><?= safe_htmlspecialchars($nota1_2) ?></td>
                                <td style="background-color: #90EE90;" class="periodo-2 <?= aplicarNotaBaixa($nota2_2) ?>"><?= safe_htmlspecialchars($nota2_2) ?></td>
                                <td style="background-color: YELLOW;" class="periodo-2 <?= aplicarNotaBaixa($nota_final_2) ?>"><?= safe_htmlspecialchars($nota_final_2) ?></td>
                                <td style="background-color: #FFFFE0;" class="periodo-3 <?= aplicarNotaBaixa($nota1_3) ?>"><?= safe_htmlspecialchars($nota1_3) ?></td>
                                <td style="background-color: #FFFFE0;" class="periodo-3 <?= aplicarNotaBaixa($nota2_3) ?>"><?= safe_htmlspecialchars($nota2_3) ?></td>
                                <td style="background-color: YELLOW;" class="periodo-3 <?= aplicarNotaBaixa($nota_final_3) ?>"><?= safe_htmlspecialchars($nota_final_3) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
