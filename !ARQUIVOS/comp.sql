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
-- Table structure for table `comp`
--

DROP TABLE IF EXISTS `comp`;
CREATE TABLE IF NOT EXISTS `comp` (
  `id_comp` int NOT NULL AUTO_INCREMENT,
  `nome_comp` varchar(100) NOT NULL,
  `formato_comp` int NOT NULL COMMENT '1 - integer horas ou kg | 2 - float',
  `calculo_comp` tinyint(1) NOT NULL COMMENT 'false - não será feito cálculo |  1 true - será feito cálculo 1 | 2 true - será feito o cálculo 2',
  PRIMARY KEY (`id_comp`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comp`
--

INSERT INTO `comp` (`id_comp`, `nome_comp`, `formato_comp`, `calculo_comp`) VALUES
(1, 'Reais economizados (anual)', 2, 0),
(2, 'Ganho R$ (anual)', 1, 1),
(3, 'Horas Poupadas (anual)', 1, 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
