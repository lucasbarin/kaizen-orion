-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 11, 2025 at 08:46 PM
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
-- Table structure for table `tipo`
--

DROP TABLE IF EXISTS `tipo`;
CREATE TABLE IF NOT EXISTS `tipo` (
  `id_tipo` int NOT NULL AUTO_INCREMENT,
  `nome_tipo` varchar(150) NOT NULL,
  `numero_tipo` int NOT NULL,
  `status_tipo` int NOT NULL,
  `complemento_tipo` int NOT NULL,
  `add_tipo` int NOT NULL COMMENT '0/null = false | 1 - sim, aparecer input adicional ',
  PRIMARY KEY (`id_tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tipo`
--

INSERT INTO `tipo` (`id_tipo`, `nome_tipo`, `numero_tipo`, `status_tipo`, `complemento_tipo`, `add_tipo`) VALUES
(1, 'Segurança', 1, 0, 0, 0),
(2, 'Meio Ambiente ', 2, 0, 1, 0),
(3, 'Qualidade - Defeitos ', 3, 0, 1, 0),
(4, 'Qualidade - Defeitos ', 4, 0, 3, 0),
(5, 'Fluxo de trabalho', 5, 0, 3, 0),
(6, 'Redução de Custo', 6, 0, 1, 0),
(7, 'Produtividade', 7, 0, 2, 0),
(8, 'Outros', 8, 0, 1, 1),
(9, 'Outros', 9, 0, 2, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
