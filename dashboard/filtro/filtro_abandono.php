

<?php
// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Funçao para carregar as opções de filtro
function fetchOptions($mysqli, $table, $idField, $nameField) {
    $result = $mysqli->query("SELECT $idField, $nameField FROM $table");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Carrega as opções de filtro
$distritos = fetchOptions($mysqli, 'distrito', 'id', 'nome_distrito');
$escolas = fetchOptions($mysqli, 'escola', 'id', 'nome');
$classes = fetchOptions($mysqli, 'classe', 'id', 'nivel_classe');
$religioes = fetchOptions($mysqli, 'religiao', 'id', 'nome_religiao');
$cursos = fetchOptions($mysqli, 'curso', 'id', 'nome_area');

// Configura os filtros e busca os alunos
$query = "SELECT aluno.id, aluno.nome, aluno.genero, escola.nome AS escola_nome, classe.nivel_classe AS classe_nome, 
          curso.nome_area AS curso_nome, religiao.nome_religiao AS religiao_nome, distrito.nome_distrito AS distrito_nome, 
          aluno.idade, aluno.estrategia_recuperacao, aluno.motivo_abandono, aluno.endereco, aluno.contato_encarregado, aluno.nome_encarregado, aluno.situacao_economica
          FROM aluno
          LEFT JOIN escola ON aluno.escola_id = escola.id
          LEFT JOIN classe ON aluno.classe_id = classe.id
          LEFT JOIN curso ON aluno.curso_id = curso.id
          LEFT JOIN religiao ON aluno.religiao_id = religiao.id
          LEFT JOIN distrito ON aluno.id_distrito = distrito.id
          WHERE aluno.motivo_abandono IS NOT NULL
            AND aluno.motivo_abandono <> ''";

$conditions = [];
$params = [];

// Adiciona os filtros de acordo com os parâmetros
if (!empty($_GET['distrito_id'])) {
    $conditions[] = "aluno.id_distrito = ?";
    $params[] = $_GET['distrito_id'];
}

if (!empty($_GET['escola_id'])) {
    $conditions[] = "aluno.escola_id = ?";
    $params[] = $_GET['escola_id'];
}

if (!empty($_GET['classe_id'])) {
    $conditions[] = "aluno.classe_id = ?";
    $params[] = $_GET['classe_id'];
}

if (!empty($_GET['religiao_id'])) {
    $conditions[] = "aluno.religiao_id = ?";
    $params[] = $_GET['religiao_id'];
}

if (!empty($_GET['idade'])) {
    $conditions[] = "aluno.idade = ?";
    $params[] = $_GET['idade'];
}

if (!empty($_GET['curso_id'])) {
    $conditions[] = "aluno.curso_id = ?";
    $params[] = $_GET['curso_id'];
}

if (!empty($_GET['genero'])) {
    $conditions[] = "aluno.genero = ?";
    $params[] = $_GET['genero'];
}

// Concatena as condições na consulta
if (count($conditions) > 0) {
    $query .= " AND " . implode(" AND ", $conditions);
}

// Prepara e executa a consulta
$stmt = $mysqli->prepare($query);

// Liga os parâmetros, se houver
if (count($params) > 0) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$alunos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Filtro de Alunos(as)-Abandono</title>


<!-- CSS do Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS do Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
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
     XLSX.utils.book_append_sheet(wb, ws, 'Alunos Abandono');
     XLSX.writeFile(wb, 'Relatorio_Alunos_Abandono.xlsx');
 }




    </script>

<style>
      * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f9fafb;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 30px;
    min-height: 100vh;
}

h1 {
    color: #4e73df;
    font-size: 2.5rem;
    margin-bottom: 30px;
    font-weight: 600;
    text-align: center;
}

.filter-form {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    background-color: #ffffff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    width: 100%;
    max-width: 1200px;
    transition: transform 0.3s ease-in-out;
}

.filter-form:hover {
    transform: translateY(-5px);
}

.filter-form label {
    font-weight: 600;
    font-size: 1rem;
    color: #333;
}

.filter-form select, 
.filter-form input[type="number"] {
    padding: 12px 18px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 1rem;
    transition: border 0.3s ease-in-out;
}

.filter-form select:focus, 
.filter-form input[type="number"]:focus {
    border: 1px solid #4e73df;
    outline: none;
}

.filter-form button {
    background-color: #4e73df;
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.filter-form button:hover {
    background-color: #2e59d9;
}

.student-list {
    width: 100%;
    max-width: 1200px;
    margin-top: 20px;
    text-align: center;
}

.student-list table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px auto;
}

.student-list th, 
.student-list td {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    text-align: center;
    font-size: 1rem;
}

.student-list th {
    background-color: #4e73df;
    color: #ffffff;
    font-weight: 600;
}

.student-list tr:nth-child(even) {
    background-color: #f9fafb;
}

.student-list tr:hover {
    background-color: #e1e8f4;
    transform: scale(1.02);
    transition: background-color 0.3s ease, transform 0.2s ease;
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

@media screen and (max-width: 768px) {
    body {
        padding: 20px;
    }

    .filter-form {
        flex-direction: column;
        gap: 15px;
    }

    .student-list table {
        font-size: 0.9rem;
    }

    .student-list th, 
    .student-list td {
        padding: 10px;
    }

    .fixed-top-button {
        font-size: 1rem;
    }
}

    </style>


</head>
<body>

<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>
<div style="margin-top: 60px;"></div>

<h1>Filtro de Alunos(as)-Abandono</h1>

<!-- Filtros -->
<form method="GET" class="filter-form">
    <label for="distrito_id">Distrito:</label>
    <select name="distrito_id" id="distrito_id">
        <option value="">Todos</option>
        <?php foreach ($distritos as $distrito): ?>
            <option value="<?= $distrito['id']; ?>" <?= (isset($_GET['distrito_id']) && $_GET['distrito_id'] == $distrito['id']) ? 'selected' : ''; ?>><?= $distrito['nome_distrito']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="escola_id">Escola:</label>
    <select name="escola_id" id="escola_id">
        <option value="">Todas</option>
        <?php foreach ($escolas as $escola): ?>
            <option value="<?= $escola['id']; ?>" <?= (isset($_GET['escola_id']) && $_GET['escola_id'] == $escola['id']) ? 'selected' : ''; ?>><?= $escola['nome']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="classe_id">Classe:</label>
    <select name="classe_id" id="classe_id">
        <option value="">Todas</option>
        <?php foreach ($classes as $classe): ?>
            <option value="<?= $classe['id']; ?>" <?= (isset($_GET['classe_id']) && $_GET['classe_id'] == $classe['id']) ? 'selected' : ''; ?>><?= $classe['nivel_classe']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="religiao_id">Religiao:</label>
    <select name="religiao_id" id="religiao_id">
        <option value="">Todas</option>
        <?php foreach ($religioes as $religiao): ?>
            <option value="<?= $religiao['id']; ?>" <?= (isset($_GET['religiao_id']) && $_GET['religiao_id'] == $religiao['id']) ? 'selected' : ''; ?>><?= $religiao['nome_religiao']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="idade">Idade:</label>
    <input type="number" name="idade" id="idade" min="1" value="<?= isset($_GET['idade']) ? $_GET['idade'] : ''; ?>">

    <label for="curso_id">Curso:</label>
    <select name="curso_id" id="curso_id">
        <option value="">Todos</option>
        <?php foreach ($cursos as $curso): ?>
            <option value="<?= $curso['id']; ?>" <?= (isset($_GET['curso_id']) && $_GET['curso_id'] == $curso['id']) ? 'selected' : ''; ?>><?= $curso['nome_area']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="genero">Gênero:</label>
    <select name="genero" id="genero">
        <option value="">Todos</option>
        <option value="Masculino" <?= (isset($_GET['genero']) && $_GET['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
        <option value="Feminino" <?= (isset($_GET['genero']) && $_GET['genero'] == 'Feminino') ? 'selected' : ''; ?>>Feminino</option>
        <option value="Outro" <?= (isset($_GET['genero']) && $_GET['genero'] == 'Outro') ? 'selected' : ''; ?>>Outro</option>
    </select>

    <button type="submit">Filtrar</button>
</form>



<div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#" class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download"></i> Baixar Tabela
    </a>
</div>



<div class="student-list">
    <table id="table-results">
    <tr>
            <th  class="field-checkbox"  value="nome">Nome</th>
            <th  class="field-checkbox" value="idade">Idade</th>
            <th  class="field-checkbox" value="motivo_abandono">Motivo do Abandono</th>
            <th  class="field-checkbox" value="estrategia_recuperacao">Estrat. Recuperaçao</th>
            <th  class="field-checkbox" value="situacao_economica">Situaçao Económica</th>
            <th  class="field-checkbox" value="endereco">Endereço</th>
            <th  class="field-checkbox" value="distrito_nome">Distrito</th>
            <th  class="field-checkbox" value="religiao_nome">Religiao</th>
            <th  class="field-checkbox" value="nome_encarregado">Encarregado</th>
            <th  class="field-checkbox" value="contato_encarregado">Cont. Encarregado</th>
            <th  class="field-checkbox" value="escola_nome">Escola</th>
            <th  class="field-checkbox" value="classe_nome">Classe</th>
            <th  class="field-checkbox" value="curso_nome">Curso</th>
        </tr>

        <?php foreach ($alunos as $aluno): ?>
            <tr>
                    <td class="nome"><?= htmlspecialchars($aluno['nome'] ?? 'N/A'); ?></td>
                    <td class="idade"><?= htmlspecialchars($aluno['idade'] ?? 'N/A'); ?></td>
                    <td class="motivo_abandono"><?= htmlspecialchars($aluno['motivo_abandono'] ?? 'N/A'); ?></td>
                    <td class="estrategia_recuperacao"><?= htmlspecialchars($aluno['estrategia_recuperacao'] ?? 'N/A'); ?></td>
                    <td class="situacao_economica"><?= htmlspecialchars($aluno['situacao_economica'] ?? 'N/A'); ?></td>
                    <td class="endereco"><?= htmlspecialchars($aluno['endereco'] ?? 'N/A'); ?></td>
                    <td class="distrito_nome"><?= htmlspecialchars($aluno['distrito_nome'] ?? 'N/A'); ?></td>
                    <td class="religiao_nome"><?= htmlspecialchars($aluno['religiao_nome'] ?? 'N/A'); ?></td>
                    <td class="nome_encarregado"><?= htmlspecialchars($aluno['nome_encarregado'] ?? 'N/A'); ?></td>
                    <td class="contato_encarregado"><?= htmlspecialchars($aluno['contato_encarregado'] ?? 'N/A'); ?></td>
                    <td class="escola_nome"><?= htmlspecialchars($aluno['escola_nome'] ?? 'N/A'); ?></td>
                    <td class="classe_nome"><?= htmlspecialchars($aluno['classe_nome'] ?? 'N/A'); ?></td>
                    <td class="curso_nome"><?= htmlspecialchars($aluno['curso_nome'] ?? 'N/A'); ?></td>
                </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>