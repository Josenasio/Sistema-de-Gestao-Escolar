<?php 
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o id_escola da sessão
$id_escola = $_SESSION['id_escola'];
$alunos_por_periodo = [];
$mensagem = "";

// Filtragem de aluno por BI
if (isset($_POST['buscar_bi'])) {
    $bi = $_POST['bi'];
    $query_bi = "SELECT id, nome FROM aluno WHERE bi = ? AND escola_id = ?";
    $stmt_bi = $mysqli->prepare($query_bi);
    $stmt_bi->bind_param("si", $bi, $id_escola);
    $stmt_bi->execute();
    $resultado_bi = $stmt_bi->get_result();
    $aluno_selecionado = $resultado_bi->fetch_assoc();
}

// Processamento da transferencia
if (isset($_POST['transferir'])) {
    $id_aluno = $_POST['id_aluno'];
    $id_escola_destino = $_POST['id_escola_destino'];

    // Inserir na tabela transferencia
    $query_transferir = "INSERT IGNORE INTO transferencias (id_aluno, id_escola, transferidos, recebidos) VALUES (?, ?, 1, 0), (?, ?, 0, 1)";

    $stmt_transferir = $mysqli->prepare($query_transferir);
    $stmt_transferir->bind_param("iiii", $id_aluno, $id_escola, $id_aluno, $id_escola_destino);
    if ($stmt_transferir->execute()) {
        // Resetar campos do aluno
        $query_reset = "UPDATE aluno SET escola_id = ?, turma_id = NULL, periododia_id = NULL, id_diretor_turma = NULL, numero_ordem = NULL WHERE id = ?";
        $stmt_reset = $mysqli->prepare($query_reset);
        $stmt_reset->bind_param("ii", $id_escola_destino, $id_aluno);
        $stmt_reset->execute();
        $mensagem = "Transferência realizada com sucesso!";
    } else {
        $mensagem = "Erro ao transferir o aluno.";
    }
}

// Consulta os alunos organizados por periodo, classe e turma
$query = "
    SELECT 
        aluno.id AS aluno_id,
        aluno.nome AS aluno_nome,
        aluno.numero_ordem AS aluno_numero,
        aluno.bi AS bilhete,
        turma.nome_turma,
        classe.nivel_classe,
        periodo_dia.descricao AS periodo_nome
    FROM aluno
    LEFT JOIN turma ON aluno.turma_id = turma.id
    LEFT JOIN classe ON aluno.classe_id = classe.id
    LEFT JOIN periodo_dia ON aluno.periododia_id = periodo_dia.id
    WHERE aluno.escola_id = ?
    ORDER BY periodo_dia.id, classe.id, turma.id, aluno.nome
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_escola);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $alunos_por_periodo[$row['periodo_nome']][$row['nivel_classe']][$row['nome_turma']][] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferência de Alunos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Adicione o link para o Font Awesome no cabeçalho -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <style>
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

<!-- Botão com ícone -->
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
</button>
<div style="margin-top: 90px;"></div>
<div class="container mt-4">
    <h2>Transferência de Alunos</h2>
    <?php if ($mensagem): ?>
        <div class="alert alert-info"> <?php echo $mensagem; ?> </div>
    <?php endif; ?>

    <!-- Formulário de Busca -->
    <form method="POST" class="mb-4">
        <div class="form-row">
            <div class="col-12 col-sm-8 col-md-10">
                <input type="text" name="bi" class="form-control" placeholder="Digite o BI do aluno" required>
            </div>
            <div class="col-12 col-sm-4 col-md-2">
                <button type="submit" name="buscar_bi" class="btn btn-primary btn-block"><i class="fas fa-search"></i>
                 Buscar</button>
            </div>
        </div>
    </form>

    <!-- Resultado da Busca -->
    <?php if (isset($aluno_selecionado)): ?>
        <div class="alert alert-success">
            Aluno encontrado: <strong><?php echo htmlspecialchars($aluno_selecionado['nome']); ?></strong>
            <button class="btn btn-sm btn-warning ml-3" data-toggle="modal" data-target="#modalTransferir" data-id="<?php echo $aluno_selecionado['id']; ?>"><i class="fas fa-exchange-alt"></i> transferir</button>
        </div>
    <?php endif; ?>

    <!-- Tabela de Alunos -->

    <?php foreach ($alunos_por_periodo as $periodo => $classes): ?>
        <h5>Período: <?php echo htmlspecialchars($periodo); ?></h5>
        <?php foreach ($classes as $classe => $turmas): ?>
            <h6>Classe: <?php echo htmlspecialchars($classe); ?></h6>
            <?php foreach ($turmas as $turma => $alunos): ?>
                <strong>Turma: <?php echo htmlspecialchars($turma); ?></strong>
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                        <th><i class="fas fa-hashtag me-2"></i> Número</th>
<th><i class="fas fa-user me-2"></i> Nome</th>
<th><i class="fas fa-id-card me-2"></i> Bilhete de Identidade</th>
<th><i class="fas fa-cogs me-2"></i> Ações</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alunos as $aluno): ?>
                            <tr> 
                            <td><?php echo htmlspecialchars($aluno['aluno_numero'] ?? ''); ?></td>

                                <td><?php echo htmlspecialchars($aluno['aluno_nome']); ?></td>
                                <td><?php echo htmlspecialchars($aluno['bilhete']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalTransferir" data-id="<?php echo $aluno['aluno_id']; ?>"><i class="fas fa-exchange-alt"></i> transferir
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>

<!-- Modal Transferir -->
<div class="modal fade" id="modalTransferir" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transferir Aluno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_aluno" id="id_aluno">
                    <div class="form-group">
                        <label for="id_escola_destino">Selecione a Escola de Destino</label>
                        <select name="id_escola_destino" id="id_escola_destino" class="form-control" required>
                            <option value="" selected disabled>Escolha...</option>
                            <?php
                            $query_escolas = "SELECT id, nome FROM escola WHERE id != ?";
                            $stmt_escolas = $mysqli->prepare($query_escolas);
                            $stmt_escolas->bind_param("i", $id_escola);
                            $stmt_escolas->execute();
                            $result_escolas = $stmt_escolas->get_result();
                            while ($escola = $result_escolas->fetch_assoc()): ?>
                                <option value="<?php echo $escola['id']; ?>">
                                    <?php echo htmlspecialchars($escola['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="transferir" class="btn btn-success"><i class="fas fa-exchange-alt"></i> transferir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$('#modalTransferir').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id_aluno = button.data('id');
    $('#id_aluno').val(id_aluno);
});
</script>
</body>
</html>
