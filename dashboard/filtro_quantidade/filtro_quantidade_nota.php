<?php
// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Consultas SQL para obter os valores de filtro
$escolas = $mysqli->query("SELECT DISTINCT nome FROM escola");
$classes = $mysqli->query("SELECT DISTINCT nivel_classe FROM classe");
$turmas = $mysqli->query("SELECT DISTINCT nome_turma FROM turma");
$cursos = $mysqli->query("SELECT DISTINCT nome_area FROM curso");
$distritos = $mysqli->query("SELECT DISTINCT nome_distrito FROM distrito");
$generos = ['Masculino', 'Feminino'];
$periodos = $mysqli->query("SELECT DISTINCT descricao FROM periodo_dia");
$disciplinas = $mysqli->query("SELECT DISTINCT nome_disciplina FROM disciplina");





// Definir o intervalo selecionado e a condiçao correspondente para o filtro SQL
$intervaloCondicao = "";
$validIntervalos = [
    '0-4' => "nota_final1 BETWEEN 0 AND 4 AND nota_final1 IS NOT NULL",
    '5-9' => "nota_final1 BETWEEN 5 AND 9 AND nota_final1 IS NOT NULL",
    '10-13' => "nota_final1 BETWEEN 10 AND 13 AND nota_final1 IS NOT NULL",
    '14-17' => "nota_final1 BETWEEN 14 AND 17 AND nota_final1 IS NOT NULL",
    '18-20' => "nota_final1 BETWEEN 18 AND 20 AND nota_final1 IS NOT NULL"
];

// Verificar se o parâmetro 'intervalo' foi passado e se é um valor válido
if (isset($_GET['intervalo']) && !empty($_GET['intervalo']) && array_key_exists($_GET['intervalo'], $validIntervalos)) {
    $intervaloCondicao = $validIntervalos[$_GET['intervalo']];
}

// Preparar a parte da consulta para o intervalo, se necessário
$intervaloConditionQueryPart = "";
if (!empty($intervaloCondicao)) {
    $intervaloConditionQueryPart = "SUM(CASE WHEN $intervaloCondicao THEN 1 ELSE 0 END) AS intervalo1,";
}












// Definir o intervalo selecionado e a condiçao correspondente para o filtro SQL
$intervaloCondicao2 = "";
$validIntervalos2 = [
    '0-4' => "nota_final2 BETWEEN 0 AND 4 AND nota_final2 IS NOT NULL",
    '5-9' => "nota_final2 BETWEEN 5 AND 9 AND nota_final2 IS NOT NULL",
    '10-13' => "nota_final2 BETWEEN 10 AND 13 AND nota_final2 IS NOT NULL",
    '14-17' => "nota_final2 BETWEEN 14 AND 17 AND nota_final2 IS NOT NULL",
    '18-20' => "nota_final2 BETWEEN 18 AND 20 AND nota_final2 IS NOT NULL"
];

// Verificar se o parâmetro 'intervalo' foi passado e se é um valor válido
if (isset($_GET['intervalo2']) && !empty($_GET['intervalo2']) && array_key_exists($_GET['intervalo2'], $validIntervalos2)) {
    $intervaloCondicao2 = $validIntervalos2[$_GET['intervalo2']];
}

// Preparar a parte da consulta para o intervalo, se necessário
$intervaloConditionQueryPart2 = "";
if (!empty($intervaloCondicao2)) {
    $intervaloConditionQueryPart2 = "SUM(CASE WHEN $intervaloCondicao2 THEN 1 ELSE 0 END) AS intervalo1_2,";
}








// Definir o intervalo selecionado e a condiçao correspondente para o filtro SQL
$intervaloCondicao3 = "";
$validIntervalos3 = [
    '0-4' => "nota_final3 BETWEEN 0 AND 4 AND nota_final3 IS NOT NULL",
    '5-9' => "nota_final3 BETWEEN 5 AND 9 AND nota_final3 IS NOT NULL",
    '10-13' => "nota_final3 BETWEEN 10 AND 13 AND nota_final3 IS NOT NULL",
    '14-17' => "nota_final3 BETWEEN 14 AND 17 AND nota_final3 IS NOT NULL",
    '18-20' => "nota_final3 BETWEEN 18 AND 20 AND nota_final3 IS NOT NULL"
];

// Verificar se o parâmetro 'intervalo' foi passado e se é um valor válido
if (isset($_GET['intervalo3']) && !empty($_GET['intervalo3']) && array_key_exists($_GET['intervalo3'], $validIntervalos3)) {
    $intervaloCondicao3 = $validIntervalos3[$_GET['intervalo3']];
}

// Preparar a parte da consulta para o intervalo, se necessário
$intervaloConditionQueryPart3 = "";
if (!empty($intervaloCondicao3)) {
    $intervaloConditionQueryPart3 = "SUM(CASE WHEN $intervaloCondicao3 THEN 1 ELSE 0 END) AS intervalo1_3,";
}






















// Consulta SQL principal com filtros e cálculo do total para o intervalo
$sql = "SELECT 
            escola.nome AS escola_nome,
            aluno.genero,
            COUNT(CASE WHEN aluno.genero = 'Masculino' THEN 1 END) AS genero_masculino,
            COUNT(CASE WHEN aluno.genero = 'Feminino' THEN 1 END) AS genero_feminino,
            $intervaloConditionQueryPart
              $intervaloConditionQueryPart2
              $intervaloConditionQueryPart3
            SUM(CASE WHEN nota1 >= 10 THEN 1 ELSE 0 END) AS total_positiva_nota1,
            SUM(CASE WHEN nota1 < 10 THEN 1 ELSE 0 END) AS total_negativa_nota1,
            SUM(CASE WHEN nota2 >= 10 THEN 1 ELSE 0 END) AS total_positiva_nota2,
            SUM(CASE WHEN nota2 < 10 THEN 1 ELSE 0 END) AS total_negativa_nota2,
            SUM(CASE WHEN nota3 >= 10 THEN 1 ELSE 0 END) AS total_positiva_nota3,
            SUM(CASE WHEN nota3 < 10 THEN 1 ELSE 0 END) AS total_negativa_nota3,
            SUM(CASE WHEN nota4 >= 10 THEN 1 ELSE 0 END) AS total_positiva_nota4,
            SUM(CASE WHEN nota4 < 10 THEN 1 ELSE 0 END) AS total_negativa_nota4,
            SUM(CASE WHEN nota5 >= 10 THEN 1 ELSE 0 END) AS total_positiva_nota5,
            SUM(CASE WHEN nota5 < 10 THEN 1 ELSE 0 END) AS total_negativa_nota5,
            SUM(CASE WHEN nota6 >= 10 THEN 1 ELSE 0 END) AS total_positiva_nota6,
            SUM(CASE WHEN nota6 < 10 THEN 1 ELSE 0 END) AS total_negativa_nota6,
            SUM(CASE WHEN nota_final1 >= 10 THEN 1 ELSE 0 END) AS total_positiva_pauta1,
            SUM(CASE WHEN nota_final1 < 10 THEN 1 ELSE 0 END) AS total_negativa_pauta1,
            SUM(CASE WHEN nota_final2 >= 10 THEN 1 ELSE 0 END) AS total_positiva_pauta2,
            SUM(CASE WHEN nota_final2 < 10 THEN 1 ELSE 0 END) AS total_negativa_pauta2,
            SUM(CASE WHEN nota_final3 >= 10 THEN 1 ELSE 0 END) AS total_positiva_pauta3,
            SUM(CASE WHEN nota_final3 < 10 THEN 1 ELSE 0 END) AS total_negativa_pauta3
        FROM aluno
        LEFT JOIN escola ON aluno.escola_id = escola.id
        LEFT JOIN nota ON aluno.id = nota.id_aluno
        LEFT JOIN classe ON aluno.classe_id = classe.id
        LEFT JOIN turma ON aluno.turma_id = turma.id
        LEFT JOIN curso ON aluno.curso_id = curso.id
        LEFT JOIN distrito ON escola.distrito_id = distrito.id
        LEFT JOIN periodo_dia ON nota.periodo_dia_id = periodo_dia.id
        LEFT JOIN disciplina ON nota.disciplina_id = disciplina.id";


// Aplicar filtros adicionais
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
if (isset($_GET['disciplina']) && $_GET['disciplina'] != '') {
    $filters[] = "disciplina.nome_disciplina LIKE '%" . $_GET['disciplina'] . "%'";
}

// Adicionar filtros ao SQL
if (count($filters) > 0) {
    $sql .= " WHERE " . implode(" AND ", $filters);
}
$sql .= " GROUP BY escola.nome, aluno.genero";

$result = $mysqli->query($sql);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

<!-- CSS do Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
     XLSX.writeFile(wb, 'Relatorio_Alunos_quantidade_nota.xlsx');
 }




    </script>


    <title>Relatório de Alunos</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form, table {
            margin: 0 auto;
            width: 100%;
            max-width: 1200px;
        }
        form select, form button {
            padding: 8px;
            margin: 5px;
            font-size: 1em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
             
        }
        table, th, td {
            border: 2px solid black;
        }
        th, td {
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
            cursor: pointer;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const headers = document.querySelectorAll("th");
            headers.forEach((header, index) => {
                header.addEventListener("click", () => sortTable(index));
            });
        });

        function sortTable(columnIndex) {
            const table = document.querySelector("table");
            const rows = Array.from(table.rows).slice(1);
            const isAscending = table.dataset.sortOrder === "asc";
            rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[columnIndex].textContent;
                const cellB = rowB.cells[columnIndex].textContent;
                return isAscending
                    ? cellA.localeCompare(cellB, undefined, {numeric: true})
                    : cellB.localeCompare(cellA, undefined, {numeric: true});
            });
            table.tBodies[0].append(...rows);
            table.dataset.sortOrder = isAscending ? "desc" : "asc";
        }
    </script>
  
</head>
<body>
    

<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>



<!-- Adicione um espaçamento para compensar o botao fixo -->
<div style="margin-top: 60px;"></div>

<h2>Relatório de Alunos</h2>

<!-- Filtros de busca -->
<form method="get">
    <select name="escola">
        <option value="">-- Selecione a Escola --</option>
        <?php while ($row = $escolas->fetch_assoc()): ?>
            <option value="<?php echo $row['nome']; ?>" <?php echo isset($_GET['escola']) && $_GET['escola'] == $row['nome'] ? 'selected' : ''; ?>><?php echo $row['nome']; ?></option>
        <?php endwhile; ?>
    </select>

    <select name="classe">
        <option value="">-- Selecione a Classe --</option>
        <?php while ($row = $classes->fetch_assoc()): ?>
            <option value="<?php echo $row['nivel_classe']; ?>" <?php echo isset($_GET['classe']) && $_GET['classe'] == $row['nivel_classe'] ? 'selected' : ''; ?>><?php echo $row['nivel_classe']; ?></option>
        <?php endwhile; ?>
    </select>

    <select name="turma">
        <option value="">-- Selecione a Turma --</option>
        <?php while ($row = $turmas->fetch_assoc()): ?>
            <option value="<?php echo $row['nome_turma']; ?>" <?php echo isset($_GET['turma']) && $_GET['turma'] == $row['nome_turma'] ? 'selected' : ''; ?>><?php echo $row['nome_turma']; ?></option>
        <?php endwhile; ?>
    </select>

    <select name="curso">
        <option value="">-- Selecione o Curso --</option>
        <?php while ($row = $cursos->fetch_assoc()): ?>
            <option value="<?php echo $row['nome_area']; ?>" <?php echo isset($_GET['curso']) && $_GET['curso'] == $row['nome_area'] ? 'selected' : ''; ?>><?php echo $row['nome_area']; ?></option>
        <?php endwhile; ?>
    </select>

    <select name="distrito">
        <option value="">-- Selecione o Distrito --</option>
        <?php while ($row = $distritos->fetch_assoc()): ?>
            <option value="<?php echo $row['nome_distrito']; ?>" <?php echo isset($_GET['distrito']) && $_GET['distrito'] == $row['nome_distrito'] ? 'selected' : ''; ?>><?php echo $row['nome_distrito']; ?></option>
        <?php endwhile; ?>
    </select>

    <select name="genero">
        <option value="">-- Selecione o Gênero --</option>
        <?php foreach ($generos as $genero): ?>
            <option value="<?php echo $genero; ?>" <?php echo isset($_GET['genero']) && $_GET['genero'] == $genero ? 'selected' : ''; ?>><?php echo $genero; ?></option>
        <?php endforeach; ?>
    </select>

    <select name="periodo_dia">
        <option value="">-- Selecione o Periodo --</option>
        <?php while ($row = $periodos->fetch_assoc()): ?>
            <option value="<?php echo $row['descricao']; ?>" <?php echo isset($_GET['periodo_dia']) && $_GET['periodo_dia'] == $row['descricao'] ? 'selected' : ''; ?>><?php echo $row['descricao']; ?></option>
        <?php endwhile; ?>
    </select>

    <select name="disciplina">
        <option value="">-- Selecione a Disciplina --</option>
        <?php while ($row = $disciplinas->fetch_assoc()): ?>
            <option value="<?php echo $row['nome_disciplina']; ?>" <?php echo isset($_GET['disciplina']) && $_GET['disciplina'] == $row['nome_disciplina'] ? 'selected' : ''; ?>><?php echo $row['nome_disciplina']; ?></option>
        <?php endwhile; ?>
    </select>




    <select name="intervalo">
        <option value="">--Intervalo Pauta 1º P--</option>
        <option value="0-4" <?php echo isset($_GET['intervalo']) && $_GET['intervalo'] == '0-4' ? 'selected' : ''; ?>>nota 0 - 4</option>
        <option value="5-9" <?php echo isset($_GET['intervalo']) && $_GET['intervalo'] == '5-9' ? 'selected' : ''; ?>>nota 5 - 9</option>
        <option value="10-13" <?php echo isset($_GET['intervalo']) && $_GET['intervalo'] == '10-13' ? 'selected' : ''; ?>>nota 10 - 13</option>
        <option value="14-17" <?php echo isset($_GET['intervalo']) && $_GET['intervalo'] == '14-17' ? 'selected' : ''; ?>>nota 14 - 17</option>
        <option value="18-20" <?php echo isset($_GET['intervalo']) && $_GET['intervalo'] == '18-20' ? 'selected' : ''; ?>>nota 18 - 20</option>
    </select>





    <select name="intervalo2">
        <option value="">--Intervalo Pauta 2º P--</option>
        <option value="0-4" <?php echo isset($_GET['intervalo2']) && $_GET['intervalo2'] == '0-4' ? 'selected' : ''; ?>>nota 0 - 4</option>
        <option value="5-9" <?php echo isset($_GET['intervalo2']) && $_GET['intervalo2'] == '5-9' ? 'selected' : ''; ?>>nota 5 - 9</option>
        <option value="10-13" <?php echo isset($_GET['intervalo2']) && $_GET['intervalo2'] == '10-13' ? 'selected' : ''; ?>>nota 10 - 13</option>
        <option value="14-17" <?php echo isset($_GET['intervalo2']) && $_GET['intervalo2'] == '14-17' ? 'selected' : ''; ?>>nota 14 - 17</option>
        <option value="18-20" <?php echo isset($_GET['intervalo2']) && $_GET['intervalo2'] == '18-20' ? 'selected' : ''; ?>>nota 18 - 20</option>
    </select>





    <select name="intervalo3">
        <option value="">--Intervalo Pauta 3º P--</option>
        <option value="0-4" <?php echo isset($_GET['intervalo3']) && $_GET['intervalo3'] == '0-4' ? 'selected' : ''; ?>>nota 0 - 4</option>
        <option value="5-9" <?php echo isset($_GET['intervalo3']) && $_GET['intervalo3'] == '5-9' ? 'selected' : ''; ?>>nota 5 - 9</option>
        <option value="10-13" <?php echo isset($_GET['intervalo3']) && $_GET['intervalo3'] == '10-13' ? 'selected' : ''; ?>>nota 10 - 13</option>
        <option value="14-17" <?php echo isset($_GET['intervalo3']) && $_GET['intervalo3'] == '14-17' ? 'selected' : ''; ?>>nota 14 - 17</option>
        <option value="18-20" <?php echo isset($_GET['intervalo3']) && $_GET['intervalo3'] == '18-20' ? 'selected' : ''; ?>>nota 18 - 20</option>
    </select>
    

    <button type="submit">Filtrar</button>
</form>


<div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#" class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download"></i> Baixar Tabela
    </a>
</div>

<!--    -->
<table border="1" id="table-results">
    <thead>
        <tr>
            <th class="field-checkbox"  value="escola_nome">Escola</th>
            <th class="field-checkbox"  value="genero_masculino">Gênero M</th>
            <th class="field-checkbox"  value="genero_feminino">Gênero F</th>



            <th class="field-checkbox"  value="total_positiva_nota1" style="background-color:  rgba(54, 252, 4, 0.623);">Posit. 1 AV.</th>
            <th class="field-checkbox"  value="total_negativa_nota1" style="background-color: rgba(226, 10, 10, 0.623);">Negat. 1 AV.</th>
            <th class="field-checkbox"  value="total_positiva_nota2" style="background-color:  rgba(54, 252, 4, 0.623);">Posit. 2 AV.</th>
            <th class="field-checkbox"  value="total_negativa_nota2" style="background-color: rgba(226, 10, 10, 0.623);">Negat. 2 AV.</th>

            <th class="field-checkbox"  value="total_positiva_pauta1" style="background-color: rgb(53, 252, 4);">Positivas Pauta 1</th>
            <th class="field-checkbox"  value="total_negativa_pauta1" style="background-color: red;">Negativas Pauta 1</th>
            <th class="field-checkbox"  value="intervalo1">Intervalo (<?php echo isset($_GET['intervalo']) ? $_GET['intervalo'] : 'vazio'; ?>)</th>

          


            <th class="field-checkbox"  value="total_positiva_nota3" style="background-color:  rgba(54, 252, 4, 0.623);">Posit. 1 AV.</th>
            <th class="field-checkbox"  value="total_negativa_nota3" style="background-color: rgba(226, 10, 10, 0.623);">Negat. 1 AV.</th>
            <th class="field-checkbox"  value="total_positiva_nota4" style="background-color:  rgba(54, 252, 4, 0.623);">Posit. 2 AV.</th>
            <th class="field-checkbox"  value="total_negativa_nota4" style="background-color: rgba(226, 10, 10, 0.623);">Negat. 2 AV.</th>

            <th class="field-checkbox"  value="total_positiva_pauta2" style="background-color: rgb(53, 252, 4);">Positivas Pauta 2</th>
            <th class="field-checkbox"  value="total_negativa_pauta2" style="background-color: red;">Negativas Pauta 2</th>

            <th class="field-checkbox"  value="intervalo1_2">Intervalo (<?php echo isset($_GET['intervalo2']) ? $_GET['intervalo2'] : 'vazio'; ?>)</th>




            <th class="field-checkbox"  value="total_positiva_nota5" style="background-color:  rgba(54, 252, 4, 0.623);">Posit. 1 AV.</th>
            <th class="field-checkbox"  value="total_negativa_nota5" style="background-color: rgba(226, 10, 10, 0.623);">Negat. 1 AV.</th>
            <th class="field-checkbox"  value="total_positiva_nota6" style="background-color:  rgba(54, 252, 4, 0.623);">Posit. 2 AV.</th>
            <th class="field-checkbox"  value="total_negativa_nota6" style="background-color: rgba(226, 10, 10, 0.623);">Negat. 2 AV.</th>

            <th class="field-checkbox"  value="total_positiva_pauta3" style="background-color: rgb(53, 252, 4);">Positivas Pauta 3</th>
            <th class="field-checkbox"  value="total_negativa_pauta3" style="background-color: red;">Negativas Pauta 3</th>

            <th class="field-checkbox"  value="intervalo1_3">Intervalo (<?php echo isset($_GET['intervalo3']) ? $_GET['intervalo3'] : 'vazio'; ?>)</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["escola_nome"]; ?></td>
                    <td><?php echo $row["genero_masculino"]; ?></td>
                    <td><?php echo $row["genero_feminino"]; ?></td>


                    <td style="background-color:  rgba(54, 252, 4, 0.623); color:#ffff"><?php echo $row["total_positiva_nota1"]; ?></td>
                    <td style="background-color: rgba(226, 10, 10, 0.623); color:#ffff"><?php echo $row["total_negativa_nota1"]; ?></td>
                    <td style="background-color:  rgba(54, 252, 4, 0.623); color:#ffff"><?php echo $row["total_positiva_nota2"]; ?></td>
                    <td style="background-color: rgba(226, 10, 10, 0.623); color:#ffff"><?php echo $row["total_negativa_nota2"]; ?></td>

                    <td style="background-color: rgb(53, 252, 4); color:#ffff"><?php echo $row["total_positiva_pauta1"]; ?></td>
                    <td style="background-color: red; color:#ffff"><?php echo $row["total_negativa_pauta1"]; ?></td>

                    <td><?php echo $row['intervalo1']; ?></td> 
                    





                    <td style="background-color:  rgba(54, 252, 4, 0.623); color:#ffff"><?php echo $row["total_positiva_nota3"]; ?></td>
                    <td style="background-color: rgba(226, 10, 10, 0.623); color:#ffff"><?php echo $row["total_negativa_nota3"]; ?></td>
                    <td style="background-color:  rgba(54, 252, 4, 0.623); color:#ffff"><?php echo $row["total_positiva_nota4"]; ?></td>
                    <td style="background-color: rgba(226, 10, 10, 0.623); color:#ffff"><?php echo $row["total_negativa_nota4"]; ?></td>

                    <td style="background-color: rgb(53, 252, 4); color:#ffff"><?php echo $row["total_positiva_pauta2"]; ?></td>
                    <td style="background-color: red; color:#ffff"><?php echo $row["total_negativa_pauta2"]; ?></td>


                    <td><?php echo $row['intervalo1_2']; ?></td> 



                    <td style="background-color:  rgba(54, 252, 4, 0.623); color:#ffff"><?php echo $row["total_positiva_nota5"]; ?></td>
                    <td style="background-color: rgba(226, 10, 10, 0.623); color:#ffff"><?php echo $row["total_negativa_nota5"]; ?></td>
                    <td style="background-color:  rgba(54, 252, 4, 0.623); color:#ffff"><?php echo $row["total_positiva_nota6"]; ?></td>
                    <td style="background-color: rgba(226, 10, 10, 0.623); color:#ffff"><?php echo $row["total_negativa_nota6"]; ?></td>

                   
                    <td style="background-color: rgb(53, 252, 4); color:#ffff"><?php echo $row["total_positiva_pauta3"]; ?></td>
                    <td style="background-color: red; color:#ffff"><?php echo $row["total_negativa_pauta3"]; ?></td>

                    <td><?php echo $row['intervalo1_3']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="21">Nenhum registro encontrado</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


<?php $mysqli->close(); ?>
</body>
</html>