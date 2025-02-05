<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'direcao') {
    header("Location: ../../index.php");
    exit;
}

// Conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Conexao com o banco de dados
$host = 'localhost';
$db   = 'escola_db_pro';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexao: " . $e->getMessage());
}




// Verificar se o id_escola está definido na sessão
if (!isset($_SESSION['id_escola'])) {
    die("Erro: ID da escola não definido.");
}
 
// Recuperar o id_escola da sessão
$id_escola = $_SESSION['id_escola'];



// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $professores = $_POST['professores'];

    foreach ($professores as $professor) {
        $stmt = $pdo->prepare("INSERT INTO professor (nome, nome_facebook, idade, telefone, novo, nivel_academico, data_contrato, endereco, email, funcao, data_nascimento, genero, distrito_id, religiao_id, area_formacao1, area_formacao2, duracao_formacao, estado_civil, categoria_salarial, titulo, naturalidade, id_escola) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Execute o insert com os dados do professor
        $stmt->execute([
            $professor['nome'],
            $professor['nome_facebook'],
            $professor['idade'],
            $professor['telefone'],
            $professor['novo'],
            $professor['nivel_academico'],
            $professor['data_contrato'],
            $professor['endereco'],
            $professor['email'],
            $professor['funcao'],
            $professor['data_nascimento'],
            $professor['genero'],
            $professor['distrito_id'],
            $professor['religiao_id'],
            $professor['area_formacao1'],
            $professor['area_formacao2'],
            $professor['duracao_formacao'],
            $professor['estado_civil'],
            $professor['categoria_salarial'],
            $professor['titulo'],
            $professor['naturalidade'],
            $id_escola
        ]);

// Obtem o último id do professor inserido
$professor_id = $pdo->lastInsertId();



             // Inserir disciplinas associadas ao professor
    foreach ($professor['disciplinas'] as $disciplina_id) {
        $stmt = $pdo->prepare("INSERT INTO professor_disciplina (professor_id, disciplina_id) VALUES (?, ?)");
        $stmt->execute([$professor_id, $disciplina_id]);
    }


            // Inserir turmas associadas ao professor
    foreach ($professor['id_turma'] as $turma_id) {
        $stmt = $pdo->prepare("INSERT INTO professor_turma (professor_id, turma_id) VALUES (?, ?)");
        $stmt->execute([$professor_id, $turma_id]);
    }

     // Inserir classes associadas ao professor
     foreach ($professor['id_classe'] as $classe_id) {
        $stmt = $pdo->prepare("INSERT INTO professor_classe (id_professor, id_classe) VALUES (?, ?)");
        $stmt->execute([$professor_id, $classe_id]);
    }
    }

     // Redirecionar para outra página após o sucesso
     header("Location: sucesso.php"); // Substitua "outra_pagina.php" pelo endereço desejado
     exit;
}

?>

 
 <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Adicione o link para o Font Awesome no cabeçalho -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">



    <title>Cadastro de Professores</title>
    <style>
        /* Estilos de CSS */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #c1efde;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

/* Efeito de preenchimento para inputs com valor */
input[type="text"]:valid,
input[type="number"]:valid,
select:valid {
  border-color: #00ff3c;
  box-shadow: 0 0 8px rgba(40, 167, 69, 0.3);
}
        
        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input, select, button {
            padding: 8px;
            margin-top: 5px;
            font-size: 16px;
            width: 100%;
        }
        button {
            cursor: pointer;
        }
        button[type="button"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            margin-top: 10px;
            padding: 10px;
        }
        button[type="submit"] {
            background-color: rgb(4, 114, 10);
            color: #fff;
            border: none;
            padding: 15px;
        }

        button[type="submit"]:hover {
    background-color: rgb(4, 255, 16); /* altera o fundo quando o mouse estiver sobre o botao */
    color: black; /* opcional: altera a cor do texto ao passar o mouse */
}


        .professor {
            border-top: 2px solid #007bff;
            margin-top: 20px;
            padding-top: 10px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
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



          /* Estilos para diferenciar os grupos */
  .group-auxiliar {
    background-color: #f8d7da; /* Vermelho claro */
  }

  .group-adjunto {
    background-color: #d1ecf1; /* Azul claro */
  }

  .group-titular {
    background-color: #d4edda; /* Verde claro */
  }

  .group-superior {
    background-color: #fff3cd; /* Amarelo claro */
  }

  .group-coordenador {
    background-color: #e2e3e5; /* Cinza claro */
  }

    </style>
</head>
<body>
    <?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');
    ?>

    <!-- Botão com ícone -->
    <button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
  <i class="fas fa-tachometer-alt"></i> Voltar a Pagina Inicial
</button>
<br><br>


    <div class="container">
        <h1>Cadastro de Professores(as)</h1>

        <form id="professorForm" method="POST" action="cadastro_professor.php">
            <!-- Campo Escola -->
            <input type="hidden" name="id_escola" value="<?php echo htmlspecialchars($_SESSION['id_escola'] ?? ''); ?>">

            <div id="professores">
                <!-- Professor Template -->
                <div class="professor">
                    <h3 style="color: rgb(255, 0, 0);">Professor(a)</h3>
                    <button type="button" onclick="removerProfessor(this)" style="background-color: red;">Remover Professor</button>

                    <!-- Campos do Professor -->
                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nome:</label><input type="text" name="professores[0][nome]" required>
                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Idade:</label><input type="number" name="professores[0][idade]" required>
                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Gênero:</label>
                    <select name="professores[0][genero]" required>
                    <option value="" disabled selected>Selecione um genero</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                    </select>
                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Estado Civil:</label>
                    <select name="professores[0][estado_civil]" required>
                    <option value="" disabled selected>Selecione um estado civil</option>
                        <option value="Solteiro">Solteiro(a)</option>
                        <option value="Casado">Casado(a)</option>
                        <option value="Divorciado">Divorciado(a)</option>
                        <option value="Divorciado">Viuvo(a)</option>
                        <option value="Amantizado">Amantizado(a)</option>
                    </select>
                    <label>Telefone:</label><input type="text" name="professores[0][telefone]">
                    <br>
                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Endereço (Localizaçao):</label><input type="text" name="professores[0][endereco]" required>
                    <br>
                    <label>Email:</label><input type="email" name="professores[0][email]">
                    <br>
                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Data de Nascimento:</label><input type="date" name="professores[0][data_nascimento]" required>
                    <br>
                    <label>Nome do Facebook:</label><input type="text" name="professores[0][nome_facebook]">
                    <br>
                    <div class="religioes">
                        <label>Religiao:</label>
                        <select name="professores[0][religiao_id]" required>
                        <option value="" disabled selected>Selecione uma religiao</option>
                            <?php
                            $stmt = $pdo->query("SELECT id, nome_religiao FROM religiao");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='{$row['id']}'>{$row['nome_religiao']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Distrito:</label>
                    <select name="professores[0][distrito_id]" required>
                    <option value="" disabled selected>Selecione um distrito</option>
                        <?php
                        $stmt = $pdo->query("SELECT id, nome_distrito FROM distrito");
                        while ($row = $stmt->fetch()) {
                            echo "<option value='{$row['id']}'>{$row['nome_distrito']}</option>";
                        }
                        ?>
                    </select>


                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Data de Contrato:</label><input type="date" name="professores[0][data_contrato]" required>

                    <label for="novo"><span style="color: red; font-size:15px; font-weight:bolder">* </span>É um professor novo?:</label>
<select id="novo" name="professores[0][novo]" required>
    <option value="">Selecione a resposta</option>
    <option value="1">Sim</option>
    <option value="0">Nao</option>
</select><br>


                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Funçao:</label><input type="text" name="professores[0][funcao]" required>

                   

                    <div class="disciplinas">
                        <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Disciplinas:</label>
                        <div class="disciplina">
                            <select name="professores[0][disciplinas][]" required>
                            <option value="" disabled selected>Selecione uma disciplina</option>
                                <?php
                                $stmt = $pdo->query("SELECT id, nome_disciplina FROM disciplina");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='{$row['id']}'>{$row['nome_disciplina']}</option>";
                                }
                                ?>
                            </select>
                            <button type="button" onclick="adicionarCampo(this, 'disciplina')">Adicionar Disciplina</button>
                        </div>
                    </div>

                    <div class="turmas">
                        <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Turmas que o Professor(a) Leciona:</label>
                        <div class="turma">
                            <select name="professores[0][id_turma][]" required>
                            <option value="" disabled selected>Selecione uma turma</option>
                                <?php
                                $stmt = $pdo->query("SELECT id, nome_turma FROM turma");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='{$row['id']}'>{$row['nome_turma']}</option>";
                                }
                                ?>
                            </select>
                            <button type="button" onclick="adicionarCampo(this, 'turma')">Adicionar Turma</button>
                        </div>
                    </div>

                    <div class="classes">
                        <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Classes que o Professor(a) Leciona:</label>
                        <div class="classe">
                            <select name="professores[0][id_classe][]" required>
                            <option value="" disabled selected>Selecione uma classe</option>
                                <?php
                                $stmt = $pdo->query("SELECT id, nivel_classe FROM classe");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='{$row['id']}'>{$row['nivel_classe']}</option>";
                                }
                                ?>
                            </select>
                            <button type="button" onclick="adicionarCampo(this, 'classe')">Adicionar Classe</button>
                        </div>
                    </div>

                    



                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Nivel Académico:</label>
                    <select name="professores[0][nivel_academico]" required>
                         <option value="" disabled selected>Selecione o nivel académico</option>
                         <option value="9ª Classe">9ª Classe</option>
                         <option value="10ª Classe">10ª Classe</option>
                        <option value="11ª Classe">11ª Classe</option>
                        <option value="12ª Classe">12ª Classe</option>
                        <option value="1º Ano de Licenciatura">1º Ano de Licenciatura</option>
                        <option value="2º Ano de Licenciatura">2º Ano de Licenciatura</option>
                        <option value="3º Ano de Licenciatura">3º Ano de Licenciatura</option>
                        <option value="4º Ano de Licenciatura">4º Ano de Licenciatura</option>
                        <option value="Licenciado(a)">Licenciado(a)</option>
                        <option value="Mestrado(a)">Mestrado(a)</option>
                        <option value="Doutorado(a)">Doutorado(a)</option>
                        <option value="Pós-Doutorado(a)">Pós-Doutorado(a)</option>
                        <option value="Outros">Outros</option>
                    </select>

                    <div class="formacao">
                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Área da Formaçao Feita - 1</label><input type="text" name="professores[0][area_formacao1]" required>
                    <label>Área da Formaçao Feita - 2 <span style="color:  rgba(0, 0, 0, 0.6);">(opcional)</span></label><input type="text" name="professores[0][area_formacao2]">

                    <label>Duraçao da Formaçao Feita:</label>
                    <input type="text" name="professores[0][duracao_formacao]" required>
                    </div>


                  
                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Categoria Salarial:</label>
                        <select name="professores[0][categoria_salarial]" required>
                        <option value="" disabled selected>Selecione a categoria salarial</option>

<option value="" disabled class="group-auxiliar">«-------- Auxiliar --------»</option>
<option value="Auxiliar da 1ª Classe">Auxiliar da 1ª Classe</option>
<option value="Auxiliar da 2ª Classe">Auxiliar da 2ª Classe</option>
<option value="Auxiliar da 3ª Classe">Auxiliar da 3ª Classe</option>

<option value="" disabled class="group-adjunto">«-------- Adjunto --------»</option>
<option value="Adjunto da 1ª Classe">Adjunto da 1ª Classe</option>
<option value="Adjunto da 2ª Classe">Adjunto da 2ª Classe</option>
<option value="Adjunto da 3ª Classe">Adjunto da 3ª Classe</option>

<option value="" disabled class="group-titular">«--------- Titular ---------»</option>
<option value="Titular da 1ª Classe">Titular da 1ª Classe</option>
<option value="Titular da 2ª Classe">Titular da 2ª Classe</option>
<option value="Titular da 3ª Classe">Titular da 3ª Classe</option>

<option value="" disabled class="group-superior">«-------- Superior --------»</option>
<option value="Superior da 1ª Classe">Superior da 1ª Classe</option>
<option value="Superior da 2ª Classe">Superior da 2ª Classe</option>
<option value="Superior da 3ª Classe">Superior da 3ª Classe</option>

<option value="" disabled class="group-coordenador">«------- Coordenador -------»</option>
<option value="Coordenador da 1ª Classe">Coordenador da 1ª Classe</option>
<option value="Coordenador da 2ª Classe">Coordenador da 2ª Classe</option>
<option value="Coordenador da 3ª Classe">Coordenador da 3ª Classe</option>

                        </select>




                    <label><span style="color: red; font-size:15px; font-weight:bolder">* </span>Titulo:</label>    
                <select id="novo" name="professores[0][titulo]" required>
                    <option value="" disabled selected>Selecione um titulo</option>

                    <option value="Cooperador">Cooperador</option>

                    <option disabled value="" class="group-coordenador">«------ Extraordinário ------»</option>
                        <option value="Extraordinário - Nomeado">Extraordinário - Nomeado</option>
                        <option value="Extraordinário -Não Nomeado">Extraordinário -Não Nomeado</option>

                    <option value="" disabled class="group-superior">«---------- Efectivo ----------»</option>
                        <option value="Efectivo - Nomeado">Efectivo - Nomeado</option>
                        <option value="Efectivo -Não Nomeado">Efectivo -Não Nomeado</option>

                    <option value="" disabled class="group-titular">«------ Extraordinário e Efectivo ------»</option>
                    <option value="Extraordinário e Efectivo - Nomeado">Extraordinário e Efectivo - Nomeado</option>
                    <option value="Extraordinário e Efectivo -Não Nomeado">Extraordinário e Efectivo -Não Nomeado</option>

                  
                 
                </select><br>









                    <label>Naturalidade:</label>
                    <input type="text" name="professores[0][naturalidade]">
                </div>
            </div>

            <button type="button" onclick="adicionarProfessor()" style="background-color: black;">Adicionar Outro Professor</button>
            <button type="submit">Cadastrar</button>
        </form>
    </div>

    <script>
        // JavaScript para funcionalidades dinâmicas
        let professorIndex = 1;

        function adicionarProfessor() {
            const professorForm = document.querySelector('.professor').cloneNode(true);
            professorForm.querySelectorAll('input, select').forEach(input => {
                const name = input.name.replace(/\[0\]/, `[${professorIndex}]`);
                input.name = name;
                input.value = '';
            });
            document.getElementById('professores').appendChild(professorForm);
            professorIndex++;
        }

        function removerProfessor(button) {
            if (document.querySelectorAll('.professor').length > 1) {
                button.closest('.professor').remove();
            } else {
                alert('É necessário ter pelo menos um professor.');
            }
        }

        function adicionarCampo(button, tipo) {
            const campoContainer = button.closest(`.${tipo}`);
            const campoClone = campoContainer.cloneNode(true);

            campoClone.querySelector('button').innerText = 'Remover';
campoClone.querySelector('button').style.backgroundColor = 'red';

            campoClone.querySelector('button').setAttribute('onclick', `removerCampo(this, '${tipo}')`);
            campoClone.querySelector('select').value = '';
            campoContainer.parentNode.appendChild(campoClone);
        }

        function removerCampo(button, tipo) {
            const campos = document.querySelectorAll(`.${tipo}`);
            if (campos.length > 1) {
                button.closest(`.${tipo}`).remove();
            } else {
                alert(`É necessário ter pelo menos um ${tipo}.`);
            }
        }
    </script>



</body>
</html>
