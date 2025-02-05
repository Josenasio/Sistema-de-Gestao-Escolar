<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: ../index.php");
    exit;
}

// Inclui a conexão com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Obtém a lista de escolas
$queryEscolas = "SELECT id, nome FROM escola ORDER BY nome";
$resultEscolas = $mysqli->query($queryEscolas);

$escolaSelecionada = isset($_GET['id_escola']) ? (int)$_GET['id_escola'] : 0;
$professoresPorPeriodo = [];

if ($escolaSelecionada > 0) {
    $stmt = $mysqli->prepare("SELECT u.id, u.nome, c.nivel_classe, t.nome_turma, p.id as periodo_id, COALESCE(p.descricao, 'Sem Período') as descricao 
                              FROM usuarios u
                              LEFT JOIN classe c ON u.classe_id = c.id
                              LEFT JOIN turma t ON u.turma_id = t.id
                              LEFT JOIN periodo_dia p ON u.periodo_dia_id = p.id
                              WHERE u.tipo = 'professor' AND u.id_escola = ? 
                              ORDER BY p.id, u.nome");
    $stmt->bind_param("i", $escolaSelecionada);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $professoresPorPeriodo[$row['periodo_id']]['descricao'] = $row['descricao'];
        $professoresPorPeriodo[$row['periodo_id']]['professores'][] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Professores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
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


</head>
<body class="bg-light">
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Página Inicial
</button>
<br>
<br>



    <div class="container my-4">
        <h2 class="text-center text-primary">Controle de Professores e Alunos</h2>
        
        <form method="GET" class="mb-4">
            <label for="id_escola" class="form-label">Selecione a Escola:</label>
            <select name="id_escola" id="id_escola" class="form-select" onchange="this.form.submit()">
                <option value="">-- Escolha uma escola --</option>
                <?php while ($escola = $resultEscolas->fetch_assoc()): ?>
                    <option value="<?= $escola['id'] ?>" <?= ($escolaSelecionada == $escola['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($escola['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
        
        <?php if ($escolaSelecionada > 0): ?>
            <h3 class="text-center text-success">Professores e seus Alunos por Período</h3>
            <?php if (count($professoresPorPeriodo) > 0): ?>
                <?php foreach ($professoresPorPeriodo as $periodo): ?>
                    <div class="card my-3">
                        <div class="card-header bg-primary text-white">
                            <h4>Período: <?= htmlspecialchars($periodo['descricao'] ?? 'Sem Período') ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($periodo['professores'] as $professor): ?>
                                    <div class="col-md-6">
                                        <div class="card shadow-sm mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Professor: <?= htmlspecialchars($professor['nome']) ?></h5>
                                                <p class="card-text"><strong>Classe:</strong> <?= htmlspecialchars($professor['nivel_classe'] ?? 'N/A') ?></p>
                                                <p class="card-text"><strong>Turma:</strong> <?= htmlspecialchars($professor['nome_turma'] ?? 'N/A') ?></p>
                                                <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#alunos-<?= $professor['id'] ?>">Ver Alunos</button>
                                                <div class="collapse mt-3" id="alunos-<?= $professor['id'] ?>">
                                                    <table class="table table-bordered table-hover">
                                                        <thead class="table-dark">
                                                            <tr>
                                                            <th>Número</th>
                                                                <th>Nome</th>
                                                            
                                                                <th>Gênero</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $stmtAlunos = $mysqli->prepare("SELECT id, nome, numero_ordem, genero FROM aluno WHERE id_diretor_turma = ? ORDER BY numero_ordem");
                                                            $stmtAlunos->bind_param("i", $professor['id']);
                                                            $stmtAlunos->execute();
                                                            $resultAlunos = $stmtAlunos->get_result();
                                                            if ($resultAlunos->num_rows > 0):
                                                                while ($aluno = $resultAlunos->fetch_assoc()): ?>
                                                                    <tr>
                                                                    <td><?= $aluno['numero_ordem'] ?></td>
                                                                        <td><?= htmlspecialchars($aluno['nome']) ?></td>
                                                                       
                                                                        <td><?= htmlspecialchars($aluno['genero']) ?></td>
                                                                    </tr>
                                                                <?php endwhile;
                                                            else: ?>
                                                                <tr>
                                                                    <td colspan="4" class="text-center">Nenhum aluno encontrado.</td>
                                                                </tr>
                                                            <?php endif;
                                                            $stmtAlunos->close(); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="alert alert-warning text-center">Nenhum professor encontrado.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
