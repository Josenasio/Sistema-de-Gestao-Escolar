<?php  
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Inicializa as variáveis de filtro
$generoFiltro = isset($_GET['genero']) ? $_GET['genero'] : '';
$escolaFiltro = isset($_GET['escola']) ? $_GET['escola'] : '';
$distritoFiltro = isset($_GET['distrito']) ? $_GET['distrito'] : '';
$idadeFiltro = isset($_GET['idade']) ? $_GET['idade'] : '';

// Consulta para selecionar dados da tabela escola junto com o nome do distrito
$sql = "
    SELECT 
        p.id, 
        p.nome, 
        p.contacto, 
        p.endereco, 
        p.idade, 
        p.nif, 
        p.genero, 
        p.data_contrato, 
        p.funcao, 
        p.estado_civil,
        p.numero_conta_bancaria,
        p.ano_servico, 
        p.ano_inicio_servico, 
        p.nivel_academico, 
        d.nome_distrito, 
        e.nome AS nome_escola, 
        r.nome_religiao
    FROM 
        pessoal_nao_docente AS p
    JOIN 
        escola AS e ON p.escola_id = e.id
    JOIN 
        distrito AS d ON e.distrito_id = d.id
    LEFT JOIN 
        religiao AS r ON p.religiao_id = r.id
    WHERE 1=1
";

// Adiciona filtros à consulta
if ($generoFiltro != '') {
    $sql .= " AND p.genero = '" . $mysqli->real_escape_string($generoFiltro) . "'";
}
if ($escolaFiltro != '') {
    $sql .= " AND e.nome = '" . $mysqli->real_escape_string($escolaFiltro) . "'";
}
if ($distritoFiltro != '') {
    $sql .= " AND d.nome_distrito = '" . $mysqli->real_escape_string($distritoFiltro) . "'";
}
if ($idadeFiltro != '') {
    $sql .= " AND p.idade = " . intval($idadeFiltro);
}

$sql .= " ORDER BY e.id ASC";

$result = $mysqli->query($sql);

// Consulta para contar os dados por escola e gênero (Masculino e Feminino)
$sqlEscolaGenero = "
    SELECT 
        e.nome AS escola, 
        p.genero, 
        COUNT(*) AS total_genero
    FROM 
        pessoal_nao_docente AS p
    JOIN 
        escola AS e ON p.escola_id = e.id
    JOIN 
        distrito AS d ON e.distrito_id = d.id
    LEFT JOIN 
        religiao AS r ON p.religiao_id = r.id
    WHERE 1=1
";

// Aplica filtros no total por escola e gênero
if ($escolaFiltro != '') {
    $sqlEscolaGenero .= " AND e.nome = '" . $mysqli->real_escape_string($escolaFiltro) . "'";
}
if ($generoFiltro != '') {
    $sqlEscolaGenero .= " AND p.genero = '" . $mysqli->real_escape_string($generoFiltro) . "'";
}
if ($distritoFiltro != '') {
    $sqlEscolaGenero .= " AND d.nome_distrito = '" . $mysqli->real_escape_string($distritoFiltro) . "'";
}
if ($idadeFiltro != '') {
    $sqlEscolaGenero .= " AND p.idade = " . intval($idadeFiltro);
}

$sqlEscolaGenero .= " GROUP BY e.nome, p.genero";

// Executa a consulta
$resultEscolaGenero = $mysqli->query($sqlEscolaGenero);
?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
.dashboard-content {
  padding: 20px;
  background-color: #f4f7fc;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table-card {
  margin-top: 20px;
  padding: 20px;
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.styled-table {
  width: 100%;
  border-collapse: collapse;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
  border-radius: 8px;
}

.styled-table thead tr {
  background-color: #1e2a37;
  color: #ffffff;
  text-align: left;
  font-weight: bold;
}

.styled-table th, .styled-table td {
  padding: 14px 20px;
  text-align: left;
  border-bottom: 1px solid #e0e0e0;
  font-size: 16px;
}

.styled-table th {
  font-size: 18px;
  background-color: #2a3a52;
  color: #ffffff;
}

.styled-table tbody tr:nth-child(even) {
  background-color: #f8f8f8;
}

.styled-table tbody tr:hover {
  background-color: #e6eff6;
  cursor: pointer;
  transform: scale(1.02);
  transition: all 0.3s ease;
}

.status.completed {
  color: #2e7d32;
  font-weight: bold;
}

.status.pending {
  color: #ff6d00;
  font-weight: bold;
}

.status.in-progress {
  color: #ffb300;
  font-weight: bold;
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
  background-color: #263043;
  border-radius: 10px;
  color: #ffffff;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.chart-card:hover {
  background-color: #33475b;
  transform: translateY(-10px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.dashboard-cards .card:hover {
  background: #3b4b69;
  transform: scale(1.05);
  transition: all 0.3s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
  .charts-container {
    flex-direction: column;
  }

  .chart-card {
    flex: 1 1 100%;
    margin-bottom: 20px;
  }

  .styled-table th, .styled-table td {
    padding: 10px 15px;
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .dashboard-content {
    padding: 10px;
  }

  .styled-table th, .styled-table td {
    padding: 8px 12px;
    font-size: 12px;
  }

  .chart-card {
    padding: 15px;
  }
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


<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>



<!-- Adicione um espaçamento para compensar o botao fixo -->
<div style="margin-top: 60px;"></div>



<!-- dashboard.html -->
<div class="dashboard-content">
    <!-- Titulo do Dashboard -->
    <div class="main-title">
      <h2>Pessoal Nao Docente</h2>
    </div>
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

        <label for="genero">Gênero:</label>
        <select name="genero" id="genero">
            <option value="">Todos</option>
            <option value="Masculino" <?= ($generoFiltro == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
            <option value="Feminino" <?= ($generoFiltro == 'Feminino') ? 'selected' : '' ?>>Feminino</option>
        </select>

        <label for="escola">Escola:</label>
        <select name="escola" id="escola">
            <option value="">Selecione uma Escola</option>
            <?php
            // Consulta para pegar as escolas
            $escolasQuery = "SELECT id, nome FROM escola ORDER BY nome ASC";
            $escolasResult = $mysqli->query($escolasQuery);

            // Verifica se há escolas no banco
            while ($escola = $escolasResult->fetch_assoc()) {
                // Marca a escola como selecionada caso seja o filtro
                $selected = ($escolaFiltro == $escola['nome']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($escola['nome']) . "' $selected>" . htmlspecialchars($escola['nome']) . "</option>";
            }
            ?>
        </select>

        <label for="distrito">Distrito do Pessoal Nao Docente:</label>
        <select name="distrito" id="distrito">
            <option value="">Selecione um Distrito</option>
            <?php
            // Consulta para pegar os distritos
            $distritosQuery = "SELECT nome_distrito FROM distrito ORDER BY nome_distrito ASC";
            $distritosResult = $mysqli->query($distritosQuery);

            // Verifica se há distritos no banco
            while ($distrito = $distritosResult->fetch_assoc()) {
                // Marca o distrito como selecionado caso seja o filtro
                $selected = ($distritoFiltro == $distrito['nome_distrito']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($distrito['nome_distrito']) . "' $selected>" . htmlspecialchars($distrito['nome_distrito']) . "</option>";
            }
            ?>
        </select>

        <label for="idade">Idade:</label>
        <input type="number" name="idade" id="idade" value="<?= htmlspecialchars($idadeFiltro) ?>">

        <button type="submit">Filtrar</button>
    </form>

    <div class="table-card">
        <h3>Total por Escola (Masculino / Feminino)</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Escola</th>
                    <th>Total Masculino</th>
                    <th>Total Feminino</th>
                    <th>Total M/F</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Processa os dados e organiza por escola
                $escolas = [];
                while ($row = $resultEscolaGenero->fetch_assoc()) {
                    $escola = $row['escola'];
                    $genero = $row['genero'];
                    $totalGenero = $row['total_genero'];

                    // Organiza os totais por escola e gênero
                    if (!isset($escolas[$escola])) {
                        $escolas[$escola] = ['Masculino' => 0, 'Feminino' => 0];
                    }

                    // Atualiza o total por gênero
                    if ($genero == 'Masculino') {
                        $escolas[$escola]['Masculino'] = $totalGenero;
                    } elseif ($genero == 'Feminino') {
                        $escolas[$escola]['Feminino'] = $totalGenero;
                    }
                }

                // Exibe os totais para cada escola
                foreach ($escolas as $escola => $totais) {
                    $totalMF = $totais['Masculino'] + $totais['Feminino'];
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($escola) . "</td>";
                    echo "<td>" . $totais['Masculino'] . "</td>";
                    echo "<td>" . $totais['Feminino'] . "</td>";
                    echo "<td>" . $totalMF . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
