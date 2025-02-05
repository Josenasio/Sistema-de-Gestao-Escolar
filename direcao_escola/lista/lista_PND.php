    <!-- Botão com ícone -->
    <button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
</button>
<br><br><br>

<?php
// Inicia a sessão
session_start();

// Verifica se a sessão da escola está ativa
if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
}

// Inclui a conexão com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verifica se a variável de conexão foi definida corretamente
if (!isset($mysqli)) {
    die("Erro na conexão com o banco de dados.");
}

// Recupera o ID da escola da sessão
$escola_id = $_SESSION['id_escola'];

// Consulta a tabela pessoal_nao_docente para listar os funcionários
$sql = "SELECT pnd.id, pnd.nome, pnd.contacto, pnd.endereco, pnd.idade, pnd.nif, 
        pnd.genero, pnd.data_contrato, pnd.funcao, pnd.estado_civil, 
        pnd.numero_conta_bancaria, pnd.ano_servico, pnd.ano_inicio_servico, 
        pnd.nivel_academico, dist.nome_distrito
        FROM pessoal_nao_docente pnd
        LEFT JOIN distrito dist ON pnd.distrito_id = dist.id
        WHERE pnd.escola_id = ?
        ORDER BY pnd.nome";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt->bind_param("i", $escola_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se há pessoal não docente
if ($result->num_rows > 0) {
    echo "<h2 class='text-center text-primary my-3'>Lista de Pessoal Não Docente</h2>";
    echo "<table class='table table-striped table-bordered table-hover table-responsive' id='table-results'>";
    echo "<thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Contacto</th>
                <th>Endereço</th>
                <th>Idade</th>
                <th>NIF</th>
                <th>Gênero</th>
                <th>Data de Contrato</th>
                <th>Função</th>
                <th>Estado Civil</th>
                <th>Nível Acadêmico</th>
                <th>Distrito</th>
                <th>Ano de Serviço</th>
                <th>Ano de Início</th>
                <th>Conta Bancária</th>
                <th>Ação</th>
            </tr>
          </thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr class='mobile-row'>"; 
        echo "<td data-label='ID'>" . $row['id'] . "</td>";
        echo "<td data-label='Nome' contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"nome\")'>" . $row['nome'] . "</td>";
        echo "<td data-label='Contacto' contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"contacto\")'>" . $row['contacto'] . "</td>";
        echo "<td data-label='Endereço' contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"endereco\")'>" . $row['endereco'] . "</td>";
        echo "<td data-label='Idade' contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"idade\")'>" . $row['idade'] . "</td>";
        echo "<td data-label='NIF'>" . $row['nif'] . "</td>";
        echo "<td data-label='Gênero'>" . $row['genero'] . "</td>";
        echo "<td data-label='Data de Contrato'>" . $row['data_contrato'] . "</td>";
        echo "<td data-label='Função' contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"funcao\")'>" . $row['funcao'] . "</td>";
        echo "<td data-label='Estado Civil'>" . $row['estado_civil'] . "</td>";
        echo "<td data-label='Nível Acadêmico'>" . $row['nivel_academico'] . "</td>";
        echo "<td data-label='Distrito'>" . $row['nome_distrito'] . "</td>";
        echo "<td data-label='Ano de Serviço' contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"ano_servico\")'>" . $row['ano_servico'] . "</td>";
        echo "<td data-label='Ano de Início' contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"ano_inicio_servico\")'>" . $row['ano_inicio_servico'] . "</td>";
        echo "<td data-label='Conta Bancária' contenteditable='true' onblur='salvarEdicao(this, " . $row['id'] . ", \"numero_conta_bancaria\")'>" . $row['numero_conta_bancaria'] . "</td>";
        echo "<td data-label='Ação'><button class='btn btn-primary' onclick='editarFuncionario(" . $row['id'] . ")'>Atualizar</button></td>";
        echo "</tr>";
        
    }

    echo "</tbody></table>";
} else {
    echo "Não há pessoal não docente cadastrado para esta escola.";
}

// Fecha a conexão
$stmt->close();
$mysqli->close();
?>

<script>
    function salvarEdicao(elemento, id, campo) {
        var valor = elemento.innerText;

        // Enviar para o servidor via AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "salvar_edicaoPND.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log("Alteração salva com sucesso.");
            }
        };
        xhr.send("id=" + id + "&campo=" + campo + "&valor=" + encodeURIComponent(valor));
    }

    function editarFuncionario(id) {
        alert('Edição do funcionário ' + id);
    }
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">



 <!-- CSS do Bootstrap -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS do Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
body {
    margin: 0;
    padding: 20px;
    font-family: sans-serif;
    background-color: #c1efde;
}


    /* Estilo padrão e responsivo já incluído */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
    }
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 0.5rem;
        text-align: left;
    }
    @media (max-width: 768px) {
        .table {
            border: 0;
        }
        .table thead {
            display: none;
        }
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .table tbody tr td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            font-size: 0.9rem;
            border: none;
            border-bottom: 1px solid #ddd;
        }
        .table tbody tr td:last-child {
            border-bottom: 0;
        }
        .table tbody tr td[data-label]::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 1rem;
            text-transform: capitalize;
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
    XLSX.writeFile(wb, 'Lista_PND.xlsx');
}
</script>