<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $mysqli->real_escape_string($_POST['senha']);

    // Consulta ao banco de dados
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificação da senha (criptografada no banco com password_hash)
        if (password_verify($senha, $user['senha'])) {
            // Armazenar dados na sessão
            $_SESSION['id'] = $user['id'];
            $_SESSION['tipo'] = $user['tipo'];
            $_SESSION['id_escola'] = $user['id_escola'];

            
            // Obter nome da escola com base no id_escola
            $query_escola = "SELECT nome FROM escola WHERE id = ?";
            $stmt_escola = $mysqli->prepare($query_escola);
            $stmt_escola->bind_param("i", $user['id_escola']);
            $stmt_escola->execute();
            $result_escola = $stmt_escola->get_result();

            if ($result_escola->num_rows > 0) {
                $escola = $result_escola->fetch_assoc();
                $_SESSION['nome_escola'] = $escola['nome'];
            } else {
                $_SESSION['nome_escola'] = 'Escola não Registrada';
            }

            // Redirecionamento baseado no nível de acesso
            switch ($user['tipo']) {
                case 'administrador':
                    header("Location: ../dashboard/dashboard.php");
                    break;
                case 'direcao':
                    header("Location: ../direcao_escola/dashboard.php");
                    break;
                case 'professor':
                    header("Location: ../diretor_turma/dashboard/index.php");
                    break;
                default:
                    echo "Tipo de usuário não reconhecido!";
            }
            exit;
        } else {
            header("Location: ../erro/senha_errada.php");
        }
    } else {
        header("Location: ../erro/senha_errada.php");
    }
} else {
    echo "Método de requisição inválido!";
}
?>
