<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aluno - Deficiente</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard.php'">Voltar a Pagina Inicial</button>
<br>
<br>
<br>
<br>
<br>


    <form action="deficiencia.php" method="POST" id="deficientee">
    <h1>Formulário sobre Deficiência</h1>
   

            <div class="form-group">
                <label for="bi">Número de BI do(a) Aluno(a):</label>
                <input type="number" name="bi[]" required>
            </div>

            <div class="form-group">
                <label for="numero_ordem">Número de Ordem do Aluno:</label>
                <input type="number" name="numero_ordem[]" required>
            </div>

            <div class="form-group">
                <label for="deficiente">Deficiente:</label>
                <select name="deficiente[]" required>
                    <option value="1">Sim</option>
                    <option value="0">Nao</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo_deficiencia">Tipo de Deficiência:</label>
                <textarea name="tipo_deficiencia[]" rows="3" required></textarea>
            </div>
        </div>

        <button type="button" class="btn btn-secondary" id="add-deficiencia">Adicionar Outro Caso de Deficiência</button>
        <button type="submit" class="btn">Enviar</button>
    </form>

    <div class="popup" id="successPopup">
    Dados enviados com sucesso!
</div>

    <script>
        document.getElementById('add-deficiencia').addEventListener('click', function() {
            const fields = ` <div class="separatorpequeno"></div>
            <div class="form-group">
                <label for="bi">Número de BI do(a) Aluno(a):</label>
                <input type="number" name="bi[]" required>
            </div>
            <div class="form-group">
                <label for="numero_ordem">Número de Ordem do Aluno:</label>
                <input type="number" name="numero_ordem[]" required>
            </div>
            <div class="form-group">
                <label for="deficiente">Deficiente:</label>
                <select name="deficiente[]" required>
                    <option value="1">Sim</option>
                    <option value="0">Nao</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo_deficiencia">Tipo de Deficiência:</label>
                <textarea name="tipo_deficiencia[]" rows="3" required></textarea>
            </div>`;
            document.getElementById('deficiencia-fields').insertAdjacentHTML('beforeend', fields);
        });
        document.getElementById('deficientee').addEventListener('submit', function (event) {
        event.preventDefault(); // Previne o envio normal
        const formData = new FormData(this);

        // Envia os dados via AJAX
        fetch('deficiencia.php', {
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
    SET deficiente = ?, tipo_deficiencia = ? 
    WHERE bi = ? 
    AND numero_ordem = ?");

    // Loop pelos dados recebidos do formulário
    foreach ($_POST['bi'] as $index => $bi) {
        $numero_ordem = $_POST['numero_ordem'][$index];
        $deficiente = $_POST['deficiente'][$index];
        $tipo_deficiencia = $_POST['tipo_deficiencia'][$index];

        // Executar a query
        $stmt->bind_param('isii', $deficiente, $tipo_deficiencia, $bi, $numero_ordem);
        $stmt->execute();
    }
}
$mysqli->close();
?>
