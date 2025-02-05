<?php
// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Consulta para selecionar dados da tabela escola junto com o nome do distrito
$sql = "SELECT escola.id, escola.nome, escola.telefone, escola.email, escola.codigo, 
               escola.localizacao, escola.endereco, distrito.nome_distrito 
        FROM escola 
        JOIN distrito ON escola.distrito_id = distrito.id 
        ORDER BY escola.id ASC";
$result = $mysqli->query($sql);
?>

<style>
/* Estilos CSS para a tabela e os cartões */
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

.dashboard-cards .card:hover {
  background: #363c6c;
}
</style>

<div class="dashboard-content">
    <div class="main-title">
      <h2>Escola</h2>
    </div>

    <div class="dashboard-tables-charts">
        <div class="table-card">
            <h3 class="table-title">Lista de Escolas</h3>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Código</th>
                        <th>Localizaçao</th>
                        <th>Endereço</th>
                        <th>Distrito</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Exibindo os dados das escolas na tabela
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['telefone'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['codigo'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['localizacao'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['endereco'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome_distrito']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Nenhuma escola encontrada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Fechar a conexao com o banco de dados
$result->close();
$mysqli->close();
?>
