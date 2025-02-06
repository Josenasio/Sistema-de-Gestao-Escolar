<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: ../../index.php");
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

// Recuperar o ID do usuário da sessão
$id_usuario = $_SESSION['id']; // ID do usuário armazenado na sessão

// Consultar o nome do usuário no banco de dados
$query_usuario = "SELECT nome FROM usuarios WHERE id = ?";
$stmt_usuario = $mysqli->prepare($query_usuario);
if (!$stmt_usuario) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();

// Armazenar o nome do usuário em uma variável
$nome_usuario = $usuario['nome'] ?? 'Usuário';
$stmt_usuario->close();

// Consultar o total de alunos com id_diretor_turma igual ao id do usuário
$query_alunos = "SELECT COUNT(*) AS total FROM aluno WHERE id_diretor_turma = ?";
$stmt_alunos = $mysqli->prepare($query_alunos);
if (!$stmt_alunos) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_alunos->bind_param("i", $id_usuario);
$stmt_alunos->execute();
$result_alunos = $stmt_alunos->get_result();
$alunos = $result_alunos->fetch_assoc();

// Obter o total de alunos
$total = $alunos['total'] ?? 0;
$stmt_alunos->close();

// Consultar o total de alunos deficientes (campo 'deficiente' = 1)
$query_deficientes = "SELECT COUNT(*) AS total_deficientes FROM aluno WHERE id_diretor_turma = ? AND deficiente = 1";
$stmt_deficientes = $mysqli->prepare($query_deficientes);
if (!$stmt_deficientes) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_deficientes->bind_param("i", $id_usuario);
$stmt_deficientes->execute();
$result_deficientes = $stmt_deficientes->get_result();
$deficientes = $result_deficientes->fetch_assoc();

// Obter o total de alunos deficientes
$total_deficientes = $deficientes['total_deficientes'] ?? 0;
$stmt_deficientes->close();

// Consultar o total de alunas grávidas (campo 'gravidez' = 1)
$query_gravidez = "SELECT COUNT(*) AS total_gravidez FROM aluno WHERE id_diretor_turma = ? AND gravidez = 1";
$stmt_gravidez = $mysqli->prepare($query_gravidez);
if (!$stmt_gravidez) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_gravidez->bind_param("i", $id_usuario);
$stmt_gravidez->execute();
$result_gravidez = $stmt_gravidez->get_result();
$gravidez = $result_gravidez->fetch_assoc();

// Obter o total de alunas grávidas
$total_gravidez = $gravidez['total_gravidez'] ?? 0;
$stmt_gravidez->close();

// Consultar o total de alunos com motivo_abandono diferente de NULL
$query_abandono = "SELECT COUNT(*) AS total_abandono FROM aluno WHERE id_diretor_turma = ? AND motivo_abandono IS NOT NULL AND motivo_abandono != ''
";
$stmt_abandono = $mysqli->prepare($query_abandono);
if (!$stmt_abandono) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_abandono->bind_param("i", $id_usuario);
$stmt_abandono->execute();
$result_abandono = $stmt_abandono->get_result();
$abandono = $result_abandono->fetch_assoc();

// Obter o total de alunos com motivo de abandono
$total_abandono = $abandono['total_abandono'] ?? 0;
$stmt_abandono->close();

// Recuperar os alunos com base no id_diretor_turma e ordenar por numero_ordem
$query_alunos = "SELECT aluno.*, turma.nome_turma AS nome_turma 
                 FROM aluno 
                 JOIN turma ON aluno.turma_id = turma.id
                 WHERE aluno.id_diretor_turma = ?
                 ORDER BY aluno.numero_ordem ASC"; // Ordena por numero_ordem em ordem crescente

$stmt_alunos = $mysqli->prepare($query_alunos);
$stmt_alunos->bind_param("i", $id_usuario);
$stmt_alunos->execute();
$result_alunos = $stmt_alunos->get_result();
$alunos = $result_alunos->fetch_all(MYSQLI_ASSOC);
$stmt_alunos->close();




// Recuperar opções de religião
$query_religiao = "SELECT id, nome_religiao FROM religiao";
$result_religiao = $mysqli->query($query_religiao);
$opcoes_religiao = $result_religiao->fetch_all(MYSQLI_ASSOC);

// Recuperar opções de distrito
$query_distrito = "SELECT id, nome_distrito FROM distrito";
$result_distrito = $mysqli->query($query_distrito);
$opcoes_distrito = $result_distrito->fetch_all(MYSQLI_ASSOC);





// Consultar o classe_id do usuário no banco de dados
$query_classe = "SELECT classe_id FROM usuarios WHERE id = ?";
$stmt_classe = $mysqli->prepare($query_classe);
if (!$stmt_classe) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt_classe->bind_param("i", $id_usuario);
$stmt_classe->execute();
$result_classe = $stmt_classe->get_result();
$classe = $result_classe->fetch_assoc();
$classe_id = $classe['classe_id'] ?? null;
$stmt_classe->close();





?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diretor Turma</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#"><?= htmlspecialchars($nome_usuario) ?></a>
                </div>
            </div>
            <ul class="sidebar-nav">
    
                <li class="sidebar-item">
    <a href="../formulario/cadastrar_aluno.php" class="sidebar-link">
        <i class="lni lni-user"></i>
        <span>+ Aluno</span>
    </a>
</li>


<li class="sidebar-item">
    <a href="tabelas/adicionar_abandono.php" class="sidebar-link">
        <i class="lni lni-warning"></i>
        <span>+ Abandono</span>
    </a>
</li>

<li class="sidebar-item">
<a href="tabelas/adicionar_deficiente.php" class="sidebar-link">
        <i class="lni lni-wheelchair"></i>
        <span>+ Deficiente</span>
    </a>
</li>

<li class="sidebar-item">
<a href="tabelas/adicionar_gravidez.php" class="sidebar-link">
        <i class="lni lni-heart"></i>
        <span>+ Grávida</span>
    </a>
</li>


<li class="sidebar-item">
    <a href="/destp_pro/diretor_turma/dashboard/nota/notas.php" class="sidebar-link">
        <i class="lni lni-clipboard"></i>
        <span>+ Notas</span>
    </a>
</li>






<?php
    // Verifica se o classe_id é 6 ou 3
    if ($classe_id == 6 || $classe_id == 3) {
        echo '
        <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#add-exame" aria-expanded="false" aria-controls="add-exame">
                <i class="lni lni-files"></i>
                <span>+ Exame</span>
            </a>
            <ul id="add-exame" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/destp_pro/diretor_turma/exame/exame.php" class="sidebar-link">Fase 1</a>
                </li>
                <li class="sidebar-item">
                    <a href="/destp_pro/diretor_turma/exame/exame2.php"  class="sidebar-link">Fase 2</a>
                </li>
            </ul>
        </li>';
    }
    ?>











                


                
            </ul>
            <div class="sidebar-footer">
                <a href="/destp_pro/direcao_escola/sair.php"class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Sair</span>
                </a>
            </div>
        </aside>
        <div class="main">
           
        


        <nav class="navbar navbar-expand px-4 py-3" style="background-color: #0C2237;">
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
        
                <a href="user/user.php" class="nav-link" id="navbarDropdown">
                    <!-- Ícone de usuário -->
                    <img src="../../direcao_escola/imagem/user.png" alt="Descrição do ícone" width="50" style="border-radius: 50%; border: 2px solid rgb(42, 253, 0); padding:5px">
                

                </a>
                
           
        </ul>
    </div>
</nav>





            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div class="mb-3">
                    <h3 class="fw-bold fs-4 mb-3">
    <i class="fas fa-cogs me-2"></i> Painel de Administração
</h3>




                        <div class="row">
    <!-- Card 1: Total de Alunos -->
    <div class="col-12 col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body py-4" style="background-color: #0C2237; border-radius:5px">
                <h5 class="mb-2 text-primary fw-bold">
                <img src="../../direcao_escola/imagem/graduated.png" alt="Descrição do ícone" width="35"> Total de Alunos
                </h5>
                <p class="mb-2 fw-bold" style="color: #ffffff;">
                    <?= $total ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Card 2: Total de Abandono -->
    <div class="col-12 col-md-4 mb-4">
    <a href="tabelas/tabela_abandono.php" >
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body py-4" style="background-color: #0C2237; border-radius:5px">
                <h5 class="mb-2 text-primary fw-bold">
                <img src="../../direcao_escola/imagem/user (1).png" alt="Descrição do ícone" width="35"> Total de Abandono
                </h5>
                <p class="mb-2 fw-bold" style="color: #ffffff;">
                    <?= $total_abandono ?>
                </p>
            </div>
        </div>
</a>
    </div>

    <!-- Card 3: Total de Gravidez -->
    <div class="col-12 col-md-4 mb-4">
    <a href="tabelas/tabela_gravida.php" >
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body py-4" style="background-color: #0C2237; border-radius:5px">
                <h5 class="mb-2 text-primary fw-bold">
                <img src="../../direcao_escola/imagem/prenatal-care.png" alt="Descrição do ícone" width="35"> Total de Gravidez
                </h5>
                <p class="mb-2 fw-bold" style="color: #ffffff;">
                    <?= $total_gravidez ?>
                </p>
            </div>
        </div>
</a>
    </div>

    <!-- Card 4: Total de Deficientes -->
    <div class="col-12 col-md-4 mb-4">
    <a href="tabelas/tabela_deficiente.php">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body py-4" style="background-color: #0C2237; border-radius:5px">
                <h5 class="mb-2 text-primary fw-bold">
                <img src="../../direcao_escola/imagem/wheelchair.png" alt="Descrição do ícone" width="35"> Total de Deficientes
                </h5>
                <p class="mb-2 fw-bold" style="color: #ffffff;">
                    <?= $total_deficientes ?>
                </p>
            </div>
        </div>
</a>
    </div>







    <div class="col-12 col-md-4 mb-4">
    <a href="tabelas/tabela_nota.php">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body py-4" style="background-color: #0C2237; border-radius:5px">
                <h5 class="mb-2 text-primary fw-bold">
                <img src="../../direcao_escola/imagem/bill.png" alt="Descrição do ícone" width="35"> Notas
                </h5>
                <p class="mb-0 fw-bold" style="color: #ffffff;">
              publicação das notas
                </p>
            </div>
        </div>
    </a>
</div>

</div>



































<div class="container">
    <h3 class="fw-bold fs-4 my-3 text-center">Lista de Alunos(as) da Turma</h3> 
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
        <div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="bg-primary text-white">
            <tr>
      <th scope="col" style=" background-color: #0C2237; color:#ffffff"><i class="fas fa-hashtag"></i> Número</th>
      <th scope="col" style=" background-color: #0C2237; color:#ffffff"><i class="fas fa-user"></i> Nome</th>
      <th scope="col" style=" background-color: #0C2237; color:#ffffff"><i class="fas fa-venus-mars"></i> Gênero</th>
      <th scope="col" style=" background-color: #0C2237; color:#ffffff"><i class="fas fa-birthday-cake"></i> Idade</th>
      <th scope="col" style=" background-color: #0C2237; color:#ffffff"><i class="fas fa-redo-alt"></i> Repitente</th>
      <th scope="col" style=" background-color: #0C2237; color:#ffffff"><i class="fas fa-school"></i> Turma</th>
      <th scope="col" style=" background-color: #0C2237; color:#ffffff"><i class="fas fa-cogs"></i> Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alunos as $aluno): ?>
                <tr>
                    <th scope="row" style=" background-color:rgba(12, 34, 55, 0.35); color:black"><?= $aluno['numero_ordem'] ?></th>
                    <td style=" background-color:rgba(12, 34, 55, 0.35); color:black"><?= $aluno['nome'] ?></td>
                    <td style=" background-color:rgba(12, 34, 55, 0.35); color:black"><?= $aluno['genero'] ?></td>
                    <td style=" background-color:rgba(12, 34, 55, 0.35); color:black"><?= $aluno['idade'] ?></td>
                    <td style=" background-color:rgba(12, 34, 55, 0.35); color:black"><?= $aluno['numero_frequencia'] ?></td>
                    <td style=" background-color:rgba(12, 34, 55, 0.35); color:black"><?= $aluno['nome_turma'] ?></td>
                    <td style=" background-color:#0C2237; color:black">
                        <button 
                            class="btn btn-warning btn-sm edit-btn" 
                            data-id="<?= $aluno['id'] ?>"
                            data-nome="<?= $aluno['nome'] ?>"
                            data-idade="<?= $aluno['idade'] ?>"
                            data-genero="<?= $aluno['genero'] ?>"
                            data-numero_ordem="<?= $aluno['numero_ordem'] ?>"
                            data-numero_frequencia="<?= $aluno['numero_frequencia'] ?>"
                            data-endereco="<?= $aluno['endereco'] ?>"
                            data-nome_encarregado="<?= $aluno['nome_encarregado'] ?>"
                            data-contato_encarregado="<?= $aluno['contato_encarregado'] ?>"
                            data-bi="<?= $aluno['bi'] ?>"
                            data-naturalidade="<?= $aluno['naturalidade'] ?>"
                            data-data_emissao_bi="<?= $aluno['data_emissao_bi'] ?>"
                            data-situacao_economica="<?= $aluno['situacao_economica'] ?>"
                            data-religiao_id="<?= $aluno['religiao_id'] ?>"
                            data-id_distrito="<?= $aluno['id_distrito'] ?>"
                        >
                        <i class="fas fa-edit fa-lg"></i>editar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Formulário Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg rounded-3">
            <form id="editForm" method="POST" action="editar_aluno.php">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editModalLabel">Editar Aluno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="id" id="editId">

                    <!-- Dados Pessoais -->
                    <div class="mb-4">
                        <h5 style="color: #0C6EF7;"><i class="fas fa-user"></i> Dados Pessoais</h5>

                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="editNome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="editNome" name="nome" required>
                        </div>

                        <!-- Idade -->
                        <div class="mb-3">
                            <label for="editIdade" class="form-label">Idade</label>
                            <input type="number" class="form-control" id="editIdade" name="idade" required>
                        </div>

                        <!-- Gênero -->
                        <div class="mb-3">
                            <label for="editGenero" class="form-label">Gênero</label>
                            <select class="form-select" id="editGenero" name="genero" required>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                            </select>
                        </div>

                        <!-- Naturalidade -->
                        <div class="mb-3">
                            <label for="editNaturalidade" class="form-label">Naturalidade</label>
                            <input type="text" class="form-control" id="editNaturalidade" name="naturalidade" required>
                        </div>
                    </div>
                    <!-- BI -->
                    <div class="mb-3">
                            <label for="editBI" class="form-label">BI</label>
                            <input type="text" class="form-control" id="editBI" name="bi" required>
                        </div>
                        <!-- Data de Emissão do BI -->
                        <div class="mb-3">
                            <label for="editDataEmissaoBI" class="form-label">Data de Emissão do BI</label>
                            <input type="date" class="form-control" id="editDataEmissaoBI" name="data_emissao_bi" required>
                        </div>

                    <!-- Informações de Contato -->
                    <div class="mb-4">
                    <h5 style="color: #0C6EF7;"><i class="fas fa-map-marker-alt"></i> Localização</h5>


                        <!-- Endereço -->
                        <div class="mb-3">
                            <label for="editEndereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="editEndereco" name="endereco" required>
                        </div>

                        
                        <!-- Distrito -->
                        <div class="mb-3">
                            <label for="editDistrito" class="form-label">Distrito</label>
                            <select class="form-select" id="editDistrito" name="id_distrito" required>
                                <?php foreach ($opcoes_distrito as $distrito): ?>
                                    <option value="<?= $distrito['id'] ?>"><?= $distrito['nome_distrito'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                       
                    </div>

                    <!-- Informações Acadêmicas -->
                    <div class="mb-4">
                        <h5 style="color: #0C6EF7;"><i class="fas fa-book"></i> Informações Acadêmicas</h5>

                        <!-- Número de Frequência -->
                        <div class="mb-3">
                            <label for="editNumeroFrequencia" class="form-label">Número de Frequência (Repitente)</label>
                            <input type="number" class="form-control" id="editNumeroFrequencia" name="numero_frequencia" required>
                        </div>

                        <!-- Número de Ordem -->
                        <div class="mb-3">
                            <label for="editNumeroOrdem" class="form-label">Número de Ordem</label>
                            <input type="number" class="form-control" id="editNumeroOrdem" name="numero_ordem" required>
                        </div>
                    </div>

                    <!-- Dados do Encarregado -->
                    <div class="mb-4">
                        <h5 style="color: #0C6EF7;"><i class="fas fa-user-tie"></i> Dados do Encarregado</h5>

                        <!-- Nome do Encarregado -->
                        <div class="mb-3">
                            <label for="editNomeEncarregado" class="form-label">Nome do Encarregado</label>
                            <input type="text" class="form-control" id="editNomeEncarregado" name="nome_encarregado" required>
                        </div>

                         <!-- Contato do Encarregado -->
                         <div class="mb-3">
                            <label for="editContatoEncarregado" class="form-label">Contato do Encarregado</label>
                            <input type="text" class="form-control" id="editContatoEncarregado" name="contato_encarregado" required>
                        </div>

                    </div>

                    <!-- Informações Adicionais -->
                    <div class="mb-4">
                        <h5 style="color: #0C6EF7;"><i class="fas fa-info-circle"></i> Informações Adicionais</h5>

                        

                        <!-- Situação Econômica -->
                        <div class="mb-3">
                            <label for="editSituacaoEconomica" class="form-label">Situação Econômica</label>
                            <select class="form-select" id="editSituacaoEconomica" name="situacao_economica" required>
                                <option value="pobre">Pobre</option>
                                <option value="muito pobre">Muito Pobre</option>
                                <option value="médio">Médio</option>
                                <option value="rico">Rico</option>
                                <option value="muito rico">Muito Rico</option>
                            </select>
                        </div>

                        <!-- Religião -->
                        <div class="mb-3">
                            <label for="editReligiao" class="form-label">Religião</label>
                            <select class="form-select" id="editReligiao" name="religiao_id" required>
                                <?php foreach ($opcoes_religiao as $religiao): ?>
                                    <option value="<?= $religiao['id'] ?>"><?= $religiao['nome_religiao'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 py-2">Salvar</button>
                    <button type="button" class="btn btn-secondary w-100 py-2" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>




            </div>
        </div>
    </div>
</div>































            </main>
           
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>




    <script>
 document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.querySelector('#editModal');
        document.querySelector('#editId').value = button.dataset.id;
        document.querySelector('#editNome').value = button.dataset.nome;
        document.querySelector('#editIdade').value = button.dataset.idade;
        document.querySelector('#editEndereco').value = button.dataset.endereco;
        document.querySelector('#editGenero').value = button.dataset.genero;
        document.querySelector('#editNumeroFrequencia').value = button.dataset.numero_frequencia;
        document.querySelector('#editNumeroOrdem').value = button.dataset.numero_ordem;
        document.querySelector('#editNomeEncarregado').value = button.dataset.nome_encarregado;
        document.querySelector('#editContatoEncarregado').value = button.dataset.contato_encarregado;
        document.querySelector('#editBI').value = button.dataset.bi;
        document.querySelector('#editNaturalidade').value = button.dataset.naturalidade;
        document.querySelector('#editDataEmissaoBI').value = button.dataset.data_emissao_bi;
        document.querySelector('#editSituacaoEconomica').value = button.dataset.situacao_economica;
        document.querySelector('#editReligiao').value = button.dataset.religiao_id;
        document.querySelector('#editDistrito').value = button.dataset.id_distrito;


        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    });
});


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

</body>

</html>