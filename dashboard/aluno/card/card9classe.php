<link rel="stylesheet" href="/destp_pro/dashboard/aluno/card/css/style.css">

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Definindo o filtro para somente classe 8
$escolas = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35];
$classes = [3]; // Filtro especifico para classe 8

$escola_placeholders = implode(',', array_fill(0, count($escolas), '?'));
$classe_placeholders = implode(',', array_fill(0, count($classes), '?'));

$sql = "SELECT 
            e.nome AS escola_nome,
            t.nome_turma AS turma_nome,
            c.nome_area AS curso_nome,
            a.nome, 
            a.genero, 
            a.idade, 
            a.numero_ordem, 
            a.bi, 
            a.numero_frequencia, 
            a.endereco, 
            a.telefone, 
            a.situacao_economica, 
            a.contato_encarregado
        FROM aluno a
        INNER JOIN escola e ON a.escola_id = e.id
        INNER JOIN turma t ON a.turma_id = t.id
        INNER JOIN curso c ON a.curso_id = c.id
        WHERE a.escola_id IN ($escola_placeholders) AND a.classe_id IN ($classe_placeholders)
        ORDER BY a.escola_id ASC, a.turma_id ASC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param(str_repeat('i', count($escolas) + count($classes)), ...$escolas, ...$classes);
$stmt->execute();
$result = $stmt->get_result();

// Contando o total de alunos
$total_alunos = $result->num_rows;

// Contando o total de alunos do gênero Masculino
$sql_masculino = "SELECT COUNT(*) as total_masculino
                  FROM aluno a
                  WHERE a.escola_id IN ($escola_placeholders) AND a.classe_id IN ($classe_placeholders) AND a.genero = 'Masculino'";
$stmt_masculino = $mysqli->prepare($sql_masculino);
$stmt_masculino->bind_param(str_repeat('i', count($escolas) + count($classes)), ...$escolas, ...$classes);
$stmt_masculino->execute();
$result_masculino = $stmt_masculino->get_result();
$total_masculino = $result_masculino->fetch_assoc()['total_masculino'];

// Contando o total de alunos do gênero Feminino
$sql_feminino = "SELECT COUNT(*) as total_feminino
                 FROM aluno a
                 WHERE a.escola_id IN ($escola_placeholders) AND a.classe_id IN ($classe_placeholders) AND a.genero = 'Feminino'";
$stmt_feminino = $mysqli->prepare($sql_feminino);
$stmt_feminino->bind_param(str_repeat('i', count($escolas) + count($classes)), ...$escolas, ...$classes);
$stmt_feminino->execute();
$result_feminino = $stmt_feminino->get_result();
$total_feminino = $result_feminino->fetch_assoc()['total_feminino'];
?>
<div class="dashboard-tables-charts">
<div class="table-card">
<h3 class="table-title">
    Lista de Alunos da <span class="highlight">9ª Classe</span> 
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
            </tr>
        </thead>
        <tbody>
        <?php
// Gerando as linhas da tabela filtrada
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['escola_nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['turma_nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['curso_nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['genero']) . "</td>";
        echo "<td>" . htmlspecialchars($row['idade']) . "</td>";
        echo "<td>" . htmlspecialchars($row['numero_ordem']) . "</td>";
        echo "<td>" . htmlspecialchars($row['bi']) . "</td>";
        echo "<td>" . htmlspecialchars($row['numero_frequencia']) . "</td>";
        echo "<td>" . htmlspecialchars($row['endereco']) . "</td>";
        echo "<td>" . htmlspecialchars($row['telefone']) . "</td>";
        echo "<td>" . htmlspecialchars($row['situacao_economica']) . "</td>";
        echo "<td>" . htmlspecialchars($row['contato_encarregado']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='13'>Nenhum aluno encontrado para a classe selecionada.</td></tr>";
}
?>
        </tbody>
    </table>
</div>
</div>

<?php
$stmt->close();
$mysqli->close();
?>
