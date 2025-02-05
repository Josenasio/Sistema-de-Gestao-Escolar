<?php   
session_start();

if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

if (!isset($mysqli)) {
    die("Erro na conexão com o banco de dados.");
}

$escola_id = $_SESSION['id_escola'];

// Função para gerar opções do select
function getOptions($table, $id_col, $name_col, $mysqli) {
    $sql = "SELECT $id_col, $name_col FROM $table";
    $result = $mysqli->query($sql);
    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
    return $options;
}

// Obtendo dados para os selects
$classes = getOptions('classe', 'id', 'nivel_classe', $mysqli);
$distritos = getOptions('distrito', 'id', 'nome_distrito', $mysqli);
$turmas = getOptions('turma', 'id', 'nome_turma', $mysqli);
$cursos = getOptions('curso', 'id', 'nome_area', $mysqli);
$periodos = getOptions('periodo_dia', 'id', 'descricao', $mysqli);
$diretores = getOptions('usuarios', 'id', 'nome', $mysqli);

// Inicializando filtros com valores padrão
$filtros = [
    'classe_id' => $_GET['classe_id'] ?? '',
    'id_distrito' => $_GET['id_distrito'] ?? '',
    'turma_id' => $_GET['turma_id'] ?? '',
    'curso_id' => $_GET['curso_id'] ?? '',
    'periododia_id' => $_GET['periododia_id'] ?? '',
    'id_diretor_turma' => $_GET['id_diretor_turma'] ?? '',
    'idade' => $_GET['idade'] ?? '',
    'genero' => $_GET['genero'] ?? '',
    'fase' => $_GET['fase'] ?? ''
];

// Inicializando filtros para a consulta
$where = "a.escola_id = ?";
$params = [$escola_id];
$types = 'i';

// Adicionando filtros dinamicamente
foreach ($filtros as $campo => $valor) {
    if (!empty($valor)) {
        // Adicionando o filtro de gênero corretamente
        if ($campo == 'genero') {
            $where .= " AND a.genero = ?";
        } elseif ($campo == 'fase') {
            $where .= " AND ne.fase = ?";
        } else {
            $where .= " AND a.{$campo} = ?";
        }
        $params[] = $valor;
        $types .= 's'; // 's' para string (usado para o filtro de gênero e fase)
    }
}

// Consulta para obter disciplinas
$disciplinas_sql = "SELECT id_disciplina, nome FROM disciplinas_exame";
$disciplinas_result = $mysqli->query($disciplinas_sql);

$disciplinas = [];
while ($disciplina = $disciplinas_result->fetch_assoc()) {
    $disciplinas[$disciplina['id_disciplina']] = $disciplina['nome'];
}

// Consulta principal
$sql = "SELECT 
            a.id AS aluno_id, 
            a.numero_ordem, 
            a.nome, 
            class.nivel_classe AS classe,
            t.nome_turma AS turma_nome,
            c.nome_area AS curso_nome
        FROM aluno a
        INNER JOIN escola e ON a.escola_id = e.id
        INNER JOIN turma t ON a.turma_id = t.id
        INNER JOIN curso c ON a.curso_id = c.id
        INNER JOIN classe class ON a.classe_id = class.id
        INNER JOIN usuarios u ON u.id = a.id_diretor_turma
        LEFT JOIN notas_exame ne ON ne.id_aluno = a.id
        WHERE $where
        ORDER BY a.numero_ordem ASC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$alunos_result = $stmt->get_result();
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos com Filtros</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- CSS do Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS do Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>


    <script>
        // Função para submeter o formulário automaticamente quando um filtro for alterado
        function autoSubmit() {
            document.getElementById("filtersForm").submit();
        }
    </script>

<style>
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
<button class="fixed-top-button btn btn-secondary" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
        <i class="fas fa-tachometer-alt"></i> Voltar à Página Inicial
    </button>
<br><br>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Lista de Notas Exame</h1>

        <form id="filtersForm" method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="classe_id" class="form-label">Classe:</label>
                    <select name="classe_id" class="form-select" onchange="autoSubmit()">
                        <option value="">Todas</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= $filtros['classe_id'] == $class['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['nivel_classe']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="id_distrito" class="form-label">Distrito:</label>
                    <select name="id_distrito" class="form-select" onchange="autoSubmit()">
                        <option value="">Todos</option>
                        <?php foreach ($distritos as $distrito): ?>
                            <option value="<?= $distrito['id'] ?>" <?= $filtros['id_distrito'] == $distrito['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($distrito['nome_distrito']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="turma_id" class="form-label">Turma:</label>
                    <select name="turma_id" class="form-select" onchange="autoSubmit()">
                        <option value="">Todas</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?= $turma['id'] ?>" <?= $filtros['turma_id'] == $turma['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($turma['nome_turma']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="curso_id" class="form-label">Curso:</label>
                    <select name="curso_id" class="form-select" onchange="autoSubmit()">
                        <option value="">Todos</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id'] ?>" <?= $filtros['curso_id'] == $curso['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nome_area']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="periododia_id" class="form-label">Período:</label>
                    <select name="periododia_id" class="form-select" onchange="autoSubmit()">
                        <option value="">Todos</option>
                        <?php foreach ($periodos as $periodo): ?>
                            <option value="<?= $periodo['id'] ?>" <?= $filtros['periododia_id'] == $periodo['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($periodo['descricao']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="id_diretor_turma" class="form-label">Diretor Turma:</label>
                    <select name="id_diretor_turma" class="form-select" onchange="autoSubmit()">
                        <option value="">Todos</option>
                        <?php foreach ($diretores as $diretor): ?>
                            <option value="<?= $diretor['id'] ?>" <?= $filtros['id_diretor_turma'] == $diretor['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($diretor['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="fase" class="form-label">Fase:</label>
                    <select name="fase" class="form-select" onchange="autoSubmit()">
                        <option value="">Todas</option>
                        <option value="1" <?= $filtros['fase'] == '1' ? 'selected' : '' ?>>Fase 1</option>
                        <option value="2" <?= $filtros['fase'] == '2' ? 'selected' : '' ?>>Fase 2</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="idade" class="form-label">Idade:</label>
                    <input type="number" name="idade" class="form-control" value="<?= htmlspecialchars($filtros['idade']) ?>" min="1" onchange="autoSubmit()">
                </div>

                <div class="col-md-4">
                    <label for="genero" class="form-label">Gênero:</label>
                    <select name="genero" class="form-select" onchange="autoSubmit()">
                        <option value="">Todos</option>
                        <option value="Masculino" <?= $filtros['genero'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                        <option value="Feminino" <?= $filtros['genero'] == 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                    </select>
                </div>
            </div>
        </form>


        <div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
            <a href="#"  class="btn btn-outline-primary me-2" onclick="exportToExcel()">
                <i class="fas fa-download" ></i> Baixar Tabela
            </a>
        </div>
<br>


        <table class="table table-bordered table-striped" id='table-results'>
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Nome</th>
                    <?php foreach ($disciplinas as $disciplina_nome): ?>
                        <th><?= htmlspecialchars($disciplina_nome) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($aluno = $alunos_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($aluno['numero_ordem']) ?></td>
                    <td><?= htmlspecialchars($aluno['nome']) ?></td>
                    <?php 
                    foreach ($disciplinas as $disciplina_id => $disciplina_nome): 
                        $nota_sql = "SELECT nota FROM notas_exame WHERE id_aluno = ? AND id_disciplina = ?";
                        $nota_stmt = $mysqli->prepare($nota_sql);
                        $nota_stmt->bind_param('ii', $aluno['aluno_id'], $disciplina_id);
                        $nota_stmt->execute();
                        $nota_result = $nota_stmt->get_result();
                        $nota = $nota_result->fetch_assoc();
                    ?>
                        <td><?= htmlspecialchars($nota['nota'] ?? 'N/A') ?></td>
                    <?php 
                        $nota_stmt->close();
                    endforeach; 
                    ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

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
     const visibleColumns = Array.from(table.querySelectorAll('th')).filter(th => th.style.display !== 'none');
     const visibleRows = Array.from(table.querySelectorAll('tr')).map(tr => {
         return Array.from(tr.querySelectorAll('td')).filter(td => td.style.display !== 'none');
     });

     const wb = XLSX.utils.book_new();
     const ws = XLSX.utils.aoa_to_sheet([visibleColumns.map(th => th.innerText), ...visibleRows.map(row => row.map(td => td.innerText))]);
     XLSX.utils.book_append_sheet(wb, ws, 'Alunos Aluno');
     XLSX.writeFile(wb, 'Relatorio_Notas_EXAME.xlsx');
 }
</script>
    

 
</body>
</html>


