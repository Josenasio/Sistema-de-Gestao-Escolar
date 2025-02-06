<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");
    exit;
}
// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');


// Verificar se o id_escola está definido na sessão
if (!isset($_SESSION['id_escola'])) {
    die("Erro: ID da escola não definido.");
}
 
// Recuperar o id_escola da sessão
$id_escola = $_SESSION['id_escola'];





// Carrega os nomes das escolas para o select
$nomes = $mysqli->query("SELECT nome FROM escola");

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera os valores dos campos
  
    $nome_diretor = $_POST['nome_diretor'];
    $telefone_fixo = $_POST['telefone_fixo'];
    $email_escola = $_POST['email_escola'];
    $agua_consumida = $_POST['agua_consumida'];
    $abastecimento_energia = $_POST['abastecimento_energia'];
    $destinacao_lixo = $_POST['destinacao_lixo'];
    $numero_computador = $_POST['numero_computador'];
    $numero_computador_funcionamento = $_POST['numero_computador_funcionamento'];
    $acesso_internet = $_POST['acesso_internet'];
    $acesso_banda_larga = $_POST['acesso_banda_larga'];
    $alimentacao = $_POST['alimentacao'];
    $vedacao = $_POST['vedacao'];
    $via_aluno_deficiente = $_POST['via_aluno_deficiente'];
    $biblioteca = $_POST['biblioteca'];
    $anfiteatro = $_POST['anfiteatro'];
    $cantina = $_POST['cantina'];
    $ginasio = $_POST['ginasio'];
    $campo_desportivo = $_POST['campo_desportivo'];
    $numero_wc_professor = $_POST['numero_wc_professor'];
    $numero_wc_diretor = $_POST['numero_wc_diretor'];
    $numero_wc_aluno = $_POST['numero_wc_aluno'];
    $numero_wc_aluna = $_POST['numero_wc_aluna'];
    $laboratorio_fisica = $_POST['laboratorio_fisica'];
    $laboratorio_quimica = $_POST['laboratorio_quimica'];
    $laboratorio_biologia = $_POST['laboratorio_biologia'];
    $sala_informatica = $_POST['sala_informatica'];
    $sala_professor = $_POST['sala_professor'];
    $numero_sala_aula_existente = $_POST['numero_sala_aula_existente'];
    $wc_masculino_feminino = $_POST['wc_masculino_feminino'];
    $manha7 = $_POST['7manha'];
    $tarde7 = $_POST['7tarde'];
    $manha8 = $_POST['8manha'];
    $tarde8 = $_POST['8tarde'];
    $manha9 = $_POST['9manha'];
    $tarde9 = $_POST['9tarde'];
    $manha10 = $_POST['10manha'];
    $tarde10 = $_POST['10tarde'];
    $manha11 = $_POST['11manha'];
    $tarde11 = $_POST['11tarde'];
    $manha12 = $_POST['12manha'];
    $tarde12 = $_POST['12tarde'];

    // Prepara a consulta de atualizaçao
    $stmt = $mysqli->prepare("UPDATE escola SET 
        nome_diretor=?, telefone_fixo=?, email_escola=?, agua_consumida=?, abastecimento_energia=?, destinacao_lixo=?, numero_computador=?,
         numero_computador_funcionamento=?, acesso_internet=?, acesso_banda_larga=?, alimentacao=?, vedacao=?, via_aluno_deficiente=?, biblioteca=?, 
         anfiteatro=?, cantina=?, ginasio=?, campo_desportivo=?, numero_wc_professor=?, numero_wc_diretor=?, numero_wc_aluno=?, numero_wc_aluna=?, laboratorio_fisica=?, 
         laboratorio_quimica=?, laboratorio_biologia=?, sala_informatica=?, sala_professor=?, numero_sala_aula_existente=?, wc_masculino_feminino=?, 7manha=?,
          7tarde=?, 8manha=?, 8tarde=?, 9manha=?, 9tarde=?, 10manha=?, 10tarde=?, 11manha=?, 11tarde=?, 12manha=?, 12tarde=? WHERE id=$id_escola");

    // Bind dos parâmetros
    $stmt->bind_param("ssssssiissssssssssiiiisssssssssssssssssss", 
        $nome_diretor, $telefone_fixo, $email_escola, $agua_consumida, $abastecimento_energia, $destinacao_lixo, $numero_computador, $numero_computador_funcionamento, $acesso_internet, $acesso_banda_larga, $alimentacao, $vedacao, $via_aluno_deficiente, $biblioteca, $anfiteatro, $cantina, $ginasio, $campo_desportivo, $numero_wc_professor, $numero_wc_diretor, $numero_wc_aluno, $numero_wc_aluna, $laboratorio_fisica, $laboratorio_quimica, $laboratorio_biologia, $sala_informatica, $sala_professor, $numero_sala_aula_existente, $wc_masculino_feminino, $manha7, $tarde7, $manha8, $tarde8, $manha9, $tarde9, $manha10, $tarde10, $manha11, $tarde11, $manha12, $tarde12);

    // Executa a atualizaçao
    $stmt->execute();

    // Nao exibir mensagens de sucesso ou erro
    $stmt->close();

        // Redireciona para outra página após a execução
        header("Location: sucesso.php");
        exit; // Encerra o script após o redirecionamento
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Atualização da Escola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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
    </style>
</head>
<body>
<!-- Botão com ícone -->
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
</button>
<br>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Atualizar Informações da Escola</h1>
        <form method="POST" action="">
            <!-- Diretor e Telefone -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="nome_diretor" class="form-label"><i class="fas fa-user"></i> Nome do Diretor</label>
                    <input type="text" id="nome_diretor" name="nome_diretor" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="telefone_fixo" class="form-label"><i class="fas fa-phone"></i> Telefone Fixo</label>
                    <input type="text" id="telefone_fixo" name="telefone_fixo" class="form-control" required>
                </div>
            </div>

            <!-- Email e Água Consumida -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="email_escola" class="form-label"><i class="fas fa-envelope"></i> Email da Escola</label>
                    <input type="email" id="email_escola" name="email_escola" class="form-control" required>
                </div>
                <div class="col-md-6">
    <label for="agua_consumida" class="form-label"><i class="fas fa-tint"></i> Água Consumida</label>
    <select id="agua_consumida" name="agua_consumida" class="form-control" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Nao tem">Não tem</option>
        <option value="Potável">Água Potável</option>
        <option value="Nao Potável">Água Não Potável</option>
        <option value="Água de Rio">Água de Rio</option>
        <option value="Trazem de casa">Trazem de casa</option>
        <option value="Outros">Outros</option>
    </select>
</div>

            </div>

            <!-- Energia e Lixo -->
            <div class="row g-3 mb-4">
            <div class="col-md-6">
    <label for="abastecimento_energia" class="form-label"><i class="fas fa-bolt"></i> Abastecimento de Energia</label>
    <select id="abastecimento_energia" name="abastecimento_energia" class="form-control" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Energia Renovável">Energia Renovável</option>
        <option value="Energia Nao Renovável">Energia Não Renovável</option>
        <option value="EMAE">EMAE</option>
        <option value="Nao Tem">Não Tem</option>
        <option value="Outros">Outros</option>
    </select>
</div>

<div class="col-md-6">
    <label for="destinacao_lixo" class="form-label"><i class="fas fa-trash-alt"></i> Destinação do Lixo</label>
    <select id="destinacao_lixo" name="destinacao_lixo" class="form-control" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Queima">Queima</option>
        <option value="Deita Noutra Área">Deita Noutra Área</option>
        <option value="Coloca no Contentor">Coloca no Contentor</option>
        <option value="Joga no Mato">Joga no Mato</option>
        <option value="Outros">Outros</option>
    </select>
</div>

            </div>

            <!-- Computadores -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="numero_computador" class="form-label"><i class="fas fa-desktop"></i> Número de Computadores Existentes</label>
                    <input type="number" id="numero_computador" name="numero_computador" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="numero_computador_funcionamento" class="form-label"><i class="fas fa-check-circle"></i> Número de Computadores em Funcionamento</label>
                    <input type="number" id="numero_computador_funcionamento" name="numero_computador_funcionamento" class="form-control" required>
                </div>
            </div>

            <!-- Internet e Banda Larga -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="acesso_internet" class="form-label"><i class="fas fa-wifi"></i> Acesso à Internet</label>
                    <select id="acesso_internet" name="acesso_internet" class="form-select" required>
                    <option disabled selected value="">Selecione uma opção</option>
                    <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="acesso_banda_larga" class="form-label"><i class="fas fa-signal"></i> Banda Larga</label>
                    <select id="acesso_banda_larga" name="acesso_banda_larga" class="form-select" required>
                    <option disabled selected value="">Selecione uma opção</option>   
                    <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>
            </div>

            <!-- Alimentação e Vedação -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="alimentacao" class="form-label"><i class="fas fa-utensils"></i> Alimentação</label>
                    <select id="alimentacao" name="alimentacao" class="form-select" required>
                    <option disabled selected value="">Selecione uma opção</option>   
                    <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                        
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="vedacao" class="form-label"><i class="fas fa-building"></i> Vedação</label>
                    <select id="vedacao" name="vedacao" class="form-select" required>
                    <option value="">Selecione uma opçao</option>
        <option value="Cercado com Flôr">Cercado com Flôr</option>
        <option value="Cercado com Chapa">Cercado com Chapa</option>
        <option value="Cercado com Murro">Cercado com Murro</option>
        <option value="Cercado com Rede">Nao Tem Cercado</option>
        <option value="Outros">Outros</option>
                    </select>
                </div>
            </div>

            <!-- Instalações -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="biblioteca" class="form-label"><i class="fas fa-book"></i>A Escola Possui Biblioteca</label>
                    <select id="biblioteca" name="biblioteca" class="form-select" required>
                    <option value="">Selecione uma opçao</option>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="anfiteatro" class="form-label"><i class="fas fa-theater-masks"></i> A Escola possui Anfiteatro ?</label>
                    <select id="anfiteatro" name="anfiteatro" class="form-select" required>
                    <option value="">Selecione uma opçao</option>
        <option value="Sim Adequado">Sim Adequado</option>
        <option value="Sim Inadequado">Sim Inadequado</option>
        <option value="Nao Tem">Nao Tem</option>
                    </select>
                </div>
                
            </div>

            <!-- Ginásio e Campos -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="ginasio" class="form-label"><i class="fas fa-dumbbell"></i> A Escola possui Ginásio ?:</label>
                    <select id="ginasio" name="ginasio" class="form-select" required>
                    <option value="">Selecione uma opçao</option>
        <option value="Sim Adequado">Sim Adequado</option>
        <option value="Sim Inadequado">Sim Inadequado</option>
        <option value="Nao">Nao</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="campo_desportivo" class="form-label"><i class="fas fa-futbol"></i> A Escola possui Campo Desportivo ?:</label>
                    <select id="campo_desportivo" name="campo_desportivo" class="form-select" required>
                    <option value="">Selecione uma opçao</option>
        <option value="Sim Adequado">Sim, Adequado</option>
        <option value="Sim Inadequado">Sim, Inadequado</option>
        <option value="Nao">Nao</option>
                    </select>
                </div>
            </div>


  <!-- Salas e Laboratórios -->
  <div class="row g-3 mb-4">
  <div class="col-md-6">
    <label for="laboratorio_fisica" class="form-label"><i class="fas fa-flask"></i> A Escola tem Laboratório de Física</label>
    <select id="laboratorio_fisica" name="laboratorio_fisica" class="form-control" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Sim Adequado">Sim, Adequado</option>
        <option value="Sim Inadequado">Sim, Inadequado</option>
        <option value="Nao">Não</option>
    </select>
</div>

<div class="col-md-6">
    <label for="laboratorio_quimica" class="form-label"><i class="fas fa-flask"></i> A Escola tem Laboratório de Química</label>
    <select id="laboratorio_quimica" name="laboratorio_quimica" class="form-control" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Sim Adequado">Sim, Adequado</option>
        <option value="Sim Inadequado">Sim, Inadequado</option>
        <option value="Nao">Não</option>
    </select>
</div>
              
            </div>

            <div class="row g-3 mb-4">
            <div class="col-md-6">
    <label for="sala_informatica" class="form-label"><i class="fas fa-flask"></i> A Escola tem Sala de Informática</label>
    <select id="sala_informatica" name="sala_informatica" class="form-control" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Sim Adequado">Sim, Adequado</option>
        <option value="Sim Inadequado">Sim, Inadequado</option>
        <option value="Nao">Não</option>
    </select>
</div>
<div class="col-md-6">
    <label for="sala_professor" class="form-label"><i class="fas fa-flask"></i> A Escola tem Sala dos Professores ?:</label>
    <select id="sala_professor" name="sala_professor" class="form-control" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Sim Adequado">Sim, Adequado</option>
        <option value="Sim Inadequado">Sim, Inadequado</option>
        <option value="Nao">Não</option>
    </select>
</div>
            </div>





            <div class="row g-3 mb-4">
            <div class="col-md-6">
                    <label for="cantina" class="form-label"><i class="fas fa-coffee"></i> A Escola possui Cantina ?</label>
                    <select id="cantina" name="cantina" class="form-select" required>
                    <option disabled selected value="">Selecione uma opçao</option>
        <option value="Sim">Sim</option>
        <option value="Nao">Nao</option>
                    </select>
                </div>

                <div class="col-md-6">
    <label for="laboratorio_biologia" class="form-label"><i class="fas fa-flask"></i> A Escola tem Laboratório de Biologia</label>
    <select id="laboratorio_biologia" name="laboratorio_biologia" class="form-control" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Sim Adequado">Sim, Adequado</option>
        <option value="Sim Inadequado">Sim, Inadequado</option>
        <option value="Nao">Não</option>
    </select>
</div>
            </div>









            




            <!-- Banheiros -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="numero_wc_diretor" class="form-label"><i class="fas fa-restroom"></i>Número de da WC Direção</label>
                    <input type="number" id="numero_wc_diretor" name="numero_wc_diretor" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="numero_wc_professor" class="form-label"><i class="fas fa-restroom"></i>Número de WC dos Professores(as)</label>
                    <input type="number" id="numero_wc_professor" name="numero_wc_professor" class="form-control" required>
                </div>
            </div>










    <!-- teste -->
 <div class="row g-3 mb-4">
    <div class="col-md-6">
    <label for="via_aluno_deficiente" class="form-label"><i class="fas fa-wheelchair"></i> Via para Aluno Deficiente:</label>
    <select id="via_aluno_deficiente" name="via_aluno_deficiente" class="form-select" required>
        <option disabled selected value="">Selecione uma opção</option>
        <option value="Sim">Sim</option>
        <option value="Não">Não</option>
    </select>
</div>
              
     <div class="col-md-6">
         <label for="wc_masculino_feminino" class="form-label"><i class="fas fa-restroom"></i>Número de WC Unissex (alunos(as))</label>
         <input type="number" id="wc_masculino_feminino" name="wc_masculino_feminino" class="form-control" required>
     </div>
 </div>











                

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="numero_wc_aluna" class="form-label"><i class="fas fa-restroom"></i> Número de WC das Alunas</label>
                    <input type="number" id="numero_wc_aluna" name="numero_wc_aluna" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="numero_wc_aluno" class="form-label"><i class="fas fa-restroom"></i> Número de WC dos Alunos</label>
                    <input type="number" id="numero_wc_aluno" name="numero_wc_aluno" class="form-control" required>
                </div>
            </div>



          

          

            <!-- Salas de Aula -->
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label for="numero_sala_aula_existente" class="form-label"><i class="fas fa-school"></i> Número de Salas de Aula Existentes</label>
                    <input type="number" id="numero_sala_aula_existente" name="numero_sala_aula_existente" class="form-control" required>
                </div>
            </div>

            <!-- Turnos -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="7manha" class="form-label">Total de Alunos(as) da 7ª Classe <span style="color:#87CEEB; font-weight:bold; letter-spacing: 2px;">Manhã</span></label>
                    <input type="number" id="7manha" name="7manha" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="7tarde" class="form-label">Total de Alunos(as) da 7ª Classe <span style="color:#FF8C00; font-weight:bold; letter-spacing: 2px;">Tarde</span></label>
                    <input type="number" id="7tarde" name="7tarde" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="8manha" class="form-label">Total de Alunos(as) da 8ª Classe <span style="color:#87CEEB; font-weight:bold; letter-spacing: 2px;">Manhã</span></label>
                    <input type="number" id="8manha" name="8manha" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="8tarde" class="form-label">Total de Alunos(as) da 8ª Classe <span style="color:#FF8C00; font-weight:bold; letter-spacing: 2px;">Tarde</span></label>
                    <input type="number" id="8tarde" name="8tarde" class="form-control">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="9manha" class="form-label">Total de Alunos(as) da 9ª Classe <span style="color:#87CEEB; font-weight:bold; letter-spacing: 2px;">Manhã</span></label>
                    <input type="number" id="9manha" name="9manha" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="9tarde" class="form-label">Total de Alunos(as) da 9ª Classe <span style="color:#FF8C00; font-weight:bold; letter-spacing: 2px;">Tarde</span></label>
                    <input type="number" id="9tarde" name="9tarde" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="10manha" class="form-label">Total de Alunos(as) da 10ª Classe <span style="color:#87CEEB; font-weight:bold; letter-spacing: 2px;">Manhã</span></label>
                    <input type="number" id="10manha" name="10manha" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="10tarde" class="form-label">Total de Alunos(as) da 10ª Classe <span style="color:#FF8C00; font-weight:bold; letter-spacing: 2px;">Tarde</span></label>
                    <input type="number" id="10tarde" name="10tarde" class="form-control">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="11manha" class="form-label">Total de Alunos(as) da 11ª Classe <span style="color:#87CEEB; font-weight:bold; letter-spacing: 2px;">Manhã</span></label>
                    <input type="number" id="11manha" name="11manha" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="11tarde" class="form-label">Total de Alunos(as) da 11ª Classe <span style="color:#FF8C00; font-weight:bold; letter-spacing: 2px;">Tarde</span></label>
                    <input type="number" id="11tarde" name="11tarde" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="12manha" class="form-label">Total de Alunos(as) da 12ª Classe <span style="color:#87CEEB; font-weight:bold; letter-spacing: 2px;">Manhã</span></label>
                    <input type="number" id="12manha" name="12manha" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="12tarde" class="form-label">Total de Alunos(as) da 12ª Classe <span style="color:#FF8C00; font-weight:bold; letter-spacing: 2px;">Tarde</span></label>
                    <input type="number" id="12tarde" name="12tarde" class="form-control">
                </div>
            </div>



    




            <!-- Botão de Envio -->
            <div class="text-center mt-4">
    <button type="submit" class="btn btn-primary  mb-3" style="font-size: 1.2rem; width:80%;">
        <i class="fas fa-save"></i> Atualizar
    </button>
</div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
