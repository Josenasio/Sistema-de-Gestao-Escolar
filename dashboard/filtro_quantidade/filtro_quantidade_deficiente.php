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

// Configura os filtros e busca o número de alunos
$query = "SELECT COUNT(*) as total_alunos
          FROM aluno
          LEFT JOIN escola ON aluno.escola_id = escola.id
          LEFT JOIN classe ON aluno.classe_id = classe.id
          LEFT JOIN curso ON aluno.curso_id = curso.id
          LEFT JOIN religiao ON aluno.religiao_id = religiao.id
          LEFT JOIN distrito ON aluno.id_distrito = distrito.id
          WHERE aluno.deficiente = 1";

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
$total_alunos = $result->fetch_assoc()['total_alunos'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Filtro de Alunos Deficientes</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector(".filter-form");
            form.addEventListener("change", () => form.submit());
        });
    </script>











<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        h1 {
            color: #0056b3;
        }
        .filter-form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
            max-width: 1000px;
        }
        .filter-form label {
            font-weight: bold;
        }
        .filter-form select, .filter-form input[type="number"] {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .filter-form button {
            background-color: #0056b3;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .filter-form button:hover {
            background-color: #003d80;
        }
        .student-list {
            width: 100%;
            max-width: 1000px;
            text-align: center;
        }
        .student-list table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        .student-list th, .student-list td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .student-list th {
            background-color: #0056b3;
            color: white;
        }
        .student-list tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .student-list tr:hover {
            background-color: #e9f5ff;
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



<!-- Adicione um espaçamento para compensar o botao fixo -->
<div style="margin-top: 60px;"></div>

<h1>Filtro de Alunos(as) Deficientes</h1>

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

<div class="student-count">
    <h2>Total de alunos(as) encontrados(as): <span style="color: green;"><?= $total_alunos; ?></span></h2>
</div>

</body>
</html>
