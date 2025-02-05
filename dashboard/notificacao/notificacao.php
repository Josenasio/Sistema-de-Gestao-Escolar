<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Consultar a lista de escolas
$query_escolas = "SELECT id, nome FROM escola ORDER BY nome";
$result_escolas = $mysqli->query($query_escolas);

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids_escolas = $_POST['ids_escolas'] ?? [];
    $notificacao = $_POST['notificacao'] ?? '';

    if (!empty($ids_escolas) && !empty($notificacao)) {
        // Inserir notificações para todas as escolas selecionadas
        $query_insert = "INSERT INTO notificacoes (id_escola, notificacao) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query_insert);

        foreach ($ids_escolas as $id_escola) {
            $stmt->bind_param("is", $id_escola, $notificacao);
            $stmt->execute();
        }

        $stmt->close();
        $msg_sucesso = "Notificação enviada com sucesso para as escolas selecionadas!";
    } else {
        $msg_erro = "Por favor, selecione ao menos uma escola e insira a notificação.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Notificações</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined">
    <style>
        body {
            background-color: #1B203B;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .alert {
            margin-top: 20px;
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
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/dashboard/dashboard.php'">
  <i class="fas fa-arrow-left"></i> Voltar a Pagina Inicial
</button>
<div style="margin-top: 60px;"></div><br>
<h3 class="mb-0"  style="color: #007bff; text-align:center">Escreve uma Notificação</h3>

    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Enviar Notificações</h4>
            </div>
            <div class="card-body">
                <?php if (isset($msg_sucesso)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($msg_sucesso); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($msg_erro)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($msg_erro); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="ids_escolas" class="form-label">Selecione as Escolas</label>
                        <select name="ids_escolas[]" id="ids_escolas" class="form-select" multiple required>
                            <?php while ($escola = $result_escolas->fetch_assoc()): ?>
                                <option value="<?php echo $escola['id']; ?>">
                                    <?php echo htmlspecialchars($escola['nome']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <small class="form-text text-muted">Use Ctrl (ou Command no Mac) para selecionar múltiplas escolas.</small>
                    </div>

                    <div class="mb-3">
                        <label for="notificacao" class="form-label">Notificação</label>
                        <textarea name="notificacao" id="notificacao" class="form-control" rows="4" placeholder="Digite a notificação" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Notificação</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
