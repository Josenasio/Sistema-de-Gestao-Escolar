<?php 
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

$escola_id = $_SESSION['id_escola'];

$query_pessoal = "SELECT * FROM pessoal_nao_docente WHERE escola_id = ? ORDER BY nome";
$stmt_pessoal = $mysqli->prepare($query_pessoal);
$stmt_pessoal->bind_param("i", $escola_id);
$stmt_pessoal->execute();
$result_pessoal = $stmt_pessoal->get_result();
$pessoal = $result_pessoal->fetch_all(MYSQLI_ASSOC);
$stmt_pessoal->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['motivo_abandono'] as $id_pessoal => $motivo) {
        $data_abandono = $_POST['data_abandono'][$id_pessoal] ?? null;

        $query_update = "UPDATE pessoal_nao_docente SET motivo_abandono = ?, data_abandono = ? WHERE id = ?";
        $stmt_update = $mysqli->prepare($query_update);
        $stmt_update->bind_param("ssi", $motivo, $data_abandono, $id_pessoal);
        $stmt_update->execute();
        $stmt_update->close();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar ou Atualizar Casos de Abandono</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>
<body>
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar à Página Inicial
</button>
<br><br><br>

<div class="container">
    <h1 class="text-center mb-4">Abandono Pessoal Não Docente</h1>

    <?php if (count($pessoal) > 0): ?>
        <form method="POST">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Gênero</th>
                            <th>Idade</th>
                            <th>Função</th>
                            <th>Motivo de Abandono</th>
                            <th>Data Abandono</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pessoal as $pessoa): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pessoa['nome'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($pessoa['genero'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($pessoa['idade'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($pessoa['funcao'] ?? ''); ?></td>
                                <td>
                                    <textarea class="form-control" name="motivo_abandono[<?php echo $pessoa['id']; ?>]" rows="3"><?php echo htmlspecialchars($pessoa['motivo_abandono'] ?? ''); ?></textarea>
                                </td>
                                <td>
                                    <input type="date" class="form-control" name="data_abandono[<?php echo $pessoa['id']; ?>]" value="<?php echo htmlspecialchars($pessoa['data_abandono'] ?? ''); ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Atualizar Casos de Abandono</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning text-center">Não há pessoal não docente registrado nesta escola.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
