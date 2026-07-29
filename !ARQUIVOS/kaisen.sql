-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.69
-- Generation Time: 14-Out-2019 às 15:41
-- Versão do servidor: 5.6.40-84.0-log
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kaisen`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome_categoria` varchar(150) NOT NULL,
  `numero_categoria` int(11) NOT NULL,
  `status_categoria` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nome_categoria`, `numero_categoria`, `status_categoria`) VALUES
(42, 'Ordem / Limpeza', 1, 0),
(43, 'Processo / Segurança', 2, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `catproduto`
--

CREATE TABLE `catproduto` (
  `id_catproduto` int(11) NOT NULL,
  `nome_catproduto` varchar(150) NOT NULL,
  `numero_catproduto` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `colaborador`
--

CREATE TABLE `colaborador` (
  `id_colaborador` int(11) NOT NULL,
  `nome_colaborador` varchar(150) NOT NULL,
  `email_colaborador` varchar(100) NOT NULL,
  `usuario_colaborador` varchar(20) NOT NULL,
  `senha_colaborador` varchar(20) NOT NULL,
  `unidade_colaborador` int(11) NOT NULL,
  `setor_colaborador` int(11) NOT NULL,
  `status_colaborador` int(11) NOT NULL,
  `lider_colaborador` int(11) NOT NULL,
  `ponto_colaborador` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fotopg`
--

CREATE TABLE `fotopg` (
  `id_foto` int(11) NOT NULL,
  `id_pg` int(11) NOT NULL,
  `tem_legenda` int(11) NOT NULL,
  `imagem_foto` varchar(100) NOT NULL,
  `label_foto` varchar(100) NOT NULL,
  `legenda_foto` varchar(150) NOT NULL,
  `bg_foto` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `kaizen`
--

CREATE TABLE `kaizen` (
  `id_kaizen` int(11) NOT NULL,
  `categoria_kaizen` int(11) NOT NULL,
  `tipo_kaizen` int(11) NOT NULL,
  `tempo_kaizen` int(11) NOT NULL,
  `custo_kaizen` int(11) NOT NULL,
  `reducao_kaizen` int(11) NOT NULL,
  `destaque_kaizen` int(11) NOT NULL,
  `onde_kaizen` text NOT NULL,
  `resultado_kaizen` text NOT NULL,
  `imagem1_kaizen` varchar(100) NOT NULL,
  `imagem2_kaizen` varchar(100) NOT NULL,
  `colaborador_kaizen` int(11) NOT NULL,
  `colaborador1_kaizen` int(11) NOT NULL,
  `colaborador2_kaizen` int(11) NOT NULL,
  `colaborador3_kaizen` int(11) NOT NULL,
  `colaborador4_kaizen` int(11) NOT NULL,
  `lider_kaizen` int(11) NOT NULL,
  `datacadastro_kaizen` date NOT NULL,
  `dataconclusao_kaizen` date NOT NULL,
  `status_kaizen` int(11) NOT NULL,
  `admin_kaizen` int(11) NOT NULL,
  `pontos_kaizen` int(11) NOT NULL,
  `unidade_kaizen` int(11) NOT NULL,
  `setor_kaizen` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `lidersetor`
--

CREATE TABLE `lidersetor` (
  `id_lidersetor` int(11) NOT NULL,
  `nome_lidersetor` varchar(150) NOT NULL,
  `setores_lidersetor` text NOT NULL,
  `status_lidersetor` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `login`
--

CREATE TABLE `login` (
  `id_login` int(11) NOT NULL,
  `nome_login` varchar(15) NOT NULL,
  `senha_login` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `login`
--

INSERT INTO `login` (`id_login`, `nome_login`, `senha_login`) VALUES
(1, 'login', 'senha');

-- --------------------------------------------------------

--
-- Estrutura da tabela `logtroca`
--

CREATE TABLE `logtroca` (
  `id_logtroca` int(11) NOT NULL,
  `modificacao_logtroca` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_colaborador` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `nome_logtroca` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `ponto_logtroca` int(11) NOT NULL,
  `data_logtroca` datetime NOT NULL,
  `status_logtroca` int(11) NOT NULL COMMENT '1 aguardando | 2 aceita  | 3 recusada/devolvida'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `opcao`
--

CREATE TABLE `opcao` (
  `id_opcao` int(11) NOT NULL,
  `nome_opcao` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `opcao`
--

INSERT INTO `opcao` (`id_opcao`, `nome_opcao`) VALUES
(1, 'Sim'),
(2, 'Não');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pg`
--

CREATE TABLE `pg` (
  `id_pg` int(11) NOT NULL,
  `nome1_pg` varchar(150) NOT NULL,
  `texto1_pg` text NOT NULL,
  `nome2_pg` varchar(150) NOT NULL,
  `texto2_pg` text NOT NULL,
  `nome3_pg` varchar(150) NOT NULL,
  `texto3_pg` text NOT NULL,
  `nome4_pg` varchar(150) NOT NULL,
  `texto4_pg` text NOT NULL,
  `nome5_pg` varchar(150) NOT NULL,
  `texto5_pg` text NOT NULL,
  `nome6_pg` varchar(150) NOT NULL,
  `texto6_pg` text NOT NULL,
  `nome7_pg` varchar(150) NOT NULL,
  `texto7_pg` text NOT NULL,
  `nome8_pg` varchar(150) NOT NULL,
  `texto8_pg` text NOT NULL,
  `nome9_pg` varchar(150) NOT NULL,
  `texto9_pg` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pg`
--

INSERT INTO `pg` (`id_pg`, `nome1_pg`, `texto1_pg`, `nome2_pg`, `texto2_pg`, `nome3_pg`, `texto3_pg`, `nome4_pg`, `texto4_pg`, `nome5_pg`, `texto5_pg`, `nome6_pg`, `texto6_pg`, `nome7_pg`, `texto7_pg`, `nome8_pg`, `texto8_pg`, `nome9_pg`, `texto9_pg`) VALUES
(101, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome_produto` varchar(150) NOT NULL,
  `catproduto_produto` int(11) NOT NULL,
  `numero_produto` int(11) NOT NULL,
  `pontos_produto` int(11) NOT NULL,
  `imagem1_produto` varchar(100) NOT NULL,
  `site_produto` varchar(300) NOT NULL,
  `texto1_produto` text NOT NULL,
  `status_produto` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reducao`
--

CREATE TABLE `reducao` (
  `id_reducao` int(11) NOT NULL,
  `nome_reducao` varchar(100) NOT NULL,
  `numero_reducao` int(11) NOT NULL,
  `pontos_reducao` int(11) NOT NULL,
  `tipo_reducao` int(11) NOT NULL COMMENT '1 custo | 2 tempo'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `reducao`
--

INSERT INTO `reducao` (`id_reducao`, `nome_reducao`, `numero_reducao`, `pontos_reducao`, `tipo_reducao`) VALUES
(1, 'até R$ 100,00', 1, 2, 1),
(2, 'até R$ 200,00', 2, 3, 1),
(3, 'até R$ 300,00', 3, 4, 1),
(4, 'até R$ 400,00', 4, 5, 1),
(5, 'R$ 500,00 ou mais', 5, 6, 1),
(6, 'até 25%', 1, 2, 2),
(7, 'até 50%', 2, 3, 2),
(8, '75% ou mais', 3, 4, 2),
(11, 'Ordem / Limpeza', 1, 1, 3),
(12, 'Processo / Segurança', 1, 2, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `setor`
--

CREATE TABLE `setor` (
  `id_setor` int(11) NOT NULL,
  `nome_setor` varchar(150) NOT NULL,
  `status_setor` int(11) NOT NULL,
  `lider_setor` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE `status` (
  `id_status` int(11) NOT NULL,
  `nome_status` varchar(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `status`
--

INSERT INTO `status` (`id_status`, `nome_status`) VALUES
(1, 'Ativo'),
(2, 'Excluído');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tabela`
--

CREATE TABLE `tabela` (
  `id_tabela` int(11) NOT NULL,
  `nome_tabela` varchar(150) NOT NULL,
  `email_tabela` varchar(100) NOT NULL,
  `pdf1_tabela` varchar(100) NOT NULL,
  `imagem1_tabela` varchar(100) NOT NULL,
  `opcao_tabela` int(11) NOT NULL,
  `texto1_tabela` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo`
--

CREATE TABLE `tipo` (
  `id_tipo` int(11) NOT NULL,
  `nome_tipo` varchar(150) NOT NULL,
  `numero_tipo` int(11) NOT NULL,
  `status_tipo` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tipo`
--

INSERT INTO `tipo` (`id_tipo`, `nome_tipo`, `numero_tipo`, `status_tipo`) VALUES
(1, 'Redução de custo', 3, 0),
(2, 'Redução de Tempo', 4, 0),
(3, 'Ordem / Limpeza', 1, 0),
(4, 'Processo / Segurança', 2, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidade`
--

CREATE TABLE `unidade` (
  `id_unidade` int(11) NOT NULL,
  `nome_unidade` varchar(150) NOT NULL,
  `status_unidade` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `catproduto`
--
ALTER TABLE `catproduto`
  ADD PRIMARY KEY (`id_catproduto`);

--
-- Indexes for table `colaborador`
--
ALTER TABLE `colaborador`
  ADD PRIMARY KEY (`id_colaborador`);

--
-- Indexes for table `fotopg`
--
ALTER TABLE `fotopg`
  ADD PRIMARY KEY (`id_foto`);

--
-- Indexes for table `kaizen`
--
ALTER TABLE `kaizen`
  ADD PRIMARY KEY (`id_kaizen`);

--
-- Indexes for table `lidersetor`
--
ALTER TABLE `lidersetor`
  ADD PRIMARY KEY (`id_lidersetor`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indexes for table `logtroca`
--
ALTER TABLE `logtroca`
  ADD PRIMARY KEY (`id_logtroca`);

--
-- Indexes for table `opcao`
--
ALTER TABLE `opcao`
  ADD PRIMARY KEY (`id_opcao`);

--
-- Indexes for table `pg`
--
ALTER TABLE `pg`
  ADD PRIMARY KEY (`id_pg`);

--
-- Indexes for table `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`);

--
-- Indexes for table `reducao`
--
ALTER TABLE `reducao`
  ADD PRIMARY KEY (`id_reducao`);

--
-- Indexes for table `setor`
--
ALTER TABLE `setor`
  ADD PRIMARY KEY (`id_setor`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexes for table `tabela`
--
ALTER TABLE `tabela`
  ADD PRIMARY KEY (`id_tabela`);

--
-- Indexes for table `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indexes for table `unidade`
--
ALTER TABLE `unidade`
  ADD PRIMARY KEY (`id_unidade`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `catproduto`
--
ALTER TABLE `catproduto`
  MODIFY `id_catproduto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `colaborador`
--
ALTER TABLE `colaborador`
  MODIFY `id_colaborador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fotopg`
--
ALTER TABLE `fotopg`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kaizen`
--
ALTER TABLE `kaizen`
  MODIFY `id_kaizen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4000;

--
-- AUTO_INCREMENT for table `lidersetor`
--
ALTER TABLE `lidersetor`
  MODIFY `id_lidersetor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logtroca`
--
ALTER TABLE `logtroca`
  MODIFY `id_logtroca` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opcao`
--
ALTER TABLE `opcao`
  MODIFY `id_opcao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pg`
--
ALTER TABLE `pg`
  MODIFY `id_pg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reducao`
--
ALTER TABLE `reducao`
  MODIFY `id_reducao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `setor`
--
ALTER TABLE `setor`
  MODIFY `id_setor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tabela`
--
ALTER TABLE `tabela`
  MODIFY `id_tabela` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tipo`
--
ALTER TABLE `tipo`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `unidade`
--
ALTER TABLE `unidade`
  MODIFY `id_unidade` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
