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



// Funçao para buscar dados das tabelas
function fetchOptions($mysqli, $table, $column) {
    $options = '';
    $result = $mysqli->query("SELECT id, $column FROM $table");
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row['id']}'>{$row[$column]}</option>";
    }
    return $options;
}

// Processar o formulário quando submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 

    foreach ($_POST['dados'] as $dados) {
        if (empty($dados['nome']) || empty($dados['contacto']) || empty($dados['endereco']) || empty($dados['idade']) || empty($dados['nif']) || empty($dados['genero']) || empty($dados['data_contrato']) || empty($dados['funcao']) || empty($dados['estado_civil']) || empty($dados['numero_conta_bancaria']) || empty($dados['ano_servico']) || empty($dados['ano_inicio_servico']) || empty($dados['nivel_academico']) || empty($dados['distrito_id']) || empty($dados['religiao_id'])) {
            die("Erro: Todos os campos sao obrigatórios para cada registro.");
        }

        $stmt = $mysqli->prepare("INSERT INTO pessoal_nao_docente (nome, contacto, endereco, idade, nif, genero, data_contrato, funcao, estado_civil, numero_conta_bancaria, ano_servico, ano_inicio_servico, nivel_academico, escola_id, distrito_id, religiao_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "ssssssssssssssss",
            $dados['nome'], 
            $dados['contacto'], 
            $dados['endereco'], 
            $dados['idade'], 
            $dados['nif'], 
            $dados['genero'], 
            $dados['data_contrato'], 
            $dados['funcao'], 
            $dados['estado_civil'], 
            $dados['numero_conta_bancaria'], 
            $dados['ano_servico'], 
            $dados['ano_inicio_servico'], 
            $dados['nivel_academico'], 
            $id_escola, 
            $dados['distrito_id'], 
            $dados['religiao_id']
        );

        if (!$stmt->execute()) {
            echo "<script>alert('Erro ao inserir registro: " . $stmt->error . "');</script>";
        }
    }


    $stmt->close();

      // Redirecionar para outra página após sucesso
      header("Location: sucesso.php"); // Substitua "outra_pagina.php" pelo endereço desejado
      exit;
}


?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário Pessoal Nao Docente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<!-- Adicione o link para o Font Awesome no cabeçalho -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">





    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #c1efde;;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: black;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        /* Botao de adicionar registro */
        .add-record-btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .add-record-btn i {
            margin-right: 8px;
        }
        .add-record-btn:hover {
            background: #0056b3;
        }
        /* Botao de salvar registros */
        .save-records-btn {
            width: 100%;
            padding: 10px;
            background: rgb(4, 114, 10);
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .save-records-btn i {
            margin-right: 8px;
        }
        .save-records-btn:hover {
            background: rgb(0, 252, 13);
        }


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
    <script>
        let recordCount = 1;

        function addNewRecord() {
            recordCount++;
            const container = document.getElementById("record-container");
            const recordTemplate = `
                <div class="record">
                    <h3 style="color:red;">Registro ${recordCount}</h3>
                    <div class="form-group"><label for="nome"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nome</label><input type="text" name="dados[${recordCount}][nome]" placeholder="Digite o nome"   required></div>
                    <div class="form-group"><label for="contacto"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Contacto</label><input type="number" name="dados[${recordCount}][contacto]" placeholder="Digite o contacto"></div>
                    <div class="form-group"><label for="endereco"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Endereço</label><input type="text" name="dados[${recordCount}][endereco]" placeholder="Digite o endereço" required></div>
                    <div class="form-group"><label for="idade"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Idade</label><input type="number" name="dados[${recordCount}][idade]" placeholder="Digite a idade" required></div>
                    <div class="form-group"><label for="nif"><span style="color: red; font-size:15px; font-weight:bolder">* </span>NIF</label><input type="number" name="dados[${recordCount}][nif]" placeholder="Digite o nif"  required></div>
                    <div class="form-group"><label for="genero"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Gênero</label><select name="dados[${recordCount}][genero]" required>
                     <option value="">Selecione um genero</option>
                    <option value="Masculino">Masculino</option><option value="Feminino">Feminino</option><option value="Outro">Outro</option></select></div>
                    <div class="form-group"><label for="data_contrato"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Data do Contrato</label><input type="date" name="dados[${recordCount}][data_contrato]" required></div>
                    <div class="form-group"><label for="funcao"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Funçao</label><input type="text" name="dados[${recordCount}][funcao]" placeholder="Digite a funçao" required></div>
                    <div class="form-group"><label for="estado_civil"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Estado Civil</label><select name="dados[${recordCount}][estado_civil]" required>
                     <option value="">Selecione um estado civil</option>
                    
                    <option value="Solteiro">Solteiro(a)</option><option value="Casado">Casado(a)</option><option value="Divorciado">Divorciado(a)</option><option value="Viúvo">Viúvo(a)</option></select></div>
                    <div class="form-group"><label for="numero_conta_bancaria"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número da Conta Bancária</label><input type="number" name="dados[${recordCount}][numero_conta_bancaria]" placeholder="Digite o número de conta bancária" required></div>
                    <div class="form-group"><label for="ano_servico"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Ano de Serviço Prestado</label><input type="number" name="dados[${recordCount}][ano_servico]" placeholder="Digite a quantidade de anos de serviços prestados" required></div>
                    <div class="form-group"><label for="ano_inicio_servico"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Ano de Inicio de Serviço</label><input type="date" name="dados[${recordCount}][ano_inicio_servico]" required></div>
                    <div class="form-group"><label for="nivel_academico"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nivel Acadêmico</label><input type="text" name="dados[${recordCount}][nivel_academico]" placeholder="Digite o nivel académico" required></div>
                    <div class="form-group"><label for="distrito_id"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Distrito</label><select name="dados[${recordCount}][distrito_id]" required>
                     <option value="">Selecione um distrito</option>
                    
                    <?php echo fetchOptions($mysqli, 'distrito', 'nome_distrito'); ?></select></div>
                    <div class="form-group"><label for="religiao_id"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Religiao</label><select name="dados[${recordCount}][religiao_id]" required>
                    
                    
                     <option value="">Selecione uma religiao</option>
                     <?php echo fetchOptions($mysqli, 'religiao', 'nome_religiao'); ?></select></div>
                </div>
            `;
            container.insertAdjacentHTML("beforeend", recordTemplate);
        }
    </script>
</head>

<body>
    
<!-- Botão com ícone -->
<button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
</button>
<br>
<br>
<div class="container">
    <h2>Adicionar Pessoal Nao Docente</h2>
    <form action="" method="POST">
    <input type="hidden" name="escola_id" value="<?php echo htmlspecialchars($_SESSION['escola_id'] ?? ''); ?>">

        <div id="record-container">
            <!-- Campo inicial para o primeiro registro -->
            <div class="record">
                <h3 style="color: red;">Registro 1</h3>
                <div class="form-group"><label for="nome"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nome</label><input type="text" name="dados[1][nome]" placeholder="Digite o nome"  required></div>
                <div class="form-group"><label for="contacto"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Contacto</label><input type="number" name="dados[1][contacto]" placeholder="Digite o contacto"  required></div>
                <div class="form-group"><label for="endereco"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Endereço</label><input type="text" name="dados[1][endereco]" placeholder="Digite o endereço"  required></div>
                <div class="form-group"><label for="idade"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Idade</label><input type="number" name="dados[1][idade]" placeholder="Digite a idade" required></div>
                <div class="form-group"><label for="nif"><span style="color: red; font-size:15px; font-weight:bolder">* </span>NIF</label><input type="number" name="dados[1][nif]" placeholder="Digite o nif" required></div>
                <div class="form-group"><label for="genero"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Gênero</label><select name="dados[1][genero]" required>
                    
                <option value="">Selecione um genero</option>
                <option value="Masculino">Masculino</option><option value="Feminino">Feminino</option></select></div>
                <div class="form-group"><label for="data_contrato"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Data do Contrato</label><input type="date" name="dados[1][data_contrato]" required></div>
                <div class="form-group"><label for="funcao"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Funçao</label><input type="text" name="dados[1][funcao]" placeholder="Digite a funçao" required></div>
                <div class="form-group"><label for="estado_civil"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Estado Civil</label><select name="dados[1][estado_civil]" required>
                    
                <option value="">Selecione um estado civil</option><option value="Solteiro">Solteiro(a)</option><option value="Casado">Casado(a)</option><option value="Divorciado">Divorciado(a)</option><option value="Viúvo">Viúvo(a)</option></select></div>
                <div class="form-group"><label for="numero_conta_bancaria"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Número da Conta Bancária</label><input type="number" name="dados[1][numero_conta_bancaria]" placeholder="Digite o número de conta bancária" required></div>
                <div class="form-group"><label for="ano_servico"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Ano de Serviço Prestado</label><input type="number" name="dados[1][ano_servico]" placeholder="Digite a quantidade de anos de serviços prestados"  required></div>
                <div class="form-group"><label for="ano_inicio_servico"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Ano de Inicio de Serviço</label><input type="date" name="dados[1][ano_inicio_servico]" required></div>
                <div class="form-group"><label for="nivel_academico"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nivel Acadêmico</label><input type="text" name="dados[1][nivel_academico]" placeholder="Digite o nivel académico" required></div>
                <div class="form-group"><label for="distrito_id"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Distrito</label><select name="dados[1][distrito_id]" required>
                <option value="">Selecione um distrito</option>
                
                <?php echo fetchOptions($mysqli, 'distrito', 'nome_distrito'); ?></select></div>
                <div class="form-group"><label for="religiao_id"><span style="color: red; font-size:15px; font-weight:bolder">* </span>Religiao</label><select name="dados[1][religiao_id]" required>
                    
                <option value="">Selecione uma religiao</option><?php echo fetchOptions($mysqli, 'religiao', 'nome_religiao'); ?></select></div>
            </div>
        </div>
        <button type="button" onclick="addNewRecord()" class="add-record-btn">Adicionar Novo Pessoal</button>
        <button type="submit" class="save-records-btn">Salvar Todos </button>
    </form>
</div>







</body>
</html>

<?php
$mysqli->close();
?>