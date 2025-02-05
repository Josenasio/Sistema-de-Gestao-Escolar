<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Filtro de Alunos</title>


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
     XLSX.utils.book_append_sheet(wb, ws, 'Alunos Aluno');
     XLSX.writeFile(wb, 'Relatorio_Alunos.xlsx');
 }




    </script>

    <style>
        /* Estilo básico para a página */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
         


/* Container do filtro */
.filter-container {
    margin: 20px;
    padding: 15px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: Arial, sans-serif;
}

/* Título */
.filter-container h2 {
    margin-bottom: 15px;
    font-size: 1.5rem;
    color: #333;
    text-align: center;
}

/* Estilo geral do formulário */
.filter-container form {
    display: flex;
    flex-wrap: wrap; /* Responsivo: permite quebra de linha */
    gap: 15px; /* Espaçamento entre itens */
    justify-content: center;
}

/* Grupos de formulário */
.filter-container .form-group {
    display: flex;
    flex-direction: column;
    min-width: 200px; /* Define largura mínima */
    max-width: 300px;
    flex: 1;
}

/* Estilo dos rótulos */
.filter-container label {
    margin-bottom: 5px;
    font-size: 1rem;
    color: #555;
}

/* Estilo dos campos */
.filter-container select,
.filter-container input {
    padding: 8px;
    font-size: 0.9rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
    box-sizing: border-box; /* Inclui padding e border na largura */
}

/* Botão de filtrar */
.filter-container button {
    padding: 10px 20px;
    font-size: 1rem;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Hover no botão */
.filter-container button:hover {
    background-color: #0056b3;
}

/* Ajustes para telas menores */
@media (max-width: 600px) {
    .filter-container .form-group {
        min-width: 100%; /* Cada campo ocupa 100% da linha */
    }

    .filter-container button {
        width: 100%; /* Botão ocupa toda a largura */
    }
}



        
        table {
            width: 100%;
  border-collapse: collapse;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
  border-radius: 8px;
        }
        th, td {
            padding: 14px 20px;
  text-align: left;
  border-bottom: 1px solid #e0e0e0;
  font-size: 16px;
        }
        th {
            font-size: 18px;
  background-color: #2a3a52;
  color: #ffffff;
        }


        td {
            font-size: 18px;
  background-color: #2a3a52;
  color: #ffffff;
        }
        tr:hover {
            background-color: #f9f9f9;
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
</head>
<body>




<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>



<!-- Adicione um espaçamento para compensar o botao fixo -->
<div style="margin-top: 60px;"></div>



<div class="filter-container">
    <h2>Filtrar Alunos</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="distrito_id">Distrito:</label>
            <select id="distrito_id" name="distrito_id" onchange="updateSchools(this.value)">
                <option value="">Selecione um distrito</option>
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
            <select id="escola_id" name="escola_id" onchange="updateSchools(this.value)">
                <option value="">Selecione uma escola</option>
                <option value="todos">TODOS</option> <!-- Opçao "Todas" -->
                <?php
              // Conexao com o banco de dados
                include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

                // Carregar os escolas
                $escolas = $mysqli->query("SELECT id, nome FROM escola");
                while ($escola = $escolas->fetch_assoc()) {
                    echo "<option value='{$escola['id']}'>{$escola['nome']}</option>";
                }
                ?>
            </select>
        </div>

        
        
        <div class="form-group">
            <label for="classe_id">Classe:</label>
            <select id="classe_id" name="classe_id">
                <option value="">Selecione uma classe</option>
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
                <option value="">Selecione uma turma</option>
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
                <option value="">Selecione um curso</option>
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
            <label for="genero">Gênero:</label>
            <select id="genero" name="genero">
                <option value="">Selecione um gênero</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
             
            </select>
        </div>

        <div class="form-group">
            <label for="idade">Idade:</label>
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
                // Carregar as classes
                $distritoss = $mysqli->query("SELECT id, nome_distrito FROM distrito");
                while ($distrito = $distritoss->fetch_assoc()) {
                    echo "<option value='{$distrito['id']}'>{$distrito['nome_distrito']}</option>";
                }
                ?>
            </select>
        </div>



        <div class="form-group">
            <label for="situacao_economica">Situação Económica:</label>
            <select id="situacao_economica" name="situacao_economica">
            <option value="">--Selecione--</option>
    <option value="pobre">Pobre</option>
    <option value="muito pobre">Muito Pobre</option>
    <option value="médio">Médio</option>
    <option value="rico">Rico</option>
    <option value="muito rico">Muito Rico</option>
             
            </select>
        </div>




        <div class="form-group">
            <label for="filhos">Alunos com Filhos</label>
            <select id="filhos" name="filhos">
            <option value="">--Selecione--</option>
    <option value="Não">Não Tem Filhos</option>
    <option value="Sim">Tem Filhos</option>
        
            </select>
        </div>
     

<br>

        <div class="form-group">
            <button type="submit">Filtrar</button>
        </div>
    </form>
</div>


<div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#" class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download"></i> Baixar Tabela
    </a>
</div>
<br>




<?php
// Obtendo os filtros do formulário
$distrito_id = $_POST['distrito_id'] ?? null;
$escola_id = $_POST['escola_id'] ?? null;
$classe_id = $_POST['classe_id'] ?? null;
$turma_id = $_POST['turma_id'] ?? null;
$curso_id = $_POST['curso_id'] ?? null;
$genero = $_POST['genero'] ?? null;
$idade = $_POST['idade'] ?? null;
$numero_frequencia = $_POST['numero_frequencia'] ?? null;

$id_distrito = $_POST['id_distrito'] ?? null;



$situacao_economica = $_POST['situacao_economica'] ?? null;

$filhos = $_POST['filhos'] ?? null;

// Montando a consulta com base nos filtros
$sql = "SELECT 
            aluno.nome, 

aluno.nome_encarregado, 
aluno.contato_encarregado, 
aluno.numero_ordem, 
aluno.filhos, 

            aluno.genero, 


            aluno.idade, 
            aluno.numero_frequencia, 
            escola.nome AS escola_nome, 
            turma.nome_turma AS turma_nome, 
            curso.nome_area AS curso_nome, 
            aluno.situacao_economica, 
            aluno.endereco, 
            distrito.nome_distrito AS distrito_nome
        FROM aluno
        LEFT JOIN escola ON aluno.escola_id = escola.id
        LEFT JOIN turma ON aluno.turma_id = turma.id
        LEFT JOIN curso ON aluno.curso_id = curso.id
        LEFT JOIN distrito ON aluno.id_distrito = distrito.id
        WHERE 1=1";

$params = [];
$types = "";

// Adiciona filtros conforme preenchidos
if ($distrito_id && $distrito_id !== 'todos') {
    $sql .= " AND escola.distrito_id = ?";
    $params[] = $distrito_id;
    $types .= "i";
}
if ($escola_id) {
    $sql .= " AND aluno.escola_id = ?";
    $params[] = $escola_id;
    $types .= "i";
}
if ($classe_id) {
    $sql .= " AND aluno.classe_id = ?";
    $params[] = $classe_id;
    $types .= "i";
}
if ($turma_id) {
    $sql .= " AND aluno.turma_id = ?";
    $params[] = $turma_id;
    $types .= "i";
}
if ($curso_id) {
    $sql .= " AND aluno.curso_id = ?";
    $params[] = $curso_id;
    $types .= "i";
}
if ($genero) {
    $sql .= " AND aluno.genero = ?";
    $params[] = $genero;
    $types .= "s";
}
if ($idade) {
    $sql .= " AND aluno.idade = ?";
    $params[] = $idade;
    $types .= "i";
}
if ($numero_frequencia !== '') {
    $sql .= " AND aluno.numero_frequencia = ?";
    $params[] = $numero_frequencia;
    $types .= "s";
}
if ($id_distrito) {
    $sql .= " AND aluno.id_distrito = ?";
    $params[] = $id_distrito;
    $types .= "i";
}






if ($situacao_economica) {
    $sql .= " AND aluno.situacao_economica = ?";
    $params[] = $situacao_economica;
    $types .= "s";
}


if ($filhos) {
    $sql .= " AND aluno.filhos = ?";
    $params[] = $filhos;
    $types .= "s";
}

// Prepara e executa a consulta
$stmt = $mysqli->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();






if ($result->num_rows > 0) {
    echo "<table id='table-results'>
            <tr>
            <th class='field-checkbox'  value='numero_ordem'>ID</th>
                <th class='field-checkbox'  value='nome'>Nome</th>
                <th class='field-checkbox'  value='genero'>Gênero</th>
                <th class='field-checkbox'  value='idade'>Idade</th>
                <th class='field-checkbox'  value='numero_frequencia'>Número de Frequência</th>
                <th class='field-checkbox'  value='escola_nome'>Escola</th>
                <th class='field-checkbox'  value='turma_nome'>Turma</th>
                <th class='field-checkbox'  value='curso_nome'>Curso</th>
                <th class='field-checkbox'  value='situacao_economica'>S.Econômica</th>
                <th class='field-checkbox'  value='endereco'>Endereço</th>
                <th class='field-checkbox'  value='distrito_nome'>Distrito</th>


                  <th class='field-checkbox'  value='nome_encarregado'>Encarregado</th>
                <th class='field-checkbox'  value='contato_encarregado'>Tel.Encarregado</th>
      
            </tr>";

    while ($aluno = $result->fetch_assoc()) {
        echo "<tr>
           <td>{$aluno['numero_ordem']}</td>
                <td>{$aluno['nome']}</td>
                <td>{$aluno['genero']}</td>
                <td>{$aluno['idade']}</td>
                <td>{$aluno['numero_frequencia']}</td>
                <td>{$aluno['escola_nome']}</td>
                <td>{$aluno['turma_nome']}</td>
                <td>{$aluno['curso_nome']}</td>
                <td>{$aluno['situacao_economica']}</td>
                <td>{$aluno['endereco']}</td>
                <td>{$aluno['distrito_nome']}</td>

                <td>{$aluno['nome_encarregado']}</td>
                <td>{$aluno['contato_encarregado']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>Nenhum aluno encontrado.</p>";
}

$mysqli->close();
?>

</body>
</html>
