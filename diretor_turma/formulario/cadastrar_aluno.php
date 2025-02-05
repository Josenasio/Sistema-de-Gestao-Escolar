<?php 
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../index.php");  // Caminho relativo para subir 4 níveis
    exit;
}

// Conexão com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Verificar se o id_escola está definido na sessão
if (!isset($_SESSION['id_escola'])) {
    die("Erro: ID da escola não definido.");
}

// Recuperar o id_escola e id_usuario da sessão
$id_escola = $_SESSION['id_escola'];
$id_usuario = $_SESSION['id'];  // ID do usuário logado (professor)

// Função para buscar dados da tabela
function fetchData($mysqli, $table) {
    $result = $mysqli->query("SELECT * FROM $table");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Obter dados das tabelas necessárias
$classes = fetchData($mysqli, 'classe');
$turmas = fetchData($mysqli, 'turma');
$periodos = fetchData($mysqli, 'periodo_dia');
$cursos = fetchData($mysqli, 'curso');
$religioes = fetchData($mysqli, 'religiao');
$distritos = fetchData($mysqli, 'distrito');

// Buscar os dados do usuário logado (professor) para preencher os campos turma_id, classe_id, curso_id, periodo_dia_id
$query_usuario = $mysqli->prepare("SELECT classe_id, turma_id, curso_id, periodo_dia_id FROM usuarios WHERE id = ?");
$query_usuario->bind_param("i", $id_usuario);
$query_usuario->execute();
$result_usuario = $query_usuario->get_result();
$usuario_data = $result_usuario->fetch_assoc();

// Verificar se os dados do usuário foram encontrados
if (!$usuario_data) {
    die("Erro: Não foi possível recuperar os dados do usuário.");
}

$usuario_classe_id = $usuario_data['classe_id'];
$usuario_turma_id = $usuario_data['turma_id'];
$usuario_curso_id = $usuario_data['curso_id'];
$usuario_periododia_id = $usuario_data['periodo_dia_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se o array 'alunos' existe no POST
    if (isset($_POST['alunos']) && is_array($_POST['alunos'])) {
        $alunos = $_POST['alunos']; // Array de alunos

        foreach ($alunos as $aluno) {
            // Dados do aluno
            $nome = $aluno['nome'];
            $idade = $aluno['idade'];
            $data_nascimento = $aluno['data_nascimento'];
            $endereco = $aluno['endereco'];
            $genero = $aluno['genero'];
            $numero_frequencia = $aluno['numero_frequencia'];
            $numero_ordem = $aluno['numero_ordem'];
            $telefone = $aluno['telefone'];
            $nome_encarregado = $aluno['nome_encarregado'];
            $contato_encarregado = $aluno['contato_encarregado'];
            $bi = $aluno['bi'];
            $naturalidade = $aluno['naturalidade'];
            $data_emissao_bi = $aluno['data_emissao_bi'];
            $situacao_economica = $aluno['situacao_economica'];
            $filhos = $aluno['filhos'];
            $id_distrito = $aluno['id_distrito'];
            $religiao_id = $aluno['religiao_id'];

            // Inserir aluno no banco de dados com os valores do usuário logado
            $stmt = $mysqli->prepare("INSERT INTO aluno (nome, idade, data_nascimento, endereco, genero, numero_frequencia, numero_ordem, telefone, nome_encarregado, contato_encarregado, bi, naturalidade, filhos, data_emissao_bi, situacao_economica, escola_id, turma_id, classe_id, curso_id, religiao_id, periododia_id, id_distrito, id_diretor_turma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisssssssssssssiiiiiiii", 
                $nome, $idade, $data_nascimento, $endereco, $genero, $numero_frequencia, 
                $numero_ordem, $telefone, $nome_encarregado, $contato_encarregado, $bi, 
                $naturalidade, $filhos, $data_emissao_bi, $situacao_economica, $id_escola, 
                $usuario_turma_id, $usuario_classe_id, $usuario_curso_id, $religiao_id, 
                $usuario_periododia_id, $id_distrito, $id_usuario);

            if (!$stmt->execute()) {
                echo "<script>alert('Erro ao cadastrar aluno: " . $stmt->error . "');</script>";
            }
            $aluno_id = $stmt->insert_id; // ID do aluno recém inserido
            $stmt->close();
        }

  // Redireciona usando o header
  header("Location: sucesso.php"); // O caminho do arquivo de sucesso
  exit;
    } else {
        echo "<script>alert('Nenhum aluno foi cadastrado.');</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Alunos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">



    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgba(0, 0, 0, 0.19);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
        button {
            background-color: rgb(4, 114, 10);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: rgb(0, 252, 13);
        }
        @media (max-width: 600px) {
            .container {
                width: 100%;
                padding: 5px;
               
            }
            button {
                width: 48%;
            }
        }
        .aluno {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .remove-aluno {
            background-color: #dc3545;
            margin-top: 10px;
        }
        .remove-aluno:hover {
            background-color: #c82333;
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
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/diretor_turma/dashboard/index.php'">
    <i class="fa fa-arrow-left"></i> Voltar a Pagina Inicial
</button>

<br>
<br>




<div class="container">
    <h1>Cadastro de Alunos(as)</h1>
    <form method="post" action="cadastrar_aluno.php">

    
    <input type="hidden" name="escola_id" value="<?= $id_escola ?>">

  
<input type="hidden" name="classe_id" id="classe_id" value="<?= htmlspecialchars($usuario_classe_id) ?>" required>
<input type="hidden" name="turma_id" id="turma_id" value="<?= htmlspecialchars($usuario_turma_id) ?>" required>
<input type="hidden" name="curso_id" id="curso_id" value="<?= htmlspecialchars($usuario_curso_id) ?>" required>
<input type="hidden" name="periodo_id" id="periodo_id" value="<?= htmlspecialchars($usuario_periododia_id) ?>" required>


 

<div id="aluno-container">
    <div class="aluno">
        <h3 style="color:red;"><i class="fa fa-user"></i> Aluno(a) 1</h3>

        <input type="hidden" name="alunos[0][id]" value="0">

        <!-- Dados Pessoais -->
        <fieldset>
            <legend><i class="fa fa-id-card"></i> Dados Pessoais</legend>

            <label for="nome"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nome do Aluno(a):</label>
            <input type="text" name="alunos[0][nome]" pattern="[A-Za-zÀ-ÿ\s]+" title="O nome deve conter apenas letras e espaços." placeholder="Digite o nome do(a) aluno(a)" required>

            <label for="idade"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Idade do Aluno(a):</label>
            <input type="number" name="alunos[0][idade]" min="10" max="50" title="A idade deve estar entre 10 a 50 anos" placeholder="Digite a idade do(a) aluno(a)" required>

            <label for="data_nascimento"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Data de Nascimento do Aluno(a):</label>
            <input type="date" name="alunos[0][data_nascimento]" min="1974-01-01" max="2015-12-31" required>

            <label for="genero"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Gênero do Aluno(a):</label>
            <select name="alunos[0][genero]" required>
                <option value="" disabled selected>--Selecione um gênero--</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
            </select>

            <label for="naturalidade"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Naturalidade do Aluno(a):</label>
            <input type="text" name="alunos[0][naturalidade]" maxlength="100" placeholder="Digite a naturalidade do(a) aluno(a)" required>

            <label for="numero_frequencia"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de Frequência (Repitente):</label>
            <input type="number" name="alunos[0][numero_frequencia]" min="0" max="10" placeholder="Digite o número de frequência do(a) aluno(a)" required>

            <label for="numero_ordem"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de Ordem do Aluno(a):</label>
            <input type="number" name="alunos[0][numero_ordem]" min="1" max="100" placeholder="Digite o número de ordem do(a) aluno(a)" required>
        </fieldset>
        <br>

        <!-- Informações de Contato -->
        <fieldset>
            <legend><i class="fa fa-phone"></i> Informações de Contato</legend>

            <label for="telefone">Telefone do Aluno(a):</label>
            <input type="text" name="alunos[0][telefone]" minlength="7" maxlength="7" pattern="\d{7}" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 7);" placeholder="Digite o telefone do(a) aluno(a)">

            <label for="bi"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Numero de BI do Aluno(a):</label>
            <input type="text" name="alunos[0][bi]" minlength="6" maxlength="6" pattern="\d{6}" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6);" placeholder="Digite o número de BI do(a) aluno(a)" required>

            <label for="data_emissao_bi"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Data de Emissao do BI do Aluno(a):</label>
            <input type="date" name="alunos[0][data_emissao_bi]" required>
        </fieldset>

        <br>
        <!-- Informações de Endereço -->
        <fieldset>
            <legend><i class="fa fa-home"></i> Informações de Endereço</legend>

            <label for="endereco"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Endereço do Aluno(a):</label>
            <input type="text" name="alunos[0][endereco]" maxlength="200" placeholder="Digite o endereço do(a) aluno(a)" required>

            <label for="id_distrito"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Distrito do Aluno(a):</label>
            <select name="alunos[0][id_distrito]" id="id_distrito" required>
                <option value="" disabled selected>--Selecione um distrito--</option>
                <?php foreach ($distritos as $distrito): ?>
                    <option value="<?= $distrito['id'] ?>"><?= $distrito['nome_distrito'] ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
        <br>

        <!-- Situação Econômica e Familia -->
        <fieldset>
            <legend><i class="fa fa-money"></i> Situação Econômica e Família</legend>

            <label for="situacao_economica"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Situação Econômica do Aluno(a):</label>
            <select name="alunos[0][situacao_economica]" required>
                <option value="" disabled selected>--Selecione--</option>
                <option value="pobre">Pobre</option>
                <option value="muito pobre">Muito Pobre</option>
                <option value="médio">Médio</option>
                <option value="rico">Rico</option>
                <option value="muito rico">Muito Rico</option>
            </select>

            <label for="filhos"><span style="color: red; font-size:15px; font-weight:bolder">* </span>O(A) aluno(a) tem filho(s)?</label>
            <select name="alunos[0][filhos]" required>
                <option disabled selected value="">--Selecione--</option>
                <option value="Não">Não</option>
                <option value="Sim">Sim</option>
            </select>
        </fieldset>
        <br>

        <!-- Dados Religiosos e Encarregado -->
        <fieldset>
            <legend><i class="fa fa-religion"></i> Dados Religiosos e Encarregado</legend>

            <label for="religiao_id"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Religião do Aluno(a):</label>
            <select name="alunos[0][religiao_id]" id="religiao_id" required>
                <option value="" disabled selected>--Selecione uma religião--</option>
                <?php foreach ($religioes as $religiao): ?>
                    <option value="<?= $religiao['id'] ?>"><?= $religiao['nome_religiao'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="nome_encarregado"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nome do Encarregado do Aluno(a):</label>
            <input type="text" name="alunos[0][nome_encarregado]" pattern="[A-Za-zÀ-ÿ\s]+" title="Digite um nome válido." placeholder="Digite o nome do(a) encarregado(a) do(a) aluno(a)" required>

            <label for="contato_encarregado">Contato do Encarregado do Aluno(a):</label>
            <input type="text" name="alunos[0][contato_encarregado]" minlength="7" maxlength="7" pattern="\d{7}" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 7);" placeholder="Digite o contacto do(a) encarregado(a) do(a) aluno(a)">
        </fieldset>
        <br>

        <input type="button" value="Remover Aluno" class="remove-aluno" onclick="removeAluno(this)">
    </div>
</div>


        <input type="button" value="Adicionar + Aluno(a)" onclick="addAluno()">
        <div class="button-container">
            <button type="submit">Cadastrar</button>
        </div>
    </form>
</div>

<script>
    let alunoCount = 1; // Começando com 1 porque já existe um aluno

    function addAluno() {
     
        const alunoContainer = document.getElementById('aluno-container');
        
        const alunoDiv = document.createElement('div');

        alunoDiv.classList.add('aluno');
        alunoDiv.innerHTML = `
            <h3 style="color:red;"><i class="fa fa-user"></i> Aluno(a) ${alunoCount + 1}</h3>
            <input type="hidden" name="alunos[${alunoCount}][id]" value="0">
       <!-- Dados Pessoais -->
        <fieldset>
            <legend><i class="fa fa-id-card"></i> Dados Pessoais</legend>

          
         <label for="nome"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nome do Aluno(a):</label>
<input type="text" name="alunos[${alunoCount}][nome]" 
       minlength="2" maxlength="200" 
       placeholder="Digite o nome do(a) aluno(a)" 
       onkeyup="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, ' ');" 
       title="O nome deve conter apenas letras e espaços." 
       required>


              <label for="idade"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Idade:</label>
            <input type="number" name="alunos[${alunoCount}][idade]" min="10" max="50"   title="A idade deve estar entre 10 a 50 anos" placeholder="Digite a idade do(a) aluno(a)" required>

             <label for="data_nascimento"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Data de Nascimento do Aluno(a):</label>
            <input type="date" name="alunos[${alunoCount}][data_nascimento]" min="1974-01-01" max="2015-12-31" required>

                      <label for="genero"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Gênero do Aluno(a):</label>
            <select name="alunos[${alunoCount}][genero]" required>
                <option value=""  disabled selected >--Selecione um gênero--</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
           
            </select>

             <label for="naturalidade"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Naturalidade do Aluno(a):</label>
            <input type="text" name="alunos[${alunoCount}][naturalidade]" maxlength="100" placeholder="Digite a naturalidade do(a) aluno(a)" required>

           <label for="numero_frequencia"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de Frequência(Repitente):</label>
            <input type="number" name="alunos[${alunoCount}][numero_frequencia]"  min="0"  max="10"  placeholder="Digite o número de frequência do(a) aluno(a)" required>

                       <label for="numero_ordem"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de Ordem do Aluno(a):</label>
            <input type="number" name="alunos[${alunoCount}][numero_ordem]" min="1"  max="100" placeholder="Digite o número de ordem do(a) aluno(a)" required>
        </fieldset>
        <br>

        <!-- Informações de Contato -->
        <fieldset>
            <legend><i class="fa fa-phone"></i> Informações de Contato</legend>

             <label for="telefone">Telefone do Aluno(a):</label>
            <input type="text" name="alunos[${alunoCount}][telefone]" minlength="7" maxlength="7" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 7);" placeholder="Digite o telefone do(a) aluno(a)">

          <label for="bi"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número de BI do Aluno(a)::</label>
            <input type="text" name="alunos[${alunoCount}][bi]" minlength="6" maxlength="6" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6);" placeholder="Digite o número de BI do(a) aluno(a)" required>

                <label for="data_emissao_bi"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Data de Emissao do BI do Aluno(a):</label>
            <input type="date" name="alunos[${alunoCount}][data_emissao_bi]" required>
        </fieldset>

        <br>
        <!-- Informações de Endereço -->
        <fieldset>
            <legend><i class="fa fa-home"></i> Informações de Endereço</legend>

           <label for="endereco"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Endereço do Aluno(a):</label>
            <input type="text" name="alunos[${alunoCount}][endereco]" maxlength="100" placeholder="Digite o endereço do(a) aluno(a)" required>

           
 <label for="id_distrito"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Distrito do(a) Aluno(a):</label>
        <select name="alunos[${alunoCount}][id_distrito]" required>
            <option disabled selected value="">--Selecione um distrito--</option>
            <?php foreach ($distritos as $distrito): ?>
                <option value="<?= $distrito['id'] ?>"><?= $distrito['nome_distrito'] ?></option>
            <?php endforeach; ?>
        </select>

        </fieldset>
        <br>

        <!-- Situação Econômica e Familia -->
        <fieldset>
            <legend><i class="fa fa-money"></i> Situação Econômica e Família</legend>

                 <label for="situacao_economica"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Situaçao Econômica do Aluno(a):</label>
<select name="alunos[${alunoCount}][situacao_economica]" required>
<option  disabled selected value="">--Selecione--</option>
    <option value="pobre">Pobre</option>
    <option value="muito pobre">Muito Pobre</option>
    <option value="médio">Médio</option>
    <option value="rico">Rico</option>
    <option value="muito rico">Muito Rico</option>
</select>

          <label for="filhos"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Tem Filho ?:</label>
<select name="alunos[${alunoCount}][filhos]" required>
<option  disabled selected value="">--Selecione--</option>
    <option value="Não">Não</option>
    <option value="Sim">Sim</option>
</select>
        </fieldset>
        <br>

        <!-- Dados Religiosos e Encarregado -->
        <fieldset>
            <legend><i class="fa fa-religion"></i> Dados Religiosos e Encarregado</legend>

           <label for="religiao_id"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Religiao do Aluno(a):</label>
            <select name="alunos[${alunoCount}][religiao_id]" required>
                <option value=""  disabled selected>--Selecione uma religiao--</option>
                <?php foreach ($religioes as $religiao): ?>
                    <option value="<?= $religiao['id'] ?>"><?= $religiao['nome_religiao'] ?></option>
                <?php endforeach; ?>
            </select>

           <label for="nome_encarregado"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nome do Encarregado do Aluno(a):</label>
            <input type="text" name="alunos[${alunoCount}][nome_encarregado]" minlength="2" maxlength="200" 
       placeholder="Digite o nome do(a) encarregado(a) do(a) aluno(a)" 
       oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, ' ');" 
       title="O nome deve conter apenas letras e espaços." required>

             <label for="contato_encarregado">Contato do Encarregado do Aluno(a):</label>
            <input type="text" name="alunos[${alunoCount}][contato_encarregado]" minlength="7" maxlength="7" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 7);" placeholder="Digite o contacto do(a) encarregado(a) do(a) aluno(a)">
        </fieldset>

    
    </div>
</div>
<br>


            <input type="button" value="Remover Aluno ${alunoCount+ 1}" class="remove-aluno" onclick="removeAluno(this)">
        `;

        alunoContainer.appendChild(alunoDiv);
        alunoCount++;
    }

    function removeAluno(button) {
        const alunoDiv = button.parentElement;
        alunoDiv.remove();
    }
</script>




</body>
</html>
