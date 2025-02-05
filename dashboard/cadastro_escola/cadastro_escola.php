<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verifique se o usuário está logado e tem permissão de administrador
if (!isset($_SESSION['id_escola']) || $_SESSION['tipo'] != 'administrador') {
    die("Você não tem permissão para acessar esta página.");
}

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validação para garantir que a senha tenha no máximo 6 caracteres
    if (strlen($senha) > 6) {
        die("A senha deve ter no máximo 6 caracteres.");
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT); // Criptografando a senha
    $tipo = $_POST['tipo'];
    $id_escola = $_POST['id_escola'];

    // Inserir o novo usuário na tabela 'usuarios'
    $sql = "INSERT INTO usuarios (nome, email, senha, tipo, id_escola) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $mysqli->error);
    }

    // Vincula os parâmetros da consulta
    $stmt->bind_param("ssssi", $nome, $email, $senha_hash, $tipo, $id_escola);

    // Executa a consulta e verifica o sucesso
    if ($stmt->execute()) {
        // Redireciona de volta para a página anterior após o cadastro
        header('Location: sucesso.php'); // Altere para a URL desejada
    } else {
        // Exibe erro mais detalhado se a execução falhar
        echo "<p>Erro ao cadastrar direção: " . $stmt->error . "</p>";
    }

    // Fecha o statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Direção</title>
    <!-- Link para o Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
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

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-control {
            font-size: 16px;
        }

        .alert-success {
            color: green;
            font-weight: bold;
        }

        .alert-danger {
            color: red;
            font-weight: bold;
        }
    </style>
    <script>
        // Impedir que mais de 6 caracteres sejam digitados no campo de senha
        function limitarSenha(input) {
            if (input.value.length > 6) {
                input.value = input.value.slice(0, 6);
            }
        }
    </script>
</head>
<body>

<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>

<div class="form-container">
    <h2 class="text-center text-primary my-5">Cadastro de Direção</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome Diretor</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email da Escola</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" maxlength="6" oninput="limitarSenha(this)" required>
        </div>

        <div class="mb-3" style="display: none;">
            <label for="tipo" class="form-label">Tipo de Usuário</label>
            <select class="form-control" id="tipo" name="tipo" required>
                <option value="direcao">Direção</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_escola" class="form-label">Escola</label>
            <select class="form-control" id="id_escola" name="id_escola" required>
                <option value="" disabled selected>Selecione a escola</option>
                <?php
                // Carregar as escolas do banco de dados
                $sql_escolas = "SELECT id, nome FROM escola";
                $result_escolas = $mysqli->query($sql_escolas);

                if ($result_escolas->num_rows > 0) {
                    while ($row = $result_escolas->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhuma escola encontrada</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
</div>

<!-- Link para o script do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$mysqli->close();
?>
