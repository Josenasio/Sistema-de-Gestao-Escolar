<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verificar se o id_escola está definido na sessão
if (!isset($_SESSION['id_escola'])) {
    die("Erro: ID da escola não definido.");
}

// Recuperar o id_escola da sessão
$id_escola = $_SESSION['id_escola'];
$id_usuario = $_SESSION['id'];

// Obter listas para os selects
$query_classes = "SELECT id, nivel_classe FROM classe ORDER BY id";
$result_classes = $mysqli->query($query_classes);

$query_periodos = "SELECT id, descricao FROM periodo_dia ORDER BY descricao";
$result_periodos = $mysqli->query($query_periodos);

$query_cursos = "SELECT id, CONCAT(sigla, ' - ', nome_area) AS nome_curso FROM curso ORDER BY sigla DESC";
$result_cursos = $mysqli->query($query_cursos);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Diretor de Turma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const turmaInput = document.getElementById('turma_id');
        turmaInput.addEventListener('input', function () {
            turmaInput.value = turmaInput.value.replace(/\s+/g, '').toUpperCase().slice(0, 3);
        });

        const senhaInput = document.getElementById('senha');
        senhaInput.addEventListener('input', function () {
            if (senhaInput.value.length > 6) {
                senhaInput.value = senhaInput.value.slice(0, 6);
            }
        });

        const classeSelect = document.getElementById('classe_id');
        const cursoSelect = document.getElementById('curso_id');
        const cursoMessage = document.createElement('small'); // Criando uma mensagem para ser exibida
        cursoMessage.classList.add('form-text', 'text-danger'); // Classe de estilo
        cursoMessage.textContent = 'Selecione primeiro a Classe';

        // Função AJAX para carregar cursos baseados na classe selecionada
        classeSelect.addEventListener('change', function() {
            const classeId = this.value;
            if (classeId) {
                // Requisição AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_cursos.php?classe_id=' + classeId, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        cursoSelect.innerHTML = xhr.responseText;
                        cursoSelect.disabled = false; // Habilitar o campo de cursos
                        // Remove qualquer mensagem anterior
                        if (cursoSelect.nextElementSibling && cursoSelect.nextElementSibling.classList.contains('form-text')) {
                            cursoSelect.nextElementSibling.remove();
                        }
                    }
                };
                xhr.send();
            } else {
                cursoSelect.innerHTML = '<option selected disabled value="">Selecione o Curso</option>';
                cursoSelect.disabled = true; // Desabilitar o campo de cursos
                // Exibir a mensagem informando ao usuário
                if (!cursoSelect.nextElementSibling || !cursoSelect.nextElementSibling.classList.contains('form-text')) {
                    cursoSelect.parentNode.appendChild(cursoMessage);
                }
            }
        });

        // Bloquear o acesso ao curso caso tente selecionar sem escolher a classe
        cursoSelect.addEventListener('click', function(e) {
            if (classeSelect.value === '') {
                e.preventDefault(); // Impede a ação de seleção
                alert('Selecione Primeiro a Classe e depois o Curso');
            }
        });
    });
</script>

    <style>
        .input-group-text {
            background-color: #f8f9fa;
        }
        .form-control:focus {
            box-shadow: none;
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
    <!-- Botão com ícone -->
    <button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
        <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
    </button>
    <br>
    <br>

    <div class="container py-5">
        <h2 class="text-center mb-4">Cadastro do(a) Diretor(a) de Turma</h2>

        <form action="processar.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_escola" value="<?php echo $id_escola; ?>">
            <input type="hidden" name="tipo" value="professor">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nome">Nome do(a) Diretor(a)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="nome" name="nome" required>
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="email" name="email" required>
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="senha">Senha</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="senha" name="senha" required maxlength="6">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="classe_id">Classe</label>
                    <div class="input-group">
                        <select class="form-control" id="classe_id" name="classe_id" required>
                            <option selected disabled value="">Selecione a Classe</option>
                            <?php while ($row = $result_classes->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nivel_classe']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="periodo_dia_id">Período</label>
                    <div class="input-group">
                        <select class="form-control" id="periodo_dia_id" name="periodo_dia_id" required>
                        <option selected disabled value="">Selecione o Período</option>
                            <?php while ($row = $result_periodos->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['descricao']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="curso_id">Curso</label>
                    <div class="input-group">
                        <select class="form-control" id="curso_id" name="curso_id" required>
                            <option selected disabled value="">Selecione o Curso</option>
                        </select>
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="turma_id">Criar Turma</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="turma_id" name="turma_id" placeholder="Digite o nome da nova turma" required maxlength="3">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                    </div>
                </div>
            </div>
            <br>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Cadastrar Diretor de Turma</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Link do Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</body>
</html>
