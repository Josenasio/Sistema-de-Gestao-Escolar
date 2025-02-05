<?php 
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Deletar uma notificação se o ID for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_notificacao'])) {
    $id_notificacao = $_POST['id_notificacao'];
    $query_delete = "DELETE FROM notificacoes WHERE id = ?";
    $stmt = $mysqli->prepare($query_delete);
    $stmt->bind_param("i", $id_notificacao);
    $stmt->execute();
    $stmt->close();
    $msg_sucesso = "Notificação removida com sucesso.";
}

// Consultar todas as notificações
$query_notificacoes = "SELECT n.id, n.notificacao, e.nome AS escola FROM notificacoes n JOIN escola e ON n.id_escola = e.id ORDER BY n.id DESC";
$result_notificacoes = $mysqli->query($query_notificacoes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Notificações</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined">
    <style>
        body {
            background-color: #1B203B;
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #2C3E50;
        }
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
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
<br>
<br>
<br>
<h3 class="mb-0"  style="color: red; text-align:center">Apaga uma Notificação</h3>

<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Lista de Notificações</h4>
        </div>
        <div class="card-body">
            <?php if (isset($msg_sucesso)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($msg_sucesso); ?>
                </div>
            <?php endif; ?>

            <?php if ($result_notificacoes->num_rows > 0): ?>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Escola</th>
                            <th>Notificação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($notificacao = $result_notificacoes->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($notificacao['id']); ?></td>
                                <td><?php echo htmlspecialchars($notificacao['escola']); ?></td>
                                <td><?php echo htmlspecialchars($notificacao['notificacao']); ?></td>
                                <td>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="id_notificacao" value="<?php echo $notificacao['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">Nenhuma notificação encontrada.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
