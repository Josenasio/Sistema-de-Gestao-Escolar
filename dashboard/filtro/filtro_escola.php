<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Inicializa a variável de filtro para o distrito
$distritoFiltro = isset($_GET['distrito']) ? $_GET['distrito'] : '';

// Consulta para selecionar dados da tabela escola junto com o nome do distrito
$sql = "
    SELECT 
        p.id, 
        p.nome, 
        p.telefone_fixo, 
        p.nome_diretor, 
        p.email_escola, 
        p.agua_consumida, 
        p.abastecimento_energia, 
        p.destinacao_lixo, 
        p.numero_computador, 
        p.numero_computador_funcionamento,
        p.acesso_internet,
        p.acesso_banda_larga, 
        p.alimentacao, 
        p.vedacao, 
        p.via_aluno_deficiente, 
        p.biblioteca,
        p.anfiteatro,
        p.cantina, 
        p.ginasio, 
        p.campo_desportivo, 
        p.numero_wc_professor,
        p.numero_wc_diretor,
        p.numero_wc_aluno,
        p.numero_wc_aluna, 
        p.laboratorio_fisica, 
        p.laboratorio_quimica, 
        p.laboratorio_biologia,
        p.sala_informatica,
        p.sala_professor,
        p.numero_sala_aula_existente, 
        p.wc_masculino_feminino, 
        p.7manha, 
        p.7tarde,
        p.8manha, 
        p.8tarde,
        p.9manha, 
        p.9tarde,
        p.10manha, 
        p.10tarde,
        p.11manha, 
        p.11tarde,
        p.12manha, 
        p.12tarde,
        d.id AS distrito_id,
        d.nome_distrito
    FROM 
        escola AS p
    JOIN 
        distrito AS d ON p.distrito_id = d.id
    WHERE 1=1
";

// Adiciona filtro se for selecionado um distrito
if ($distritoFiltro != '') {
    $sql .= " AND d.nome_distrito = '" . $mysqli->real_escape_string($distritoFiltro) . "'";
}

$sql .= " ORDER BY p.id ASC";

$result = $mysqli->query($sql);
?>






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
     XLSX.utils.book_append_sheet(wb, ws, 'Escolas Escola');
     XLSX.writeFile(wb, 'Relatorio_Escola.xlsx');
 }




    </script>







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



<div class="dashboard-content">
 
    <!-- Titulo do Dashboard -->
    <div class="main-title">
        <h2>Descriçao das Escolas</h2>
    </div>

    <!-- Filtro por Distrito -->
    <div class="filter-container">
        <label for="distrito">Distrito:</label>
        <select name="distrito" id="distrito" onchange="window.location.href='?distrito=' + this.value;">
            <option value="">Selecione um Distrito</option>
            <?php
            // Consulta para pegar os distritos
            $distritosQuery = "SELECT nome_distrito FROM distrito ORDER BY nome_distrito ASC";
            $distritosResult = $mysqli->query($distritosQuery);

            // Verifica se há distritos no banco
            while ($distrito = $distritosResult->fetch_assoc()) {
                $selected = ($distritoFiltro == $distrito['nome_distrito']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($distrito['nome_distrito']) . "' $selected>" . htmlspecialchars($distrito['nome_distrito']) . "</option>";
            }
            ?>
        </select>
    </div>



    <div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#" class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download"></i> Baixar Tabela
    </a>
</div>


    <!-- Tabela de Escolas -->
    <div class="dashboard-tables-charts">
        <div class="table-card">
            <h3 class="table-title">Lista das Escolas</h3>
            <table id="table-results" class="styled-table">
                <thead>
                    <tr>
                        <th class="field-checkbox"  value="id">ID</th>
                        <th class="field-checkbox"  value="nome">Nome</th>
                        <th class="field-checkbox"  value="telefone_fixo">Telefone Fixo</th>
                        <th class="field-checkbox"  value="nome_diretor">Nome do Diretor</th>
                        <th class="field-checkbox"  value="email_escola">Email da Escola</th>
                        <th class="field-checkbox"  value="agua_consumida">Água Consumida</th>
                        <th class="field-checkbox"  value="abastecimento_energia">Abastecimento de Energia</th>
                        <th class="field-checkbox"  value="destinacao_lixo">Destinaçao de Lixo</th>
                        <th class="field-checkbox"  value="numero_computador">Número de Computador</th>
                        <th class="field-checkbox"  value="numero_computador_funcionamento">Computador em Funcionamento</th>
                        <th class="field-checkbox"  value="acesso_internet">Acesso à Internet</th>
                        <th class="field-checkbox"  value="acesso_banda_larga">Acesso à Banda Larga</th>
                        <th class="field-checkbox"  value="alimentacao">Alimentaçao</th>
                        <th class="field-checkbox"  value="vedacao">Vedaçao</th>
                        <th class="field-checkbox"  value="via_aluno_deficiente">Via Aluno Deficiente</th>
                        <th class="field-checkbox"  value="biblioteca">Biblioteca</th>
                        <th class="field-checkbox"  value="anfiteatro">Anfiteatro</th>
                        <th class="field-checkbox"  value="cantina">Cantina</th>
                        <th class="field-checkbox"  value="ginasio">Ginásio</th>
                        <th class="field-checkbox"  value="campo_desportivo">Campo Desportivo</th>
                        <th class="field-checkbox"  value="numero_wc_professor">Número de WC Professor</th>
                        <th class="field-checkbox"  value="numero_wc_diretor">Número de WC Diretor</th>
                        <th class="field-checkbox"  value="numero_wc_aluno">Número de WC Aluno</th>
                        <th class="field-checkbox"  value="numero_wc_aluna">Número de WC Aluna</th>
                        <th class="field-checkbox"  value="laboratorio_fisica">Laboratório de Fisica</th>
                        <th class="field-checkbox"  value="laboratorio_quimica">Laboratório de Quimica</th>
                        <th class="field-checkbox"  value="laboratorio_biologia">Laboratório de Biologia</th>
                        <th class="field-checkbox"  value="sala_informatica">Sala de Informática</th>
                        <th class="field-checkbox"  value="sala_professor">Sala de Professor</th>
                        <th class="field-checkbox"  value="numero_sala_aula_existente">Número de Sala de Aula Existente</th>
                        <th class="field-checkbox"  value="wc_masculino_feminino">WC Masculino/Feminino</th>
                        <th class="field-checkbox"  value="7manha">7º Manha</th>
                        <th class="field-checkbox"  value="7tarde">7º Tarde</th>
                        <th class="field-checkbox"  value="8manha">8º Manha</th>
                        <th class="field-checkbox"  value="8tarde">8º Tarde</th>
                        <th class="field-checkbox"  value="9manha">9º Manha</th>
                        <th class="field-checkbox"  value="9tarde">9º Tarde</th>
                        <th class="field-checkbox"  value="10manha">10º Manha</th>
                        <th class="field-checkbox"  value="10tarde">10º Tarde</th>
                        <th class="field-checkbox"  value="11manha">11º Manha</th>
                        <th class="field-checkbox"  value="11tarde">11º Tarde</th>
                        <th class="field-checkbox"  value="12manha">12º Manha</th>
                        <th class="field-checkbox"  value="12tarde">12º Tarde</th>
                  
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Exibindo os dados da escola na tabela
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['telefone_fixo'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nome_diretor'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['email_escola'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['agua_consumida'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['abastecimento_energia'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['destinacao_lixo'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_computador'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_computador_funcionamento'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['acesso_internet'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['acesso_banda_larga'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['alimentacao'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['vedacao'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['via_aluno_deficiente'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['biblioteca'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['anfiteatro'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['cantina'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['ginasio'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['campo_desportivo'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_wc_professor'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_wc_diretor'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_wc_aluno'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_wc_aluna'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['laboratorio_fisica'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['laboratorio_quimica'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['laboratorio_biologia'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['sala_informatica'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['sala_professor'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_sala_aula_existente'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['wc_masculino_feminino'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['7manha'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['7tarde'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['8manha'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['8tarde'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['9manha'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['9tarde'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['10manha'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['10tarde'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['11manha'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['11tarde'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['12manha'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['12tarde'] ?? '') . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
