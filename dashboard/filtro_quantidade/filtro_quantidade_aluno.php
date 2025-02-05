
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Filtro de Alunos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    /* Estilo básico para a página */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f4f8;
    margin: 0;
    padding: 20px;
    color: #333;
}

.filter-container {
    margin: 20px auto;
    padding: 40px;
    max-width: 1200px;
    background-color: #ffffff;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    text-align: center;
}

.filter-container h2 {
    color: #4e73df;
    margin-bottom: 30px;
    font-size: 28px;
    font-weight: 600;
    letter-spacing: 1px;
}

.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.form-group {
    flex: 1 1 220px;
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
    min-width: 220px;
}

.form-group label {
    font-weight: 500;
    margin-bottom: 8px;
    color: #4e73df;
    font-size: 14px;
    text-align: left;
}

.form-group select,
.form-group input {
    padding: 12px;
    font-size: 16px;
    border: 2px solid #ddd;
    border-radius: 8px;
    background-color: #fafafa;
    transition: border-color 0.3s ease;
}

.form-group select:focus,
.form-group input:focus {
    border-color: #4e73df;
    outline: none;
}

.form-group input[type="number"] {
    -moz-appearance: textfield; /* Remove the spinner in number inputs */
}

/* Estilo do botao */
.form-group button {
    padding: 12px 25px;
    font-size: 16px;
    background-color: #4e73df;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: 600;
}

.form-group button:hover {
    background-color: #3651a3;
}

/* Link para limpar filtros */
.form-group a {
    padding: 12px 25px;
    font-size: 16px;
    color: #fff;
    background-color: #e74a3b;
    text-decoration: none;
    border-radius: 8px;
    display: inline-block;
    text-align: center;
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.form-group a:hover {
    opacity: 0.9;
}

/* Estilo da tabela */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
    margin-top: 30px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 14px;
    text-align: left;
    border-bottom: 2px solid #f0f0f0;
}

th {
    font-size: 18px;
    background-color: #4e73df;
    color: #fff;
    font-weight: 600;
}

td {
    background-color: #f9f9f9;
    font-size: 15px;
}

tr:hover {
    background-color: #f1f1f1;
}

tr:nth-child(even) td {
    background-color: #f9f9f9;
}

/* Responsividade */
@media (max-width: 768px) {
    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }
    .form-group {
        width: 100%;
        margin-bottom: 15px;
    }
    .form-group button, .form-group a {
        width: 100%;
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


</head>
<body>

<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>



<!-- Adicione um espaçamento para compensar o botao fixo -->
<div style="margin-top: 60px;"></div>




<div class="filter-container">
    <h2>Filtrar Alunos em Quantidade</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="distrito_id">Distrito:</label>
            <select id="distrito_id" name="distrito_id" onchange="updateSchools(this.value)">
                <option value="">--Selecione um distrito--</option>
                <option value="todos">TODOS</option> <!-- Opçao "Todas" -->
                <?php
              // Conexao com o banco de dados
                include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

                // Carregar os distritos
                $distritos = $mysqli->query("SELECT id, nome_distrito FROM distrito");
                while ($distrito = $distritos->fetch_assoc()) {
                    echo "<option value='{$distrito['id']}'>{$distrito['nome_distrito']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="escola_id">Escola:</label>
            <select id="escola_id" name="escola_id">
                <option value="">--Selecione uma escola--</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="classe_id">Classe:</label>
            <select id="classe_id" name="classe_id">
                <option value="">--Selecione uma classe--</option>
                <?php
                // Carregar as classes
                $classes = $mysqli->query("SELECT id, nivel_classe FROM classe");
                while ($classe = $classes->fetch_assoc()) {
                    echo "<option value='{$classe['id']}'>{$classe['nivel_classe']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="turma_id">Turma:</label>
            <select id="turma_id" name="turma_id">
                <option value="">--Selecione uma turma--</option>
                <?php
                // Carregar as turmas
                $turmas = $mysqli->query("SELECT id, nome_turma FROM turma");
                while ($turma = $turmas->fetch_assoc()) {
                    echo "<option value='{$turma['id']}'>{$turma['nome_turma']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="curso_id">Curso:</label>
            <select id="curso_id" name="curso_id">
                <option value="">--Selecione um curso--</option>
                <?php
                // Carregar os cursos
                $cursos = $mysqli->query("SELECT id, nome_area FROM curso");
                while ($curso = $cursos->fetch_assoc()) {
                    echo "<option value='{$curso['id']}'>{$curso['nome_area']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="periododia_id">Periodo do Dia:</label>
            <select id="periododia_id" name="periododia_id">
                <option value="">--Selecione um periodo do dia--</option>
                <?php
                // Carregar os periodos do dia
                $periodos = $mysqli->query("SELECT id, descricao FROM periodo_dia");
                while ($periodo = $periodos->fetch_assoc()) {
                    echo "<option value='{$periodo['id']}'>{$periodo['descricao']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="genero">Gênero:</label>
            <select id="genero" name="genero">
                <option value="">--Selecione um gênero--</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
            </select>
        </div>

        <div class="form-group">
            <label for="idade">Idade:</label>
            <select id="idade_operador" name="idade_operador">
                <option value="=">=</option>
                <option value=">">Maior que</option>
                <option value="<">Menor que</option>
            </select>
            <input type="number" id="idade" name="idade" placeholder="Digite a idade" min="0" />
        </div>

        <div class="form-group">
            <label for="numero_frequencia">Número de Frequência:</label>
            <input type="number" id="numero_frequencia" name="numero_frequencia" placeholder="Digite o número de frequência" min="0"/>
        </div>

        <div class="form-group">
            <label for="id_distrito">Distrito do(a) Aluno(a):</label>
            <select id="id_distrito" name="id_distrito">
                <option value="">Selecione um distrito</option>
                <?php
                // Carregar os distritos
                $distritoss = $mysqli->query("SELECT id, nome_distrito FROM distrito");
                while ($distrito = $distritoss->fetch_assoc()) {
                    echo "<option value='{$distrito['id']}'>{$distrito['nome_distrito']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <button type="submit">Filtrar</button>
            <a href="filtro_quantidade_aluno.php">Limpar Filtros</a>
        </div>
    </form>
</div>

<?php
$distrito_id = $_POST['distrito_id'] ?? null;
$escola_id = $_POST['escola_id'] ?? null;
$classe_id = $_POST['classe_id'] ?? null;
$turma_id = $_POST['turma_id'] ?? null;
$curso_id = $_POST['curso_id'] ?? null;
$periodo_dia_id = $_POST['periododia_id'] ?? null;
$genero = $_POST['genero'] ?? null;
$idade = $_POST['idade'] ?? null;
$numero_frequencia = $_POST['numero_frequencia'] ?? null;
$id_distrito = $_POST['id_distrito'] ?? null;

// Consulta para contar os resultados filtrados
$sql = "SELECT COUNT(*) as total, 
               SUM(nome IS NOT NULL) as nome,
               SUM(genero IS NOT NULL) as genero,
               SUM(idade IS NOT NULL) as idade,
               SUM(numero_frequencia IS NOT NULL) as numero_frequencia,
               COUNT(DISTINCT escola_id) As escola,
               COUNT(DISTINCT turma_id) AS turma,
               SUM(curso_id IS NOT NULL) as curso,
               SUM(situacao_economica IS NOT NULL) as situacao_economica,
               SUM(endereco IS NOT NULL) as endereco,
               COUNT(DISTINCT id_distrito) AS distrito
        FROM aluno
        WHERE 1=1";

// Adiciona filtros conforme preenchidos
$params = [];
$types = "";
if ($distrito_id && $distrito_id !== 'todos') {
    $sql .= " AND escola_id IN (SELECT id FROM escola WHERE distrito_id = ?)";
    $params[] = $distrito_id;
    $types .= "i";
}
if ($escola_id) {
    $sql .= " AND escola_id = ?";
    $params[] = $escola_id;
    $types .= "i";
}
if ($classe_id) {
    $sql .= " AND classe_id = ?";
    $params[] = $classe_id;
    $types .= "i";
}
if ($turma_id) {
    $sql .= " AND turma_id = ?";
    $params[] = $turma_id;
    $types .= "i";
}
if ($curso_id) {
    $sql .= " AND curso_id = ?";
    $params[] = $curso_id;
    $types .= "i";
}
if ($periodo_dia_id) {
    $sql .= " AND periododia_id = ?";
    $params[] = $periodo_dia_id;
    $types .= "i";
}
if ($genero) {
    $sql .= " AND genero = ?";
    $params[] = $genero;
    $types .= "s";
}
$idade = $_POST['idade'] ?? null;
$idade_operador = $_POST['idade_operador'] ?? '='; // Padrao é "="

// Modificar a consulta SQL para aplicar o operador na condiçao de idade
if ($idade) {
    if (in_array($idade_operador, ['=', '>', '<'])) { // Garantir que o operador é válido
        $sql .= " AND idade {$idade_operador} ?";
        $params[] = $idade;
        $types .= "i";
    }
}
if ($numero_frequencia !== '') {
    $sql .= " AND numero_frequencia = ?";
    $params[] = $numero_frequencia;
    $types .= "s";
}
if ($id_distrito) {
    $sql .= " AND id_distrito = ?";
    $params[] = $id_distrito;
    $types .= "i";
}

$stmt = $mysqli->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$count_result = $stmt->get_result()->fetch_assoc();

echo "<table>
        <tr>
            <th>Total Aluno</th>
            <th>Gênero</th>
            <th>Idade</th>
            <th>Número de Frequência</th>
            <th>Total Escola</th>
            <th>Turma</th>
            <th>Curso</th>
            <th>Situaçao Econômica</th>
            <th>Endereço</th>
            <th>Distrito</th>
        </tr>
        <tr>
            <td>{$count_result['nome']}</td>
            <td>{$count_result['genero']}</td>
            <td>{$count_result['idade']}</td>
            <td>{$count_result['numero_frequencia']}</td>
            <td>{$count_result['escola']}</td>
            <td>{$count_result['turma']}</td>
            <td>{$count_result['curso']}</td>
            <td>{$count_result['situacao_economica']}</td>
            <td>{$count_result['endereco']}</td>
            <td>{$count_result['distrito']}</td>
        </tr>
      </table>";

$mysqli->close();
?>

</body>
</html>

<script>
    // Funçao para atualizar o select de escolas com base no distrito selecionado
    function updateSchools(distritoId) {
        const escolaSelect = document.getElementById('escola_id');
        escolaSelect.innerHTML = ''; // Limpa o select de escolas

        if (distritoId) {
            fetch(`get_escolas.php?distrito_id=${distritoId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(escola => {
                        const option = document.createElement('option');
                        option.value = escola.id; // Supondo que cada escola tem um ID
                        option.textContent = escola.nome; // Supondo que cada escola tem um nome
                        escolaSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Erro ao carregar escolas:', error));
        }
    }
</script>
