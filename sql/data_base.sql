-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 05-Fev-2025 às 16:00
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
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

--
-- Extraindo dados da tabela `classe_curso`
--

INSERT INTO `classe_curso` (`classe_id`, `curso_id`) VALUES
(1, 12),
(2, 12),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(4, 10),
(4, 11),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(6, 6),
(6, 7),
(6, 8),
(6, 9),
(6, 10),
(6, 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `classe_curso_disciplina`
--

DROP TABLE IF EXISTS `classe_curso_disciplina`;
CREATE TABLE IF NOT EXISTS `classe_curso_disciplina` (
  `id` int NOT NULL AUTO_INCREMENT,
  `classe_id` int NOT NULL,
  `curso_id` int NOT NULL,
  `disciplina_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `classe_id` (`classe_id`,`curso_id`,`disciplina_id`),
  KEY `curso_id` (`curso_id`),
  KEY `disciplina_id` (`disciplina_id`)
) ENGINE=MyISAM AUTO_INCREMENT=771 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `classe_curso_disciplina`
--

INSERT INTO `classe_curso_disciplina` (`id`, `classe_id`, `curso_id`, `disciplina_id`) VALUES
(1, 1, 12, 76),
(2, 1, 12, 75),
(3, 1, 12, 64),
(4, 1, 12, 63),
(5, 1, 12, 113),
(6, 1, 12, 119),
(7, 1, 12, 118),
(8, 1, 12, 121),
(9, 1, 12, 122),
(10, 1, 12, 123),
(11, 1, 12, 66),
(12, 1, 12, 69),
(13, 1, 12, 116),
(14, 2, 12, 76),
(15, 2, 12, 75),
(16, 2, 12, 64),
(17, 2, 12, 63),
(18, 2, 12, 113),
(19, 2, 12, 119),
(20, 2, 12, 118),
(21, 2, 12, 121),
(22, 2, 12, 122),
(23, 2, 12, 123),
(24, 2, 12, 66),
(25, 2, 12, 69),
(26, 2, 12, 116),
(27, 3, 12, 76),
(28, 3, 12, 75),
(29, 3, 12, 64),
(30, 3, 12, 63),
(31, 3, 12, 113),
(32, 3, 12, 119),
(33, 3, 12, 118),
(34, 3, 12, 121),
(35, 3, 12, 122),
(36, 3, 12, 57),
(37, 3, 12, 66),
(38, 3, 12, 69),
(39, 3, 12, 116),
(40, 3, 11, 75),
(41, 3, 11, 119),
(42, 1, 11, 118),
(43, 3, 11, 74),
(44, 3, 11, 57),
(45, 3, 11, 60),
(46, 3, 11, 52),
(47, 3, 11, 51),
(48, 3, 11, 47),
(49, 3, 11, 109),
(50, 3, 11, 65),
(51, 3, 11, 79),
(52, 3, 11, 89),
(53, 3, 11, 61),
(54, 3, 10, 75),
(55, 3, 10, 119),
(56, 3, 10, 118),
(57, 3, 10, 74),
(58, 3, 10, 57),
(59, 3, 10, 60),
(60, 3, 10, 78),
(61, 3, 10, 51),
(62, 3, 10, 94),
(63, 3, 10, 109),
(64, 3, 10, 59),
(65, 3, 10, 46),
(66, 3, 10, 61),
(67, 3, 10, 90),
(68, 3, 10, 91),
(69, 3, 9, 75),
(70, 3, 9, 119),
(71, 3, 9, 118),
(72, 3, 9, 74),
(73, 3, 9, 57),
(74, 3, 9, 60),
(75, 3, 9, 77),
(76, 3, 9, 63),
(77, 3, 9, 64),
(78, 3, 9, 109),
(79, 3, 9, 108),
(80, 3, 9, 49),
(81, 3, 9, 61),
(82, 3, 9, 80),
(83, 3, 9, 105),
(84, 3, 8, 75),
(85, 3, 8, 119),
(86, 3, 8, 118),
(87, 3, 8, 74),
(88, 3, 8, 57),
(89, 3, 8, 60),
(90, 3, 8, 77),
(91, 3, 8, 63),
(92, 3, 8, 64),
(93, 3, 8, 109),
(94, 3, 8, 55),
(95, 3, 8, 106),
(96, 3, 8, 61),
(97, 3, 8, 85),
(98, 3, 8, 99),
(99, 3, 8, 100),
(100, 3, 8, 103),
(101, 3, 8, 97),
(102, 3, 8, 73),
(103, 3, 7, 75),
(104, 3, 7, 119),
(105, 3, 7, 118),
(106, 3, 7, 74),
(107, 3, 7, 57),
(108, 3, 7, 60),
(109, 3, 7, 70),
(110, 3, 7, 68),
(111, 3, 7, 81),
(112, 3, 7, 83),
(113, 3, 7, 84),
(114, 3, 7, 109),
(115, 3, 6, 75),
(116, 3, 6, 119),
(117, 3, 6, 118),
(118, 3, 6, 74),
(119, 3, 6, 77),
(120, 3, 6, 57),
(121, 3, 6, 60),
(122, 3, 6, 86),
(123, 3, 6, 95),
(124, 3, 6, 53),
(125, 3, 6, 58),
(126, 3, 6, 61),
(127, 3, 6, 87),
(128, 3, 6, 88),
(129, 3, 6, 102),
(130, 3, 6, 109),
(131, 3, 5, 75),
(132, 3, 5, 119),
(133, 3, 5, 118),
(134, 3, 5, 74),
(135, 3, 5, 57),
(136, 3, 5, 62),
(137, 3, 5, 60),
(138, 3, 5, 92),
(139, 3, 5, 71),
(140, 3, 5, 66),
(141, 3, 5, 98),
(142, 3, 5, 61),
(143, 3, 5, 96),
(144, 3, 5, 101),
(145, 3, 5, 109),
(146, 3, 4, 75),
(147, 3, 4, 119),
(148, 3, 4, 118),
(149, 3, 4, 74),
(150, 3, 4, 62),
(151, 3, 4, 57),
(152, 3, 4, 60),
(153, 3, 4, 82),
(154, 3, 4, 68),
(155, 3, 4, 70),
(156, 3, 4, 78),
(157, 3, 4, 109),
(158, 3, 3, 75),
(159, 3, 3, 119),
(160, 3, 3, 118),
(161, 3, 3, 74),
(162, 3, 3, 62),
(163, 3, 3, 57),
(164, 3, 3, 77),
(165, 3, 3, 60),
(166, 3, 3, 69),
(167, 3, 3, 109),
(168, 3, 3, 56),
(169, 3, 3, 92),
(170, 3, 3, 93),
(171, 3, 2, 75),
(172, 3, 2, 119),
(173, 3, 2, 118),
(174, 3, 2, 74),
(175, 3, 2, 62),
(176, 3, 2, 57),
(177, 3, 2, 77),
(178, 3, 2, 60),
(179, 3, 2, 58),
(180, 3, 2, 56),
(181, 3, 2, 93),
(182, 3, 2, 66),
(183, 3, 2, 109),
(184, 3, 1, 75),
(185, 3, 1, 119),
(186, 3, 1, 118),
(187, 3, 1, 74),
(188, 3, 1, 62),
(189, 3, 1, 57),
(190, 3, 1, 77),
(191, 3, 1, 60),
(192, 3, 1, 64),
(193, 3, 1, 109),
(194, 3, 1, 50),
(195, 3, 1, 68),
(196, 3, 1, 67),
(287, 4, 11, 61),
(286, 4, 11, 89),
(285, 4, 11, 79),
(284, 4, 11, 65),
(283, 4, 11, 109),
(282, 4, 11, 47),
(281, 4, 11, 51),
(280, 4, 11, 52),
(279, 4, 11, 60),
(278, 4, 11, 57),
(277, 4, 11, 74),
(276, 4, 11, 118),
(275, 4, 11, 119),
(274, 4, 11, 75),
(273, 4, 12, 116),
(272, 4, 12, 69),
(271, 4, 12, 66),
(270, 4, 12, 57),
(269, 4, 12, 122),
(268, 4, 12, 121),
(267, 4, 12, 118),
(266, 4, 12, 119),
(265, 4, 12, 113),
(264, 4, 12, 63),
(263, 4, 12, 64),
(262, 4, 12, 75),
(261, 4, 12, 76),
(288, 4, 10, 75),
(289, 4, 10, 119),
(290, 4, 10, 118),
(291, 4, 10, 74),
(292, 4, 10, 57),
(293, 4, 10, 60),
(294, 4, 10, 78),
(295, 4, 10, 51),
(296, 4, 10, 94),
(297, 4, 10, 109),
(298, 4, 10, 59),
(299, 4, 10, 46),
(300, 4, 10, 61),
(301, 4, 10, 90),
(302, 4, 10, 91),
(303, 4, 9, 75),
(304, 4, 9, 119),
(305, 4, 9, 118),
(306, 4, 9, 74),
(307, 4, 9, 57),
(308, 4, 9, 60),
(309, 4, 9, 77),
(310, 4, 9, 63),
(311, 4, 9, 64),
(312, 4, 9, 109),
(313, 4, 9, 108),
(314, 4, 9, 49),
(315, 4, 9, 61),
(316, 4, 9, 80),
(317, 4, 9, 105),
(318, 4, 8, 75),
(319, 4, 8, 119),
(320, 4, 8, 118),
(321, 4, 8, 74),
(322, 4, 8, 57),
(323, 4, 8, 60),
(324, 4, 8, 77),
(325, 4, 8, 63),
(326, 4, 8, 64),
(327, 4, 8, 109),
(328, 4, 8, 55),
(329, 4, 8, 106),
(330, 4, 8, 61),
(331, 4, 8, 85),
(332, 4, 8, 99),
(333, 4, 8, 100),
(334, 4, 8, 103),
(335, 4, 8, 97),
(336, 4, 8, 73),
(337, 4, 7, 75),
(338, 4, 7, 119),
(339, 4, 7, 118),
(340, 4, 7, 74),
(341, 4, 7, 57),
(342, 4, 7, 60),
(343, 4, 7, 70),
(344, 4, 7, 68),
(345, 4, 7, 81),
(346, 4, 7, 83),
(347, 4, 7, 84),
(348, 4, 7, 109),
(349, 4, 6, 75),
(350, 4, 6, 119),
(351, 4, 6, 118),
(352, 4, 6, 74),
(353, 4, 6, 77),
(354, 4, 6, 57),
(355, 4, 6, 60),
(356, 4, 6, 86),
(357, 4, 6, 95),
(358, 4, 6, 53),
(359, 4, 6, 58),
(360, 4, 6, 61),
(361, 4, 6, 87),
(362, 4, 6, 88),
(363, 4, 6, 102),
(364, 4, 6, 109),
(365, 4, 5, 75),
(366, 4, 5, 119),
(367, 4, 5, 118),
(368, 4, 5, 74),
(369, 4, 5, 57),
(370, 4, 5, 62),
(371, 4, 5, 60),
(372, 4, 5, 92),
(373, 4, 5, 71),
(374, 4, 5, 66),
(375, 4, 5, 98),
(376, 4, 5, 61),
(377, 4, 5, 96),
(378, 4, 5, 101),
(379, 4, 5, 109),
(380, 4, 4, 75),
(381, 4, 4, 119),
(382, 4, 4, 118),
(383, 4, 4, 74),
(384, 4, 4, 62),
(385, 4, 4, 57),
(386, 4, 4, 60),
(387, 4, 4, 82),
(388, 4, 4, 68),
(389, 4, 4, 70),
(390, 4, 4, 78),
(391, 4, 4, 109),
(392, 4, 3, 75),
(393, 4, 3, 119),
(394, 4, 3, 118),
(395, 4, 3, 74),
(396, 4, 3, 62),
(397, 4, 3, 57),
(398, 4, 3, 77),
(399, 4, 3, 60),
(400, 4, 3, 69),
(401, 4, 3, 109),
(402, 4, 3, 56),
(403, 4, 3, 92),
(404, 4, 3, 93),
(405, 4, 2, 75),
(406, 4, 2, 119),
(407, 4, 2, 118),
(408, 4, 2, 74),
(409, 4, 2, 62),
(410, 4, 2, 57),
(411, 4, 2, 77),
(412, 4, 2, 60),
(413, 4, 2, 58),
(414, 4, 2, 56),
(415, 4, 2, 93),
(416, 4, 2, 66),
(417, 4, 2, 109),
(418, 4, 1, 75),
(419, 4, 1, 119),
(420, 4, 1, 118),
(421, 4, 1, 74),
(422, 4, 1, 62),
(423, 4, 1, 57),
(424, 4, 1, 77),
(425, 4, 1, 60),
(426, 4, 1, 64),
(427, 4, 1, 109),
(428, 4, 1, 50),
(429, 4, 1, 68),
(430, 4, 1, 67),
(431, 5, 12, 76),
(432, 5, 12, 75),
(433, 5, 12, 64),
(434, 5, 12, 63),
(435, 5, 12, 113),
(436, 5, 12, 119),
(437, 5, 12, 118),
(438, 5, 12, 121),
(439, 5, 12, 122),
(440, 5, 12, 57),
(441, 5, 12, 66),
(442, 5, 12, 69),
(443, 5, 12, 116),
(444, 5, 11, 75),
(445, 5, 11, 119),
(446, 5, 11, 118),
(447, 5, 11, 74),
(448, 5, 11, 57),
(449, 5, 11, 60),
(450, 5, 11, 52),
(451, 5, 11, 51),
(452, 5, 11, 47),
(453, 5, 11, 109),
(454, 5, 11, 65),
(455, 5, 11, 79),
(456, 5, 11, 89),
(457, 5, 11, 61),
(458, 5, 10, 75),
(459, 5, 10, 119),
(460, 5, 10, 118),
(461, 5, 10, 74),
(462, 5, 10, 57),
(463, 5, 10, 60),
(464, 5, 10, 78),
(465, 5, 10, 51),
(466, 5, 10, 94),
(467, 5, 10, 109),
(468, 5, 10, 59),
(469, 5, 10, 46),
(470, 5, 10, 61),
(471, 5, 10, 90),
(472, 5, 10, 91),
(473, 5, 9, 75),
(474, 5, 9, 119),
(475, 5, 9, 118),
(476, 5, 9, 74),
(477, 5, 9, 57),
(478, 5, 9, 60),
(479, 5, 9, 77),
(480, 5, 9, 63),
(481, 5, 9, 64),
(482, 5, 9, 109),
(483, 5, 9, 108),
(484, 5, 9, 49),
(485, 5, 9, 61),
(486, 5, 9, 80),
(487, 5, 9, 105),
(488, 5, 8, 75),
(489, 5, 8, 119),
(490, 5, 8, 118),
(491, 5, 8, 74),
(492, 5, 8, 57),
(493, 5, 8, 60),
(494, 5, 8, 77),
(495, 5, 8, 63),
(496, 5, 8, 64),
(497, 5, 8, 109),
(498, 5, 8, 55),
(499, 5, 8, 106),
(500, 5, 8, 61),
(501, 5, 8, 85),
(502, 5, 8, 99),
(503, 5, 8, 100),
(504, 5, 8, 103),
(505, 5, 8, 97),
(506, 5, 8, 73),
(507, 5, 7, 75),
(508, 5, 7, 119),
(509, 5, 7, 118),
(510, 5, 7, 74),
(511, 5, 7, 57),
(512, 5, 7, 60),
(513, 5, 7, 70),
(514, 5, 7, 68),
(515, 5, 7, 81),
(516, 5, 7, 83),
(517, 5, 7, 84),
(518, 5, 7, 109),
(519, 5, 6, 75),
(520, 5, 6, 119),
(521, 5, 6, 118),
(522, 5, 6, 74),
(523, 5, 6, 77),
(524, 5, 6, 57),
(525, 5, 6, 60),
(526, 5, 6, 86),
(527, 5, 6, 95),
(528, 5, 6, 53),
(529, 5, 6, 58),
(530, 5, 6, 61),
(531, 5, 6, 87),
(532, 5, 6, 88),
(533, 5, 6, 102),
(534, 5, 6, 109),
(535, 5, 5, 75),
(536, 5, 5, 119),
(537, 5, 5, 118),
(538, 5, 5, 74),
(539, 5, 5, 57),
(540, 5, 5, 62),
(541, 5, 5, 60),
(542, 5, 5, 92),
(543, 5, 5, 71),
(544, 5, 5, 66),
(545, 5, 5, 98),
(546, 5, 5, 61),
(547, 5, 5, 96),
(548, 5, 5, 101),
(549, 5, 5, 109),
(550, 5, 4, 75),
(551, 5, 4, 119),
(552, 5, 4, 118),
(553, 5, 4, 74),
(554, 5, 4, 62),
(555, 5, 4, 57),
(556, 5, 4, 60),
(557, 5, 4, 82),
(558, 5, 4, 68),
(559, 5, 4, 70),
(560, 5, 4, 78),
(561, 5, 4, 109),
(562, 5, 3, 75),
(563, 5, 3, 119),
(564, 5, 3, 118),
(565, 5, 3, 74),
(566, 5, 3, 62),
(567, 5, 3, 57),
(568, 5, 3, 77),
(569, 5, 3, 60),
(570, 5, 3, 69),
(571, 5, 3, 109),
(572, 5, 3, 56),
(573, 5, 3, 92),
(574, 5, 3, 93),
(575, 5, 2, 75),
(576, 5, 2, 119),
(577, 5, 2, 118),
(578, 5, 2, 74),
(579, 5, 2, 62),
(580, 5, 2, 57),
(581, 5, 2, 77),
(582, 5, 2, 60),
(583, 5, 2, 58),
(584, 5, 2, 56),
(585, 5, 2, 93),
(586, 5, 2, 66),
(587, 5, 2, 109),
(588, 5, 1, 75),
(589, 5, 1, 119),
(590, 5, 1, 118),
(591, 5, 1, 74),
(592, 5, 1, 62),
(593, 5, 1, 57),
(594, 5, 1, 77),
(595, 5, 1, 60),
(596, 5, 1, 64),
(597, 5, 1, 109),
(598, 5, 1, 50),
(599, 5, 1, 68),
(600, 5, 1, 67),
(601, 6, 12, 76),
(602, 6, 12, 75),
(603, 6, 12, 64),
(604, 6, 12, 63),
(605, 6, 12, 113),
(606, 6, 12, 119),
(607, 6, 12, 118),
(608, 6, 12, 121),
(609, 6, 12, 122),
(610, 6, 12, 57),
(611, 6, 12, 66),
(612, 6, 12, 69),
(613, 6, 12, 116),
(614, 6, 11, 75),
(615, 6, 11, 119),
(616, 6, 11, 118),
(617, 6, 11, 74),
(618, 6, 11, 57),
(619, 6, 11, 60),
(620, 6, 11, 52),
(621, 6, 11, 51),
(622, 6, 11, 47),
(623, 6, 11, 109),
(624, 6, 11, 65),
(625, 6, 11, 79),
(626, 6, 11, 89),
(627, 6, 11, 61),
(628, 6, 10, 75),
(629, 6, 10, 119),
(630, 6, 10, 118),
(631, 6, 10, 74),
(632, 6, 10, 57),
(633, 6, 10, 60),
(634, 6, 10, 78),
(635, 6, 10, 51),
(636, 6, 10, 94),
(637, 6, 10, 109),
(638, 6, 10, 59),
(639, 6, 10, 46),
(640, 6, 10, 61),
(641, 6, 10, 90),
(642, 6, 10, 91),
(643, 6, 9, 75),
(644, 6, 9, 119),
(645, 6, 9, 118),
(646, 6, 9, 74),
(647, 6, 9, 57),
(648, 6, 9, 60),
(649, 6, 9, 77),
(650, 6, 9, 63),
(651, 6, 9, 64),
(652, 6, 9, 109),
(653, 6, 9, 108),
(654, 6, 9, 49),
(655, 6, 9, 61),
(656, 6, 9, 80),
(657, 6, 9, 105),
(658, 6, 8, 75),
(659, 6, 8, 119),
(660, 6, 8, 118),
(661, 6, 8, 74),
(662, 6, 8, 57),
(663, 6, 8, 60),
(664, 6, 8, 77),
(665, 6, 8, 63),
(666, 6, 8, 64),
(667, 6, 8, 109),
(668, 6, 8, 55),
(669, 6, 8, 106),
(670, 6, 8, 61),
(671, 6, 8, 85),
(672, 6, 8, 99),
(673, 6, 8, 100),
(674, 6, 8, 103),
(675, 6, 8, 97),
(676, 6, 8, 73),
(677, 6, 7, 75),
(678, 6, 7, 119),
(679, 6, 7, 118),
(680, 6, 7, 74),
(681, 6, 7, 57),
(682, 6, 7, 60),
(683, 6, 7, 70),
(684, 6, 7, 68),
(685, 6, 7, 81),
(686, 6, 7, 83),
(687, 6, 7, 84),
(688, 6, 7, 109),
(689, 6, 6, 75),
(690, 6, 6, 119),
(691, 6, 6, 118),
(692, 6, 6, 74),
(693, 6, 6, 77),
(694, 6, 6, 57),
(695, 6, 6, 60),
(696, 6, 6, 86),
(697, 6, 6, 95),
(698, 6, 6, 53),
(699, 6, 6, 58),
(700, 6, 6, 61),
(701, 6, 6, 87),
(702, 6, 6, 88),
(703, 6, 6, 102),
(704, 6, 6, 109),
(705, 6, 5, 75),
(706, 6, 5, 119),
(707, 6, 5, 118),
(708, 6, 5, 74),
(709, 6, 5, 57),
(710, 6, 5, 62),
(711, 6, 5, 60),
(712, 6, 5, 92),
(713, 6, 5, 71),
(714, 6, 5, 66),
(715, 6, 5, 98),
(716, 6, 5, 61),
(717, 6, 5, 96),
(718, 6, 5, 101),
(719, 6, 5, 109),
(720, 6, 4, 75),
(721, 6, 4, 119),
(722, 6, 4, 118),
(723, 6, 4, 74),
(724, 6, 4, 62),
(725, 6, 4, 57),
(726, 6, 4, 60),
(727, 6, 4, 82),
(728, 6, 4, 68),
(729, 6, 4, 70),
(730, 6, 4, 78),
(731, 6, 4, 109),
(732, 6, 3, 75),
(733, 6, 3, 119),
(734, 6, 3, 118),
(735, 6, 3, 74),
(736, 6, 3, 62),
(737, 6, 3, 57),
(738, 6, 3, 77),
(739, 6, 3, 60),
(740, 6, 3, 69),
(741, 6, 3, 109),
(742, 6, 3, 56),
(743, 6, 3, 92),
(744, 6, 3, 93),
(745, 6, 2, 75),
(746, 6, 2, 119),
(747, 6, 2, 118),
(748, 6, 2, 74),
(749, 6, 2, 62),
(750, 6, 2, 57),
(751, 6, 2, 77),
(752, 6, 2, 60),
(753, 6, 2, 58),
(754, 6, 2, 56),
(755, 6, 2, 93),
(756, 6, 2, 66),
(757, 6, 2, 109),
(758, 6, 1, 75),
(759, 6, 1, 119),
(760, 6, 1, 118),
(761, 6, 1, 74),
(762, 6, 1, 62),
(763, 6, 1, 57),
(764, 6, 1, 77),
(765, 6, 1, 60),
(766, 6, 1, 64),
(767, 6, 1, 109),
(768, 6, 1, 50),
(769, 6, 1, 68),
(770, 6, 1, 67);

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`id`, `nome_area`, `sigla`) VALUES
(1, 'Ciências e Tecnologias', 'A'),
(2, 'Ciências Socioeconómicas', 'B'),
(3, 'Ciências Línguas e Humanidades', 'C'),
(4, 'Artes Visuais', 'D'),
(5, 'Humanísticas/ Turismo', 'Geral'),
(6, 'Gestão e Administração', 'P'),
(7, 'Artes e Design', 'P'),
(8, 'Tecnologias Industriais', 'P'),
(9, 'Informática', 'P'),
(10, 'Produção Agrícola e Animal', 'P'),
(11, 'Desporto', 'P'),
(12, 'Geral', 'P');

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

--
-- Extraindo dados da tabela `curso_disciplina`
--

INSERT INTO `curso_disciplina` (`curso_id`, `disciplina_id`) VALUES
(1, 50),
(1, 57),
(1, 60),
(1, 62),
(1, 64),
(1, 67),
(1, 68),
(1, 74),
(1, 75),
(1, 77),
(1, 109),
(1, 118),
(1, 119),
(2, 56),
(2, 57),
(2, 58),
(2, 60),
(2, 62),
(2, 66),
(2, 74),
(2, 75),
(2, 77),
(2, 93),
(2, 109),
(2, 118),
(2, 119),
(3, 56),
(3, 57),
(3, 60),
(3, 62),
(3, 69),
(3, 74),
(3, 75),
(3, 77),
(3, 92),
(3, 93),
(3, 109),
(3, 118),
(3, 119),
(4, 57),
(4, 60),
(4, 62),
(4, 68),
(4, 70),
(4, 74),
(4, 75),
(4, 78),
(4, 82),
(4, 109),
(4, 118),
(4, 119),
(5, 57),
(5, 60),
(5, 61),
(5, 62),
(5, 66),
(5, 71),
(5, 74),
(5, 75),
(5, 92),
(5, 96),
(5, 98),
(5, 101),
(5, 109),
(5, 118),
(5, 119),
(6, 53),
(6, 57),
(6, 58),
(6, 60),
(6, 61),
(6, 74),
(6, 75),
(6, 77),
(6, 86),
(6, 87),
(6, 88),
(6, 95),
(6, 102),
(6, 109),
(6, 118),
(6, 119),
(7, 54),
(7, 57),
(7, 60),
(7, 61),
(7, 68),
(7, 70),
(7, 74),
(7, 75),
(7, 81),
(7, 83),
(7, 84),
(7, 104),
(7, 107),
(7, 109),
(7, 118),
(7, 119),
(8, 55),
(8, 57),
(8, 60),
(8, 61),
(8, 63),
(8, 64),
(8, 73),
(8, 74),
(8, 75),
(8, 77),
(8, 85),
(8, 97),
(8, 99),
(8, 100),
(8, 103),
(8, 106),
(8, 109),
(8, 118),
(8, 119),
(9, 48),
(9, 49),
(9, 57),
(9, 60),
(9, 61),
(9, 63),
(9, 64),
(9, 74),
(9, 75),
(9, 77),
(9, 80),
(9, 105),
(9, 108),
(9, 109),
(9, 118),
(9, 119),
(10, 46),
(10, 51),
(10, 57),
(10, 59),
(10, 60),
(10, 61),
(10, 74),
(10, 75),
(10, 78),
(10, 90),
(10, 91),
(10, 94),
(10, 109),
(10, 118),
(10, 119),
(11, 47),
(11, 51),
(11, 52),
(11, 57),
(11, 60),
(11, 61),
(11, 65),
(11, 74),
(11, 75),
(11, 79),
(11, 89),
(11, 109),
(11, 118),
(11, 119),
(12, 57),
(12, 63),
(12, 64),
(12, 66),
(12, 69),
(12, 75),
(12, 76),
(12, 113),
(12, 116),
(12, 118),
(12, 119),
(12, 121),
(12, 122),
(12, 123);

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
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `disciplina`
--

INSERT INTO `disciplina` (`id`, `nome_disciplina`) VALUES
(76, 'Matemática'),
(77, 'Matemática A'),
(78, 'Matemática B'),
(79, 'Metodologia dos Desportos e da Animação Desportiva'),
(80, 'Manutenção e Montagem de Equipamentos Informáticos'),
(58, 'Economia'),
(46, 'Agricultura Geral e Zootecnia'),
(47, 'Animação Desportiva'),
(48, 'Aplicações Informáticas'),
(49, 'Bases de Programação'),
(50, 'Biologia'),
(52, 'Biologia Humana'),
(53, 'Contabilidade'),
(55, 'Desenho Técnico e Geometria Descritiva'),
(56, 'Direito'),
(59, 'Economia e Associativismo'),
(60, 'Empreendedorismo'),
(61, 'Especificação'),
(62, 'Filosofia'),
(64, 'Química'),
(67, 'Geologia'),
(69, 'História'),
(71, 'História e Património'),
(75, 'Língua Portuguesa'),
(81, 'Oficina de Arte e Design'),
(74, 'Integração Social'),
(73, 'Instalações Elétricas'),
(122, 'Expressão Plástica'),
(70, 'História das Artes'),
(68, 'Geometria Descritiva'),
(66, 'Geografia'),
(65, 'Fundamentos do Treino Desportivo'),
(63, 'Física'),
(57, 'Educação Física'),
(54, 'Desenho'),
(51, 'Biologia Animal e Vegetal'),
(82, 'Oficina de Artes'),
(83, 'Oficina de Design de Equipamento'),
(84, 'Oficina de Design Gráfico'),
(85, 'Oficinas'),
(86, 'Organização e Gestão Empresarial'),
(87, 'Práticas de Contabilidade e Gestão'),
(88, 'Práticas de Secretariado'),
(89, 'Práticas Desportivas e Recreativas'),
(90, 'Produção Animal'),
(91, 'Produção Vegetal'),
(92, 'Psicologia'),
(93, 'Sociologia'),
(94, 'Solos e Clima'),
(95, 'Técnicas Administrativas e Comerciais'),
(96, 'Técnicas de Agência de Viagens e Transportes'),
(97, 'Técnicas de Carpintaria'),
(98, 'Técnicas de Comunicação e Relações Interpessoais'),
(99, 'Técnicas de Condução de Obra'),
(100, 'Técnicas de Desenho de Construção Civil'),
(101, 'Técnicas de Informação e de Animação Turística'),
(102, 'Técnicas de Marketing'),
(103, 'Técnicas de Medições e Orçamentos'),
(104, 'Técnicas de Pintura e Escultura'),
(105, 'Técnicas de Software e Gestão de Bases de Dados'),
(106, 'Tecnologias'),
(107, 'Tecnologias de Arte e Design'),
(108, 'Tecnologias Informáticas'),
(109, 'TIC'),
(110, 'Alemão'),
(111, 'Astronomia'),
(112, 'CSPQ'),
(113, 'Ciências Naturais'),
(114, 'Educação Ambiental'),
(115, 'Educação para Saúde'),
(116, 'Educação Visual e Oficinal'),
(117, 'Formação Cívica'),
(118, 'Inglês'),
(119, 'Francês'),
(120, 'TAA'),
(121, 'Expressão Musical'),
(123, 'Expressão Motora');

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
) ENGINE=MyISAM AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `id_escola`, `classe_id`, `turma_id`, `curso_id`, `periodo_dia_id`) VALUES
(1, 'João da Silva', 'joao.silva@escola.com', '$2y$10$ET03.qqaPP29H1bOCQ8Z2unjuRZ99G8ZXyYKeXmED7f7QBgq6AQCi', 'administrador', 2, NULL, NULL, NULL, NULL),
(21, 'Elisete', 'elisete@escola.com', '$2y$10$B5bzFdItyuWFn3R3S0RztuF.tB7bCjeXFMuD9znqDq5dNVmOkAGcW', 'direcao', 35, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
