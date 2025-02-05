<!-- Botão com ícone -->
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Página Inicial
</button>

<br><br><br>

<?php
// Inicia a sessão
session_start();

// Verifica se a sessão da escola está ativa
if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
    exit;
}

// Inclui a conexão com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verifica se a variável de conexão foi definida corretamente
if (!isset($mysqli)) {
    die("Erro na conexão com o banco de dados.");
}

// Recupera o ID da escola da sessão
$escola_id = $_SESSION['id_escola'];

// Consulta para listar professores, suas classes, disciplinas e turmas
$sql = "
    SELECT 
        p.id AS id_professor,
        p.nome,
        p.email,
        p.idade,
        p.telefone,
        p.endereco,
        p.genero,
        p.data_contrato,
        p.funcao,
        p.nome_facebook,
        p.nivel_academico,
        p.area_formacao1,
        p.categoria_salarial,
        dist.nome_distrito,
        GROUP_CONCAT(DISTINCT c.nivel_classe ORDER BY c.nivel_classe SEPARATOR ', ') AS classes,
        GROUP_CONCAT(DISTINCT d.nome_disciplina ORDER BY d.nome_disciplina SEPARATOR ', ') AS disciplinas,
        GROUP_CONCAT(DISTINCT t.nome_turma ORDER BY t.nome_turma SEPARATOR ', ') AS turmas
    FROM professor p
    LEFT JOIN professor_classe pc ON p.id = pc.id_professor
    LEFT JOIN classe c ON pc.id_classe = c.id
    LEFT JOIN professor_disciplina pd ON p.id = pd.professor_id
    LEFT JOIN disciplina d ON pd.disciplina_id = d.id
    LEFT JOIN professor_turma pt ON p.id = pt.professor_id
    LEFT JOIN turma t ON pt.turma_id = t.id
    LEFT JOIN distrito dist ON p.distrito_id = dist.id
    WHERE p.id_escola = ? 
    GROUP BY p.id
    ORDER BY c.nivel_classe, p.nome";

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt->bind_param("i", $escola_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se há professores
if ($result->num_rows > 0) {
    echo "
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' rel='stylesheet'>
    <style>
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
            .table th, .table td {
                white-space: nowrap;
            }
        }
    </style>
  
    <div class='container mt-5'>
        <h2>Professores Cadastrados</h2>
        <div class='table-responsive'>
            <table class='table table-bordered table-striped'>
                <thead>
                    <tr>
                        <th>ID <i class='fas fa-id-badge'></i></th>
                        <th>Nome <i class='fas fa-user'></i></th>
                        <th>Email <i class='fas fa-envelope'></i></th>
                        <th>Idade <i class='fas fa-birthday-cake'></i></th>
                        <th>Telefone <i class='fas fa-phone'></i></th>
                        <th>Endereço <i class='fas fa-map-marker-alt'></i></th>
                        <th>Gênero <i class='fas fa-venus-mars'></i></th>
                        <th>Data de Contrato <i class='fas fa-calendar-alt'></i></th>
                        <th>Função <i class='fas fa-briefcase'></i></th>
                        <th>Facebook <i class='fab fa-facebook'></i></th>
                        <th>Nível Acadêmico <i class='fas fa-graduation-cap'></i></th>
                        <th>Área de Formação <i class='fas fa-book'></i></th>
                        <th>Categoria Salarial <i class='fas fa-dollar-sign'></i></th>
                        <th>Distrito <i class='fas fa-map'></i></th>
                        <th>Classes <i class='fas fa-chalk .fa-chalkboard-teacher'></i></th>
                        <th>Disciplinas <i class='fas fa-book-open'></i></th>
                        <th>Turmas <i class='fas fa-users'></i></th>
                    </tr>
                </thead>
                <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id_professor']}</td>
                <td>{$row['nome']}</td>
                <td>{$row['email']}</td>
                <td>{$row['idade']}</td>
                <td>{$row['telefone']}</td>
                <td>{$row['endereco']}</td>
                <td>{$row['genero']}</td>
                <td>{$row['data_contrato']}</td>
                <td>{$row['funcao']}</td>
                <td>{$row['nome_facebook']}</td>
                <td>{$row['nivel_academico']}</td>
                <td>{$row['area_formacao1']}</td>
                <td>{$row['categoria_salarial']}</td>
                <td>{$row['nome_distrito']}</td>
                <td>{$row['classes']}</td>
                <td>{$row['disciplinas']}</td>
                <td>{$row['turmas']}</td>
              </tr>";
    }

    echo "      </tbody>
            </table>
        </div>
    </div>";
} else {
    echo "<div class='container mt-5'><h3>Nenhum professor cadastrado.</h3></div>";
}

$stmt->close();
$mysqli->close();
?>





<script>
function editarProfessor(id) {
    alert("Função de edição para o professor com ID: " + id);
}
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
    /* Estilo da tabela */
    .table {
        width: 100%;
    }

    /* Estilo do botão fixo */
    .fixed-top-button {
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
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

    /* Responsividade para telas pequenas */
    @media (max-width: 767px) {
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            padding: 8px 10px;
            font-size: 12px;
        }
        .table {
            font-size: 10px;
        }
    }
</style>
