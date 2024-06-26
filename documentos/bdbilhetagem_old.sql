-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Jun-2024 às 04:58
-- Versão do servidor: 10.4.14-MariaDB
-- versão do PHP: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bdbilhetagem`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbevento_ativo`
--

CREATE TABLE `tbevento_ativo` (
  `id` int(11) NOT NULL,
  `hash` varchar(250) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1,
  `datahora_cria` varchar(250) NOT NULL,
  `idevento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbevento_ativo`
--

INSERT INTO `tbevento_ativo` (`id`, `hash`, `ativo`, `datahora_cria`, `idevento`) VALUES
(1, '321', 1, '1717542595', 1),
(2, '123', 1, '1717542595', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbentrada`
--

CREATE TABLE `tbentrada` (
  `id_entrada` int(11) NOT NULL,
  `id_prevenda` int(11) NOT NULL,
  `id_vinculado` int(11) NOT NULL,
  `perfil_acesso` int(11) NOT NULL,
  `previnculo_status` int(11) NOT NULL DEFAULT 1,
  `motivo_cancela` int(11) NOT NULL,
  `id_pacote` int(11) NOT NULL,
  `datahora_entra` varchar(250) NOT NULL,
  `datahora_saida` varchar(250) NOT NULL,
  `tempo_excede` int(11) NOT NULL,
  `pgto_extra` int(11) NOT NULL,
  `pgto_extra_valor` decimal(10,0) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1,
  `pct_valor` decimal(6,2) NOT NULL,
  `pct_valor_extra` decimal(6,2) NOT NULL,
  `pct_qtde_compra` int(11) NOT NULL,
  `pct_minuto_compra` int(11) NOT NULL,
  `pct_tolerancia` int(11) NOT NULL,
  `autoriza` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbentrada`
--

INSERT INTO `tbentrada` (`id_entrada`, `id_prevenda`, `id_vinculado`, `perfil_acesso`, `previnculo_status`, `motivo_cancela`, `id_pacote`, `datahora_entra`, `datahora_saida`, `tempo_excede`, `pgto_extra`, `pgto_extra_valor`, `ativo`, `pct_valor`, `pct_valor_extra`, `pct_qtde_compra`, `pct_minuto_compra`, `pct_tolerancia`, `autoriza`) VALUES
(1, 1, 1, 1, 3, 0, 1, '1715561552', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(2, 1, 2, 1, 3, 0, 1, '1715561552', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(3, 1, 3, 1, 3, 0, 1, '1715561552', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(4, 1, 4, 1, 3, 0, 1, '1715561552', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(5, 1, 5, 1, 2, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(6, 1, 6, 0, 2, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(7, 3, 7, 0, 3, 0, 1, '1715569594', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(8, 3, 8, 0, 2, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(9, 3, 9, 0, 2, 0, 1, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(10, 3, 10, 0, 3, 0, 1, '1715569594', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(11, 4, 11, 0, 2, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(12, 4, 12, 0, 4, 0, 1, '1715561313', '1716342096', 13007, 1, '4', 1, '0.00', '0.00', 0, 0, 0, 0),
(13, 3, 16, 0, 3, 0, 2, '1715569594', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(14, 3, 17, 0, 3, 0, 3, '1715569594', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(15, 3, 18, 0, 3, 0, 2, '1715569594', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(16, 5, 19, 0, 4, 0, 1, '1715558570', '1716338517', 12993, 1, '5', 1, '0.00', '0.00', 0, 0, 0, 0),
(17, 5, 20, 0, 4, 0, 2, '1715558570', '1716328919', 12827, 1, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(18, 2, 21, 0, 3, 0, 2, '1715729868', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(19, 6, 22, 0, 3, 0, 2, '1715575537', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(20, 6, 23, 0, 3, 0, 3, '1715575537', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(21, 7, 24, 0, 3, 0, 2, '1715745499', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(22, 7, 25, 0, 3, 0, 1, '1715745499', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(23, 8, 26, 0, 3, 0, 1, '1715807982', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(24, 8, 27, 0, 3, 0, 1, '1715807982', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(25, 8, 28, 0, 3, 0, 1, '1715807982', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(26, 8, 29, 0, 3, 0, 1, '1715807982', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(27, 9, 30, 0, 3, 0, 2, '1716260400', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(28, 14, 31, 0, 4, 0, 1, '1716354350', '1716417897', 1053, 1, '14', 1, '0.00', '0.00', 0, 0, 0, 0),
(29, 14, 32, 0, 4, 0, 1, '1716354350', '1716417897', 1053, 1, '14', 1, '0.00', '0.00', 0, 0, 0, 0),
(30, 17, 33, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(31, 19, 34, 0, 4, 0, 2, '1716417870', '1716418040', 0, 0, '19', 1, '0.00', '0.00', 0, 0, 0, 0),
(32, 19, 35, 0, 4, 0, 1, '1716417870', '1716418040', 0, 0, '19', 1, '0.00', '0.00', 0, 0, 0, 0),
(33, 22, 36, 0, 1, 0, 1, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(34, 22, 37, 0, 1, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(35, 22, 38, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(36, 22, 39, 0, 1, 0, 1, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(37, 25, 40, 0, 0, 0, 1, '', '', 0, 0, '0', 0, '0.00', '0.00', 0, 0, 0, 0),
(38, 25, 41, 0, 1, 0, 1, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(39, 25, 42, 0, 0, 0, 3, '', '', 0, 0, '0', 0, '0.00', '0.00', 0, 0, 0, 0),
(40, 25, 43, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(41, 26, 44, 0, 1, 0, 1, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(42, 26, 45, 0, 1, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(43, 26, 46, 0, 1, 0, 1, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(44, 26, 47, 0, 1, 0, 1, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(45, 26, 48, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(46, 26, 49, 0, 1, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 1),
(47, 26, 50, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(48, 26, 51, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(49, 26, 52, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(50, 26, 53, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(51, 26, 54, 0, 1, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 1),
(52, 26, 55, 0, 1, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 1),
(53, 26, 56, 0, 1, 0, 3, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(54, 26, 57, 0, 1, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(55, 26, 58, 0, 1, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(56, 26, 59, 0, 1, 0, 0, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(57, 26, 60, 0, 1, 0, 4, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 0),
(58, 26, 61, 0, 1, 0, 2, '', '', 0, 0, '0', 1, '0.00', '0.00', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbevento`
--

CREATE TABLE `tbevento` (
  `id_evento` int(11) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `local` varchar(250) NOT NULL,
  `cidade` varchar(250) NOT NULL,
  `inicio` varchar(250) NOT NULL,
  `fim` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `modo_pgto` int(11) NOT NULL,
  `hash` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbevento`
--

INSERT INTO `tbevento` (`id_evento`, `titulo`, `local`, `cidade`, `inicio`, `fim`, `status`, `modo_pgto`, `hash`) VALUES
(1, 'Metropolitan Parque Multi', 'Metropolitan', 'Rio de Janeiro', '1715180400', '', 2, 0, '0'),
(2, 'Park Shopping Cmapo grande', 'campo grande', 'São Paulo', '', '', 2, 0, '0');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbfinanceiro`
--

CREATE TABLE `tbfinanceiro` (
  `id` int(11) NOT NULL,
  `id_prevenda` int(11) NOT NULL,
  `tp_cobranca` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `forma_pgto` int(11) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1,
  `hora_pgto` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbfinanceiro`
--

INSERT INTO `tbfinanceiro` (`id`, `id_prevenda`, `tp_cobranca`, `valor`, `forma_pgto`, `ativo`, `hora_pgto`) VALUES
(2, 9, 1, '3.70', 2, 1, '1716235417'),
(3, 5, 4, '9999.99', 4, 1, '1716338530'),
(4, 4, 4, '13008.00', 2, 1, '1716342111'),
(5, 14, 1, '5.60', 1, 1, '1716354350'),
(6, 19, 1, '6.50', 3, 1, '1716417870'),
(7, 14, 4, '2108.00', 4, 1, '1716418027'),
(8, 19, 4, '0.00', 3, 1, '1716418129');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbpacotes`
--

CREATE TABLE `tbpacotes` (
  `id_pacote` int(11) NOT NULL,
  `descricao` varchar(250) NOT NULL,
  `rotulo_cliente` varchar(250) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1,
  `valor` decimal(6,2) NOT NULL,
  `duracao` int(11) NOT NULL,
  `tolerancia` int(11) NOT NULL,
  `min_adicional` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbpacotes`
--

INSERT INTO `tbpacotes` (`id_pacote`, `descricao`, `rotulo_cliente`, `id_evento`, `ativo`, `valor`, `duracao`, `tolerancia`, `min_adicional`) VALUES
(1, 'Visit ante 2.8', 'Pacote padrão', 1, 1, '2.80', 5, 2, '1.00'),
(2, 'Lojista', 'Premium', 1, 1, '3.70', 10, 3, '1.00'),
(3, 'Outros', 'menor de 6 anos', 1, 1, '4.70', 90, 12, '1.30'),
(4, 'Influencer', 'Master', 1, 1, '5.60', 60, 13, '1.40');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbprevenda`
--

CREATE TABLE `tbprevenda` (
  `id_prevenda` int(11) NOT NULL,
  `id_responsavel` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `data_acesso` date NOT NULL,
  `prevenda_status` int(11) NOT NULL DEFAULT 1,
  `datahora_solicita` varchar(250) NOT NULL,
  `datahora_efetiva` varchar(250) NOT NULL,
  `pre_pgtotipo` int(11) NOT NULL,
  `pre_pgtovalor` decimal(10,2) NOT NULL,
  `pre_pgtodatahora` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbprevenda`
--

INSERT INTO `tbprevenda` (`id_prevenda`, `id_responsavel`, `id_evento`, `data_acesso`, `prevenda_status`, `datahora_solicita`, `datahora_efetiva`, `pre_pgtotipo`, `pre_pgtovalor`, `pre_pgtodatahora`) VALUES
(1, 1, 1, '2024-05-08', 2, '1715180400', '1715561552', 0, '0.00', ''),
(2, 3, 1, '2024-05-09', 2, '1715280420', '1715729868', 0, '0.00', ''),
(3, 4, 1, '2024-05-09', 2, '1715280574', '1715569594', 0, '0.00', ''),
(4, 5, 1, '2024-05-10', 6, '1715316948', '1715561313', 0, '0.00', ''),
(5, 6, 1, '2024-05-11', 6, '1715402126', '', 0, '0.00', ''),
(6, 7, 1, '2024-05-13', 2, '1715575454', '1715575537', 0, '0.00', ''),
(7, 8, 1, '2024-05-14', 2, '1715735703', '1715743161', 0, '0.00', ''),
(8, 9, 1, '2024-05-15', 2, '1715790265', '1715807982', 0, '0.00', ''),
(9, 10, 1, '2024-05-21', 2, '1715811221', '1716260400', 0, '0.00', ''),
(10, 3, 1, '2024-05-22', 1, '1716353329', '', 0, '0.00', ''),
(11, 11, 1, '2024-05-22', 1, '1716353345', '', 0, '0.00', ''),
(12, 12, 1, '2024-05-22', 1, '1716353363', '', 0, '0.00', ''),
(13, 13, 1, '2024-05-22', 1, '1716353385', '', 0, '0.00', ''),
(14, 1, 1, '2024-05-22', 6, '1716354132', '1716354350', 0, '0.00', ''),
(15, 1, 1, '2024-05-22', 9, '1716361483', '', 0, '0.00', ''),
(16, 14, 1, '2024-05-22', 9, '1716400276', '', 0, '0.00', ''),
(17, 15, 1, '2024-05-22', 1, '1716401048', '', 0, '0.00', ''),
(18, 15, 1, '2024-05-22', 9, '1716406797', '', 0, '0.00', ''),
(19, 16, 1, '2024-05-22', 6, '19', '1716417870', 0, '0.00', ''),
(20, 16, 1, '2024-05-22', 9, '1716417775', '', 0, '0.00', ''),
(21, 17, 1, '2024-05-22', 9, '1716419253', '', 0, '0.00', ''),
(22, 18, 1, '2024-06-04', 1, '1717532948', '', 2, '14.00', '1718074506'),
(23, 18, 2, '2024-06-04', 9, '1717533266', '', 0, '0.00', ''),
(24, 18, 1, '2024-06-10', 0, '1718070228', '', 0, '0.00', ''),
(25, 18, 1, '2024-06-10', 1, '1718074528', '', 2, '7.50', '1718160457'),
(26, 18, 1, '2024-06-11', 9, '1718161058', '', 0, '0.00', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbresponsavel`
--

CREATE TABLE `tbresponsavel` (
  `id_responsavel` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `cpf` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `telefone1` varchar(250) NOT NULL,
  `telefone2` varchar(250) NOT NULL,
  `nascimento` date NOT NULL,
  `datahora_input` varchar(250) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbresponsavel`
--

INSERT INTO `tbresponsavel` (`id_responsavel`, `nome`, `cpf`, `email`, `telefone1`, `telefone2`, `nascimento`, `datahora_input`, `ativo`) VALUES
(1, 'alessandro silva', '12356789', 'ale.tpd@gmail.com', '23423423423', '', '0000-00-00', '1715180400', 1),
(2, '34534', '34534', 'fgdfg', '', '', '0000-00-00', '1715278543', 1),
(3, 'epaminondas', '00000000123', 'epa@minondas', '989898', '98989', '0000-00-00', '1715280420', 1),
(4, 'fulaninho', '565765765', 'fula@ninho', '6876876', '876876876', '0000-00-00', '1715280574', 1),
(5, 'harvey specter', '71717171', 'harvey@specter.com', '34343434', '56565656', '0000-00-00', '1715316948', 1),
(6, 'Daniel cardoso', '5675675675', 'asdas@asdas', '3232423', '43534534', '0000-00-00', '1715402126', 1),
(7, 'Thanos da Silva', '7987987987', 'thanos@silva', '987654321', '123456789', '0000-00-00', '1715575454', 1),
(8, 'fulano de tal', '9999999', 'fulano@detal', '165654654', '65465465', '0000-00-00', '1715735703', 1),
(9, 'nome da pessoa 1', '54654654654', 'pessoa1@pessoal.com', '4879865', '8798498', '0000-00-00', '1715790265', 1),
(10, 'sdfsdfsd', '8798798', 'fsdfsd', '45445', '545454', '0000-00-00', '1715811221', 1),
(11, 'epaminondas', '878787', 'epa@minondas', '989898', '98989', '0000-00-00', '1716353345', 1),
(12, 'epaminondas', '878787', 'epa@minondas', '989898', '98989', '0000-00-00', '1716353363', 1),
(13, 'epaminondas', '878787', 'epa@minondas', '989898', '98989', '0000-00-00', '1716353385', 1),
(14, 'Beltrano de Souza', '654654', 'bel@trano.com', '234234234', '', '0000-00-00', '1716400276', 1),
(15, 'jose do patrocinio', '696961', 'jose@patrocinio', '23434534', '', '0000-00-00', '1716401048', 1),
(16, 'Daniel.. bbb', '11109588704', 'B@c.com', '21818181818', '', '0000-00-00', '1716417612', 1),
(17, 'd', '1119588703', '', '', '', '0000-00-00', '1716419253', 1),
(18, 'alessandro silva pinto', '07316221704', 'ale@ale.com.br', '9999999', '987654321', '0000-00-00', '1717532948', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbtermo`
--

CREATE TABLE `tbtermo` (
  `idtermo` int(11) NOT NULL,
  `idevento` int(11) NOT NULL,
  `dataatualiza` varchar(150) NOT NULL,
  `useratualiza` int(11) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1,
  `textotermo` varchar(8000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbtermo`
--

INSERT INTO `tbtermo` (`idtermo`, `idevento`, `dataatualiza`, `useratualiza`, `ativo`, `textotermo`) VALUES
(1, 1, '1718240040', 1, 1, '<p>Responsável: {{responsavelnome}}</p>\r\n<p>Participante: {{participantenome}} - Idade: {{participanteidade}}</p>\r\n<p>O evento de parque com camas elásticas e tobogãs da empresa denominada Multicenografia Eventos, inscrita sob CNPJ 29.617.436/0001-06 em conformidade com o art. 31 do CDC, informa que exerce atividade de entretenimento, oferecendo aos seus usuários espaço com: Tobogã linear e espiral, Tirolesa, Corredor de rede, Cama elástica, Parede de escalada, Barra de equilíbrio sobre piscina de espuma, Pêndulos com bolas; que contam com equipamentos de última geração e equipe de profissionais altamente capacitada para prestar as orientações quanto a sua correta utilização; que o seu entretenimento disponibilizado aos seus usuários se trata de atividade esportiva de grande impacto físico. Em decorrência das informações acima, assim, o(s) usuário(s) acima identificado(s) firma(m) o presente termo de ciência e liberação de responsabilidade, nos termos a seguir:</p>\r\n            <ol>\r\n                <li>O(s) usuário(s) acima qualificado(s), assim como seu responsável (quando aplicável):<br>\r\n                    Declara(m), para todos os fins de direito, que não possui doenças pré-existentes que possam desencadear algum problema médico advindo do uso dos equipamentos disponibilizados pela fornecedora, e que tem pleno conhecimento dos riscos das atividades de entretenimento disponibilizadas pela Multicenografia Eventos; e assume, voluntariamente, todos os riscos decorrentes dessa atividade, como por exemplo (mas não se limitando) entorses, fraturas, ferimentos, rupturas, arranhões, luxações, contusões etc., e me comprometo a respeitar e cumprir rigorosamente às regras e orientações do estabelecimento pela fornecedora de serviços, isentando a Multicenografia Eventos, de qualquer responsabilidade, civil ou criminal, por danos físicos, emocionais ou materiais, bem comoproblema(s) de saúde pré-existente(s), desconhecido(s) ou não, bem como diagnóstico(s) futuro(s)de doença(s).</li>\r\n                    <li>Autoriza (m) a Multicenografia Eventos a reprimir, e até mesmo proibir, sua permanência em suas dependências, na hipótese de descumprir os termos do Regulamento e das Regras de Segurança, bem como, de ser constatada qualquer alteração comportamental que ponha em risco a minha integridade física, dos menores por mim representados, ou de terceiros; e assumo integral responsabilidade pela reparação dos danos que der causa, sejam eles pessoais, materiais e morais.</li>\r\n                    <li>Autoriza (m) a Multicenografia Eventos e o Shopping a fazer uso dasfilmagens, gravações ou fotografias captadas dentro do seu ambiente, para fins de direito ou de divulgação publicitária, sem que caracterize uso indevido de imagem ou qualquer violação de direitos, bem como, autoriza a utilização das referidas imagens do menor que representa. </li>\r\n                    <li>Autoriza (m) a Multicenografia Eventos, em caso de acidente, a solicitar serviços médicos de emergência e de ambulância do SAMU – Serviço de Atendimento Móvel de Urgência ou do Corpo de Bombeiros Militar.</li>\r\n                    <li>Declara(m) que leu(ram) e têm ciência do Regulamento e das Regras de Segurança do evento, ressaltando que: o(s) usuário(s) necessitam ter altura IGUAL ou SUPERIOR a 90cm, sendo esta a altura mínima para uso do espaço. Não é permitido o ingresso de crianças menores de 90cm, mesmo que acompanhadas de um responsável. Crianças de 90cm a 120cm deverão estar acompanhadas de um responsável, que terá direito a entrada gratuita para acompanhar o menor; crianças a partir de 120cm podem entrar sem acompanhante, desde que o responsável se mantenha no entorno do evento; menores de idade, independente da altura, deverão ter o termo de responsabilidade assinado por um responsável maior de idade para adentrar o parque. É obrigatório apresentar o documento do menor em nossa recepção.</li>\r\n                    <li>É proibido o acesso de maiores de 18 anos, a menos que estes estejam como acompanhantes de um menor de idade.</li>\r\n                    <li>Aceita(m) de forma total e irrestrita todos os seus termos e condições, servindo esta declaração de que o usuário e/ou seu responsável não tem qualquer impedimento e/ou problema de saúde para aderir referido regulamento.</li>\r\n                    <li>Assume(m) a responsabilidade civil e criminal pela veracidade de todas as informações acima preenchidas, declarando serem verídicas e regulares.</li>\r\n                    <li>O responsável pelo menor declara, para todos os fins de direito, que o representa legalmente, e que o autoriza à prática da atividade fornecida pela Multicenografia Eventos, e que tem conhecimento do risco acima, concordando que todos os efeitos deste documento se apliquem também ao menor usuário sob sua responsabilidade.</li>\r\n                    <li>Declara(m) que leu/leram e compreendeu/compreenderam atentamente os termos deste documento, concordando com o mesmo, assinando-o voluntariamente.\r\n                    As informações aqui contidas são confidenciais, e possuem apenas a finalidade de atender ao comando estabelecido pelo art. 31 do Código de Defesa do Consumidor. Caso alguma parte deste acordo seja considerada nula, anulável ou não aplicável à legislação brasileira, por determinação judicial, a parte remanescente permanecerá em pleno vigor e efeito. O presente Termo de Responsabilidade tem a validade por tempo indeterminado, a contar da presente data.</li>\r\n            </ol>\r\n            <p>Fica, desde já, eleito o Tribunal de Justiça do Estado para a solução de quaisquer questões referentes ao presente termo.</p>\r\n            <p>{{cidadetermo}}, {{datahoje}}</p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbusuarios`
--

CREATE TABLE `tbusuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `login` varchar(250) NOT NULL,
  `senha` varchar(250) NOT NULL,
  `perfil` int(11) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbusuarios`
--

INSERT INTO `tbusuarios` (`id_usuario`, `nome`, `login`, `senha`, `perfil`, `ativo`) VALUES
(1, 'Usuario Master', 'master@user', '86fa9bbea68969018513bb2956af832d', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbusuarios_evento`
--

CREATE TABLE `tbusuarios_evento` (
  `id_userevento` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idevento` int(11) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbusuarios_evento`
--

INSERT INTO `tbusuarios_evento` (`id_userevento`, `idusuario`, `idevento`, `ativo`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbvinculados`
--

CREATE TABLE `tbvinculados` (
  `id_vinculado` int(11) NOT NULL,
  `id_responsavel` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `nascimento` date NOT NULL,
  `tipo` int(11) NOT NULL,
  `lembrar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbvinculados`
--

INSERT INTO `tbvinculados` (`id_vinculado`, `id_responsavel`, `nome`, `nascimento`, `tipo`, `lembrar`) VALUES
(1, 1, 'Maria Alice', '2022-01-13', 1, 1),
(2, 1, 'João Victor', '2022-12-12', 2, 1),
(3, 1, 'Huguinho', '2000-01-01', 3, 0),
(4, 1, 'Zezinho', '2024-05-06', 1, 0),
(5, 1, 'mais um', '2024-02-01', 2, 0),
(6, 1, 'harvey specter', '2016-08-26', 4, 0),
(7, 4, 'filho 001', '2000-01-01', 2, 0),
(8, 4, 'filho 002', '2024-05-21', 1, 0),
(9, 4, 'filho 003', '2024-05-14', 2, 0),
(10, 4, 'filho 004', '2024-05-07', 2, 0),
(11, 5, 'mike ross', '2011-02-02', 2, 0),
(12, 5, 'louis litt', '2012-03-03', 3, 0),
(13, 4, 'uma pessoa qualquer', '2024-05-29', 1, 0),
(14, 4, 'carlos', '2024-05-30', 1, 0),
(15, 4, 'pessoinha', '2024-05-28', 1, 0),
(16, 4, 'outro participante', '2024-05-23', 1, 0),
(17, 4, 'sfdsfd', '2024-05-20', 4, 0),
(18, 4, 'bunda le le ', '2004-10-21', 2, 0),
(19, 6, 'Helena Rosa', '2024-05-23', 1, 0),
(20, 6, 'Carlos Junior', '2024-05-28', 2, 0),
(21, 3, 'tony stark', '2024-05-14', 2, 0),
(22, 7, 'filho do thanos mais novo', '2024-05-31', 2, 0),
(23, 7, 'thanos junior', '2024-06-01', 4, 0),
(24, 8, 'baby 01', '2024-05-27', 2, 0),
(25, 8, 'baby 02', '2024-05-17', 1, 0),
(26, 9, 'mais um aqui', '2024-05-30', 2, 0),
(27, 9, 'mais um aqui', '2024-05-30', 2, 0),
(28, 9, 'mais um aqui', '2024-05-30', 2, 0),
(29, 9, 'mais um aqui', '2024-05-30', 2, 0),
(30, 10, 'xdcsdfsd', '2024-05-30', 3, 0),
(31, 1, 'beltraninho', '2000-05-05', 1, 0),
(32, 1, 'enzo', '2000-01-01', 2, 0),
(33, 15, 'dfgdfg', '2024-05-17', 2, 0),
(34, 16, 'Nenena', '2024-05-30', 2, 0),
(35, 16, 'Carlinhos ', '2024-05-13', 3, 0),
(36, 18, 'joazinho', '2000-05-05', 3, 0),
(37, 18, 'ei pessoinha', '2024-06-17', 1, 0),
(38, 18, 'dfsdfsdf sd', '2024-06-19', 2, 1),
(39, 18, 'mais uim', '2024-06-14', 1, 0),
(40, 18, 'palito', '2000-05-05', 2, 1),
(41, 18, 'mais um aqui', '2002-02-18', 1, 1),
(42, 18, 'trty rty rt', '2024-06-12', 3, 1),
(43, 18, 'outra crianca', '2024-06-07', 2, 1),
(44, 18, 'filano de tal', '2018-11-08', 2, 1),
(45, 18, 'outro fulano', '2008-05-05', 3, 1),
(46, 18, 'dfg dfg df', '2024-06-04', 2, 0),
(47, 18, 'dfsdf sdf sd', '2024-06-25', 2, 1),
(48, 18, 'dfsdfs', '2024-05-30', 3, 0),
(49, 18, 'sdfsdfsd', '2024-06-14', 2, 0),
(50, 18, 'reewrgerg', '2024-06-13', 2, 1),
(51, 18, 'dasas', '2024-06-13', 2, 0),
(52, 18, 'adasdas', '2024-06-10', 3, 1),
(53, 18, 'alessandro junior', '2024-05-31', 3, 0),
(54, 18, 'swedfsdfsd', '2024-06-10', 3, 1),
(55, 18, 'sdfsdf', '2024-06-22', 3, 0),
(56, 18, 'fdg dfg df gdf ', '2024-06-15', 3, 0),
(57, 18, 'dfgdfgdf', '2024-06-07', 2, 0),
(58, 18, 'dfdfgd', '2024-06-21', 3, 0),
(59, 18, 'ffgdfgd', '2024-05-27', 2, 0),
(60, 18, 'fgfgdfg', '2024-06-03', 3, 0),
(61, 18, 'fulanin', '2024-05-30', 3, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbvinculo`
--

CREATE TABLE `tbvinculo` (
  `id_vinculo` int(11) NOT NULL,
  `descricao` varchar(250) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tbvinculo`
--

INSERT INTO `tbvinculo` (`id_vinculo`, `descricao`, `ativo`) VALUES
(1, 'Filho(a)', 1),
(2, 'Enteado(a)', 1),
(3, 'Parente', 1),
(4, 'Responsável', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tbevento_ativo`
--
ALTER TABLE `tbevento_ativo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tbentrada`
--
ALTER TABLE `tbentrada`
  ADD PRIMARY KEY (`id_entrada`);

--
-- Índices para tabela `tbevento`
--
ALTER TABLE `tbevento`
  ADD PRIMARY KEY (`id_evento`);

--
-- Índices para tabela `tbfinanceiro`
--
ALTER TABLE `tbfinanceiro`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tbpacotes`
--
ALTER TABLE `tbpacotes`
  ADD PRIMARY KEY (`id_pacote`);

--
-- Índices para tabela `tbprevenda`
--
ALTER TABLE `tbprevenda`
  ADD PRIMARY KEY (`id_prevenda`);

--
-- Índices para tabela `tbresponsavel`
--
ALTER TABLE `tbresponsavel`
  ADD PRIMARY KEY (`id_responsavel`);

--
-- Índices para tabela `tbtermo`
--
ALTER TABLE `tbtermo`
  ADD PRIMARY KEY (`idtermo`);

--
-- Índices para tabela `tbusuarios`
--
ALTER TABLE `tbusuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Índices para tabela `tbusuarios_evento`
--
ALTER TABLE `tbusuarios_evento`
  ADD PRIMARY KEY (`id_userevento`);

--
-- Índices para tabela `tbvinculados`
--
ALTER TABLE `tbvinculados`
  ADD PRIMARY KEY (`id_vinculado`);

--
-- Índices para tabela `tbvinculo`
--
ALTER TABLE `tbvinculo`
  ADD PRIMARY KEY (`id_vinculo`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbevento_ativo`
--
ALTER TABLE `tbevento_ativo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tbentrada`
--
ALTER TABLE `tbentrada`
  MODIFY `id_entrada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de tabela `tbevento`
--
ALTER TABLE `tbevento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tbfinanceiro`
--
ALTER TABLE `tbfinanceiro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tbpacotes`
--
ALTER TABLE `tbpacotes`
  MODIFY `id_pacote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbprevenda`
--
ALTER TABLE `tbprevenda`
  MODIFY `id_prevenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `tbresponsavel`
--
ALTER TABLE `tbresponsavel`
  MODIFY `id_responsavel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `tbtermo`
--
ALTER TABLE `tbtermo`
  MODIFY `idtermo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbusuarios`
--
ALTER TABLE `tbusuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbusuarios_evento`
--
ALTER TABLE `tbusuarios_evento`
  MODIFY `id_userevento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tbvinculados`
--
ALTER TABLE `tbvinculados`
  MODIFY `id_vinculado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `tbvinculo`
--
ALTER TABLE `tbvinculo`
  MODIFY `id_vinculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
