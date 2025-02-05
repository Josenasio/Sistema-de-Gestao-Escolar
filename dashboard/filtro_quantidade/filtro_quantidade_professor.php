<?php
// Conexao com o banco de dados (substitua com suas credenciais)
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Consultas SQL para obter os valores de filtro
$estadosCivis = ['Solteiro', 'Casado', 'Divorciado', 'Viúvo'];
$categoriaSalarial = ['Adjunto da Terceira', 'Titular da Terceira', 'Auxiliar da Terceira'];
$titulos = ['Extraordinário', 'Efectivo'];
$escolas = $mysqli->query("SELECT id, nome FROM escola");
$distritos = $mysqli->query("SELECT id, nome_distrito FROM distrito");
$turmas = $mysqli->query("SELECT id, nome_turma FROM turma");
$classes = $mysqli->query("SELECT id, nivel_classe FROM classe");
$disciplinas = $mysqli->query("SELECT id, nome_disciplina FROM disciplina");

$sql = "SELECT 
            professor.id AS professor_id,
            professor.genero,
            professor.estado_civil,
            professor.categoria_salarial,
            professor.novo,
            professor.titulo,
            professor.distrito_id,
            professor.id_escola,
            professor.idade,
            COUNT(DISTINCT professor_turma.turma_id) AS total_turmas,
            COUNT(DISTINCT professor_classe.id_classe) AS total_classes,
            COUNT(DISTINCT professor_disciplina.disciplina_id) AS total_disciplinas
        FROM professor

 


        LEFT JOIN professor_turma ON professor.id = professor_turma.professor_id
        LEFT JOIN professor_classe ON professor.id = professor_classe.id_professor
        LEFT JOIN professor_disciplina ON professor.id = professor_disciplina.professor_id";

// Aplicar filtros
$filters = [];
if (isset($_GET['genero']) && $_GET['genero'] != '') {
    $filters[] = "professor.genero = '" . $_GET['genero'] . "'";
}
if (isset($_GET['estado_civil']) && $_GET['estado_civil'] != '') {
    $filters[] = "professor.estado_civil = '" . $_GET['estado_civil'] . "'";
}
if (isset($_GET['categoria_salarial']) && $_GET['categoria_salarial'] != '') {
    $filters[] = "professor.categoria_salarial = '" . $_GET['categoria_salarial'] . "'";
}
if (isset($_GET['novo']) && $_GET['novo'] != '') {
    $filters[] = "professor.novo = " . intval($_GET['novo']);
}
if (isset($_GET['titulo']) && $_GET['titulo'] != '') {
    $filters[] = "professor.titulo = '" . $_GET['titulo'] . "'";
}
if (isset($_GET['distrito_id']) && $_GET['distrito_id'] != '') {
    $filters[] = "professor.distrito_id = " . intval($_GET['distrito_id']);
}
if (isset($_GET['id_escola']) && $_GET['id_escola'] != '') {
    $filters[] = "professor.id_escola = " . intval($_GET['id_escola']);
}
if (isset($_GET['idade']) && isset($_GET['idade_condicao'])) {
    if ($_GET['idade_condicao'] == 'maior') {
        $filters[] = "professor.idade > " . intval($_GET['idade']);
    } elseif ($_GET['idade_condicao'] == 'menor') {
        $filters[] = "professor.idade < " . intval($_GET['idade']);
    } elseif ($_GET['idade_condicao'] == 'igual') {
        $filters[] = "professor.idade = " . intval($_GET['idade']);
    }
}
if (isset($_GET['turma_id']) && $_GET['turma_id'] != '') {
    $filters[] = "professor_turma.turma_id = " . intval($_GET['turma_id']);
}
if (isset($_GET['classe_id']) && $_GET['classe_id'] != '') {
    $filters[] = "professor_classe.id_classe = " . intval($_GET['classe_id']);
}
if (isset($_GET['disciplina_id']) && $_GET['disciplina_id'] != '') {
    $filters[] = "professor_disciplina.disciplina_id = " . intval($_GET['disciplina_id']);
}

// Adicionando filtros à consulta
if (count($filters) > 0) {
    $sql .= " WHERE " . implode(" AND ", $filters);
}

// Agrupando para contagem de associações
$sql .= " GROUP BY professor.id";

$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtro de Professores</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<style>
    /* CSS Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    padding: 20px;
}

/* Container */
h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.filter-bar {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.filter-bar form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

input[type="number"],
select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f9f9f9;
}

button,
a {
    text-align: center;
    padding: 10px 15px;
    font-weight: bold;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    color: #fff;
    cursor: pointer;
}

button {
    background-color: #4CAF50;
}

a {
    background-color: #d9534f;
}

a:hover,
button:hover {
    opacity: 0.9;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

thead {
    background-color: #4CAF50;
    color: white;
}

th,
td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #4CAF50;
    color: #fff;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #f1f1f1;
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

<h2>Filtro de Professores</h2>

<div class="filter-bar">
    <form method="get">
        <label for="genero">Gênero:</label>
        <select name="genero" id="genero">
            <option value="">Selecione</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Outro">Outro</option>
        </select>

        <label for="estado_civil">Estado Civil:</label>
        <select name="estado_civil" id="estado_civil">
            <option value="">Selecione</option>
            <?php foreach ($estadosCivis as $estado): ?>
                <option value="<?php echo $estado; ?>"><?php echo $estado; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="categoria_salarial">Categoria Salarial:</label>
        <select name="categoria_salarial" id="categoria_salarial">
            <option value="">Selecione</option>
            <?php foreach ($categoriaSalarial as $categoria): ?>
                <option value="<?php echo $categoria; ?>"><?php echo $categoria; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="novo">Novo:</label>
        <select name="novo" id="novo">
            <option value="">Selecione</option>
            <option value="1">Sim</option>
            <option value="0">Nao</option>
        </select>

        <label for="titulo">Titulo:</label>
        <select name="titulo" id="titulo">
            <option value="">Selecione</option>
            <?php foreach ($titulos as $titulo): ?>
                <option value="<?php echo $titulo; ?>"><?php echo $titulo; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="distrito_id">Distrito:</label>
        <select name="distrito_id" id="distrito_id">
            <option value="">Selecione</option>
            <?php while ($row = $distritos->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nome_distrito']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="id_escola">Escola:</label>
        <select name="id_escola" id="id_escola">
            <option value="">Selecione</option>
            <?php while ($row = $escolas->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="idade">Idade:</label>
        <input type="number" name="idade" id="idade">
        <select name="idade_condicao">
            <option value="maior">Maior que</option>
            <option value="menor">Menor que</option>
            <option value="igual">Igual</option>
        </select>

        <label for="turma_id">Turma:</label>
        <select name="turma_id" id="turma_id">
            <option value="">Selecione</option>
            <?php while ($row = $turmas->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nome_turma']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="classe_id">Classe:</label>
        <select name="classe_id" id="classe_id">
            <option value="">Selecione</option>
            <?php while ($row = $classes->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nivel_classe']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="disciplina_id">Disciplina:</label>
        <select name="disciplina_id" id="disciplina_id">
            <option value="">Selecione</option>
            <?php while ($row = $disciplinas->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nome_disciplina']; ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Filtrar</button>
        <a href="filtro_quantidade_professor.php">Limpar Filtros</a>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Professor ID</th>
            <th>Gênero</th>
            <th>Estado Civil</th>
            <th>Categoria Salarial</th>
            <th>Novo</th>
            <th>Titulo</th>
           
            <th>Idade</th>
            <th>Total de Turmas</th>
            <th>Total de Classes</th>
            <th>Total de Disciplinas</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['professor_id']; ?></td>
                <td><?php echo $row['genero']; ?></td>
                <td><?php echo $row['estado_civil']; ?></td>
                <td><?php echo $row['categoria_salarial']; ?></td>
                <td><?php echo $row['novo'] ? 'Sim' : 'Nao'; ?></td>
                <td><?php echo $row['titulo']; ?></td>
     
      
                <td><?php echo $row['idade']; ?></td>
                <td><?php echo $row['total_turmas']; ?></td>
                <td><?php echo $row['total_classes']; ?></td>
                <td><?php echo $row['total_disciplinas']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
