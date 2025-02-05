<?php
session_start();

if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

if (!isset($mysqli)) {
    die("Erro na conexão com o banco de dados.");
}

$escola_id = $_SESSION['id_escola'];

$sql = "
    SELECT 
        a.id,
        a.nome,
        a.idade,
        a.data_nascimento,
        a.endereco,
        a.genero,
        a.numero_frequencia,
        a.numero_ordem,
        a.contato_encarregado,
        a.nome_encarregado,
        a.contato_encarregado,
        a.bi,
        a.naturalidade,
        a.data_emissao_bi,
        a.situacao_economica,
        a.tipo_deficiencia,
        c.nivel_classe AS nome_classe,
        t.nome_turma AS nome_turma
    FROM aluno a
    INNER JOIN turma t ON a.turma_id = t.id
    INNER JOIN classe c ON a.classe_id = c.id
    WHERE a.escola_id = ? AND a.deficiente = 1
    ORDER BY c.nivel_classe, t.nome_turma, a.nome
";

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt->bind_param("i", $escola_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h2 class='text-center text-primary my-5'>Lista de Alunos com Deficiência</h2>"; 
    echo "<div class='table-responsive'>"; // Responsividade
    echo "<table class='table table-striped table-bordered table-hover' id='table-results'>";
    echo "<thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Idade</th>
                <th>Data de Nascimento</th>
                <th>Endereço</th>
                <th>Gênero</th>
                <th>Contato Encarregado</th>
                <th>Encarregado</th>
                <th>BI</th>
                <th>Naturalidade</th>
                 <th>Dedifiência</th>
                <th>Classe</th>
                <th>Turma</th>
                <th>Ação</th>
            </tr>
          </thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"nome\")'>" . $row['nome'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"idade\")'>" . $row['idade'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"data_nascimento\")'>" . $row['data_nascimento'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"endereco\")'>" . $row['endereco'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"genero\")'>" . $row['genero'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"contato_encarregado\")'>" . $row['contato_encarregado'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"nome_encarregado\")'>" . $row['nome_encarregado'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"bi\")'>" . $row['bi'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"naturalidade\")'>" . $row['naturalidade'] . "</td>";
        echo "<td contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"tipo_deficiencia\")'>" . $row['tipo_deficiencia'] . "</td>";

        echo "<td>" . $row['nome_classe'] . "</td>";
        echo "<td>" . $row['nome_turma'] . "</td>";
        echo "<td><button class='btn btn-primary' onclick='editarAluno(" . $row['id'] . ")'>Atualizar</button></td>";
        echo "</tr>";
    }

    echo "</tbody></table>";





    
    echo "</div>"; // Fim da responsividade
} else {
    echo "Não há alunos deficientes cadastrados.";
}

$stmt->close();
$mysqli->close();
?>

<script>
function salvarEdicao(elemento, id, campo) {
    var valor = elemento.innerText;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "salvar_deficientes.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log("Alteração salva com sucesso.");
        }
    };
    xhr.send("id=" + id + "&campo=" + campo + "&valor=" + encodeURIComponent(valor));
}

function editarAluno(id) {
    alert('Edição do aluno ' + id);
}
</script>

<!-- Link para o Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">



    <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS do Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Adicione o link para o Font Awesome no cabeçalho -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<!-- Inclusão do CSS para tornar a tabela mais responsiva -->
<style>
body {
    margin: 0;
    padding: 20px;
    font-family: sans-serif;
    background-color: #c1efde;
   
}

* {
    box-sizing: border-box;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table td, .table th {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: center;
    font-size: 16px;
}

.table th {
    background-color: darkblue;
    color: #ffffff;
}

.table tbody tr:nth-child(even) {
    background-color: #f5f5f5;
}

/* Responsividade */
@media(max-width: 500px){
    .table thead {
        display: none;
    }

    .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
    }
    .table tr {
        margin-bottom: 15px;
    }
    .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }
    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 15px;
        font-size: 15px;
        font-weight: bold;
        text-align: left;
    }
}

/* Responsividade adicional para telas pequenas */
@media screen and (max-width: 767px) {
    table th, table td {
        padding: 5px;
        font-size: 12px;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table td, .table th {
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .table td {
        max-width: 150px;
    }
}

.fixed-top-button {
    margin-top: -2px;
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

    <!-- Botão com ícone -->
    <button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
</button>
<br><br><br>





<div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
    <a href="#"  class="btn btn-outline-primary me-2" onclick="exportToExcel()">
        <i class="fas fa-download" ></i> Baixar Tabela
    </a>
</div>

<br>


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
    XLSX.writeFile(wb, 'Lista_deficientes.xlsx');
}
</script>