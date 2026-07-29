-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 12, 2025 at 06:24 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orionv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `kaizen`
--

DROP TABLE IF EXISTS `kaizen`;
CREATE TABLE IF NOT EXISTS `kaizen` (
  `id_kaizen` int NOT NULL AUTO_INCREMENT,
  `categoria_kaizen` int NOT NULL,
  `tipo_kaizen` int NOT NULL,
  `add_tipo_texto` varchar(200) NOT NULL COMMENT 'Texto adicional quando tipo=Outros',
  `tempo_kaizen` int NOT NULL,
  `custo_kaizen` float NOT NULL,
  `complemento_kaizen` float NOT NULL DEFAULT '0' COMMENT 'Valor do benefício em R$ (calculado ou direto)',
  `valor_original_kaizen` decimal(10,2) DEFAULT '0.00' COMMENT 'Valor original informado pelo colaborador antes do cálculo',
  `tipo_complemento_kaizen` int DEFAULT '0' COMMENT 'ID do complemento usado (0=nenhum, 1=Reais, 2=Horas, 3=Kg carbono)',
  `reducao_kaizen` int NOT NULL,
  `destaque_kaizen` int NOT NULL,
  `onde_kaizen` text NOT NULL,
  `resultado_kaizen` text NOT NULL,
  `situacao_atual` text NOT NULL,
  `imagem1_kaizen` varchar(100) NOT NULL,
  `imagem2_kaizen` varchar(100) NOT NULL,
  `colaborador_kaizen` int NOT NULL,
  `colaborador1_kaizen` int NOT NULL,
  `colaborador2_kaizen` int NOT NULL,
  `colaborador3_kaizen` int NOT NULL,
  `colaborador4_kaizen` int NOT NULL,
  `lider_kaizen` int NOT NULL,
  `datacadastro_kaizen` date NOT NULL,
  `dataconclusao_kaizen` date NOT NULL,
  `status_kaizen` int NOT NULL,
  `admin_kaizen` int NOT NULL,
  `pontos_kaizen` int NOT NULL,
  `unidade_kaizen` int NOT NULL,
  `setor_kaizen` int NOT NULL,
  `outrosdep_kaizen` int NOT NULL COMMENT 'sim / não',
  `qual_kaizen` varchar(100) NOT NULL,
  `incidente_kaizen` int NOT NULL,
  `obs_kaizen` text NOT NULL,
  PRIMARY KEY (`id_kaizen`)
) ENGINE=MyISAM AUTO_INCREMENT=1600 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `kaizen`
--

INSERT INTO `kaizen` (`id_kaizen`, `categoria_kaizen`, `tipo_kaizen`, `add_tipo_texto`, `tempo_kaizen`, `custo_kaizen`, `complemento_kaizen`, `valor_original_kaizen`, `tipo_complemento_kaizen`, `reducao_kaizen`, `destaque_kaizen`, `onde_kaizen`, `resultado_kaizen`, `situacao_atual`, `imagem1_kaizen`, `imagem2_kaizen`, `colaborador_kaizen`, `colaborador1_kaizen`, `colaborador2_kaizen`, `colaborador3_kaizen`, `colaborador4_kaizen`, `lider_kaizen`, `datacadastro_kaizen`, `dataconclusao_kaizen`, `status_kaizen`, `admin_kaizen`, `pontos_kaizen`, `unidade_kaizen`, `setor_kaizen`, `outrosdep_kaizen`, `qual_kaizen`, `incidente_kaizen`, `obs_kaizen`) VALUES
(1599, 0, 9, 'teste outros', 0, 0, 0, 0.00, 2, 0, 0, 'teste 2', 'teste 3', 'teste 1', '', '', 101, 0, 0, 0, 0, 0, '2025-11-12', '0000-00-00', 1, 0, 0, 3, 9, 0, '', 0, ''),
(1598, 0, 2, '', 0, 0, 0, 0.00, 1, 0, 0, 'objetivo melhoria', 'resultado obtido', 'situação atual', '', '', 101, 0, 0, 0, 0, 0, '2025-11-12', '0000-00-00', 1, 0, 0, 3, 9, 1, 'Setor de corte', 0, ''),
(1597, 0, 2, '', 0, 0, 0, 0.00, 0, 0, 0, 'Teste melhoria', 'teste resultados', 'Teste situação', '', '', 101, 0, 0, 0, 0, 0, '2025-11-12', '0000-00-00', 1, 0, 0, 3, 9, 1, 'Todos', 0, ''),
(1596, 0, 7, '', 0, 0, 0, 0.00, 0, 0, 0, 'Teste do objetivo da melhoria.', 'Teste dos resultados obtidos', 'Teste de situação atual', '', '', 101, 0, 0, 0, 0, 0, '2025-11-12', '0000-00-00', 1, 0, 0, 3, 9, 2, '', 0, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
