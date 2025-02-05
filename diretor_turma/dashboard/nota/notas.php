<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recupera o ID do professor (usuário) da sessão
$id_usuario = $_SESSION['id'];

// Função para validar e limpar dados de entrada
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Função para aplicar a cor nas notas
function get_nota_color($nota) {
    return $nota < 10 ? "text-danger" : "text-primary";
}

// Consulta os alunos cujo id_diretor_turma seja igual ao id do usuário logado
$sql = "SELECT * FROM aluno WHERE id_diretor_turma = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas dos Alunos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .table-container {
            overflow-x: auto;
            position: relative;
        }

        .table {
            min-width: 800px;
            border-collapse: separate;
        }

        /* Fixar a primeira coluna (Disciplina) */
        .table tbody tr td:first-child,
        .table thead tr th:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #f8f9fa; /* Cor de fundo fixa */
        }

        .table thead tr th:first-child {
            z-index: 3; /* Sobrepor ao conteúdo da tabela */
        }

        input[type="number"] {
            width: 80px;
            text-align: center;
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
<body  style="background-color:rgba(0, 0, 0, 0.19);">
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard/index.php'">
    <i class="fa fa-arrow-left"></i> Voltar a Pagina Inicial
</button>
<br>
<br>

<div class="container py-4">
    <h1 class="text-center mb-4">Notas dos(as) Alunos(as)</h1>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="mb-4">
            <h3 style="color: black;">
    <i class="bi bi-person"></i> Aluno(a) Número: 
    <?= htmlspecialchars($row['numero_ordem']) ?> 
    - 
    <i class="bi bi-card-text"></i> <?= htmlspecialchars($row['nome']) ?>
</h3>


                <?php
                $classe_id = $row['classe_id'];
                $sql_disciplinas = "SELECT cd.id_disciplina, d.nome_disciplina FROM classe_disciplina cd
                                    JOIN disciplina d ON cd.id_disciplina = d.id
                                    WHERE cd.id_classe = ?";
                $stmt_disciplinas = $mysqli->prepare($sql_disciplinas);
                $stmt_disciplinas->bind_param("i", $classe_id);
                $stmt_disciplinas->execute();
                $result_disciplinas = $stmt_disciplinas->get_result();
                ?>

                <div class="table-container">
                    <table class="table table-striped table-bordered">
                    <thead class="table-dark">
    <tr>
        <th style="color: green;" rowspan="2">Disciplina</th>
        <th colspan="3" class="text-center" style="background-color:rgb(0, 252, 252); color: white;">1º Período</th>
        <th colspan="3" class="text-center" style="background-color: rgba(0, 252, 252, 0.534); color: white;">2º Período</th>
        <th colspan="3" class="text-center" style="background-color: rgba(0, 252, 252, 0.281); color: white;">3º Período</th>
    </tr>
    <tr>
        <th style="background-color: rgb(0, 252, 252); color: white;">1ª Avaliação</th>
        <th style="background-color: rgb(0, 252, 252); color: white;">2ª Avaliação</th>
        <th style="background-color: rgba(72, 253, 0, 0.726); color: white;">1ª Pauta</th>

        <th style="background-color: rgba(0, 252, 252, 0.534); color: white;">1ª Avaliação</th>
        <th style="background-color:rgba(0, 252, 252, 0.534);color: white;">2ª Avaliação</th>
        <th style="background-color: rgba(72, 253, 0, 0.726); color: white;">2ª Pauta</th>

        <th style="background-color: rgba(0, 252, 252, 0.281); color: white;">1ª Avaliação</th>
        <th style="background-color: rgba(0, 252, 252, 0.281); color: white;">2ª Avaliação</th>
        <th style="background-color: rgba(72, 253, 0, 0.726); color: white;">Pauta Final</th>
    </tr>
</thead>


                        <tbody>
                            <?php while ($disciplina = $result_disciplinas->fetch_assoc()): ?>
                                <?php
                                $disciplina_id = $disciplina['id_disciplina'];
                                $disciplina_nome = htmlspecialchars($disciplina['nome_disciplina']);

                                $sql_notas = "SELECT * FROM nota WHERE id_aluno = ? AND disciplina_id = ?";
                                $stmt_notas = $mysqli->prepare($sql_notas);
                                $stmt_notas->bind_param("ii", $row['id'], $disciplina_id);
                                $stmt_notas->execute();
                                $result_notas = $stmt_notas->get_result();
                                $notas = $result_notas->fetch_assoc();
                                ?>

                                <tr>
                                    <td><?= $disciplina_nome ?></td>
                                    <?php
                                    $nota_fields = ['nota1', 'nota2', 'nota_final1', 'nota3', 'nota4', 'nota_final2', 'nota5', 'nota6', 'nota_final3'];
                                    foreach ($nota_fields as $field):
                                        $nota_value = isset($notas[$field]) ? $notas[$field] : '';
                                        $color_class = get_nota_color($nota_value);
                                        ?>
                                        <td>
                                            <input type="number" class="nota form-control <?= $color_class ?>" 
                                                   name="<?= $field ?>" 
                                                   value="<?= htmlspecialchars($nota_value) ?>" 
                                                   min="0" max="20" step="0.1" 
                                                   data-id-aluno="<?= $row['id'] ?>" 
                                                   data-disciplina-id="<?= $disciplina_id ?>" 
                                                   data-field="<?= $field ?>">
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center text-danger">Não há alunos cadastrados para este diretor de turma.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.nota').on('change', function() {
        var idAluno = $(this).data('id-aluno');
        var disciplinaId = $(this).data('disciplina-id');
        var field = $(this).data('field');
        var value = $(this).val();

        $.ajax({
            url: 'processa_notas.php',
            type: 'POST',
            data: {
                id_aluno: idAluno,
                disciplina_id: disciplinaId,
                field: field,
                value: value
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error('Erro:', error);
            }
        });
    });
});
</script>



<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <!-- Sobre -->
            <div class="col-md-4 mb-3">
                <h5>Email</h5>
                <p><i class="bi bi-envelope-fill me-1"></i>Email: ceitajosenasio19@gmail.com</p>
            </div>
            <!-- Links Úteis -->
            <div class="col-md-4 mb-3">
                <h5>Telefone</h5>
                <ul class="list-unstyled">
            
                <p><i class="bi bi-telephone-fill me-1"></i>Telefone: (+239) 997-1781</p>
                </ul>
            </div>
            <!-- Contato -->
            <div class="col-md-4 mb-3">
                <h5>Endereço</h5>
                <p><i class="bi bi-geo-alt-fill me-1"></i>Endereço: Marginal </p>
                
               
            </div>
        </div>
        <div class="text-center border-top pt-3 mt-1">
            <p class="mb-0">© 2025 DESTP. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>

</body>
</html>
