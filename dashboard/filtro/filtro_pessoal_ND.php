
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
?>
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

<!-- CSS do Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS do Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>













<!-- HTML -->
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>
<div style="margin-top: 60px;"></div>

<div class="dashboard-content">
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
            $escolasQuery = "SELECT id, nome FROM escola ORDER BY nome ASC";
            $escolasResult = $mysqli->query($escolasQuery);
            while ($escola = $escolasResult->fetch_assoc()) {
                $selected = ($escolaFiltro == $escola['nome']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($escola['nome']) . "' $selected>" . htmlspecialchars($escola['nome']) . "</option>";
            }
            ?>
        </select>

        <label for="distrito">Distrito:</label>
        <select name="distrito" id="distrito">
            <option value="">Selecione um Distrito</option>
            <?php
            $distritosQuery = "SELECT nome_distrito FROM distrito ORDER BY nome_distrito ASC";
            $distritosResult = $mysqli->query($distritosQuery);
            while ($distrito = $distritosResult->fetch_assoc()) {
                $selected = ($distritoFiltro == $distrito['nome_distrito']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($distrito['nome_distrito']) . "' $selected>" . htmlspecialchars($distrito['nome_distrito']) . "</option>";
            }
            ?>
        </select>

        <label for="idade">Idade:</label>
        <input type="number" name="idade" id="idade" value="<?= htmlspecialchars($idadeFiltro) ?>">

        <input type="submit" value="Filtrar">
    </form>
</div>

<div class="container mt-4">
        <!-- Card com titulo e formulário -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-cogs"></i> Selecione os campos que deseja exibir:
            </div>
            <div class="card-body">
                <form id="field-selector-form">
                    <?php 
                    $fields = [
                        'id' => 'ID',
                        'nome' => 'Nome',
                        'contacto' => 'Contato',
                        'endereco' => 'Endereço',
                        'idade' => 'Idade',
                        'nif' => 'NIF',
                        'genero' => 'Gênero',
                        'data_contrato' => 'Data do Contrato',
                        'funcao' => 'Funçao',
                        'estado_civil' => 'Estado Civil',
                        'numero_conta_bancaria' => 'Nº Conta Bancária',
                        'ano_servico' => 'Ano de Serviço',
                        'ano_inicio_servico' => 'Ano de Inicio de Serviço',
                        'nivel_academico' => 'Nivel Acadêmico',
                        'nome_distrito' => 'Nome do Distrito',
                        'nome_escola' => 'Nome da Escola',
                        'nome_religiao' => 'Religiao'
                    ];
                    foreach ($fields as $field => $label) {
                        echo "<div class='form-check'>
                                <input type='checkbox' class='form-check-input field-checkbox' id='$field' value='$field' checked>
                                <label class='form-check-label field-label' for='$field'>$label</label>
                              </div>";
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
<br>

<div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#"  class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download" ></i> Baixar Tabela
    </a>
</div>


<div class="dashboard-tables-charts">
    <div class="table-card">
        <h3 class="table-title" style="text-align: center;">Lista de Pessoal Nao Docente</h3>
        <table id="table-results" class="styled-table">
            <thead>
                <tr>
                    <?php foreach ($fields as $field => $label): ?>
                        <th class="<?= $field ?>"><?= $label ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($fields as $field => $label) {
                            echo "<td class='$field'>" . htmlspecialchars($row[$field]) . "</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='17'>Nenhum registro encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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
    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet JS" });
    XLSX.writeFile(wb, 'Relatorio.xlsx');
}
</script>

<?php
$mysqli->close();
?>
