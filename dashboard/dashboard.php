
<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
    header("Location: ../index.php");
    exit;
}
// Inclui a conexao com o banco de dados
include_once($_SERVER['DOCUMENT_ROOT'].'/destp_pro/conexao/conexao.php');


// Consulta para calcular médias por disciplina
$sql = "
    SELECT 
        d.nome_disciplina, 
        AVG(COALESCE(n.nota_final1, 0)) AS media_nota1, 
        AVG(COALESCE(n.nota_final2, 0)) AS media_nota2, 
        AVG(COALESCE(n.nota_final3, 0)) AS media_nota3
    FROM 
        nota n
    JOIN 
        disciplina d ON n.disciplina_id = d.id
    GROUP BY 
        d.nome_disciplina
";

$result = $mysqli->query($sql);

$disciplinas = [];
$media_nota1 = [];
$media_nota2 = [];
$media_nota3 = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $disciplinas[] = $row['nome_disciplina'];
        $media_nota1[] = $row['media_nota1'];
        $media_nota2[] = $row['media_nota2'];
        $media_nota3[] = $row['media_nota3'];
    }
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Dashboard</title>

  <!-- Montserrat Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="personalisar/css/style.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="shortcut icon" href="/favicon/favicon.ico" type="image/x-icon">




    <!-- Bootstrap CSS e Ícones -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap JavaScript (opcional, para funcionalidades interativas) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <style>
    li:hover {
      color: #fff;
    }

    .sidebar-title {
      position: relative;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      border: 4px dotted #ffffff;
      overflow: hidden;
      cursor: pointer;
      left: 20px;
      top: 10px;
    }

    .sidebar-title img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }


    #sidebar {
            background-color:rgb(9, 35, 226);
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
        }
        .sidebar-title img {
            width: 100%;
            padding: 10px;
        }
        .sidebar-list {
            list-style: none;
            padding: 0;
        }
        .sidebar-list-item a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #ffffff;
            padding: 10px 15px;
            transition: background 0.3s;
        }
        .sidebar-list-item a:hover {
            background: #50577A;
        }
        .sidebar-list-item span {
            margin-right: 10px;
        }
        .dropdown-content, .dropdown-content1 {
            display: none;
            list-style: none;
            padding-left: 20px;
        }
        .dropdown-content li a, .dropdown-content1 li a {
            color: #ffffff;
            text-decoration: none;
            padding: 5px 10px;
            display: block;
        }
        .dropdown-content li a:hover, .dropdown-content1 li a:hover {
            background: #50577A;
        }

        .sidebar-list-item i {
    margin-right: 8px; /* Ajuste conforme necessário */
}
 
  </style>
  
</head>
<body>
  <div class="grid-container" style="background-color: #1B203C;">

    <!-- Header -->
    <header class="header">
      <div class="menu-icon" onclick="openSidebar()">
        <span class="material-icons-outlined">menu</span>
      </div>
      <div class="header-left">
        <a href="#"><span class="material-icons-outlined">search</span></a>
      </div>
      <div class="header-right">
        <a href="notificacao/notificacao.php"><span class="material-icons-outlined" style="color: #00ff15;">notifications</span></a>
        <a href="notificacao/apagar_notificacao.php"><span class="material-icons-outlined" style="color: red;">notifications_off</span></a>

        <a href="crud/usuarios.php"><span class="material-icons-outlined" style="color: #ffffff;">account_circle</span></a>
      </div>
    </header>
    <!-- End Header -->

    <aside id="sidebar" class="bg-dark text-white">
    <div class="sidebar-title text-center p-3">
        <img src="personalisar/imagens/logo destp.jpg" alt="Logo" class="img-fluid">
    </div>
    <ul class="list-unstyled">
        <li class="sidebar-list-item p-2">
            <a href="#" onclick="loadPage('dashboard.html')" class="text-white text-decoration-none">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="sidebar-list-item p-2">
            <a href="cadastro_escola/cadastro_escola.php" class="text-white text-decoration-none">
                <i class="bi bi-plus-circle"></i> Direção
            </a>
        </li>
        <li class="sidebar-list-item p-2" onclick="loadPage('escola/escola.php')">
            <a href="#" class="text-white text-decoration-none">
                <i class="bi bi-building me-m2" ></i> Escola
            </a>
        </li>
        <li class="sidebar-list-item p-2">
            <a href="#" onclick="loadPage('professor/professor.php')" class="text-white text-decoration-none">
                <i class="bi bi-person-badge"></i> Professor
            </a>
        </li>
        <li class="sidebar-list-item p-2" onclick="loadPage('aluno/aluno.php')">
                     
                    <a href="#" class="text-white text-decoration-none">
                    <i class="bi bi-mortarboard"></i> Aluno
                   </a>
        </li>

        <li class="sidebar-list-item p-2">
            <a href="#" onclick="loadPage('pessoal_nao_docente/pessoal_ND.php')" class="text-white text-decoration-none">
                <i class="bi bi-people"></i> P.N.Docente
            </a>
        </li>
        <li class="sidebar-list-item p-2">
            <a href="aluno/card/transferencia.php" class="text-white text-decoration-none">
                <i class="bi bi-arrow-left-right"></i> Transferências
            </a>
        </li>
  
        <li class="sidebar-list-item p-2" onclick="toggleDropdown()">
            <a href="#" class="text-white text-decoration-none">
                <i class="bi bi-funnel"></i> Filtro Qualidade
            </a>
            <ul class="list-unstyled dropdown-content" id="dropdown">
                <li><a href="/destp_pro/dashboard/filtro/filtro_escola.php" class="text-white text-decoration-none" ><i class="bi bi-arrow-right"></i> Escola</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_professor.php" class="text-white text-decoration-none" ><i class="bi bi-arrow-right"></i> Professor</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_abandono_professor.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> P.Abandono</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_pessoal_ND.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Pessoal N.D</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_pnd_abandono.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> PND Abandono</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_aluno.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Alunos</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_deficiente.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> A. Deficicientes</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_abandono.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> A. Abandono</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_gravidez.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> A. Gravidez</a></li>
                <li><a href="/destp_pro/dashboard/filtro/filtro_nota.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> A. Nota</a></li>
            </ul>
        </li>
        <li class="sidebar-list-item p-2" onclick="toggleDropdown1()">
            <a href="#" class="text-white text-decoration-none">
                <i class="bi bi-funnel-fill"></i> Filtro Quantidade
            </a>
            <ul class="list-unstyled dropdown-content1" id="dropdown1">
                <li><a href="/destp_pro/dashboard/filtro_quantidade/filtro_quantidade_professor.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Professor</a></li>
                <li><a href="/destp_pro/dashboard/filtro_quantidade/filtro_quantidade_PND.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Pessoal N.D</a></li>
                <li><a href="/destp_pro/dashboard/filtro_quantidade/filtro_quantidade_aluno.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Aluno</a></li>
                <li><a href="/destp_pro/dashboard/filtro_quantidade/filtro_quantidade_nota.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Aluno-Nota</a></li>
                <li><a href="/destp_pro/dashboard/filtro_quantidade/filtro_quantidade_deficiente.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Aluno Defic</a></li>
                <li><a href="/destp_pro/dashboard/filtro_quantidade/filtro_quantidade_abandono.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Aluno Aband</a></li>
                <li><a href="/destp_pro/dashboard/filtro_quantidade/filtro_quantidade_gravidez.php" class="text-white text-decoration-none"><i class="bi bi-arrow-right"></i> Aluno Gravid</a></li>
            </ul>
        </li>
        <li class="sidebar-list-item p-2">
            <a href="controlo/controlo.php" class="text-white text-decoration-none">
                <i class="bi bi-person-gear"></i> Controlo
            </a>
        </li>
    </ul>
</aside>

    <!-- End Sidebar -->

    <!-- Main Content Area -->
    <main class="main-container" id="main-content">
      <div style="width: 50%; background-color: #1d2634; padding: 5px; border-radius: 5px;">
        <h2 style="text-align: center;">Média das Notas por Disciplina</h2>
        <canvas id="notasChart"></canvas>
      </div>
    </main>
    <!-- End Main -->

  </div>

  <!-- Scripts -->
  <!-- ApexCharts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.5/apexcharts.min.js"></script>
  <!-- Custom JS -->
  <script src="personalisar/js/script.js"></script>
  <script>
    function toggleDropdown() {
      var dropdown = document.getElementById("dropdown");
      dropdown.classList.toggle("show");

    }
    function toggleDropdown1() {
      var dropdown = document.getElementById("dropdown1");
      dropdown.classList.toggle("show1");

    }



// Dados PHP para JavaScript
const disciplinas = <?php echo json_encode($disciplinas); ?>;
    const mediaNota1 = <?php echo json_encode($media_nota1); ?>;
    const mediaNota2 = <?php echo json_encode($media_nota2); ?>;
    const mediaNota3 = <?php echo json_encode($media_nota3); ?>;

    // Configuraçao do gráfico
    const ctx = document.getElementById('notasChart').getContext('2d');
    const notasChart = new Chart(ctx, {
        type: 'bar', // Gráfico de barras
        data: {
            labels: disciplinas,
            datasets: [
                {
                    label: 'Pauta 1º Periodo',
                    data: mediaNota1,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pauta 2º Periodo',
                    data: mediaNota2,
                    backgroundColor: 'rgba(255, 159, 64, 0.5)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pauta 3º Periodo',
                    data: mediaNota3,
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Médias das Notas Finais por Disciplina',   color: '#ffff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Média das Notas',
                          color: '#ffff'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Disciplinas',
                        color: '#ffff'
                    }
                }
            }
        }
    });
    
  </script>
   <script>
        function toggleDropdown() {
            let dropdown = document.getElementById("dropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }
        function toggleDropdown1() {
            let dropdown1 = document.getElementById("dropdown1");
            dropdown1.style.display = dropdown1.style.display === "block" ? "none" : "block";
        }
    </script>
</body>
</html>
