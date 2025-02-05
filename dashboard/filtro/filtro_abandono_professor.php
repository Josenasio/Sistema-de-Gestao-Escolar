<?php
// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Função para carregar as opções de filtro
function fetchOptions($mysqli, $table, $idField, $nameField) {
    $result = $mysqli->query("SELECT $idField, $nameField FROM $table");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Carrega as opções de filtro
$escolas = fetchOptions($mysqli, 'escola', 'id', 'nome');

// Configura os filtros e busca os professores abandonados
$query = "SELECT professor.id, professor.nome, professor.idade, professor.genero, professor.telefone, professor.endereco, 
          professor.funcao, professor.motivo_abandono, professor.data_abandono, escola.nome AS escola_nome
          FROM professor
          LEFT JOIN escola ON professor.id_escola = escola.id
          WHERE professor.motivo_abandono IS NOT NULL
            AND professor.motivo_abandono <> ''";

$conditions = [];
$params = [];

// Adiciona os filtros de acordo com os parâmetros
if (!empty($_GET['idade']) && !empty($_GET['idade_op'])) {
    $op = $_GET['idade_op'];
    $conditions[] = "professor.idade $op ?";
    $params[] = $_GET['idade'];
}

if (!empty($_GET['genero'])) {
    $conditions[] = "professor.genero = ?";
    $params[] = $_GET['genero'];
}

if (!empty($_GET['id_escola'])) {
    $conditions[] = "professor.id_escola = ?";
    $params[] = $_GET['id_escola'];
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
$professores = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Filtro de Professores Abandonados</title>
      <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  
   

<!-- CSS do Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .filter-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            margin-top: 20px;
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".filter-form input, .filter-form select").forEach(element => {
                element.addEventListener("change", () => document.querySelector(".filter-form").submit());
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
     XLSX.utils.book_append_sheet(wb, ws, 'Professor professor');
     XLSX.writeFile(wb, 'Relatorio_abandono_professor.xlsx');
 }
    </script>
</head>
<body>
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>
    <div class="container">
        <h1 class="text-center text-primary">Professores - Abandono</h1>
        <br>
        <form method="GET" class="filter-form row g-3">
            <div class="col-md-4">
                <label for="idade" class="form-label">Idade:</label>
                <div class="input-group">
                    <select name="idade_op" class="form-select">
                        <option value=">" <?= (isset($_GET['idade_op']) && $_GET['idade_op'] == '>') ? 'selected' : ''; ?>>&gt;</option>
                        <option value="<" <?= (isset($_GET['idade_op']) && $_GET['idade_op'] == '<') ? 'selected' : ''; ?>>&lt;</option>
                        <option value="=" <?= (isset($_GET['idade_op']) && $_GET['idade_op'] == '=') ? 'selected' : ''; ?>>=</option>
                    </select>
                    <input type="number" name="idade" id="idade" class="form-control" min="1" value="<?= isset($_GET['idade']) ? $_GET['idade'] : ''; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <label for="genero" class="form-label">Gênero:</label>
                <select name="genero" id="genero" class="form-select">
                    <option value="">Todos</option>
                    <option value="Masculino" <?= (isset($_GET['genero']) && $_GET['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                    <option value="Feminino" <?= (isset($_GET['genero']) && $_GET['genero'] == 'Feminino') ? 'selected' : ''; ?>>Feminino</option>
                    <option value="Outro" <?= (isset($_GET['genero']) && $_GET['genero'] == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="id_escola" class="form-label">Escola:</label>
                <select name="id_escola" id="id_escola" class="form-select">
                    <option value="">Todas</option>
                    <?php foreach ($escolas as $escola): ?>
                        <option value="<?= $escola['id']; ?>" <?= (isset($_GET['id_escola']) && $_GET['id_escola'] == $escola['id']) ? 'selected' : ''; ?>><?= $escola['nome']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
<br>
        <div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#" class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download"></i> Baixar Tabela
    </a>
</div>


        <table class="table table-striped table-hover" id="table-results">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Idade</th>
                    <th>Gênero</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Função</th>
          
                    <th>Motivo do Abandono</th>
                    <th>Data do Abandono</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($professores as $professor): ?>
                    <tr>
                        <td><?= $professor['nome']; ?></td>
                        <td><?= $professor['idade']; ?></td>
                        <td><?= $professor['genero']; ?></td>
                        <td><?= $professor['telefone']; ?></td>
                        <td><?= $professor['endereco']; ?></td>
                        <td><?= $professor['funcao']; ?></td>
 
                        <td><?= $professor['motivo_abandono']; ?></td>
                        <td><?= $professor['data_abandono']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
