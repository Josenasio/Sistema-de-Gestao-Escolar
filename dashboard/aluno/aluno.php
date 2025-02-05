<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Parâmetros de exemplo para filtro (usando arrays para vários valores)
$escolas = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35]; // IDs das escolas desejadas
$classes = [1, 2, 3, 4, 5, 6]; // IDs das classes desejadas

// Preparando a consulta SQL dinâmica
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

// Variável para armazenar os totais de cada classe
$total_por_classe = [];

// Consultando o total de alunos por classe
foreach ($classes as $classe_id) {
    $sql_total_classe = "SELECT COUNT(*) AS total_alunos FROM aluno WHERE classe_id = ?";
    $stmt_total_classe = $mysqli->prepare($sql_total_classe);
    $stmt_total_classe->bind_param('i', $classe_id);
    $stmt_total_classe->execute();
    $result_total_classe = $stmt_total_classe->get_result();
    $total_por_classe[$classe_id] = $result_total_classe->fetch_assoc()['total_alunos'];
    $stmt_total_classe->close();
}

// Atribuindo as variáveis totais das classes
$total_classe1 = isset($total_por_classe[1]) ? $total_por_classe[1] : 0;
$total_classe2 = isset($total_por_classe[2]) ? $total_por_classe[2] : 0;
$total_classe3 = isset($total_por_classe[3]) ? $total_por_classe[3] : 0;
$total_classe4 = isset($total_por_classe[4]) ? $total_por_classe[4] : 0;
$total_classe5 = isset($total_por_classe[5]) ? $total_por_classe[5] : 0;
$total_classe6 = isset($total_por_classe[6]) ? $total_por_classe[6] : 0;

// Fechando a conexao principal
$stmt->close();
$mysqli->close();
?>




<style>
    .dashboard-content {
  padding: 20px;
}

.dashboard-cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.card {
  flex: 1 1 220px;
  padding: 20px;
  border-radius: 5px;
  color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.card.blue { background-color: #282C4B; }
.card.orange { background-color: #ff6d00; }
.card.green { background-color: #2e7d32; }
.card.red { background-color: #d50000; }
.card.purple { background-color: #7b1fa2; }

.table-card {
  flex: 1;
  margin-top: 20px;
}

.styled-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 16px;
}

.styled-table thead tr {
  background-color: #263043;
  color: #ffffff;
  text-align: left;
}

.styled-table th, .styled-table td {
  padding: 12px 15px;
}

.styled-table tbody tr:nth-child(even) {
  background-color: #1d2634;
}

.status.completed {
  color: #2e7d32;
}

.status.pending {
  color: #ff6d00;
}

.charts-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 20px;
}

.chart-card {
  flex: 1 1 300px;
  padding: 20px;
  border-radius: 5px;
  background-color: #263043;
  color: #ffffff;

}




.styled-table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}
.styled-table th, .styled-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
.styled-table th {
    background-color: #f4f4f4;
    color: #333;
}
.styled-table tr:nth-child(even) {
    background-color: #f9f9f9;
}
.styled-table tr:hover {
    background-color: black;
}

.dashboard-cards .card:hover {
  background: #363c6c;

  
}



h3 {
    font-size: 35px;               /* Tamanho da fonte */
    font-weight: bold;             /* Deixa o texto em negrito */
    color: black;                /* Cor do texto - verde */
    
            /* Espaçamento interno */
           /* Bordas arredondadas */
              /* Alinha o texto ao centro */
   
            /* Margem superior e inferior */
}


</style>


<!-- dashboard.html -->
<div class="dashboard-content">
 
    <!-- Dashboard Title -->
    <div class="main-title">
      <h2>Aluno</h2>
    </div>
  
    <!-- Cards Section -->
    <div class="dashboard-cards" >
      <div class="card blue" onclick="loadPage('/destp_pro/dashboard/aluno/card/card12classe.php')">
        <div class="card-inner">
        <h3><?php echo $total_classe6; ?></h3>
          <span class="material-icons-outlined">person</span>
        </div>
        <h1>12ª Classe</h1>
      </div>

  
      <div class="card blue"  onclick="loadPage('/destp_pro/dashboard/aluno/card/card11classe.php')">
        <div class="card-inner">
        <h3><?php echo $total_classe5; ?></h3>
          <span class="material-icons-outlined">person</span>
        </div>
        <h1>11ª Classe</h1>
      </div>
  
      <div class="card blue" onclick="loadPage('/destp_pro/dashboard/aluno/card/card10classe.php')">
        <div class="card-inner">
        <h3><?php echo $total_classe4; ?></h3>
     <span class="material-icons-outlined">person</span>
        </div>
        <h1>10ª Classe</h1>
      </div>
  
      <div class="card blue" onclick="loadPage('/destp_pro/dashboard/aluno/card/card9classe.php')">
        <div class="card-inner">
        <h3><?php echo $total_classe3; ?></h3>
          <span class="material-icons-outlined">person</span>
        </div>
        <h1>9ª Classe</h1>
      </div>
  
      <div class="card blue" onclick="loadPage('/destp_pro/dashboard/aluno/card/card8classe.php')">
        <div class="card-inner">
        <h3><?php echo $total_classe2; ?></h3>
          <span class="material-icons-outlined">person</span>
        </div>
        <h1>8ª Classe</h1>
      </div>

      <div class="card blue" onclick="loadPage('/destp_pro/dashboard/aluno/card/card7classe.php')">
        <div class="card-inner">
        <h3><?php echo $total_classe1; ?></h3>

          <span class="material-icons-outlined">person</span>
        </div>
        <h1>7ª Classe</h1>
      </div>

      <div class="card purple" onclick="loadPage('/destp_pro/dashboard/aluno/card/carddeficiente.php')">
  <div class="card-inner">
    <h3>ALERTS</h3>
    <span class="material-icons-outlined">accessible</span> <!-- Ícone relacionado à deficiência -->
  </div>
  <h1>Deficiente</h1>
</div>

<div class="card purple" onclick="loadPage('/destp_pro/dashboard/aluno/card/cardgravidez.php')">
  <div class="card-inner">
    <h3>ALERTS</h3>
    <span class="material-icons-outlined">pregnant_woman</span> <!-- Ícone relacionado à gravidez -->
  </div>
  <h1>Gravidez</h1>
</div>

<div class="card purple" onclick="loadPage('/destp_pro/dashboard/aluno/card/cardabandono.php')">
  <div class="card-inner">
    <h3>ALERTS</h3>
    <span class="material-icons-outlined">exit_to_app</span> <!-- Ícone relacionado ao abandono/desistência -->
  </div>
  <h1>Desistiu</h1>
</div>




    </div>
  
   <!-- Estrutura HTML para exibir a tabela -->
   <div class="dashboard-tables-charts">
        <div class="table-card">
            <h3 class="table-title">Lista de Alunos</h3>
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
                    // Exibindo os dados dos alunos na tabela
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
                        echo "<tr><td colspan='9'>Nenhum aluno encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
        </table>
    </div>
</div>
  
      <!-- Charts Section -->
      <div class="charts-container">
        <div class="chart-card">
          <h3 class="chart-title">Sales by Category</h3>
          <div id="pie-chart"></div>
        </div>
  
        <div class="chart-card">
          <h3 class="chart-title">Weekly Sales Trends</h3>
          <div id="line-chart"></div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    // Funçao para carregar gráficos após o conteúdo
function loadDashboardCharts() {
  // PIE CHART
  const pieChartOptions = {
    series: [44, 55, 13, 33],
    chart: {
      type: 'pie',
      height: 350,
    },
    labels: ['Electronics', 'Clothing', 'Furniture', 'Others'],
    colors: ['#2962ff', '#ff6d00', '#2e7d32', '#d50000'],
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          width: 200
        },
        legend: {
          position: 'bottom'
        }
      }
    }]
  };
  const pieChart = new ApexCharts(document.querySelector("#pie-chart"), pieChartOptions);
  pieChart.render();

  // LINE CHART
  const lineChartOptions = {
    series: [
      {
        name: 'Sales',
        data: [31, 40, 28, 51, 42, 109, 100]
      },
      {
        name: 'Revenue',
        data: [11, 32, 45, 32, 34, 52, 41]
      }
    ],
    chart: {
      height: 350,
      type: 'line',
    },
    colors: ['#2962ff', '#d50000'],
    stroke: {
      width: [5, 5]
    },
    xaxis: {
      categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    }
  };
  const lineChart = new ApexCharts(document.querySelector("#line-chart"), lineChartOptions);
  lineChart.render();
}

// Execute ao carregar a página do dashboard
document.addEventListener('DOMContentLoaded', loadDashboardCharts);

  </script>



