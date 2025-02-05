<link rel="stylesheet" href="/destp_pro/dashboard/aluno/card/css/style.css">

<?php
// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Parâmetros para filtro
$escolas = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35];
$classes = [1, 2, 3, 4, 5, 6];

// Preparando a consulta SQL dinâmica
$escola_placeholders = implode(',', array_fill(0, count($escolas), '?'));
$classe_placeholders = implode(',', array_fill(0, count($classes), '?'));

// Consulta principal para listar alunos que abandonaram a escola
$sql = "SELECT escola.nome AS escola_nome, turma.nome_turma AS turma_nome, curso.nome_area AS curso_nome, 
               aluno.nome, aluno.genero, aluno.idade, aluno.numero_ordem, aluno.bi, 
               aluno.numero_frequencia, aluno.endereco, aluno.telefone, aluno.situacao_economica, 
               aluno.contato_encarregado, aluno.motivo_abandono, aluno.estrategia_recuperacao
        FROM aluno
        INNER JOIN escola ON aluno.escola_id = escola.id
        INNER JOIN turma ON aluno.turma_id = turma.id
        INNER JOIN curso ON aluno.curso_id = curso.id
        WHERE aluno.escola_id IN ($escola_placeholders) 
          AND aluno.classe_id IN ($classe_placeholders) 
          AND aluno.motivo_abandono IS NOT NULL
            AND aluno.motivo_abandono <> ''
        ORDER BY escola.nome ASC, turma.nome_turma ASC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param(str_repeat('i', count($escolas) + count($classes)), ...$escolas, ...$classes);
$stmt->execute();
$result = $stmt->get_result();

// Total de alunos que abandonaram
$total_alunos = $result->num_rows;

// Contagem de alunos por gênero
$sql_genero = "SELECT genero, COUNT(*) as total 
               FROM aluno
               WHERE escola_id IN ($escola_placeholders) 
                 AND classe_id IN ($classe_placeholders) 
                 AND motivo_abandono IS NOT NULL
                   AND aluno.motivo_abandono <> ''
               GROUP BY genero";
$stmt_genero = $mysqli->prepare($sql_genero);
$stmt_genero->bind_param(str_repeat('i', count($escolas) + count($classes)), ...$escolas, ...$classes);
$stmt_genero->execute();
$result_genero = $stmt_genero->get_result();

$total_masculino = 0;
$total_feminino = 0;
while ($row = $result_genero->fetch_assoc()) {
    if ($row['genero'] === 'Masculino') {
        $total_masculino = $row['total'];
    } elseif ($row['genero'] === 'Feminino') {
        $total_feminino = $row['total'];
    }
}
?>

<div class="dashboard-tables-charts">
    <div class="table-card">
        <h3 class="table-title">
            Lista dos(as) Alunos(as) que <span class="highlight">Abandonaram a Escola</span> 
            | Total: <span class="count total"><?php echo $total_alunos; ?></span>
            | Masculino: <span class="count masculino"><?php echo $total_masculino; ?></span>
            | Feminino: <span class="count feminino"><?php echo $total_feminino; ?></span>
        </h3>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Escola</th>
                    <th>Turma</th>
                    <th>Curso</th>
                    <th>Nome</th>
                    <th>Gênero</th>
                    <th>Idade</th>
                    <th>Nº Ordem</th>
                    <th>B.I</th>
                    <th>Nº Frequência</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>S.Económica</th>
                    <th>T.Encarregado</th>
                    <th>Motivo</th>
                    <th>Estrat. Recuperaçao</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['escola_nome'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['turma_nome'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['curso_nome'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['genero'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['idade'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['numero_ordem'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['bi'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['numero_frequencia'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['endereco'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefone'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['situacao_economica'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['contato_encarregado'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['motivo_abandono'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['estrategia_recuperacao'] ?? '') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='15'>Nenhum aluno encontrado.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$stmt->close();
$stmt_genero->close();
$mysqli->close();
?>
