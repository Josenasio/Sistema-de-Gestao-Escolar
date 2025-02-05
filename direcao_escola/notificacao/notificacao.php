<?php 
session_start();
if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/destp_pro/conexao/conexao.php');

// Recuperar o ID da escola logada
$id_escola = $_SESSION['id_escola'];

// Busca as notificações para exibição
$query_select = "SELECT notificacao, visualizado, DATE_FORMAT(data_criacao, '%d/%m/%Y %H:%i:%s') AS data_criacao
                 FROM notificacoes
                 WHERE id_escola = ?
                 ORDER BY data_criacao DESC";
$notificacoes = [];
if ($stmt_select = $mysqli->prepare($query_select)) {
    $stmt_select->bind_param("i", $id_escola);
    $stmt_select->execute();
    $result_notificacoes = $stmt_select->get_result();
    $notificacoes = $result_notificacoes->fetch_all(MYSQLI_ASSOC);
    $stmt_select->close();
}

// Atualiza notificações para visualizadas somente após a exibição
$query_update = "UPDATE notificacoes SET visualizado = 1 WHERE id_escola = ? AND visualizado = 0";
if ($stmt_update = $mysqli->prepare($query_update)) {
    $stmt_update->bind_param("i", $id_escola);
    $stmt_update->execute();
    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações Recebidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .notification-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
            transition: transform 0.2s ease-in-out;
        }
        .notification-card:hover {
            transform: translateY(-5px);
        }
        .notification-date {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .notification-content {
            font-size: 1rem;
            color: #495057;
        }
        .empty-state {
            text-align: center;
            color: #adb5bd;
            margin-top: 50px;
        }
        .icon-empty {
            font-size: 50px;
            color: #ced4da;
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
        .recent-notification {
    background-color:rgba(0, 255, 0, 0.84); /* Fundo verde claro */
    color: red !important; /* Texto vermelho garantido */
}

    </style>
</head>
<body>
    <!-- Botão com ícone -->
    <button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
        <i class="fas fa-tachometer-alt"></i> Voltar a Página Inicial
    </button>
    <div style="margin-top: 60px;"></div>

    <div class="container">
        <h1 class="mb-4 text-center">Notificações Recebidas</h1>
        <?php if (count($notificacoes) > 0): ?>
            <?php foreach ($notificacoes as $notificacao): ?>
                <div class="notification-card <?php echo $notificacao['visualizado'] == 0 ? 'recent-notification' : ''; ?>">
                    <div class="notification-date">
                        <i class="far fa-clock"></i> Recebida em: <?php echo htmlspecialchars($notificacao['data_criacao']); ?>
                    </div>
                    <div class="notification-content mt-2">
                        <i class="fas fa-bell"></i> <?php echo htmlspecialchars($notificacao['notificacao']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox icon-empty"></i>
                <p class="mt-3">Nenhuma notificação encontrada.</p>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
