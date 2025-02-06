<?php 
session_start();

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verificar se a sessão está iniciada
if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
    exit;
}

$id_escola = $_SESSION['id_escola'];

// Buscar usuários do tipo professor na escola logada
$query = "
    SELECT u.*, c.nivel_classe, p.descricao, cu.nome_area, cu.sigla, t.nome_turma 
    FROM usuarios u
    LEFT JOIN classe c ON u.classe_id = c.id
    LEFT JOIN periodo_dia p ON u.periodo_dia_id = p.id
    LEFT JOIN curso cu ON u.curso_id = cu.id
    LEFT JOIN turma t ON u.turma_id = t.id
    WHERE u.id_escola = ? AND u.tipo = 'professor'";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $id_escola);
$stmt->execute();
$result = $stmt->get_result();

// Função para deletar usuário
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM usuarios WHERE id = ?";
    $delete_stmt = $mysqli->prepare($delete_query);
    $delete_stmt->bind_param('i', $delete_id);
    $delete_stmt->execute();
    header('Location: usuarios.php'); // Redireciona após excluir
    exit;
}

// Função para editar usuário
if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $classe_id = $_POST['classe_id'];
    $curso_id = $_POST['curso_id'];
    $periodo_dia_id = $_POST['periodo_dia_id'];
    $turma = $_POST['turma']; // Alterado para receber como input de texto

    // Aqui verificamos se a turma já existe no banco de dados, se não, inserimos uma nova turma
    $check_turma_query = "SELECT id FROM turma WHERE nome_turma = ?";
    $check_turma_stmt = $mysqli->prepare($check_turma_query);
    $check_turma_stmt->bind_param('s', $turma);
    $check_turma_stmt->execute();
    $check_turma_stmt->store_result();

    if ($check_turma_stmt->num_rows > 0) {
        // Se a turma já existir, pegamos o ID da turma
        $check_turma_stmt->bind_result($turma_id);
        $check_turma_stmt->fetch();
    } else {
        // Caso contrário, inserimos uma nova turma
        $insert_turma_query = "INSERT INTO turma (nome_turma) VALUES (?)";
        $insert_turma_stmt = $mysqli->prepare($insert_turma_query);
        $insert_turma_stmt->bind_param('s', $turma);
        $insert_turma_stmt->execute();
        $turma_id = $insert_turma_stmt->insert_id; // Pega o ID da nova turma
    }

    // Atualizamos o usuário com o ID da turma
    $update_query = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, classe_id = ?, curso_id = ?, periodo_dia_id = ?, turma_id = ? WHERE id = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param('sssiisii', $nome, $email, $senha, $classe_id, $curso_id, $periodo_dia_id, $turma_id, $edit_id);
    $update_stmt->execute();

    // Atualiza a tabela aluno com os novos dados
    $update_aluno_query = "
        UPDATE aluno 
        SET turma_id = ?, curso_id = ?, classe_id = ?
        WHERE id_diretor_turma = ?";
    $update_aluno_stmt = $mysqli->prepare($update_aluno_query);
    $update_aluno_stmt->bind_param('iiii', $turma_id, $curso_id, $classe_id, $edit_id);
    $update_aluno_stmt->execute();

    header('Location: usuarios.php'); // Redireciona após editar
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diretor Turma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }
            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            .table tbody td {
                display: block;
                text-align: right;
                position: relative;
                padding-left: 50%;
                text-align: left;
            }
            .table tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                font-weight: bold;
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
</head>
<body>
    <button class="fixed-top-button btn btn-secondary" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
        <i class="fas fa-tachometer-alt"></i> Voltar à Página Inicial
    </button>
    <br>

    <div class="container my-5">
        <h1 class="text-center text-primary mb-4">Lista Diretor Turma</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                    <th><i class="fas fa-user me-2"></i> Nome</th>
<th><i class="fas fa-envelope me-2"></i> Email</th>
<th><i class="fas fa-key me-2"></i> Senha</th>
<th><i class="fas fa-users me-2"></i> Classe</th>
<th><i class="fas fa-book me-2"></i> Curso</th>
<th><i class="fas fa-clock me-2"></i> Período do Dia</th>
<th><i class="fas fa-school me-2"></i> Turma</th>
<th><i class="fas fa-cogs me-2"></i> Ações</th>

                    </tr>
                </thead>
                <tbody>
                    <?php while ($usuario = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Nome"><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td data-label="Email"><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td data-label="Senha" style="color: red;">******</td>
                            <td data-label="Classe"><?php echo htmlspecialchars($usuario['nivel_classe'] ?? 'N/A'); ?></td>
                            <td data-label="Curso"><?php echo htmlspecialchars(' (' .$usuario['sigla'] . ')'.  $usuario['nome_area'] ); ?></td>
                            <td data-label="Período do Dia"><?php echo htmlspecialchars($usuario['descricao'] ?? 'N/A'); ?></td>
                            <td data-label="Turma"><?php echo htmlspecialchars($usuario['nome_turma'] ?? 'N/A'); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $usuario['id']; ?>" data-nome="<?php echo htmlspecialchars($usuario['nome']); ?>" data-email="<?php echo htmlspecialchars($usuario['email']); ?>" data-classe="<?php echo htmlspecialchars($usuario['classe_id'] ?? ''); ?>" data-curso="<?php echo htmlspecialchars($usuario['curso_id'] ?? ''); ?>" data-periodo="<?php echo htmlspecialchars($usuario['periodo_dia_id'] ?? ''); ?>" data-turma="<?php echo htmlspecialchars($usuario['nome_turma'] ?? ''); ?>"><i class="fas fa-edit fa-lg"></i>editar</button>
                                <a href="usuarios.php?delete_id=<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')"><i class="fas fa-trash-alt me-2"></i>apagar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Diretor Turma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="usuarios.php">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" name="senha" id="senha">
                        </div>
                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select class="form-control" name="classe_id" id="classe_id">
                                <option value="">Selecione a Classe</option>
                                <?php
                                    $classe_query = "SELECT * FROM classe";
                                    $classe_result = $mysqli->query($classe_query);
                                    while ($classe = $classe_result->fetch_assoc()) {
                                        echo '<option value="' . $classe['id'] . '">' . htmlspecialchars($classe['nivel_classe']) . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="curso_id" class="form-label">Curso</label>
                            <select class="form-control" name="curso_id" id="curso_id">
                                <option value="">Selecione o Curso</option>
                                <?php
                                    $curso_query = "SELECT * FROM curso";
                                    $curso_result = $mysqli->query($curso_query);
                                    while ($curso = $curso_result->fetch_assoc()) {
                                        echo '<option value="' . $curso['id'] . '">' . htmlspecialchars($curso['nome_area']) . ' (' . $curso['sigla'] . ')</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="periodo_dia_id" class="form-label">Período do Dia</label>
                            <select class="form-control" name="periodo_dia_id" id="periodo_dia_id">
                                <option value="">Selecione o Período</option>
                                <?php
                                    $periodo_query = "SELECT * FROM periodo_dia";
                                    $periodo_result = $mysqli->query($periodo_query);
                                    while ($periodo = $periodo_result->fetch_assoc()) {
                                        echo '<option value="' . $periodo['id'] . '">' . htmlspecialchars($periodo['descricao']) . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
    <label for="turma" class="form-label">Turma</label>
    <input type="text" 
           class="form-control" 
           name="turma" 
           id="turma" 
           maxlength="3" 
           pattern="[A-Za-z1-9]{1,3}" 
           title="A turma pode conter de 1 a 3 caracteres alfanuméricos.">
</div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 
    <script>
        // Abrir modal com os dados do usuário
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var modal = this;
            modal.querySelector('#edit_id').value = button.getAttribute('data-id');
            modal.querySelector('#nome').value = button.getAttribute('data-nome');
            modal.querySelector('#email').value = button.getAttribute('data-email');
            modal.querySelector('#classe_id').value = button.getAttribute('data-classe');
            modal.querySelector('#curso_id').value = button.getAttribute('data-curso');
            modal.querySelector('#periodo_dia_id').value = button.getAttribute('data-periodo');
            modal.querySelector('#turma').value = button.getAttribute('data-turma').toUpperCase();
        });

        // Converte a turma para maiúsculas e impede inserção de espaços ou acentos
        document.getElementById('turma').addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/\s+/g, '').normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        });
    </script>
</body>
</html>
