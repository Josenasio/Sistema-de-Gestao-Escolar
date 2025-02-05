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

</style>


<!-- dashboard.html -->
<div class="dashboard-content">
 
    <!-- Dashboard Title -->
    <div class="main-title">
      <h2>Pessoal Nao Docente</h2>
    </div>
   
     
    </div>
  
   <!-- Estrutura HTML para exibir a tabela -->
   <div class="dashboard-tables-charts">
        <div class="table-card">
            <h3 class="table-title">Lista de Pessoal Nao Docente</h3>
            <table class="styled-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Contato</th>
            <th>Endereço</th>
            <th>Idade</th>
            <th>NIF</th>
            <th>Gênero</th>
            <th>Data do Contrato</th>
            <th>Funçao</th>
            <th>Estado Civil</th>
            <th>Nº Conta Bancária</th>
            <th>Ano de Serviço</th>
            <th>Ano de Inicio de Serviço</th>
            <th>Nivel Acadêmico</th>
            <th>Nome do Distrito</th>
            <th>Nome da Escola</th>
            <th>Religiao</th>
           
        </tr>
    </thead>
    <tbody>
        <?php
        // Exibindo os dados do pessoal nao docente na tabela
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($row['contacto']) . "</td>";
                echo "<td>" . htmlspecialchars($row['endereco']) . "</td>";
                echo "<td>" . htmlspecialchars($row['idade']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nif']) . "</td>";
                echo "<td>" . htmlspecialchars($row['genero']) . "</td>";
                echo "<td>" . htmlspecialchars($row['data_contrato']) . "</td>";
                echo "<td>" . htmlspecialchars($row['funcao']) . "</td>";
                echo "<td>" . htmlspecialchars($row['estado_civil']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numero_conta_bancaria']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ano_servico']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ano_inicio_servico']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nivel_academico']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nome_distrito']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nome_escola']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nome_religiao']) . "</td>";
                
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
  
     
    </div>
  </div>
  
  


<?php
// Fechar a conexao com o banco de dados

$mysqli->close();
?>
