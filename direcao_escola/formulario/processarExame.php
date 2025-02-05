<?php
// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

$bi = $_POST['bi'];
$numero_ordem = $_POST['numero_ordem'];
$nome_disciplina = $_POST['nome_disciplina'];
$nota_fase1 = $_POST['nota_fase1'];

$success = true;

for ($i = 0; $i < count($bi); $i++) {
    $bi_value = $bi[$i];
    $numero_ordem_value = $numero_ordem[$i];
    $nome_disciplina_value = $nome_disciplina[$i];
    $nota_fase1_value = $nota_fase1[$i];

    // Verificar se o aluno existe
    $sql = "SELECT id FROM aluno WHERE bi='$bi_value' AND numero_ordem='$numero_ordem_value'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $aluno_id = $row['id'];

        // Inserir na tabela exame
        $sql_insert = "INSERT INTO exame (aluno_id, nome_didciplina, nota_fase1) VALUES ('$aluno_id', '$nome_disciplina_value', '$nota_fase1_value')";
        if (!$mysqli->query($sql_insert)) {
            $success = false;
            break;
        }
    } else {
        $success = false;
        break;
    }
}

$mysqli->close();

if ($success) {
    echo "<script>
            window.opener.document.getElementById('popup-message').innerText = 'Registro realizado com sucesso!';
            window.opener.document.getElementById('popup').style.display = 'block';
            window.close();
          </script>";
} else {
    echo "<script>
            window.opener.document.getElementById('popup-message').innerText = 'Falha ao registrar. Verifique os dados.';
            window.opener.document.getElementById('popup').style.display = 'block';
            window.close();
          </script>";
}
?>