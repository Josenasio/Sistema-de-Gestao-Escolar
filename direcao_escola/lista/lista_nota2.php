    <!-- Botão com ícone -->
    <button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
</button>
<br><br>
</div>
<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o ID da escola
$id_escola = $_SESSION['id_escola'] ?? null;

if (!$id_escola) {
    die("Erro: ID da escola não definido.");
}

// Variáveis de filtro
$periodo_filtro = $_GET['periodo'] ?? '';
$classe_filtro = $_GET['classe'] ?? '';
$turma_filtro = $_GET['turma'] ?? '';
$diretor_filtro = $_GET['diretor'] ?? '';

// Consultar todas as classes e turmas
$query_classe = "SELECT DISTINCT nivel_classe FROM classe";
$query_turma = "SELECT DISTINCT nome_turma FROM turma";

// Executando as consultas
$result_classe = $mysqli->query($query_classe);
$result_turma = $mysqli->query($query_turma);

// Consulta para listar alunos, notas e disciplinas agrupados por diretor
$query = "
    SELECT 
        usuarios.nome AS diretor_nome,
        turma.nome_turma AS turma_nome,
        classe.nivel_classe AS classe_nome,
        periodo_dia.descricao AS periodo_nome,
        aluno.numero_ordem,
        aluno.nome AS aluno_nome,
        disciplina.nome_disciplina,
        nota.nota3,
        nota.nota4,
        nota.nota_final2
    FROM aluno
    LEFT JOIN turma ON aluno.turma_id = turma.id
    LEFT JOIN classe ON aluno.classe_id = classe.id
    LEFT JOIN periodo_dia ON aluno.periododia_id = periodo_dia.id
    INNER JOIN usuarios ON aluno.id_diretor_turma = usuarios.id
    LEFT JOIN nota ON nota.id_aluno = aluno.id
    LEFT JOIN disciplina ON nota.disciplina_id = disciplina.id
    WHERE usuarios.id_escola = ?
";

// Adicionar filtros à consulta
if ($periodo_filtro) {
    $query .= " AND periodo_dia.descricao = ?";
}
if ($classe_filtro) {
    $query .= " AND classe.nivel_classe = ?";
}
if ($turma_filtro) {
    $query .= " AND turma.nome_turma = ?";
}
if ($diretor_filtro) {
    $query .= " AND usuarios.nome = ?";
}

$query .= " ORDER BY periodo_dia.descricao, classe.nivel_classe, turma.nome_turma, usuarios.nome, aluno.numero_ordem, disciplina.nome_disciplina";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$params = [$id_escola];
if ($periodo_filtro) {
    $params[] = $periodo_filtro;
}
if ($classe_filtro) {
    $params[] = $classe_filtro;
}
if ($turma_filtro) {
    $params[] = $turma_filtro;
}
if ($diretor_filtro) {
    $params[] = $diretor_filtro;
}

$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

$current_diretor = null;
$current_turma = null;

$alunos = []; // Para armazenar dados temporariamente agrupados

while ($row = $result->fetch_assoc()) {
    $diretor_nome = $row['diretor_nome'];
    $turma_nome = $row['turma_nome'];
    $classe_nome = $row['classe_nome'];
    $periodo_nome = $row['periodo_nome'];

    // Agrupar por período, classe, turma e diretor
    $key = "{$periodo_nome}-{$classe_nome}-{$turma_nome}-{$diretor_nome}";
    if (!isset($alunos[$key])) {
        $alunos[$key] = [
            'periodo_nome' => $periodo_nome,
            'classe_nome' => $classe_nome,
            'turma_nome' => $turma_nome,
            'diretor_nome' => $diretor_nome,
            'dados' => [],
        ];
    }

    $alunos[$key]['dados'][] = [
        'numero_ordem' => $row['numero_ordem'],
        'aluno_nome' => $row['aluno_nome'],
        'disciplina' => $row['nome_disciplina'],
        'nota3' => $row['nota3'],
        'nota4' => $row['nota4'],
        'nota_final2' => $row['nota_final2'],
    ];
}

// Adicionando Bootstrap e formulário de filtros
echo "<!DOCTYPE html>
<html lang='pt'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Relatório de Notas</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>

    <style>
        .table-responsive { margin-top: 20px; }
        .table th, .table td { text-align: center; }
        .nota-vermelha { color: red !important; font-weight: bold; }
        .fundo-avaliacao { background-color: #f1f1f1; }


            
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

            .bg-light{

            background-color: #c1efde;
        }

    </style>
</head>
<body class='bg-light' style='background-color: #c1efde;'>



    <div class='container'>
        <h1 class='my-4 text-center'>Lista das Notas do 2º Periodo</h1>";

echo "<form method='get' class='mb-3'>
        <div class='row'>
            <div class='col-md-3'>
                <label for='periodo' class='form-label'>Período</label>
                <select name='periodo' id='periodo' class='form-select'>
                    <option value=''>Todos</option>
                    <option value='Manhã' " . ($periodo_filtro === 'Manhã' ? 'selected' : '') . ">Manhã</option>
                    <option value='Tarde' " . ($periodo_filtro === 'Tarde' ? 'selected' : '') . ">Tarde</option>
                    <option value='Noite' " . ($periodo_filtro === 'Noite' ? 'selected' : '') . ">Noite</option>
                </select>
            </div>
            <div class='col-md-3'>
                <label for='classe' class='form-label'>Classe</label>
                <select name='classe' id='classe' class='form-select'>
                    <option value=''>Todas</option>";
                    // Preencher o select com as classes da tabela 'classe'
                    while ($classe = $result_classe->fetch_assoc()) {
                        $selected = ($classe_filtro === $classe['nivel_classe']) ? 'selected' : '';
                        echo "<option value='{$classe['nivel_classe']}' {$selected}>{$classe['nivel_classe']}</option>";
                    }
echo "    </select>
            </div>
            <div class='col-md-3'>
                <label for='turma' class='form-label'>Turma</label>
                <select name='turma' id='turma' class='form-select'>
                    <option value=''>Todas</option>";
                    // Preencher o select com as turmas da tabela 'turma'
                    while ($turma = $result_turma->fetch_assoc()) {
                        $selected = ($turma_filtro === $turma['nome_turma']) ? 'selected' : '';
                        echo "<option value='{$turma['nome_turma']}' {$selected}>{$turma['nome_turma']}</option>";
                    }
                    echo "
                    </select>
                    </div>
                    <div class='col-md-3'>
                        <label for='diretor' class='form-label'>Diretor da Turma</label>
                        <input type='text' name='diretor' id='diretor' class='form-control' value='{$diretor_filtro}' />
                    </div>
                </div>
                <button type='submit' class='btn btn-primary mt-3'>
                    <i class='fas fa-filter'></i> Filtrar
                </button>
                
                <a href='" . $_SERVER['PHP_SELF'] . "' class='btn btn-danger mt-3'>
                    <i class='fas fa-times-circle'></i> Limpar Filtro
                </a>
                </form>
                
                
        
        
        <hr>
<div class='col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100'>
    <a href='#'  class='btn btn-outline-primary me-2' onclick='exportToExcel()'>
        <i class='fas fa-download' ></i> Baixar Tabela Filtrada
    </a>
</div> <hr>";
                ;
                
    




foreach ($alunos as $grupo) {
    echo "<div class='table-responsive'>";
    echo "<h2 class='h4 mb-3'>Período: <strong>{$grupo['periodo_nome']}</strong> | <strong>{$grupo['classe_nome']}</strong> |  <strong>{$grupo['turma_nome']}</strong> | Diretor Turma: <strong>{$grupo['diretor_nome']}</strong></h2>";

    // Organizar as notas por aluno
    $tabela_alunos = [];
    foreach ($grupo['dados'] as $nota) {
        $numero = $nota['numero_ordem'];
        $nome = $nota['aluno_nome'];
        $disciplina = $nota['disciplina'];
        $tabela_alunos["{$numero}-{$nome}"][$disciplina] = [
            'nota3' => $nota['nota3'],
            'nota4' => $nota['nota4'],
            'nota_final2' => $nota['nota_final2'],
        ];
    }

    // Iniciando a tabela
    echo "<table class='table table-bordered table-striped table-hover' id='table-results'>
            <thead class='table-primary'>
                <tr>
                    <th>Número</th>
                    <th>Nome</th>";

    // Cabeçalhos de disciplinas
    $disciplinas = [];
    foreach ($grupo['dados'] as $item) {
        if (!in_array($item['disciplina'], $disciplinas)) {
            $disciplinas[] = $item['disciplina'];
        }
    }

    foreach ($disciplinas as $disciplina) {
        echo "<th colspan='3'>{$disciplina}</th>";
    }

    echo "</tr>
            <tr class='fundo-avaliacao'>
                <td></td>
                <td></td>";

    // Segunda linha: Notas
    foreach ($disciplinas as $disciplina) {
        echo "<td>Avaliação-1</td><td>Avaliação-2</td><td>Pauta</td>";
    }
    echo "</tr>
        </thead>
        <tbody>";

    // Dados dos alunos
    foreach ($tabela_alunos as $key => $notas_por_disciplina) {
        list($numero, $nome) = explode('-', $key);
        echo "<tr>";
        echo "<td>{$numero}</td>";
        echo "<td>{$nome}</td>";

        foreach ($disciplinas as $disciplina) {
            $nota = $notas_por_disciplina[$disciplina] ?? ['nota3' => '-', 'nota4' => '-', 'nota_final2' => '-'];

            echo "<td class='" . ($nota['nota3'] < 10 ? 'nota-vermelha' : '') . "'>{$nota['nota3']}</td>";
            echo "<td class='" . ($nota['nota4'] < 10 ? 'nota-vermelha' : '') . "'>{$nota['nota4']}</td>";
            echo "<td class='" . ($nota['nota_final2'] < 10 ? 'nota-vermelha' : '') . "'>{$nota['nota_final2']}</td>";
        }
        echo "</tr>";
    }

    echo "</tbody></table></div>";
}

echo "</div></body></html>";
?>



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
    XLSX.writeFile(wb, 'Lista_nota_2ºperiodo.xlsx');
}
</script>