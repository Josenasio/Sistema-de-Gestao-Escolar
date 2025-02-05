<?php  
session_start();

if (!isset($_SESSION['id_escola'])) {
    header("Location: ../../index.php");
    exit();
}

include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');

if (!isset($mysqli)) {
    die("Erro na conexão com o banco de dados.");
}

$escola_id = $_SESSION['id_escola'];

$sql = "SELECT 
            a.id, 
            class.nivel_classe AS classe,
            t.nome_turma AS turma_nome,
            c.nome_area AS curso_nome,
            a.nome, 
            a.genero, 
            a.idade, 
            a.numero_ordem, 
            a.bi, 
            a.numero_frequencia, 
            a.endereco, 
            a.telefone, 
            a.situacao_economica, 
            a.contato_encarregado
        FROM aluno a
        INNER JOIN escola e ON a.escola_id = e.id
        INNER JOIN turma t ON a.turma_id = t.id
        INNER JOIN curso c ON a.curso_id = c.id
        INNER JOIN classe class ON a.classe_id = class.id
        WHERE a.escola_id = ?
        ORDER BY a.turma_id ASC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $escola_id);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alunos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: sans-serif;
            background-color: #c1efde;
        }

        * {
            box-sizing: border-box;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td, .table th {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
        }

        .table th {
            background-color: darkblue;
            color: #ffffff;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        /* Responsividade */
        @media(max-width: 500px){
            .table thead {
                display: none;
            }

            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }
            .table tr {
                margin-bottom: 15px;
            }
            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-size: 15px;
                font-weight: bold;
                text-align: left;
            }
        }

        /* Responsividade adicional para telas pequenas */
        @media screen and (max-width: 767px) {
            table th, table td {
                padding: 5px;
                font-size: 12px;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .table td, .table th {
                white-space: nowrap;
                text-overflow: ellipsis;
            }
            .table td {
                max-width: 150px;
            }
        }

        .fixed-top-button {
            margin-top: -2px;
            margin-bottom: 50px;
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


        .fixed-top-button {
    margin-top: -2px;
margin-bottom: 50px;

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
    <button class="fixed-top-button" onclick="window.location.href='/destp_pro/direcao_escola/dashboard.php'">
        <i class="fas fa-tachometer-alt"></i> Voltar a Página Inicial
    </button>

    <div class="container mt-5">
        <h2 class='text-center text-primary my-2'>Lista de alunos(as)</h2>
        <table class="table" id='table-results'>
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Turma</th>
                    <th>Curso</th>
                    <th>Nome</th>
                    <th>Gênero</th>
                    <th>Idade</th>
                    <th>Nº Ordem</th>
                    <th>B.I</th>
                    <th>Nº Frequência</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>S.Económica</th>
                    <th>T.Encarregado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <?php foreach ($row as $campo => $valor): ?>
                            <?php if ($campo != 'id'): ?>
                                <td contenteditable="true" onblur="salvarEdicao(this, <?= $row['id'] ?>, '<?= $campo ?>')">
                                    <?= htmlspecialchars($valor) ?>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td><button class="btn btn-success btn-sm" onclick="salvarEdicaoLinha(<?= $row['id'] ?>, this)">Atualizar</button></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
    function salvarEdicao(elemento, id, campo) {
        var valor = elemento.innerText;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "salvar_lista_aluno.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                mostrarMensagem("Alteração salva com sucesso.");
            }
        };
        xhr.send("id=" + id + "&campo=" + campo + "&valor=" + encodeURIComponent(valor));
    }

    function salvarEdicaoLinha(id, botao) {
        var linha = botao.closest('tr');
        var colunas = linha.querySelectorAll('td[contenteditable=true]');
        colunas.forEach(coluna => {
            var campo = coluna.getAttribute('onblur').match(/'([^']+)'/)[1];
            salvarEdicao(coluna, id, campo);
        });
    }

    function mostrarMensagem(texto) {
        var mensagem = document.createElement('div');
        mensagem.className = 'alert alert-success fixed-top text-center';
        mensagem.style.zIndex = '1050';
        mensagem.innerText = texto;
        document.body.appendChild(mensagem);
        setTimeout(() => { mensagem.remove(); }, 2000);
    }
    </script>

    <div class="col-auto text-center ms-auto download-grp d-flex justify-content-center align-items-center w-100">
        <a href="#" class="btn btn-outline-primary me-2" onclick="exportToExcel()">
            <i class="fas fa-download"></i> Baixar Tabela
        </a>
    </div>
    <br>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
    function exportToExcel() {
        const table = document.getElementById('table-results');
        const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet JS" });
        XLSX.writeFile(wb, 'Lista_alunos.xlsx');
    }
    </script>
</body>
</html>
