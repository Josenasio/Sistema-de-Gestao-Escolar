

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aluno - Gravidez</title>
    <link rel="stylesheet" href="style/style.css">
 
</head>
<body>

<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard.php'">Voltar a Pagina Inicial</button>
<br>
<br>
<br>
<br>
<br>

<form id="gravidezForm">
    <h1>Formulário sobre Gravidez</h1>
    <div id="gravidez-fields">
    
        <div class="separator"></div>

        <div class="form-group">
            <label for="bi">Número de BI:</label>
            <input type="number" name="bi[]" required>
        </div>

        <div class="form-group">
            <label for="numero_ordem">Número de Ordem do Aluno:</label>
            <input type="number" name="numero_ordem[]" required>
        </div>

        <div class="form-group">
            <label for="observacao">Observaçao:</label>
            <textarea name="observacao[]" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="data_conhecimento_gravidez">Data de Conhecimento da Gravidez:</label>
            <input type="date" name="data_conhecimento_gravidez[]" required>
        </div>
    </div>
    <button type="button" class="btn btn-secondary" id="add-gravidez">Adicionar Outro Caso de Gravidez</button>
    <button type="submit" class="btn">Enviar</button>
</form>

<div class="popup" id="successPopup">
    Dados enviados com sucesso!
</div>

<script>
    document.getElementById('add-gravidez').addEventListener('click', function () {
        const fields = `
        <div class="separator"></div>
        <div class="form-group">
            <label for="bi">Número de BI:</label>
            <input type="number" name="bi[]" required>
        </div>
        <div class="form-group">
            <label for="numero_ordem">Número de Ordem do Aluno:</label>
            <input type="number" name="numero_ordem[]" required>
        </div>
        <div class="form-group">
            <label for="observacao">Observaçao:</label>
            <textarea name="observacao[]" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="data_conhecimento_gravidez">Data de Conhecimento da Gravidez:</label>
            <input type="date" name="data_conhecimento_gravidez[]" required>
        </div>`;
        document.getElementById('gravidez-fields').insertAdjacentHTML('beforeend', fields);
    });

    document.getElementById('gravidezForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Previne o envio normal
        const formData = new FormData(this);

        // Envia os dados via AJAX
        fetch('gravidez.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            const popup = document.getElementById('successPopup');
            popup.classList.add('active');
            setTimeout(() => {
                popup.classList.remove('active');

                window.location.href = '/destp_pro/diretor_turma/dashboard.php';
            }, 3000);
            console.log(data); // Log para debug do servidor
        })
        .catch(error => console.error('Erro:', error));
    });
</script>
</body>
</html>
<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');
// Processando os dados do formulário ao enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare a statement to avoid SQL injection
    $stmt = $mysqli->prepare("UPDATE aluno 
                             SET observacao_gravidez = ?, data_conhecimento_gravidez = ?, gravidez = 1 
                             WHERE bi = ? 
                             AND numero_ordem = ?");

    // Loop pelos dados recebidos do formulário
    foreach ($_POST['bi'] as $index => $bi) {

        $numero_ordem = $_POST['numero_ordem'][$index];
        $observacao = $_POST['observacao'][$index];
        $data_conhecimento = $_POST['data_conhecimento_gravidez'][$index];

        // Bind parameters para o UPDATE
        $stmt->bind_param("ssss", 
            $observacao, $data_conhecimento, $bi, $numero_ordem
        );

        // Execute a query
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
            } 
        } 
    }

    // Fechar o statement e a conexao
    $stmt->close();
    $mysqli->close();


    
}
?>