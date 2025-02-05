<?php
// Conexao com o banco de dados (substitua com suas credenciais)
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Consultas SQL para obter os valores de filtro
$escolas = $mysqli->query("SELECT DISTINCT nome FROM escola");
$classes = $mysqli->query("SELECT DISTINCT nivel_classe FROM classe");
$turmas = $mysqli->query("SELECT DISTINCT nome_turma FROM turma");
$cursos = $mysqli->query("SELECT DISTINCT nome_area FROM curso");

$distritos = $mysqli->query("SELECT DISTINCT nome_distrito FROM distrito");

$generos = ['Masculino', 'Feminino', 'Outro']; // Valores fixos para Gênero
$periodos = $mysqli->query("SELECT DISTINCT descricao FROM periodo_dia");


$disciplinas = $mysqli->query("SELECT DISTINCT nome_disciplina FROM disciplina"); // Nova consulta para disciplinas

// Consulta SQL com joins para trazer os nomes e os novos campos
$sql = "SELECT 
            aluno.id AS aluno_id, 
            aluno.nome AS aluno_nome, 
            aluno.escola_id, 
            escola.nome AS escola_nome,
            aluno.curso_id, 
            curso.nome_area AS curso_nome,

  aluno.id_distrito, 
 distrito.nome_distrito AS nomedistrito,

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

 LEFT JOIN distrito ON aluno.id_distrito = distrito.id

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


if (isset($_GET['distrito']) && $_GET['distrito'] != '') {
    $filters[] = "distrito.nome_distrito LIKE '%" . $_GET['distrito'] . "%'";
}

if (isset($_GET['genero']) && $_GET['genero'] != '') {
    $filters[] = "aluno.genero LIKE '%" . $_GET['genero'] . "%'";
}
if (isset($_GET['periodo_dia']) && $_GET['periodo_dia'] != '') {
    $filters[] = "periodo_dia.descricao LIKE '%" . $_GET['periodo_dia'] . "%'";
}

if (isset($_GET['disciplina']) && $_GET['disciplina'] != '') { // Novo filtro para disciplina
    $filters[] = "disciplina.nome_disciplina LIKE '%" . $_GET['disciplina'] . "%'";
}

if (isset($_GET['idade']) && isset($_GET['idade_condicao'])) {
    if ($_GET['idade_condicao'] == 'maior') {
        $filters[] = "aluno.idade > " . intval($_GET['idade']);
    } elseif ($_GET['idade_condicao'] == 'menor') {
        $filters[] = "aluno.idade < " . intval($_GET['idade']);
    } elseif ($_GET['idade_condicao'] == 'igual') {
        $filters[] = "aluno.idade = " . intval($_GET['idade']);
    }
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
    <title>Lista de Alunos(as)-Notas</title>



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
     XLSX.utils.book_append_sheet(wb, ws, 'Alunos Nota');
     XLSX.writeFile(wb, 'Relatorio_Alunos_Nota.xlsx');
 }




    </script>


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




        <select name="distrito">
            <option value="">Selecione o Distrito do Aluno</option>
            <?php while ($row = $distritos->fetch_assoc()): ?>
                <option value="<?php echo $row['nome_distrito']; ?>" <?php echo isset($_GET['distrito']) && $_GET['distrito'] == $row['nome_distrito'] ? 'selected' : ''; ?>><?php echo $row['nome_distrito']; ?></option>
            <?php endwhile; ?>
        </select>


        


        <select name="genero">
            <option value="">Selecione o Gênero</option>
            <?php foreach ($generos as $genero): ?>
                <option value="<?php echo $genero; ?>" <?php echo isset($_GET['genero']) && $_GET['genero'] == $genero ? 'selected' : ''; ?>><?php echo $genero; ?></option>
            <?php endforeach; ?>
        </select>


        <label for="idade">Idade:</label>
        <input type="number" name="idade" id="idade">
        <select name="idade_condicao">
            <option value="maior">Maior que</option>
            <option value="menor">Menor que</option>
            <option value="igual">Igual</option>
        </select>
        
        <select name="periodo_dia">
            <option value="">Selecione o Periodo</option>
            <?php while ($row = $periodos->fetch_assoc()): ?>
                <option value="<?php echo $row['descricao']; ?>" <?php echo isset($_GET['periodo_dia']) && $_GET['periodo_dia'] == $row['descricao'] ? 'selected' : ''; ?>><?php echo $row['descricao']; ?></option>
            <?php endwhile; ?>
        </select>



        <select name="disciplina"> <!-- Novo campo de filtro de disciplina -->
            <option value="">Selecione a Disciplina</option>
            <?php while ($row = $disciplinas->fetch_assoc()): ?>
                <option value="<?php echo $row['nome_disciplina']; ?>" <?php echo isset($_GET['disciplina']) && $_GET['disciplina'] == $row['nome_disciplina'] ? 'selected' : ''; ?>><?php echo $row['nome_disciplina']; ?></option>
            <?php endwhile; ?>
        </select>

        
        <button type="submit">Filtrar</button>
    </form>
</div>

<div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#" class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download"></i> Baixar Tabela
    </a>
</div>


<!-- Tabela de dados -->
<table id="table-results">
    <thead>
        <tr>
            <th class="field-checkbox"  value="escola_nome">Escola</th>
            <th class="field-checkbox"  value="classe_nome">Classe</th>
            <th class="field-checkbox"  value="nometurma">Turma</th>
            <th class="field-checkbox"  value="curso_nome">Curso</th>
            <th class="field-checkbox"  value="disciplina_nome">Disciplina</th>
            <th class="field-checkbox"  value="numero_ordem">Ordem</th>
            <th class="field-checkbox"  value="aluno_nome">Nome</th>
            <th class="field-checkbox"  value="nota1">Nota 1</th>
            <th class="field-checkbox"  value="nota2">Nota 2</th>
            <th class="field-checkbox"  value="nota_final1">Pauta 1</th>
            <th class="field-checkbox"  value="nota3">Nota 3</th>
            <th class="field-checkbox"  value="nota4">Nota 4</th>
            <th class="field-checkbox"  value="nota_final2">Pauta 2</th>
            <th class="field-checkbox"  value="nota5">Nota 5</th>
            <th class="field-checkbox"  value="nota6">Nota 6</th>
            <th class="field-checkbox"  value="nota_final3">Pauta 3</th>
           
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

                    <td style="display:none;"><?php echo $row["nomedistrito"]; ?></td>

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
