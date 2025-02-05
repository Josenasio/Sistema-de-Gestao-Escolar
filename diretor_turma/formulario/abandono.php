<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro do Aluno - Abandono</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link rel="stylesheet" href="style/style.css">

</head>
<body>



<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard/index.php'">
    <i class="fa fa-arrow-left"></i> Voltar a Pagina Inicial
</button>

<br>
<br>
<br>
<br>
<br>






    <form action="abandono.php" method="POST" id="abandonoo">
    <h1>Formulário sobre Abandono</h1>
    <div id="abandono-fields">

        <div class="separator"></div>

     

            <div class="form-group">
                <label for="bi"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de BI do(a) Aluno(a):</label>
                <input type="number" name="bi[]" placeholder="Digite o número de BI do(a) aluno(a)"  required>
            </div>

            <div class="form-group">
                <label for="numero_ordem"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de Ordem do Aluno:</label>
                <input type="number" name="numero_ordem[]" placeholder="Digite o número de ordem do(a) aluno(a)"  required>
            </div>

            <div class="form-group">
                <label for="motivo_abandono"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Motivo de Abandono:</label>
                <textarea name="motivo_abandono[]" rows="3" placeholder="Digite o motivo de abandono do(a) aluno(a)"  required></textarea>
            </div>

            <div class="form-group">
                <label for="estrategia_recuperacao"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Estratégia de Recuperaçao do(a) Aluno(a):</label>
                <textarea name="estrategia_recuperacao[]" rows="3" placeholder="Digite a estrategia de recuperaçao do(a) aluno(a)"  required></textarea>
            </div>
        </div>

        <button type="button" class="btn btn-secondary" id="add-abandono">Adicionar Outro Caso de Abandono</button>
        <button type="submit" class="btn">Enviar</button>
    </form>


    <div class="popup" id="successPopup">
    Dados enviados com sucesso!
</div>

    <script>
        document.getElementById('add-abandono').addEventListener('click', function() {
            const fields = ` <div class="separatorpequeno"></div><div class="form-group">
                <label for="bi"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de BI do(a) Aluno(a):</label>
                <input type="number" name="bi[]" placeholder="Digite o número de BI do(a) aluno(a)"  required>
            </div>

            <div class="form-group">
                <label for="numero_ordem"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de Ordem do Aluno:</label>
                <input type="number" name="numero_ordem[]" placeholder="Digite o número de ordem do(a) aluno(a)"  required>
            </div>

            <div class="form-group">
                <label for="motivo_abandono"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Motivo de Abandono:</label>
                <textarea name="motivo_abandono[]" rows="3" placeholder="Digite o motivo de abandono do(a) aluno(a)"  required></textarea>
            </div>

            <div class="form-group">
                <label for="estrategia_recuperacao"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Estratégia de Recuperaçao do(a) Aluno(a):</label>
                <textarea name="estrategia_recuperacao[]" rows="3" placeholder="Digite a estrategia de recuperaçao do(a) aluno(a)"  required></textarea>
            </div>`;
            
            ;
            document.getElementById('abandono-fields').insertAdjacentHTML('beforeend', fields);
        });



        document.getElementById('abandonoo').addEventListener('submit', function (event) {
        event.preventDefault(); // Previne o envio normal
        const formData = new FormData(this);

        // Envia os dados via AJAX
        fetch('abandono.php', {
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
            }, 2000);
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
                             SET motivo_abandono = ?, estrategia_recuperacao = ?
                             WHERE bi = ? 
                             AND numero_ordem = ?");

    // Loop pelos dados recebidos do formulário
    foreach ($_POST['bi'] as $index => $bi) {

        $numero_ordem = $_POST['numero_ordem'][$index];
        $motivo_abandono = $_POST['motivo_abandono'][$index];
        $estrategia_recuperacao = $_POST['estrategia_recuperacao'][$index];

        // Bind parameters para o UPDATE
        $stmt->bind_param("ssss", 
            $estrategia_recuperacao, $motivo_abandono, $bi, $numero_ordem
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




