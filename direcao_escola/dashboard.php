<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o nome do usuário da sessão
$id_usuario = $_SESSION['id']; // ID do usuário armazenado na sessão

// Consultar o nome do usuário no banco de dados
$query_usuario = "SELECT nome FROM usuarios WHERE id = ?";
$stmt_usuario = $mysqli->prepare($query_usuario);
if (!$stmt_usuario) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();

// Armazenar o nome do usuário em uma variável
$nome_usuario = $usuario['nome'] ?? 'Usuário';
$stmt_usuario->close();

// Verificar se o id_escola está definido na sessão
if (!isset($_SESSION['id_escola'])) {
    die("Erro: ID da escola não definido.");
}

// Recuperar o id_escola da sessão
$id_escola = $_SESSION['id_escola'];

// Consultar o total de alunos
$query_total_alunos = "SELECT COUNT(*) AS total FROM aluno WHERE escola_id = ?";
$stmt = $mysqli->prepare($query_total_alunos);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt->bind_param("i", $id_escola);
$stmt->execute();
$result = $stmt->get_result();
$total_alunos = $result->fetch_assoc()['total'] ?? 0;

// Consultar o total de alunos com deficiência na escola
$query_total_deficientes = "SELECT COUNT(*) AS total_deficientes FROM aluno WHERE escola_id = ? AND deficiente = 1";
$stmt_deficientes = $mysqli->prepare($query_total_deficientes);
if (!$stmt_deficientes) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt_deficientes->bind_param("i", $id_escola);
$stmt_deficientes->execute();
$result_deficientes = $stmt_deficientes->get_result();
$total_deficientes = $result_deficientes->fetch_assoc()['total_deficientes'] ?? 0;
$stmt_deficientes->close();

// Consultar o total de alunas grávidas na escola
$query_total_gravida = "SELECT COUNT(*) AS total_gravida FROM aluno WHERE escola_id = ? AND gravidez = 1";
$stmt_gravida = $mysqli->prepare($query_total_gravida);
if (!$stmt_gravida) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt_gravida->bind_param("i", $id_escola);
$stmt_gravida->execute();
$result_gravida = $stmt_gravida->get_result();
$total_gravida = $result_gravida->fetch_assoc()['total_gravida'] ?? 0;
$stmt_gravida->close();

// Consultar o total de alunos com motivo de abandono diferente de NULL na escola
$query_total_abandonos = "SELECT COUNT(*) AS total_abandonos FROM aluno WHERE escola_id = ? AND motivo_abandono IS NOT NULL   AND aluno.motivo_abandono <> ''";
$stmt_abandonos = $mysqli->prepare($query_total_abandonos);
if (!$stmt_abandonos) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt_abandonos->bind_param("i", $id_escola);
$stmt_abandonos->execute();
$result_abandonos = $stmt_abandonos->get_result();
$total_abandonos = $result_abandonos->fetch_assoc()['total_abandonos'] ?? 0;
$stmt_abandonos->close();









// Consultar o total de professores com motivo de abandono diferente de NULL na escola
$query_total_abandonos = "SELECT COUNT(*) AS total_abandonosp FROM professor WHERE id_escola = ? AND motivo_abandono IS NOT NULL AND motivo_abandono <> ''";
$stmt_total_abandonos = $mysqli->prepare($query_total_abandonos);
if (!$stmt_total_abandonos) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt_total_abandonos->bind_param("i", $id_escola);
$stmt_total_abandonos->execute();
$result_total_abandonos = $stmt_total_abandonos->get_result();
$total_professores_abandonos = $result_total_abandonos->fetch_assoc()['total_abandonosp'] ?? 0;
$stmt_total_abandonos->close();



// Consultar o total de pessoal não docente com motivo de abandono diferente de NULL na escola
$query_total_abandonos = "SELECT COUNT(*) AS total_abandonosp FROM pessoal_nao_docente WHERE escola_id = ? AND motivo_abandono IS NOT NULL AND motivo_abandono <> ''";
$stmt_total_abandonos = $mysqli->prepare($query_total_abandonos);

if (!$stmt_total_abandonos) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_total_abandonos->bind_param("i", $id_escola);
$stmt_total_abandonos->execute();
$result_total_abandonos = $stmt_total_abandonos->get_result();
$total_pessoal_abandonos = $result_total_abandonos->fetch_assoc()['total_abandonosp'] ?? 0;
$stmt_total_abandonos->close();





// Consultar o total de professores
$query_total_professores = "SELECT COUNT(*) AS total_professores FROM professor WHERE id_escola = ?";
$stmt_professores = $mysqli->prepare($query_total_professores);
if (!$stmt_professores) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt_professores->bind_param("i", $id_escola);
$stmt_professores->execute();
$result_professores = $stmt_professores->get_result();
$total_professores = $result_professores->fetch_assoc()['total_professores'] ?? 0;
$stmt_professores->close();



// Consultar o total de professores
$query_total_professoresD = "SELECT COUNT(*) AS total_professoresD FROM usuarios WHERE id_escola = ? AND tipo = 'professor'";
$stmt_professoresD = $mysqli->prepare($query_total_professoresD);
if (!$stmt_professoresD) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt_professoresD->bind_param("i", $id_escola);
$stmt_professoresD->execute();
$result_professoresD = $stmt_professoresD->get_result();
$total_professoresD = $result_professoresD->fetch_assoc()['total_professoresD'] ?? 0;
$stmt_professoresD->close();




// Consultar o total de pessoal não docente
$query_total_pessoal_nao_docente = "SELECT COUNT(*) AS total_pessoal_nao_docente FROM pessoal_nao_docente WHERE escola_id = ?";
$stmt_pessoal_nao_docente = $mysqli->prepare($query_total_pessoal_nao_docente);
if (!$stmt_pessoal_nao_docente) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt_pessoal_nao_docente->bind_param("i", $id_escola);
$stmt_pessoal_nao_docente->execute();
$result_pessoal_nao_docente = $stmt_pessoal_nao_docente->get_result();
$total_pessoal_nao_docente = $result_pessoal_nao_docente->fetch_assoc()['total_pessoal_nao_docente'] ?? 0;
$stmt_pessoal_nao_docente->close();


// Consulta para obter todos os alunos organizados por periodo, classe e turma
$query = "
    SELECT 
        aluno.id AS aluno_id,
        aluno.nome AS aluno_nome,
        aluno.numero_ordem,
         aluno.idade,
          aluno.genero,
             aluno.bi,
   aluno.naturalidade,
             aluno.situacao_economica,

          aluno.endereco,
           aluno.numero_frequencia,
            aluno.contato_encarregado,
        aluno.turma_id,
        aluno.classe_id,
        aluno.periododia_id,
        turma.nome_turma AS turma_nome,
        classe.nivel_classe AS classe_nome,
        periodo_dia.descricao AS periodo_nome
    FROM aluno
    LEFT JOIN turma ON aluno.turma_id = turma.id
    LEFT JOIN classe ON aluno.classe_id = classe.id
    LEFT JOIN periodo_dia ON aluno.periododia_id = periodo_dia.id
    WHERE aluno.escola_id = ?
    ORDER BY aluno.periododia_id, aluno.classe_id, aluno.turma_id, aluno.numero_ordem
";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt->bind_param("i", $id_escola);
$stmt->execute();
$result = $stmt->get_result();

$alunos_por_periodo = [];
while ($row = $result->fetch_assoc()) {
    $alunos_por_periodo[$row['periodo_nome']][$row['classe_nome']][$row['turma_nome']][] = $row;
}

$stmt->close();
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />








    

    <title>Direção</title>


    <style>
    /* Estilo base do ícone */
    #notification-icon {
        color: red;
        transition: transform 0.3s ease-in-out;
    }

    /* Animação contínua para novas notificações */
    .new-notification {
        animation: pulse 0.3s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
    }

    .modal-content {
    border-radius: 15px;
    overflow: hidden;
    border: none;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 2px solid #ddd;
}

.modal-title {
    font-weight: bold;
    color: #333;
}

.list-group-item {
    transition: background-color 0.3s ease;
}

.list-group-item:hover {
    background-color: #c1efde;
    cursor: pointer;
}




    </style>
</head>

<body>


    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark" id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
    <img src="imagem/school.png" alt="Descrição do ícone" width="60">
    </div>
    <div class="list-group list-group-flush my-3">
        <a href="formulario/escola.php" class="list-group-item list-group-item-action bg-transparent second-text active">
        <img src="imagem/plus.png" alt="Descrição do ícone" width="30"> Escola
        </a>
     

        <a href="cadastro_diretor_turma/cadastro.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
        <img src="imagem/plus.png" alt="Descrição do ícone" width="30"> Diretor Turma
</a>


      
        <a href="formulario/cadastro_professor.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
        <img src="imagem/plus.png" alt="Descrição do ícone" width="30"> Professor
        </a>
        <a href="formulario/cadastro_PND.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
        <img src="imagem/plus.png" alt="Descrição do ícone" width="30"> P. Não Docente
        </a>
      


        <div class="dropdown">
  <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
  <i class="fas fa-file-alt me-2"></i>
   Documentos
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="#"><i class="fas fa-angle-double-right me-2"></i>

     Declaração</a></li>
    <li><a class="dropdown-item" href="#"><i class="fas fa-angle-double-right me-2"></i>
     Livro de Termo</a></li>
    <li><a class="dropdown-item" href="#"><i class="fas fa-angle-double-right me-2"></i>
    Registo Biográfico</a></li>
    <li><a class="dropdown-item" href="#"><i class="fas fa-angle-double-right me-2"></i>
    Pauta</a></li>
  </ul>
</div>



        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
    <i class="fas fa-print me-2"></i>Imprimir
</a>







        <a href="sair.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
            <i class="fas fa-sign-out-alt me-2"></i>Sair
        </a>
    </div>
</div>

        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0" style="color: green;">
                <?php 
                // Verifica se o nome da escola está disponível na sessão
                echo isset($_SESSION['nome_escola']) 
                    ? htmlspecialchars($_SESSION['nome_escola']) 
                    : 'Escola não identificada'; 
                ?>
            </h2>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

           

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

               


                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        
                    <li class="nav-item dropdown d-flex align-items-center">
    <!-- Ícone de Notificação -->
    <a href="notificacao/notificacao.php" class="me-3"> 
        <img src="imagem/notification-bell.png" alt="Notificações" width="30" id="notification-icon">
    </a>

    <!-- Ícone de Usuário e Dropdown -->
    <a class="nav-link dropdown-toggle second-text fw-bold d-flex align-items-center" href="#" id="navbarDropdown"
       role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="imagem/user.png" alt="Usuário" width="30" class="me-2">
        <?= htmlspecialchars($nome_usuario) ?>
    </a>

    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="sair.php">Sair</a></li>
    </ul>
</li>

                    </ul>
                </div>
            </nav>

            <div class="container-fluid px-4">






            
            <div class="row g-3 my-2">
    <div class="col-md-3">
    <a href="lista/lista_aluno.php" class="text-decoration-none">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
            <div>
                <h3 class="fs-2"><?= $total_alunos ?></h3>
                <p class="fs-5">Total de Alunos</p>
            </div>
            <img src="imagem/graduated.png" alt="Descrição do ícone" width="70">

        </div>
        </a>
    </div>

    <div class="col-md-3">
    <a href="lista/lista_deficientes.php" class="text-decoration-none">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
            <div>
                <h3 class="fs-2"><?= $total_deficientes ?></h3>
                <p class="fs-5">Total Deficientes</p>
            </div>
            <img src="imagem/wheelchair.png" alt="Descrição do ícone" width="70">
        </div>
        </a>
    </div>

    <div class="col-md-3">
    <a href="lista/lista_gravida.php" class="text-decoration-none">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
            <div>
                <h3 class="fs-2"><?= $total_gravida ?></h3>
                <p class="fs-5">Total de Grávidas</p>
            </div>
            <img src="imagem/prenatal-care.png" alt="Descrição do ícone" width="70">
        </div>
        </a>
    </div>

    <div class="col-md-3">
    <a href="lista/lista_abandono.php" class="text-decoration-none">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
            <div>
                <h3 class="fs-2" style="color: red;"><?= $total_abandonos ?></h3>
                <p class="fs-5">Total Abandono</p>
            </div>
            <img src="imagem/user (1).png" alt="Descrição do ícone" width="70">
        </div>
        </a>
    </div>





    <div class="col-md-3">
    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#customModal">
        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
            <div>
                <h3 class="fs-2">Notas do Aluno</h3>
                <p class="fs-5">Notas</p>
            </div>
            <img src="imagem/bill.png" alt="Descrição do ícone" width="70">
        </div>
    </a>
</div>





    <div class="col-md-3" class="container mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#transferModal">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <h3 class="fs-2">Transferência</h3>
                        <p class="fs-5">Transferências entre Escolas</p>
                    </div>
                    <img src="imagem/transfer.png" alt="Descrição do ícone" width="70">
                </div>
            </a>
        </div>



    <div class="col-md-3" >
    <a href="lista/lista_professor.php" class="text-decoration-none" >
        <div class="p-3 bg-yellow shadow-sm d-flex justify-content-around align-items-center rounded" style="background-color: green;">
            <div>
                <h3 class="fs-2" style="color: #ffffff;"><?= $total_professores ?></h3>
                <p class="fs-5" style="color: #ffffff;">Total de Professores</p>
            </div>
            <img src="imagem/teacher.png" alt="Descrição do ícone" width="70">
            
        </div>
       </a>
    </div>




    <div class="col-md-3" >
    <a href="formulario/profesor_abandono.php" class="text-decoration-none">
    <div class="p-3 bg-yellow shadow-sm d-flex justify-content-around align-items-center rounded" style="background-color: green;">
            <div>
                <h3 class="fs-2" style="color: red;"><?= $total_professores_abandonos ?></h3>
                <p class="fs-5" style="color: #ffffff;">Professor Abandono</p>
            </div>
            <img src="imagem/teacher.png" alt="Descrição do ícone" width="50">
            <img src="imagem/exit (1).png" alt="Descrição do ícone" width="50">
            
        </div>
       </a>
    </div>












    <div class="col-md-3">
    <a href="lista/lista_PND.php" class="text-decoration-none">
        <div class="p-3 bg shadow-sm d-flex justify-content-around align-items-center rounded" style="background-color: #808000;">
            <div>
                <h3 class="fs-2" style="color: #ffffff;"><?= $total_pessoal_nao_docente ?></h3>
                <p class="fs-5" style="color: #ffffff;">Total de P.N.D</p>
            </div>

            <img src="imagem/teamwork.png" alt="Descrição do ícone" width="70">

             
        </div>
        </a>
    </div>





    <div class="col-md-3">
    <a href="formulario/pnd_abandono.php" class="text-decoration-none">
        <div class="p-3 bg shadow-sm d-flex justify-content-around align-items-center rounded" style="background-color: #808000;">
            <div>
                <h3 class="fs-2" style="color: red;"><?= $total_pessoal_abandonos ?></h3>
                <p class="fs-5" style="color: #ffffff;">Abandono P.N.D</p>
            </div>

            <img src="imagem/teamwork.png" alt="Descrição do ícone" width="50">
            <img src="imagem/exit (1).png" alt="Descrição do ícone" width="50">

             
        </div>
        </a>
    </div>





    <div class="col-md-3">
        
    <a href="crud/usuarios.php" class="text-decoration-none">
    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
        <div>
            <h3 class="fs-2"><?= $total_professoresD ?></h3>
            <p class="fs-5">Diretores de Turma</p>
        </div>
        <!-- Ícone ajustado para representar liderança -->
        <img src="imagem/cyber-security.png" alt="Descrição do ícone" width="70">

    </div>
    </a>
</div>















</div>





   <!-- Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">Opções de Transferência</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item" onclick="window.location.href='tranferencia/transferido.php'">
                        <i class="fa fa-exchange-alt me-2"></i>
                        <a href="#" class="text-decoration-none text-dark">--» Transferir Alunos</a>
                    </li>
                    <li class="list-group-item" onclick="window.location.href='tranferencia/recebido.php'">
                        <i class="fa fa-inbox me-2"></i>
                        <a href="#" class="text-decoration-none text-dark">--» Alunos Recebidos</a>
                    </li>
                    <li class="list-group-item" onclick="window.location.href='tranferencia/historico.php'">
                        <i class="fa fa-history me-2"></i>
                        <a href="#" class="text-decoration-none text-dark">--» Histórico de Transferências</a>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>












































<!-- Modal -->
<div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customModalLabel">Selecione um Periodo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div> 
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item" onclick="window.location.href='lista/lista_nota.php'">
                        <a href="lista/lista_nota.php" class="text-decoration-none">--» Nota do 1º - Periodo</a>
                    </li>
                    <li class="list-group-item" onclick="window.location.href='lista/lista_nota2.php'">
                        <a href="lista/lista_nota2.php" class="text-decoration-none">--» Nota do 2º - Periodo</a>
                    </li>
                    <li class="list-group-item" onclick="window.location.href='lista/lista_nota3.php'">
                        <a href="lista/lista_nota3.php" class="text-decoration-none">--» Nota do 3º - Periodo</a>
                    </li>

                    <li class="list-group-item" onclick="window.location.href='lista/lista_exame.php'">
                        <a href="lista/lista_exame.php" class="text-decoration-none">--» Nota dos Exames</a>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>






























 
<div class="container mt-4">
    <h1 class="mb-4 text-primary">Lista de Alunos</h1>
    
    <?php foreach ($alunos_por_periodo as $periodo => $classes): ?>
        <h2 class="mt-4">Período: <span class="text-danger"><?= htmlspecialchars($periodo) ?></span></h2>
        
        <?php foreach ($classes as $classe => $turmas): ?>
            <h3 class="mt-3">Classe: <?= htmlspecialchars($classe) ?></h3>
            
            <?php foreach ($turmas as $turma => $alunos): ?>
                <h4 class="mt-2 text-primary">Turma: <?= htmlspecialchars($turma) ?></h4>

                <div class="table-responsive">
                    <table class="table table-striped table-hover bg-white rounded shadow-sm">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col" width="50"><i class="fas fa-hashtag"></i> Nº</th>
                                <th scope="col"><i class="fas fa-user"></i> Nome</th>
                                <th scope="col" width="50"><i class="fas fa-calendar-alt"></i> Idade</th>
                                <th scope="col"><i class="fas fa-venus-mars"></i> Gênero</th>
                                <th scope="col"><i class="fas fa-map-marker-alt"></i> Naturalidade</th>
                             
                
                                <th scope="col"><i class="fas fa-home"></i> Endereço</th>
                                <th scope="col" width="50"><i class="fas fa-redo"></i> Repitente</th>
                                <th scope="col"><i class="fas fa-phone"></i> Cont. Encarregado</th>
                                <th scope="col"><i class="fas fa-tools"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $index => $aluno): ?>
                                <tr>
                                    <td><?= htmlspecialchars($aluno['numero_ordem'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($aluno['aluno_nome']) ?></td>
                                    <td><?= htmlspecialchars($aluno['idade']) ?></td>
                                    <td><?= htmlspecialchars($aluno['genero']) ?></td>
                                    <td><?= htmlspecialchars($aluno['naturalidade']) ?></td>
                  
                                 
                                    <td><?= htmlspecialchars($aluno['endereco']) ?></td>
                                    <td><?= htmlspecialchars($aluno['numero_frequencia']) ?></td>
                                    <td><?= htmlspecialchars($aluno['contato_encarregado']) ?></td>
                                    <td>
                                        <a href="editar_aluno.php?id=<?= $aluno['aluno_id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>

    


            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };
    </script>



<script>
    // Função para verificar notificações no servidor
    async function checkNotifications() {
        try {
            const response = await fetch("check_notifications.php");
            const data = await response.json();

            // Se houver novas notificações, aplica a animação
            if (data.new_notifications) {
                document.getElementById("notification-icon").classList.add("new-notification");
            } else {
                document.getElementById("notification-icon").classList.remove("new-notification");
            }
        } catch (error) {
            console.error("Erro ao verificar notificações:", error);
        }
    }

    // Verifica notificações a cada 5 segundos
    setInterval(checkNotifications, 5000);

    // Verifica notificações ao carregar a página
    document.addEventListener("DOMContentLoaded", checkNotifications);
</script>






















</body>

</html>