<?php
// Conexao com o banco de dados (substitua com suas credenciais)
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Consultas SQL para obter os valores de filtro
$escolas = $mysqli->query("SELECT DISTINCT nome FROM escola");
$classes = $mysqli->query("SELECT DISTINCT nivel_classe FROM classe");
$turmas = $mysqli->query("SELECT DISTINCT nome_turma FROM turma");
$cursos = $mysqli->query("SELECT DISTINCT nome_area FROM curso");
$generos = ['Masculino', 'Feminino', 'Outro']; // Valores fixos para Gênero
$periodos = $mysqli->query("SELECT DISTINCT descricao FROM periodo_dia");

// Consulta SQL com joins para trazer os nomes e os novos campos
$sql = "SELECT 
            aluno.id AS aluno_id, 
            aluno.nome AS aluno_nome, 
            aluno.escola_id, 
            escola.nome AS escola_nome,
            aluno.curso_id, 
            curso.nome_area AS curso_nome,
            aluno.periododia_id,
            turma.nome_turma AS nometurma,
            aluno.classe_id, 
            classe.nivel_classe AS classe_nome,
            aluno.turma_id,
            aluno.numero_ordem,
            nota.nota1, 
            nota.nota2, 
            nota.nota3, 
            nota.nota4, 
            nota.nota5, 
            nota.nota6,
            nota.nota_final1, 
            nota.nota_final2, 
            nota.nota_final3,
            nota.disciplina_id,
            disciplina.nome_disciplina AS disciplina_nome,
            periodo_dia.descricao AS periodo_dia_descricao,
            aluno.genero
        FROM aluno
        LEFT JOIN nota ON aluno.id = nota.id_aluno
        LEFT JOIN escola ON aluno.escola_id = escola.id
        LEFT JOIN curso ON aluno.curso_id = curso.id
        LEFT JOIN turma ON aluno.turma_id = turma.id
        LEFT JOIN classe ON aluno.classe_id = classe.id
        LEFT JOIN disciplina ON nota.disciplina_id = disciplina.id
        LEFT JOIN periodo_dia ON aluno.periododia_id = periodo_dia.id";

// Aplicar filtros
$filters = [];
if (isset($_GET['escola']) && $_GET['escola'] != '') {
    $filters[] = "escola.nome LIKE '%" . $_GET['escola'] . "%'";
}
if (isset($_GET['classe']) && $_GET['classe'] != '') {
    $filters[] = "classe.nivel_classe LIKE '%" . $_GET['classe'] . "%'";
}
if (isset($_GET['turma']) && $_GET['turma'] != '') {
    $filters[] = "turma.nome_turma LIKE '%" . $_GET['turma'] . "%'";
}
if (isset($_GET['curso']) && $_GET['curso'] != '') {
    $filters[] = "curso.nome_area LIKE '%" . $_GET['curso'] . "%'";
}
if (isset($_GET['genero']) && $_GET['genero'] != '') {
    $filters[] = "aluno.genero LIKE '%" . $_GET['genero'] . "%'";
}
if (isset($_GET['periodo_dia']) && $_GET['periodo_dia'] != '') {
    $filters[] = "periodo_dia.descricao LIKE '%" . $_GET['periodo_dia'] . "%'";
}

// Adicionando filtros à consulta
if (count($filters) > 0) {
    $sql .= " WHERE " . implode(" AND ", $filters);
}

$result = $mysqli->query($sql);

// Funçao para gerar uma cor baseada no nome da escola
function getSchoolColor($schoolName) {
    $hash = md5($schoolName); // Gera um hash do nome da escola
    $r = hexdec(substr($hash, 0, 2)); // R -> 1ª parte do hash
    $g = hexdec(substr($hash, 2, 2)); // G -> 2ª parte do hash
    $b = hexdec(substr($hash, 4, 2)); // B -> 3ª parte do hash
    return "rgb($r, $g, $b)";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos e Notas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        td {
            background-color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            margin: 0 5px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
        .filter-bar {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .filter-bar select {
            padding: 8px;
            margin: 0 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .filter-bar button {
            padding: 8px 16px;
            font-size: 16px;
            border: 1px solid #ddd;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h2>Lista de Alunos e Notas</h2>

<!-- Filtros de busca -->
<div class="filter-bar">
    <form method="get">
        <select name="escola">
            <option value="">Selecione a Escola</option>
            <?php while ($row = $escolas->fetch_assoc()): ?>
                <option value="<?php echo $row['nome']; ?>" <?php echo isset($_GET['escola']) && $_GET['escola'] == $row['nome'] ? 'selected' : ''; ?>><?php echo $row['nome']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <select name="classe">
            <option value="">Selecione a Classe</option>
            <?php while ($row = $classes->fetch_assoc()): ?>
                <option value="<?php echo $row['nivel_classe']; ?>" <?php echo isset($_GET['classe']) && $_GET['classe'] == $row['nivel_classe'] ? 'selected' : ''; ?>><?php echo $row['nivel_classe']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <select name="turma">
            <option value="">Selecione a Turma</option>
            <?php while ($row = $turmas->fetch_assoc()): ?>
                <option value="<?php echo $row['nome_turma']; ?>" <?php echo isset($_GET['turma']) && $_GET['turma'] == $row['nome_turma'] ? 'selected' : ''; ?>><?php echo $row['nome_turma']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <select name="curso">
            <option value="">Selecione o Curso</option>
            <?php while ($row = $cursos->fetch_assoc()): ?>
                <option value="<?php echo $row['nome_area']; ?>" <?php echo isset($_GET['curso']) && $_GET['curso'] == $row['nome_area'] ? 'selected' : ''; ?>><?php echo $row['nome_area']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <select name="genero">
            <option value="">Selecione o Gênero</option>
            <?php foreach ($generos as $genero): ?>
                <option value="<?php echo $genero; ?>" <?php echo isset($_GET['genero']) && $_GET['genero'] == $genero ? 'selected' : ''; ?>><?php echo $genero; ?></option>
            <?php endforeach; ?>
        </select>
        
        <select name="periodo_dia">
            <option value="">Selecione o Periodo</option>
            <?php while ($row = $periodos->fetch_assoc()): ?>
                <option value="<?php echo $row['descricao']; ?>" <?php echo isset($_GET['periodo_dia']) && $_GET['periodo_dia'] == $row['descricao'] ? 'selected' : ''; ?>><?php echo $row['descricao']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit">Filtrar</button>
    </form>
</div>

<!-- Tabela de dados -->
<table>
    <thead>
        <tr>
        <th>Classe</th>
            <th">Turma</th>
            <th>Curso</th>
            <th>Disciplina</th>
            <th>Número</th>
            <th>Nome do Aluno</th>
            <th style="background-color: rgb(0, 247, 255);">1º Teste</th>
            <th style="background-color: rgb(0, 247, 255);">2º Teste</th>
            <th style="background-color: yellow; color:black;">Pauta 1º Periodo</th>
            <th style="background-color: rgb(0, 247, 255);">1º Teste</th>
            <th style="background-color: rgb(0, 247, 255);">2º Teste</th>
            <th style="background-color: yellow; color:black;">Pauta 2º Periodo</th>
            <th style="background-color: rgb(0, 247, 255);">1º Teste</th>
            <th style="background-color: rgb(0, 247, 255);">2º Teste</th>
            <th style="background-color: yellow; color:black;">Pauta 3º Periodo</th>
            
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr style="background-color: <?php echo getSchoolColor($row['escola_nome']); ?>;">
                <td><?php echo $row["escola_nome"]; ?></td>
                    <td><?php echo $row["classe_nome"]; ?></td>
                    <td><?php echo $row["nometurma"]; ?></td>
                    <td><?php echo $row["curso_nome"]; ?></td>
                    <td><?php echo $row["disciplina_nome"]; ?></td>
                    <td><?php echo $row["numero_ordem"]; ?></td>
                    <td><?php echo $row["aluno_nome"]; ?></td>
                    <td style="background-color: rgba(0, 247, 255, 0.267); color: <?php echo ($row["nota1"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota1"]; ?></td>
                    <td style="background-color: rgba(0, 247, 255, 0.267); color: <?php echo ($row["nota2"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota2"]; ?></td>
                    <td style="background-color: rgba(255, 255, 0, 0.541); color: <?php echo ($row["nota_final1"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota_final1"]; ?></td>
                    <td style="background-color: rgba(0, 247, 255, 0.267); color: <?php echo ($row["nota3"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota3"]; ?></td>
                    <td style="background-color: rgba(0, 247, 255, 0.267); color: <?php echo ($row["nota4"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota4"]; ?></td>
                    <td style="background-color: rgba(255, 255, 0, 0.541); color: <?php echo ($row["nota_final2"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota_final2"]; ?></td>
                    <td style="background-color: rgba(0, 247, 255, 0.267); color: <?php echo ($row["nota5"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota5"]; ?></td>
                    <td style="background-color: rgba(0, 247, 255, 0.267); color: <?php echo ($row["nota6"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota6"]; ?></td>
                    <td style="background-color: rgba(255, 255, 0, 0.541); color: <?php echo ($row["nota_final3"] < 10) ? 'red' : 'blue'; ?>;"><?php echo $row["nota_final3"]; ?></td>
                    <td style="display:none;"><?php echo $row["periodo_dia_descricao"]; ?></td>

                    <td style="display:none;"><?php echo $row["genero"]; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="18">Nenhum registro encontrado</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $mysqli->close(); ?>

</body>
</html>
