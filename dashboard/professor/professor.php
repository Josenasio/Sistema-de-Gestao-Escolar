<?php
// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Consulta para selecionar dados da tabela escola junto com o nome do distrito
$sql = "SELECT professor.id, professor.nome, professor.idade, professor.telefone, professor.email, 
professor.titulo, professor.categoria_salarial, professor.funcao, professor.area_formacao1, 
professor.area_formacao2, professor.endereco, professor.data_nascimento, professor.duracao_formacao,
professor.naturalidade,
 professor.nivel_academico, professor.data_contrato, professor.genero, professor.nome_facebook,
  professor.estado_civil, distrito.nome_distrito, religiao.nome_religiao, escola.nome AS escola_nome
        FROM professor 
        JOIN distrito ON professor.distrito_id = distrito.id 
         JOIN religiao ON professor.religiao_id = religiao.id 
          JOIN escola ON professor.id_escola = escola.id 
        ORDER BY professor.id ASC";
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
      <h2>Professor</h2>
    </div>

    <div class="dashboard-tables-charts">
        <div class="table-card">
            <h3 class="table-title">Lista de Professores</h3>
            <table class="styled-table">
                <thead>
                    <tr>
                    <th>ID</th>
            <th>Nome</th>
            <th>Idade</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Titulo</th>
            <th>Categoria Salarial</th>
            <th>Funçao</th>
            <th>Área de Formaçao 1</th>
            <th>Área de Formaçao 2</th>
            <th>Endereço</th>
            <th>Data de Nascimento</th>
            <th>Duraçao da Formaçao</th>
            <th>Naturalidade</th>
            <th>Nivel Acadêmico</th>
            <th>Data de Contrato</th>
            <th>Gênero</th>
            <th>Facebook</th>
            <th>Estado Civil</th>
            <th>Distrito</th>
            <th>Religiao</th>
            <th>Escola</th>
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
                            echo "<td>" . htmlspecialchars($row['idade'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['telefone'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['titulo'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['categoria_salarial'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['funcao'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['area_formacao1'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['area_formacao2'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['endereco'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['data_nascimento'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['duracao_formacao'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['naturalidade'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nivel_academico'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['data_contrato'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['genero'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome_facebook'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['estado_civil'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome_distrito'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome_religiao'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['escola_nome'] ?? '') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='22'>Nenhuma escola encontrada.</td></tr>";
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
