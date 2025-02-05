<?php
session_start();

// Verifica se o usuário está logado e se é do tipo "professor"
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o ID do usuário da sessão
$id_usuario = $_SESSION['id']; 

// Consultar informações do usuário, incluindo a senha
$query_usuario = "SELECT id, nome, email, senha, tipo, id_escola, classe_id, turma_id, curso_id, periodo_dia_id 
                  FROM usuarios WHERE id = ?";
$stmt_usuario = $mysqli->prepare($query_usuario);
$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
$stmt_usuario->close();

// Recuperar o nome da escola
$query_escola = "SELECT nome FROM escola WHERE id = ?";
$stmt_escola = $mysqli->prepare($query_escola);
$stmt_escola->bind_param("i", $usuario['id_escola']);
$stmt_escola->execute();
$result_escola = $stmt_escola->get_result();
$escola = $result_escola->fetch_assoc();
$stmt_escola->close();

// Recuperar o nome da classe
$query_classe = "SELECT nivel_classe FROM classe WHERE id = ?";
$stmt_classe = $mysqli->prepare($query_classe);
$stmt_classe->bind_param("i", $usuario['classe_id']);
$stmt_classe->execute();
$result_classe = $stmt_classe->get_result();
$classe = $result_classe->fetch_assoc();
$stmt_classe->close();

// Recuperar o nome da turma
$query_turma = "SELECT nome_turma FROM turma WHERE id = ?";
$stmt_turma = $mysqli->prepare($query_turma);
$stmt_turma->bind_param("i", $usuario['turma_id']);
$stmt_turma->execute();
$result_turma = $stmt_turma->get_result();
$turma = $result_turma->fetch_assoc();
$stmt_turma->close();

// Recuperar o nome do curso
$query_curso = "SELECT nome_area FROM curso WHERE id = ?";
$stmt_curso = $mysqli->prepare($query_curso);
$stmt_curso->bind_param("i", $usuario['curso_id']);
$stmt_curso->execute();
$result_curso = $stmt_curso->get_result();
$curso = $result_curso->fetch_assoc();
$stmt_curso->close();

// Recuperar o nome do período do dia
$query_periodo_dia = "SELECT descricao FROM periodo_dia WHERE id = ?";
$stmt_periodo_dia = $mysqli->prepare($query_periodo_dia);
$stmt_periodo_dia->bind_param("i", $usuario['periodo_dia_id']);
$stmt_periodo_dia->execute();
$result_periodo_dia = $stmt_periodo_dia->get_result();
$periodo_dia = $result_periodo_dia->fetch_assoc();
$stmt_periodo_dia->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <!-- Link do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Link do FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .card-body {
            background-color: #ffffff;
        }
        .info-icon {
            color: #007bff;
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
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard/index.php'">
    <i class="fa fa-arrow-left"></i> Voltar a Pagina Inicial
</button>

<br>
<br>



<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Cartão de Perfil -->
            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <h4>Perfil do Usuário</h4>
                </div>
                <div class="card-body">
                    <!-- Informações do usuário -->
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="fas fa-id-badge info-icon"></i> <strong>ID:</strong> <?php echo htmlspecialchars($usuario['id']); ?></p>
                            <p><i class="fas fa-user info-icon"></i> <strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
                            <p><i class="fas fa-envelope info-icon"></i> <strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                            <p><i class="fas fa-user-tie info-icon"></i> <strong>Tipo:</strong> <?php echo htmlspecialchars($usuario['tipo']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="fas fa-school info-icon"></i> <strong>Escola:</strong> <?php echo htmlspecialchars($escola['nome']); ?></p>
                            <p><i class="fas fa-chalkboard-teacher info-icon"></i> <strong>Classe:</strong> <?php echo htmlspecialchars($classe['nivel_classe']); ?></p>
                            <p><i class="fas fa-users info-icon"></i> <strong>Turma:</strong> <?php echo htmlspecialchars($turma['nome_turma']); ?></p>
                            <p><i class="fas fa-book-open info-icon"></i> <strong>Curso:</strong> <?php echo htmlspecialchars($curso['nome_area']); ?></p>
                            <p><i class="fas fa-clock info-icon"></i> <strong>Período do Dia:</strong> <?php echo htmlspecialchars($periodo_dia['descricao']); ?></p>
                        </div>
                    </div>
                    <!-- Botão para editar perfil -->
                
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
