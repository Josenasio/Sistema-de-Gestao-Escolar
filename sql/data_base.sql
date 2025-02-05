-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 05-Fev-2025 às 10:15
-- Versão do servidor: 8.2.0
-- versão do PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `escola_db_pro`
--
CREATE DATABASE IF NOT EXISTS `escola_db_pro` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `escola_db_pro`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno`
--

DROP TABLE IF EXISTS `aluno`;
CREATE TABLE IF NOT EXISTS `aluno` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `idade` int DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `numero_frequencia` int DEFAULT NULL,
  `numero_ordem` int DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `nome_encarregado` varchar(100) DEFAULT NULL,
  `contato_encarregado` varchar(20) DEFAULT NULL,
  `bi` varchar(50) DEFAULT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `data_emissao_bi` date DEFAULT NULL,
  `situacao_economica` enum('pobre','muito pobre','médio','rico','muito rico') DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `escola_id` int DEFAULT NULL,
  `turma_id` int DEFAULT NULL,
  `classe_id` int DEFAULT NULL,
  `curso_id` int DEFAULT NULL,
  `religiao_id` int DEFAULT NULL,
  `periododia_id` int DEFAULT NULL,
  `gravidez` tinyint(1) DEFAULT NULL,
  `data_conhecimento_gravidez` date DEFAULT NULL,
  `observacao_gravidez` text,
  `motivo_abandono` text,
  `deficiente` tinyint(1) DEFAULT NULL,
  `tipo_deficiencia` varchar(255) DEFAULT NULL,
  `estrategia_recuperacao` text,
  `id_distrito` int DEFAULT NULL,
  `filhos` enum('Não','Sim') DEFAULT NULL,
  `id_diretor_turma` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `escola_id` (`escola_id`),
  KEY `turma_id` (`turma_id`),
  KEY `classe_id` (`classe_id`),
  KEY `curso_id` (`curso_id`),
  KEY `religiao_id` (`religiao_id`),
  KEY `fk_periododia` (`periododia_id`),
  KEY `fk_id_distrito` (`id_distrito`),
  KEY `fk_id_usuarios` (`id_diretor_turma`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nivel_classe` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `classe`
--

INSERT INTO `classe` (`id`, `nivel_classe`) VALUES
(1, '7ª Classe'),
(2, '8ª Classe'),
(3, '9ª Classe'),
(4, '10ª Classe'),
(5, '11ª Classe'),
(6, '12ª Classe');

-- --------------------------------------------------------

--
-- Estrutura da tabela `classe_curso`
--

DROP TABLE IF EXISTS `classe_curso`;
CREATE TABLE IF NOT EXISTS `classe_curso` (
  `classe_id` int NOT NULL,
  `curso_id` int NOT NULL,
  PRIMARY KEY (`classe_id`,`curso_id`),
  KEY `curso_id` (`curso_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `classe_disciplina`
--

DROP TABLE IF EXISTS `classe_disciplina`;
CREATE TABLE IF NOT EXISTS `classe_disciplina` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_classe` int NOT NULL,
  `id_disciplina` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_classe` (`id_classe`),
  KEY `id_disciplina` (`id_disciplina`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

DROP TABLE IF EXISTS `curso`;
CREATE TABLE IF NOT EXISTS `curso` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_area` varchar(100) NOT NULL,
  `sigla` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`id`, `nome_area`, `sigla`) VALUES
(1, 'Ciências e Tecnologias', 'A'),
(2, 'Ciências Socio-Económicas', 'B'),
(3, 'Ciências Línguas e Humanidades', 'C'),
(4, 'Artes Visuais', 'D'),
(5, 'Geral', 'Geral');

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso_disciplina`
--

DROP TABLE IF EXISTS `curso_disciplina`;
CREATE TABLE IF NOT EXISTS `curso_disciplina` (
  `curso_id` int NOT NULL,
  `disciplina_id` int NOT NULL,
  PRIMARY KEY (`curso_id`,`disciplina_id`),
  KEY `disciplina_id` (`disciplina_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso_professor`
--

DROP TABLE IF EXISTS `curso_professor`;
CREATE TABLE IF NOT EXISTS `curso_professor` (
  `curso_id` int NOT NULL,
  `professor_id` int NOT NULL,
  PRIMARY KEY (`curso_id`,`professor_id`),
  KEY `professor_id` (`professor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso_turma`
--

DROP TABLE IF EXISTS `curso_turma`;
CREATE TABLE IF NOT EXISTS `curso_turma` (
  `curso_id` int NOT NULL,
  `turma_id` int NOT NULL,
  PRIMARY KEY (`curso_id`,`turma_id`),
  KEY `turma_id` (`turma_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplina`
--

DROP TABLE IF EXISTS `disciplina`;
CREATE TABLE IF NOT EXISTS `disciplina` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_disciplina` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `disciplina`
--

INSERT INTO `disciplina` (`id`, `nome_disciplina`) VALUES
(101, 'História 2'),
(100, 'História'),
(99, 'Geologia'),
(98, 'Geografia 2'),
(97, 'Geografia 1'),
(96, 'Geografia'),
(95, 'Francês'),
(94, 'Formação Cívica'),
(93, 'Física 2'),
(92, 'Física 1'),
(91, 'Física'),
(90, 'Filosofia'),
(89, 'Educação Física'),
(88, 'Educação Ambiental'),
(87, 'Empreendedorismo'),
(86, 'Educação Visual e Oficinal'),
(85, 'Educação para Saúde'),
(84, 'Economia 2'),
(83, 'Economia 1'),
(82, 'Economia'),
(81, 'Direito 2'),
(80, 'Direito 1'),
(79, 'Direito'),
(78, 'CSPQ'),
(77, 'Ciências Naturais'),
(76, 'Ciências'),
(75, 'Biologia 2'),
(74, 'Biologia 1'),
(73, 'Biologia'),
(72, 'Astronomia'),
(102, 'Inglês'),
(103, 'Integração Social'),
(104, 'Língua Portuguesa'),
(105, 'Matemática'),
(106, 'Oficina de Artes'),
(107, 'Química'),
(108, 'Química 1'),
(109, 'Geometria Descritiva'),
(110, 'Química 2'),
(111, 'Sociologia'),
(112, 'Sociologia 1'),
(113, 'Sociologia 2'),
(114, 'Psciologia'),
(115, 'Psciologia 1'),
(116, 'Psciologia 2'),
(117, 'Técnicas Laboratoriais de Biologia'),
(118, 'TAA'),
(119, 'TIC'),
(120, 'Alemão');

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplinas_exame`
--

DROP TABLE IF EXISTS `disciplinas_exame`;
CREATE TABLE IF NOT EXISTS `disciplinas_exame` (
  `id_disciplina` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_disciplina`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `disciplinas_exame`
--

INSERT INTO `disciplinas_exame` (`id_disciplina`, `nome`) VALUES
(1, 'Matemática'),
(2, 'Português');

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplina_turma`
--

DROP TABLE IF EXISTS `disciplina_turma`;
CREATE TABLE IF NOT EXISTS `disciplina_turma` (
  `disciplina_id` int NOT NULL,
  `turma_id` int NOT NULL,
  PRIMARY KEY (`disciplina_id`,`turma_id`),
  KEY `turma_id` (`turma_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `distrito`
--

DROP TABLE IF EXISTS `distrito`;
CREATE TABLE IF NOT EXISTS `distrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_distrito` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `distrito`
--

INSERT INTO `distrito` (`id`, `nome_distrito`) VALUES
(1, 'Água-Grande'),
(2, 'Cantagalo'),
(3, 'Lobata'),
(4, 'Lembá'),
(5, 'Cauê'),
(6, 'Mé-Zochi'),
(7, 'Principe (R.A.P)');

-- --------------------------------------------------------

--
-- Estrutura da tabela `escola`
--

DROP TABLE IF EXISTS `escola`;
CREATE TABLE IF NOT EXISTS `escola` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `distrito_id` int DEFAULT NULL,
  `nome_diretor` varchar(255) DEFAULT NULL,
  `telefone_fixo` varchar(15) DEFAULT NULL,
  `email_escola` varchar(255) DEFAULT NULL,
  `agua_consumida` varchar(255) DEFAULT NULL,
  `abastecimento_energia` varchar(255) DEFAULT NULL,
  `destinacao_lixo` varchar(255) DEFAULT NULL,
  `numero_computador` int DEFAULT NULL,
  `numero_computador_funcionamento` int DEFAULT NULL,
  `acesso_internet` varchar(100) DEFAULT NULL,
  `acesso_banda_larga` varchar(100) DEFAULT NULL,
  `alimentacao` varchar(255) DEFAULT NULL,
  `vedacao` varchar(255) DEFAULT NULL,
  `via_aluno_deficiente` varchar(100) DEFAULT NULL,
  `biblioteca` varchar(100) DEFAULT NULL,
  `anfiteatro` varchar(100) DEFAULT NULL,
  `cantina` varchar(100) DEFAULT NULL,
  `ginasio` varchar(100) DEFAULT NULL,
  `campo_desportivo` varchar(100) DEFAULT NULL,
  `numero_wc_professor` int DEFAULT NULL,
  `numero_wc_diretor` int DEFAULT NULL,
  `numero_wc_aluno` int DEFAULT NULL,
  `numero_wc_aluna` int DEFAULT NULL,
  `laboratorio_fisica` varchar(100) DEFAULT NULL,
  `laboratorio_quimica` varchar(100) DEFAULT NULL,
  `laboratorio_biologia` varchar(100) DEFAULT NULL,
  `sala_informatica` varchar(100) DEFAULT NULL,
  `sala_professor` varchar(255) DEFAULT NULL,
  `numero_sala_aula_existente` int DEFAULT NULL,
  `wc_masculino_feminino` int DEFAULT NULL,
  `7manha` int DEFAULT NULL,
  `7tarde` int DEFAULT NULL,
  `8manha` int DEFAULT NULL,
  `8tarde` int DEFAULT NULL,
  `9manha` int DEFAULT NULL,
  `9tarde` int DEFAULT NULL,
  `10manha` int DEFAULT NULL,
  `10tarde` int DEFAULT NULL,
  `11manha` int DEFAULT NULL,
  `11tarde` int DEFAULT NULL,
  `12manha` int DEFAULT NULL,
  `12tarde` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distrito_id` (`distrito_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `escola`
--

INSERT INTO `escola` (`id`, `nome`, `telefone`, `email`, `codigo`, `localizacao`, `endereco`, `distrito_id`, `nome_diretor`, `telefone_fixo`, `email_escola`, `agua_consumida`, `abastecimento_energia`, `destinacao_lixo`, `numero_computador`, `numero_computador_funcionamento`, `acesso_internet`, `acesso_banda_larga`, `alimentacao`, `vedacao`, `via_aluno_deficiente`, `biblioteca`, `anfiteatro`, `cantina`, `ginasio`, `campo_desportivo`, `numero_wc_professor`, `numero_wc_diretor`, `numero_wc_aluno`, `numero_wc_aluna`, `laboratorio_fisica`, `laboratorio_quimica`, `laboratorio_biologia`, `sala_informatica`, `sala_professor`, `numero_sala_aula_existente`, `wc_masculino_feminino`, `7manha`, `7tarde`, `8manha`, `8tarde`, `9manha`, `9tarde`, `10manha`, `10tarde`, `11manha`, `11tarde`, `12manha`, `12tarde`) VALUES
(1, 'Escola Secundária Básica de Porto Alegre', '9972532', 'Leupinto41@gmail.com', '11321022013', 'Rural', 'Porto Alegre, Água Grande', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Escola Secundária Básica de Ribeira Peixe', '9966831', 'ptavaressemedo@gmail.com', '11320820003', 'Rural', 'Avenida Secundária, Cantagalo', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Escola Secundária Básica de Angolares', '9969876', 'albertoneves802@gmail.com', '11321122033', 'Rural', 'Estrada Lembá, Lembá', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Escola Secundária Básica de Angra Toldo', '9868765', NULL, '11321220003', 'Rural', 'Praça Caué, Caué', 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Escola Secundária Básica de Colónia Açoriana', '9963674', 'slemetinosanto@gmail.com', '11310420003', 'Rural', 'Rua Infantil, Pagué', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Escola Secundária Básica de Ubá Budo', '99221893', 'mauriciocsoares19@gmail.com', '11321220002', 'Rural', 'Avenida Mezóchi, Mezóchi', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Escola Secundária Básica de Ribeira Afonso', '9968330', 'lavusieramosacramento123@gmail.com', '11311522013', 'Rural', 'Rua Lobata, Lobata', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Escola Secundária Básica de Água-Izé', '99937055', 'afonso242@gmail.com', 'INF202', 'Rural', 'Rua Infantil, Pagué', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Escola Secundária Básica de Anselmo Andrade', '9997695', NULL, '11310520003', 'Rural', 'Avenida Mezóchi, Mezóchi', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Escola Secundária Básica de Maria Augusta da Silva de la cruz Martiniz', '9803609', 'abduldeceita@gmail.com', '11311220003', 'Rural', 'Rua Lobata, Lobata', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Escola Secundária Básica de Santana', '9921396', 'Sharvardsa@gmail.com', '11311422013', 'Rural', 'Rua Infantil, Pagué', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'Escola Secundária Básica de San Finícia', '9933222', 'ademebasto35@hotmail.com', 'MEZ303', 'Rural', 'Avenida Mezóchi, Mezóchi', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'Escola Secundária Básica de Almas', '9930424', 'anaydidosprazeresferreira@gmail.com', '11221620003', 'Rural', 'Rua Lobata, Lobata', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'Escola Secundária Januário José da Costa (Bombom)', '9911310', 'satiagocismeiro3@homail.com', '11222022013', 'Rural', 'Rua Infantil, Pagué', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'Escola Secundária de Trindade', '9968891', 'Elisabet.nogueira@gmail.com', '11222220003', 'Rural', 'Avenida Mezóchi, Mezóchi', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'Escola Secundária Básica de António Francisco Afonso Píres (Monte Café)', '9939805', 'amanciosousa04@gmail.com', '11221922013', 'Rural', 'Rua Lobata, Lobata', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'Escola Secundária Maria Manuela Margarido', '9909861', 'haryluciano12@hotmail.com', '11222122013', 'Rural', 'Rua Infantil, Pagué', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'Escola Secundária Básica Albertina Matos', '9069148', 'Humbelinasantos@hotmail.com', '11221022003', 'Rural', 'Avenida Mezóchi, Mezóchi', 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'Escola Secundária Básica Bôbô-Fôrro', '9924185', 'Wanderbarros@outlook.com', '11210520003', 'Urbana', 'Rua Lobata, Lobata', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'Escola Secundária Liceu Nacional', '9044210', 'fortemaria622@gmail.com', '11211922033', 'Urbana', 'Rua Infantil, Pagué', 1, 'Francisco Jose da Costa', '9993442', 'escola@gmail.com', 'Nao Potável', 'Energia Nao Renovável', 'Coloca no Contentor', 23, 3, 'Sim', 'Não', 'Não', 'Cercado com Murro', 'Sim', 'Sim', 'Sim Adequado', 'Sim', 'Sim Inadequado', 'Nao', 22, 12, 31, 2213, 'Sim Adequado', 'Sim Inadequado', 'Sim Inadequado', 'Sim Adequado', 'Sim Adequado', 221, 12, 121, 33, 313, 878, 121, 122, 31, 443, 22, 221, 13, 53),
(21, 'Centro de Promoção Madalena Canossa', '9057182', 'promilsia@gmail.com', '11212022012', 'Urbana', 'Avenida Mezóchi, Mezóchi', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'Escola Secundária Patrice Lumumba', '992116', NULL, '11211120003', 'Urbana', 'Rua Lobata, Lobata', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'Escola Secundária Básica de Chácara', '9907176', 'dacostaviegas@gmail.com', '11211820003', 'Urbana', 'Rua Infantil, Pagué', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'Centro Politécnico', '9907964', 'dafonso21@gmail.com', 'MEZ303', 'Urbana', 'Avenida Mezóchi, Mezóchi', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'Escola Secundária Básica de Desejada', '9095740', 'limaoscarito@gmail.com', '11110422003', 'Rural', 'Rua Lobata, Lobata', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'Escola Secundária Sum Mé-Xinhô', '9087888', 'grisodias@gmail.com', 'INF202', 'Rural', 'Rua Infantil, Pagué', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'Escola Secundária Básica de Guadalupe', '9918227', 'auteriosousa@gmail.com', '11111220003', 'Rural', 'Avenida Mezóchi, Mezóchi', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'Escola Secundária Básica Diogo Vaz', '9054381', 'vladiyvilanova110@gmail.com', '11120820003', 'Rural', 'Rua Lobata, Lobata', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'Escola Secundária Básica Domingo Daio', '9926445', 'deladierlima@homail.com', '11121120003', 'Rural', 'Rua Infantil, Pagué', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'Escola Secundária de Neves', '9863993', 'sedineiveia1986@gmail.com', '11121322013', 'Rural', 'Avenida Mezóchi, Mezóchi', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'Escola Secundária Básica de Santa Catarina', '9068017', 'Jerrysanto11@0388@gmail.com', '11121022013', 'Rural', 'Rua Lobata, Lobata', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'Escola Secundária Básica de Nova Estrela', '9982075', 'danilcosta112@gmail.com', '12410220003', 'Urbana', 'Rua Infantil, Pagué', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'Escola Secundária Básica de Porto Real', '9868152', NULL, '12410420003', 'Rural', 'Avenida Mezóchi, Mezóchi', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'Escola Secundária Básica de Praia Inhame', '9952539', NULL, '12410520003', 'Rural', 'Rua Lobata, Lobata', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'Escola Secundária de Padrão', '9955711', 'egino@gmail.com', '12410922013', 'Urbana', 'Rua Lobata, Lobata', 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `nota`
--

DROP TABLE IF EXISTS `nota`;
CREATE TABLE IF NOT EXISTS `nota` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nota1` decimal(5,2) DEFAULT NULL,
  `nota2` decimal(5,2) DEFAULT NULL,
  `nota3` decimal(5,2) DEFAULT NULL,
  `nota4` decimal(5,2) DEFAULT NULL,
  `nota5` decimal(5,2) DEFAULT NULL,
  `nota6` decimal(5,2) DEFAULT NULL,
  `nota_final1` decimal(5,2) DEFAULT NULL,
  `nota_final2` decimal(5,2) DEFAULT NULL,
  `nota_final3` decimal(5,2) DEFAULT NULL,
  `comportamento1` varchar(50) DEFAULT NULL,
  `comportamento2` varchar(50) DEFAULT NULL,
  `comportamento3` varchar(50) DEFAULT NULL,
  `disciplina_id` int DEFAULT NULL,
  `id_aluno` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disciplina_id` (`disciplina_id`),
  KEY `fk_classe` (`id_aluno`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `nota`
--

INSERT INTO `nota` (`id`, `nota1`, `nota2`, `nota3`, `nota4`, `nota5`, `nota6`, `nota_final1`, `nota_final2`, `nota_final3`, `comportamento1`, `comportamento2`, `comportamento3`, `disciplina_id`, `id_aluno`) VALUES
(147, 111.00, 222.00, 0.00, NULL, NULL, NULL, 999.00, NULL, NULL, NULL, NULL, NULL, 89, 113),
(146, 0.10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 90, 113),
(145, 80.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 102, 113),
(144, NULL, NULL, NULL, 12.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 72, 111),
(143, NULL, NULL, NULL, NULL, NULL, NULL, 13.00, NULL, NULL, NULL, NULL, NULL, 110, 112),
(142, 20.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 105, 112),
(141, NULL, 10.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 99, 112),
(140, NULL, 12.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 95, 112),
(139, NULL, 18.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 84, 111),
(138, NULL, NULL, NULL, 9.90, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 87, 111),
(137, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 95, 111),
(136, 10.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 103, 111),
(135, NULL, 20.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 81, 111),
(134, NULL, NULL, NULL, NULL, 11.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 110, 111),
(133, NULL, NULL, NULL, NULL, NULL, NULL, 12.00, NULL, NULL, NULL, NULL, NULL, 102, 111),
(132, NULL, NULL, NULL, NULL, NULL, 15.00, NULL, NULL, NULL, NULL, NULL, NULL, 87, 112),
(131, NULL, NULL, NULL, NULL, NULL, 12.00, NULL, NULL, NULL, NULL, NULL, NULL, 89, 112),
(130, NULL, NULL, 10.00, 1.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 93, 112),
(129, NULL, 1.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 89, 111),
(128, NULL, 12.00, 3.00, 12.00, 11.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 100, 111),
(127, 0.00, 0.00, 4.00, 0.00, 11.00, 0.00, 0.00, 0.00, 0.10, NULL, NULL, NULL, 99, 111),
(148, 16.00, 17.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 104, 128),
(149, 5.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 95, 128),
(150, NULL, 6.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 102, 128);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notas_exame`
--

DROP TABLE IF EXISTS `notas_exame`;
CREATE TABLE IF NOT EXISTS `notas_exame` (
  `id_nota` int NOT NULL AUTO_INCREMENT,
  `id_aluno` int NOT NULL,
  `id_disciplina` int NOT NULL,
  `fase` enum('1','2') NOT NULL,
  `nota` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id_nota`),
  UNIQUE KEY `id_aluno` (`id_aluno`,`id_disciplina`,`fase`),
  KEY `id_disciplina` (`id_disciplina`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `notas_exame`
--

INSERT INTO `notas_exame` (`id_nota`, `id_aluno`, `id_disciplina`, `fase`, `nota`) VALUES
(70, 116, 1, '2', 11.00),
(69, 116, 2, '2', 20.00),
(68, 117, 1, '2', 18.00),
(67, 117, 1, '1', 6.00),
(66, 111, 2, '1', 17.00),
(65, 115, 1, '1', 15.00),
(64, 115, 1, '2', 0.00),
(71, 117, 2, '2', 16.80),
(72, 117, 2, '1', 17.90),
(73, 125, 1, '1', 12.00),
(74, 122, 2, '1', 17.00),
(75, 123, 1, '1', 6.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacao_diretor`
--

DROP TABLE IF EXISTS `notificacao_diretor`;
CREATE TABLE IF NOT EXISTS `notificacao_diretor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_diretor_turma` int NOT NULL,
  `notificacao` text NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `visualizado` tinyint(1) NOT NULL DEFAULT '0',
  `id_notificacao` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `notificacao_diretor`
--

INSERT INTO `notificacao_diretor` (`id`, `id_diretor_turma`, `notificacao`, `data_criacao`, `visualizado`, `id_notificacao`) VALUES
(1, 14, '', '2024-12-11 11:34:44', 0, 10),
(2, 1, 'bela', '2024-12-11 11:43:10', 0, 10),
(3, 1, 'bela', '2024-12-11 11:43:12', 0, 10),
(4, 1, 'bela', '2024-12-11 11:43:13', 0, 10),
(5, 1, 'bela', '2024-12-11 11:43:13', 0, 10),
(6, 1, 'bela', '2024-12-11 11:43:13', 0, 10),
(7, 1, 'bela', '2024-12-11 11:43:14', 0, 10),
(8, 1, 'bela', '2024-12-11 11:43:14', 0, 10),
(9, 1, 'bela', '2024-12-11 11:43:23', 0, 10),
(10, 1, 'bela', '2024-12-11 11:43:24', 0, 10),
(11, 1, 'bela', '2024-12-11 11:43:24', 0, 10),
(12, 14, 'bela', '2024-12-11 11:43:38', 0, 10),
(13, 1, 'bela', '2024-12-11 11:43:40', 0, 10),
(14, 1, 'bela', '2024-12-11 11:43:40', 0, 10),
(15, 1, 'bela', '2024-12-11 11:43:40', 0, 10),
(16, 1, 'bela', '2024-12-11 11:43:41', 0, 10),
(17, 1, 'bela', '2024-12-11 11:43:41', 0, 10),
(18, 1, 'bela', '2024-12-11 11:43:41', 0, 10),
(19, 1, 'bela', '2024-12-11 11:43:41', 0, 10),
(20, 1, 'bela', '2024-12-11 11:43:41', 0, 10),
(21, 1, 'bela', '2024-12-11 11:43:42', 0, 10),
(22, 1, 'bela', '2024-12-11 11:43:42', 0, 10),
(23, 1, 'bela', '2024-12-11 11:43:42', 0, 10),
(24, 1, 'bela', '2024-12-11 11:43:42', 0, 10),
(25, 1, 'bela', '2024-12-11 11:43:53', 0, 10),
(26, 1, 'bela', '2024-12-11 11:46:16', 0, 10),
(27, 1, 'bela', '2024-12-11 11:46:17', 0, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacoes`
--

DROP TABLE IF EXISTS `notificacoes`;
CREATE TABLE IF NOT EXISTS `notificacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_escola` int NOT NULL,
  `notificacao` text NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `visualizado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_escola` (`id_escola`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `id_escola`, `notificacao`, `data_criacao`, `visualizado`) VALUES
(55, 20, 'comece a recolha', '2025-01-15 09:15:54', 1),
(56, 35, 'testando', '2025-01-21 09:31:35', 1),
(57, 35, 'segundo teste', '2025-01-21 09:34:53', 1),
(58, 35, 'Josenasio Borja de Ceita tem que passar', '2025-01-21 10:58:22', 1),
(59, 35, 'teste', '2025-01-21 15:55:40', 1),
(60, 35, 'tente novamente', '2025-01-24 10:09:31', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `periodo_dia`
--

DROP TABLE IF EXISTS `periodo_dia`;
CREATE TABLE IF NOT EXISTS `periodo_dia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `periodo_dia`
--

INSERT INTO `periodo_dia` (`id`, `descricao`) VALUES
(1, 'Manhã'),
(2, 'Tarde');

-- --------------------------------------------------------

--
-- Estrutura da tabela `periodo_semestre123`
--

DROP TABLE IF EXISTS `periodo_semestre123`;
CREATE TABLE IF NOT EXISTS `periodo_semestre123` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `periodo_semestre123`
--

INSERT INTO `periodo_semestre123` (`id`, `descricao`) VALUES
(1, '1º Periodo'),
(2, '2º Periodo'),
(3, '3º Periodo'),
(4, 'Exame');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoal_nao_docente`
--

DROP TABLE IF EXISTS `pessoal_nao_docente`;
CREATE TABLE IF NOT EXISTS `pessoal_nao_docente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `contacto` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `idade` int DEFAULT NULL,
  `nif` varchar(50) DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `data_contrato` date DEFAULT NULL,
  `funcao` varchar(50) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `numero_conta_bancaria` varchar(50) DEFAULT NULL,
  `ano_servico` int DEFAULT NULL,
  `ano_inicio_servico` int DEFAULT NULL,
  `nivel_academico` varchar(50) DEFAULT NULL,
  `escola_id` int DEFAULT NULL,
  `distrito_id` int DEFAULT NULL,
  `religiao_id` int DEFAULT NULL,
  `motivo_abandono` text,
  `data_abandono` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `escola_id` (`escola_id`),
  KEY `distrito_id` (`distrito_id`),
  KEY `religiao_id` (`religiao_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `pessoal_nao_docente`
--

INSERT INTO `pessoal_nao_docente` (`id`, `nome`, `contacto`, `endereco`, `idade`, `nif`, `genero`, `data_contrato`, `funcao`, `estado_civil`, `numero_conta_bancaria`, `ano_servico`, `ano_inicio_servico`, `nivel_academico`, `escola_id`, `distrito_id`, `religiao_id`, `motivo_abandono`, `data_abandono`) VALUES
(13, 'Paulo Santos', '9993743', 'Praia MELÃO', 63, '4545454', 'Feminino', '2025-01-16', 'GUARDA', 'Divorciado', '4554455', 3, 0, '5 ANO', 35, 4, 9, '', '0000-00-00'),
(11, 'joao', '4657775', 'palha sul', 45, '5436646', 'Masculino', '2024-12-12', 'Guarda', 'Solteiro', '6575757', 8, 2024, '10 classe', 35, 4, 6, '', '0000-00-00'),
(12, 'Hernestino Dalva', '9999999', 'Ponta mina', 56, '7567776', 'Masculino', '2025-01-29', 'segurança', 'Divorciado', '4566655', 65, 2025, '2 classe', 35, 4, 9, 'viagem final', '2025-01-14');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professor`
--

DROP TABLE IF EXISTS `professor`;
CREATE TABLE IF NOT EXISTS `professor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `idade` int DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `data_contrato` date DEFAULT NULL,
  `funcao` varchar(50) DEFAULT NULL,
  `nome_facebook` varchar(100) DEFAULT NULL,
  `nivel_academico` varchar(50) DEFAULT NULL,
  `area_formacao1` varchar(100) DEFAULT NULL,
  `area_formacao2` varchar(100) DEFAULT NULL,
  `duracao_formacao` varchar(50) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `categoria_salarial` varchar(50) DEFAULT NULL,
  `novo` tinyint(1) DEFAULT NULL,
  `titulo` varchar(50) DEFAULT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `distrito_id` int DEFAULT NULL,
  `religiao_id` int DEFAULT NULL,
  `id_escola` int DEFAULT NULL,
  `id_turma` int DEFAULT NULL,
  `id_disciplina` int DEFAULT NULL,
  `id_classe` int DEFAULT NULL,
  `motivo_abandono` text,
  `data_abandono` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distrito_id` (`distrito_id`),
  KEY `religiao_id` (`religiao_id`),
  KEY `fk_id_escola` (`id_escola`),
  KEY `fk_id_turma` (`id_turma`),
  KEY `fk_id_disciplina` (`id_disciplina`),
  KEY `fk_id_classe` (`id_classe`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `professor_aluno`
--

DROP TABLE IF EXISTS `professor_aluno`;
CREATE TABLE IF NOT EXISTS `professor_aluno` (
  `professor_id` int NOT NULL,
  `aluno_id` int NOT NULL,
  PRIMARY KEY (`professor_id`,`aluno_id`),
  KEY `aluno_id` (`aluno_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `professor_classe`
--

DROP TABLE IF EXISTS `professor_classe`;
CREATE TABLE IF NOT EXISTS `professor_classe` (
  `id_professor` int NOT NULL,
  `id_classe` int NOT NULL,
  PRIMARY KEY (`id_professor`,`id_classe`),
  KEY `id_classe` (`id_classe`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professor_classe`
--

INSERT INTO `professor_classe` (`id_professor`, `id_classe`) VALUES
(32, 3),
(33, 3),
(34, 3),
(34, 4),
(35, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `professor_disciplina`
--

DROP TABLE IF EXISTS `professor_disciplina`;
CREATE TABLE IF NOT EXISTS `professor_disciplina` (
  `professor_id` int NOT NULL,
  `disciplina_id` int NOT NULL,
  PRIMARY KEY (`professor_id`,`disciplina_id`),
  KEY `disciplina_id` (`disciplina_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professor_disciplina`
--

INSERT INTO `professor_disciplina` (`professor_id`, `disciplina_id`) VALUES
(32, 89),
(33, 90),
(34, 73),
(34, 89),
(34, 96),
(34, 119),
(35, 87);

-- --------------------------------------------------------

--
-- Estrutura da tabela `professor_escola`
--

DROP TABLE IF EXISTS `professor_escola`;
CREATE TABLE IF NOT EXISTS `professor_escola` (
  `professor_id` int NOT NULL,
  `escola_id` int NOT NULL,
  PRIMARY KEY (`professor_id`,`escola_id`),
  KEY `escola_id` (`escola_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `professor_periodo_dia`
--

DROP TABLE IF EXISTS `professor_periodo_dia`;
CREATE TABLE IF NOT EXISTS `professor_periodo_dia` (
  `professor_id` int NOT NULL,
  `periodo_dia_id` int NOT NULL,
  PRIMARY KEY (`professor_id`,`periodo_dia_id`),
  KEY `periodo_dia_id` (`periodo_dia_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `professor_turma`
--

DROP TABLE IF EXISTS `professor_turma`;
CREATE TABLE IF NOT EXISTS `professor_turma` (
  `professor_id` int NOT NULL,
  `turma_id` int NOT NULL,
  PRIMARY KEY (`professor_id`,`turma_id`),
  KEY `turma_id` (`turma_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professor_turma`
--

INSERT INTO `professor_turma` (`professor_id`, `turma_id`) VALUES
(32, 7254),
(33, 7255),
(34, 7253),
(34, 7255),
(35, 7260);

-- --------------------------------------------------------

--
-- Estrutura da tabela `religiao`
--

DROP TABLE IF EXISTS `religiao`;
CREATE TABLE IF NOT EXISTS `religiao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_religiao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `religiao`
--

INSERT INTO `religiao` (`id`, `nome_religiao`) VALUES
(1, 'Católica'),
(2, 'Adventista'),
(3, 'Jeová'),
(4, 'Maná'),
(5, 'Universal'),
(6, 'Envangélica'),
(7, 'Montanha de Fogo'),
(8, 'Budista'),
(9, 'Mundial'),
(10, 'Muçulmano'),
(11, 'Outra');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transferencias`
--

DROP TABLE IF EXISTS `transferencias`;
CREATE TABLE IF NOT EXISTS `transferencias` (
  `id_escola` int NOT NULL,
  `id_aluno` int NOT NULL,
  `transferidos` int DEFAULT NULL,
  `recebidos` int DEFAULT NULL,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_escola`,`id_aluno`),
  KEY `id_aluno` (`id_aluno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma`
--

DROP TABLE IF EXISTS `turma`;
CREATE TABLE IF NOT EXISTS `turma` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_turma` varchar(50) NOT NULL,
  `escola_id` int DEFAULT NULL,
  `id_classe` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `escola_id` (`escola_id`),
  KEY `fk_classe` (`id_classe`)
) ENGINE=MyISAM AUTO_INCREMENT=7266 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `turma`
--

INSERT INTO `turma` (`id`, `nome_turma`, `escola_id`, `id_classe`) VALUES
(7265, 'U', NULL, NULL),
(7264, 'K', NULL, NULL),
(7263, 'OL1', NULL, NULL),
(7262, 'X', NULL, NULL),
(7261, 'C', NULL, NULL),
(1, 'V', NULL, NULL),
(7250, 'O', NULL, NULL),
(7260, 'P', NULL, NULL),
(7252, 'A', NULL, NULL),
(7253, 'G', NULL, NULL),
(7259, 'D', NULL, NULL),
(7255, 'B', NULL, NULL),
(7256, 'T', NULL, NULL),
(7258, 'A1', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma_periodo`
--

DROP TABLE IF EXISTS `turma_periodo`;
CREATE TABLE IF NOT EXISTS `turma_periodo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `turma_id` int NOT NULL,
  `periodo_dia_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `turma_id` (`turma_id`),
  KEY `periodo_dia_id` (`periodo_dia_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('administrador','direcao','professor') NOT NULL,
  `id_escola` int DEFAULT NULL,
  `classe_id` int DEFAULT NULL,
  `turma_id` int DEFAULT NULL,
  `curso_id` int DEFAULT NULL,
  `periodo_dia_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `id_escola` (`id_escola`),
  KEY `fk_usuarios_classe` (`classe_id`),
  KEY `fk_usuarios_turma` (`turma_id`),
  KEY `fk_usuarios_curso` (`curso_id`),
  KEY `fk_usuarios_periodo_dia` (`periodo_dia_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `id_escola`, `classe_id`, `turma_id`, `curso_id`, `periodo_dia_id`) VALUES
(1, 'João da Silva', 'joao.silva@escola.com', '$2y$10$ET03.qqaPP29H1bOCQ8Z2unjuRZ99G8ZXyYKeXmED7f7QBgq6AQCi', 'administrador', 2, NULL, NULL, NULL, NULL),
(21, 'Elisete', 'elisete@escola.com', '$2y$10$B5bzFdItyuWFn3R3S0RztuF.tB7bCjeXFMuD9znqDq5dNVmOkAGcW', 'direcao', 35, NULL, NULL, NULL, NULL),
(19, 'Josenasio Borja Ceita', 'forte@escola.com', '$2y$10$vZAXcCdwNbRs7KfroSJkgu7bo3wWPnpGJ4KDoQUG3PFRR1uY9952i', 'direcao', 35, NULL, NULL, NULL, NULL),
(43, 'Helena Santos', 'Helena@gmail.com', '$2y$10$eYpYLLZNB0zT2dj8Tqf.0.4V/ltxqDL3CBcIqutVicJffdjY9xpOO', 'professor', 20, 3, 7261, 2, 2),
(17, 'Mauro', 'mauro.silva@gmail.com', '$2y$10$1T8lEKeoC.SK/7p5bHKAdeOKNysmxDy/1ivCDCbsLGxHAj/08UA4.', 'professor', 15, 2, 7252, 5, 0),
(18, 'Tião', 'tiao@escola.com', '$2y$10$EhMPCAEm1yW5EwxTtbkIXuxNTOMtof5.nT0mjE3/chx/5drqB.XxC', 'professor', 15, NULL, NULL, NULL, NULL),
(36, 'Abdul', 'Abdul@gmail.com', '$2y$10$e9BNO3cChYMapVdXZpyGZupmu1INY99Ic.kL.AzAqMfiT7IIN4tfm', 'direcao', 20, NULL, NULL, NULL, NULL),
(23, 'Elisabete Nogueira', 'Elisabete@gmail.com', '$2y$10$B5bzFdItyuWFn3R3S0RztuF.tB7bCjeXFMuD9znqDq5dNVmOkAGcW', 'direcao', 35, NULL, NULL, NULL, NULL),
(24, 'Awold', 'Awold@escola.com', '$2y$10$6amNBRCW8OVRbBmymPbI7eMu6qBTK.BSLSSUerQIgamf0YgZPFj4G', 'professor', 15, 3, 7251, 0, 0),
(42, 'Atanásio Lima Alves de Ceita', 'ataata@escola.com', '$2y$10$zGIr4pe4aIRAnIE96W.1KOVGII8JIb6vyiRIpT2f4FvdheDOW90e6', 'direcao', 28, NULL, NULL, NULL, NULL),
(40, 'Baltazar Dallas', 'balta@gmail.com', '$2y$10$g7wOfa5pnQlA.LxZvo8MVuYcgCPIiCfnGIn3u0enjlhuHIgNoTHAK', 'professor', 6, 5, 7260, 2, 1),
(39, 'Carlos Vila Nova', 'vilacarlos@escola.com', '$2y$10$QlUB62OI1xyY2jKw90Mk/OOeDJwh/ZkX0kvw.v8SwYMlWPilz.bE.', 'direcao', 6, NULL, NULL, NULL, NULL),
(30, 'Daltom da Costa', 'costaDalton@escola.com', '$2y$10$TrJAjojKFSHDanKbV9HHPeID7SKt/qFkb67yyFjXUTbL11t.4vhJ2', 'professor', 35, 3, 7253, 3, 1),
(31, 'Zico', 'zico@gmail.com', '$2y$10$ZyavP81lB6wZ13x1SsGrYOQwIGGDX1Rd2anxoA9bTSxCBH8awWvr6', 'professor', 15, 6, 7250, 2, 1),
(38, 'Pai Grande', 'pai@gmail.com', '$2y$10$QWejKmxQgQTUmZMqIcHP8e4kxhbAiXJL3Du.P62eTMWkrlwP6yz7O', 'professor', 20, 4, 7259, 4, 2),
(33, 'Geni', 'geni@escola.com', '$2y$10$BvmVwbk604JuLRaulaJC6upvhw5uCEw0PcD8yAZbK2F.tsSaztIUq', 'professor', 35, 6, 7255, 4, 1),
(48, 'Wilker Correia', 'wilker@escola.com', '$2y$10$22PBRZtfUVIzDLmuCLlamO27jtvDeHR2wRV4tXG7bRbI4SAeZsCOe', 'professor', 35, 1, 7265, 5, 1),
(37, 'Neemias', 'Neemias@gmail.com', '$2y$10$oFrC33WxHCviSGBeEcPHMOj3tyYfh8kXDLM8lRWKyCJAEyWE13CQ.', 'professor', 20, 1, 7258, 5, 2),
(44, 'Ramiro so com cristo', 'ramiro@gmail.com', '$2y$10$jEClBp0pmKc7CO845/41R.UJ.3yc2j06fpU39DwFlKTFWs5d/VLmC', 'professor', 20, 6, 7262, 3, 2),
(45, 'Palermo teste', 'palermo@gmail.com', '$2y$10$TdtZlItqXJNLuc.UT4FaZ.BHkBu5QVU6MCYOurfnNILh0TCmdoamW', 'professor', 20, 5, 7263, 2, 1),
(46, 'Aleksander', 'castro@gmail.com', '$2y$10$IP2Cl.oh/1IkoF3ZQ5gTbOQGGmgF22hPe9P8YK1iV0YfEIM98jKJe', 'professor', 20, 1, 7250, 5, 1),
(47, 'Atrapic dos Santos Noronha', 'atracl@gmail.com', '$2y$10$Jz9rNMKafUXtaZOYwY7OSO1IWDya5qpcNY7TROnatFJIuHtvUoWw.', 'professor', 20, 2, 7264, 5, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
