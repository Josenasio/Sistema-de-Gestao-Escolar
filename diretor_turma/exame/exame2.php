<?php
session_start();

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/destp_pro/conexao/conexao.php');

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
$sql = "SELECT * FROM aluno WHERE id_diretor_turma = ? ORDER BY numero_ordem ASC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Consulta as notas dos alunos
$notas_sql = "SELECT id_aluno, id_disciplina, nota FROM notas_exame WHERE fase = 2 ";
$notas_result = $mysqli->query($notas_sql);

// Organize as notas em um array associativo
$notas_existentes = [];
while ($nota = $notas_result->fetch_assoc()) {
    $notas_existentes[$nota['id_aluno']][$nota['id_disciplina']] = $nota['nota'];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Notas de Exame</title>

    <!-- Link do FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Customização para cores de texto */
        .text-danger { color: red; }
        .text-primary { color: blue; }

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
    <script>
        function validarFormulario(event) {
            const inputs = document.querySelectorAll('input[type="number"]');
            let valido = true;

            inputs.forEach(input => {
                const valor = parseFloat(input.value);
                if (input.value !== '' && (valor < 0 || valor > 20)) {
                    valido = false;
                    alert(`Nota inválida: ${valor}. As notas devem estar entre 0 e 20.`);
                    event.preventDefault(); // Impede o envio do formulário
                    return false;
                }
            });

            return valido;
        }
    </script>
</head>
<body style="background-color:rgba(0, 0, 0, 0.19);">
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard/index.php'">
    <i class="fa fa-arrow-left"></i> Voltar a Pagina Inicial
</button>
<br>
<br>



    <div class="container my-5">
        <h1 class="text-center mb-4">Notas de Exame 2ª-Fase</h1>
        <form action="salvar_notas_exame2.php" method="POST" onsubmit="return validarFormulario(event)">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr style="background-color: rgba(0, 0, 0, 0.58); color: #ffffff; text-align:center">
                    <th>Número</th>
                        <th>Aluno</th>
                        <th>Português</th>
                        <th>Matemática</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($aluno = $result->fetch_assoc()): 
                        $id_aluno = $aluno['id'];
                        $numero = htmlspecialchars($aluno['numero_ordem']);
                        $nome_aluno = htmlspecialchars($aluno['nome']);
                        $nota_portugues = isset($notas_existentes[$id_aluno][1]) ? $notas_existentes[$id_aluno][1] : '';
                        $nota_matematica = isset($notas_existentes[$id_aluno][2]) ? $notas_existentes[$id_aluno][2] : '';

                        // Define as classes para as notas
                        $class_portugues = ($nota_portugues < 10 && $nota_portugues !== '') ? 'text-danger' : 'text-primary';
                        $class_matematica = ($nota_matematica < 10 && $nota_matematica !== '') ? 'text-danger' : 'text-primary';
                    ?>
                    <tr>
                    <td><?= $numero; ?></td>
                        <td><?= $nome_aluno; ?></td>
                        <td>
                            <input type="number" step="0.01" name="notas[<?= $id_aluno; ?>][1]" value="<?= $nota_portugues; ?>" class="form-control <?= $class_portugues; ?>">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="notas[<?= $id_aluno; ?>][2]" value="<?= $nota_matematica; ?>" class="form-control <?= $class_matematica; ?>">
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary w-100">Salvar Notas</button>
        </form>
    </div>

    <!-- Link para o JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
