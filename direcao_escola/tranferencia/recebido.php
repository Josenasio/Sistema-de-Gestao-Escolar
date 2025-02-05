<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o id_escola da sessão
$id_escola = $_SESSION['id_escola'];

// Consultar os alunos
$query = "
    SELECT 
        aluno.id AS aluno_id,
        aluno.nome AS aluno_nome,
        aluno.numero_ordem,
         aluno.genero,
         aluno.idade,
            aluno.bi,
        aluno.turma_id,
  aluno.endereco,
        aluno.contato_encarregado,

        aluno.periododia_id,
        aluno.id_diretor_turma,
        aluno.classe_id,
         aluno.numero_frequencia,
        aluno.curso_id,
        classe.nivel_classe,
        curso.nome_area
    FROM aluno
    LEFT JOIN classe ON aluno.classe_id = classe.id
    LEFT JOIN curso ON aluno.curso_id = curso.id
    WHERE aluno.escola_id = ? 
        AND aluno.numero_ordem IS NULL
        AND aluno.turma_id IS NULL
        AND aluno.periododia_id IS NULL
        AND aluno.id_diretor_turma IS NULL
";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}
$stmt->bind_param("i", $id_escola);
$stmt->execute();
$result = $stmt->get_result();

// Consultar todas as turmas (sem filtro por escola)
$query_turmas = "SELECT id, nome_turma FROM turma";
$stmt_turmas = $mysqli->prepare($query_turmas);
$stmt_turmas->execute();
$result_turmas = $stmt_turmas->get_result();

// Consultar períodos
$query_periodos = "SELECT id, descricao FROM periodo_dia";
$stmt_periodos = $mysqli->prepare($query_periodos);
$stmt_periodos->execute();
$result_periodos = $stmt_periodos->get_result();

// Consultar professores
$query_professores = "SELECT id, nome FROM usuarios WHERE tipo = 'professor' AND id_escola = ?";
$stmt_professores = $mysqli->prepare($query_professores);
$stmt_professores->bind_param("i", $id_escola);
$stmt_professores->execute();
$result_professores = $stmt_professores->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos</title>
    <!-- Inclusão do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

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

    <div class="container mt-5">
        <h2>Lista de Alunos - Recebidos</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nome</th>
                 
                    <th>Gênero</th>
                    <th>Idade</th>
                    <th>Nº B.I</th>
                    <th>Endereço</th>
                    <th>Contacto Encarregado</th>
                    <th>Classe</th>
                    <th>Repitente</th>

                    <th>Curso</th>
                    <th>Ação</th>
                </tr>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['aluno_nome']; ?></td>
                        <td><?php echo $row['genero']; ?></td>
                        <td><?php echo $row['idade']; ?> anos</td>
                        <td><?php echo $row['bi']; ?></td>
                        <td><?php echo $row['endereco']; ?></td>
                        <td><?php echo $row['contato_encarregado']; ?></td>
                        <td><?php echo $row['nivel_classe'] ?? 'Sem Classe'; ?></td>

                        <td><?php echo $row['numero_frequencia']; ?> vez(es)</td>
                        
                        <td><?php echo $row['nome_area'] ?? 'Sem Curso'; ?></td>
                        <td>
                            <!-- Botão para abrir o modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarTurma<?php echo $row['aluno_id']; ?>">Adicionar Turma</button>

                            <!-- Modal -->
                            <div class="modal fade" id="modalAdicionarTurma<?php echo $row['aluno_id']; ?>" tabindex="-1" aria-labelledby="modalAdicionarTurmaLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalAdicionarTurmaLabel">Adicionar Turma para: <?php echo $row['aluno_nome']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="adicionar_turma.php" method="POST">
                                                <div class="mb-3">
                                                    <label for="turma" class="form-label">Selecione a Turma</label>
                                                    <select class="form-select" name="turma_id" required>
                                                        <option value="">Selecione a Turma</option>
                                                        <?php
                                                        // Reinicia o ponteiro do resultado das turmas
                                                        $result_turmas->data_seek(0);
                                                        while ($turma = $result_turmas->fetch_assoc()):
                                                        ?>
                                                            <option value="<?php echo $turma['id']; ?>"><?php echo $turma['nome_turma']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="periodo" class="form-label">Selecione o Período/Dia</label>
                                                    <select class="form-select" name="periodo_id" required>
                                                        <option value="">Selecione o Período/Dia</option>
                                                        <?php while ($periodo = $result_periodos->fetch_assoc()): ?>
                                                            <option value="<?php echo $periodo['id']; ?>"><?php echo $periodo['descricao']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="numero" class="form-label">Número de Ordem</label>
                                                    <input type="number" class="form-control" name="numero_ordem" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="diretor_turma" class="form-label">Selecione o Diretor de Turma</label>
                                                    <select class="form-select" name="diretor_turma_id" required>
                                                        <option value="">Selecione o Diretor de Turma</option>
                                                        <?php while ($professor = $result_professores->fetch_assoc()): ?>
                                                            <option value="<?php echo $professor['id']; ?>"><?php echo $professor['nome']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="aluno_id" value="<?php echo $row['aluno_id']; ?>">
                                                <button type="submit" class="btn btn-success">Adicionar Turma</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$stmt_turmas->close();
$stmt_periodos->close();
$stmt_professores->close();
?>
