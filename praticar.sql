-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 31-Out-2017 às 20:44
-- Versão do servidor: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aluno_praticar`
--
CREATE DATABASE IF NOT EXISTS `aluno_praticar` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `aluno_praticar`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `exercicios`
--

CREATE TABLE `exercicios` (
  `id` int(11) NOT NULL,
  `lista` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `resposta` text NOT NULL,
  `resposta_sql` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `exercicios`
--

INSERT INTO `exercicios` (`id`, `lista`, `descricao`, `resposta`, `resposta_sql`) VALUES
(111, 4, 'Faça uma consulta que retorne todos os dados da tabela "clientes".', 'clientes', 'SELECT * FROM `clientes`'),
(112, 4, 'Faça uma consulta que retorne todos os clientes que tenham feito compras.', 'clientes ⨝ (clientes.id_cliente = compras.id_cliente) compras', 'SELECT * FROM `clientes` INNER JOIN `compras` ON `clientes`.`id_cliente` = `compras`.`id_cliente`'),
(113, 4, 'Faça uma consulta que retorne o nome de todos os carros comprados juntamente com o nome do cliente que comprou. A consulta deve retornar duas colunas chamadas "cliente" e "carro", cada uma contendo o nome do cliente e do carro, respectivamente.', 'ρ [cliente, carro] (\n    π clientes.nome , carros.nome (\n        σ clientes.id_cliente = compras.id_cliente ^ carros.id_carro = compras.id_carro (\n            clientes X compras X carros\n        )\n    )\n)', 'SELECT `clientes`.`nome` AS `cliente`,`carros`.`nome` AS `carro` FROM `clientes`,`compras`,`carros` WHERE `clientes`.`id_cliente` = `compras`.`id_cliente` AND `carros`.`id_carro` = `compras`.`id_carro`'),
(119, 9, 'Faça uma consulta que retorne todos os carros com id maior que 10.', 'σ id_carro > 10 (carros)', 'SELECT * FROM `carros` WHERE `id_carro` > 10');

-- --------------------------------------------------------

--
-- Estrutura da tabela `listas`
--

CREATE TABLE `listas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `turma` int(11) NOT NULL,
  `data_prazo` date NOT NULL,
  `hora_prazo` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `listas`
--

INSERT INTO `listas` (`id`, `titulo`, `turma`, `data_prazo`, `hora_prazo`) VALUES
(4, 'Lista 1', 6, '2017-11-05', '22:15:00'),
(9, 'Lista 2', 7, '2017-11-06', '22:15:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `respostas`
--

CREATE TABLE `respostas` (
  `id` int(11) NOT NULL,
  `exercicio` int(11) NOT NULL,
  `resposta` text,
  `resposta_sql` text,
  `tentativas` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `respostas`
--

INSERT INTO `respostas` (`id`, `exercicio`, `resposta`, `resposta_sql`, `tentativas`, `data`, `hora`) VALUES
(29, 111, 'clientes', 'SELECT * FROM `clientes`', 1, '2017-10-31', '13:30:00'),
(30, 112, 'clientes ⨝ (clientes.id_cliente = compras.id_cliente) compras', 'SELECT * FROM `clientes` INNER JOIN `compras` ON `clientes`.`id_cliente` = `compras`.`id_cliente`', 1, '2017-10-31', '13:30:00'),
(31, 113, 'ρ [cliente, carro] (π clientes.nome, carros.nome (σ carros.id_carro = compras.id_carro ^ compras.id_cliente = clientes.id_cliente (carros X compras X clientes)))', 'SELECT `clientes`.`nome` AS `cliente`,`carros`.`nome` AS `carro` FROM `carros`,`compras`,`clientes` WHERE `carros`.`id_carro` = `compras`.`id_carro` AND `compras`.`id_cliente` = `clientes`.`id_cliente`', 1, '2017-10-31', '13:30:00'),
(32, 119, 'σ id_carro > 10 (carros)', 'SELECT * FROM `carros` WHERE `id_carro` > 10', 5, '2017-10-31', '13:30:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `turmas`
--

CREATE TABLE `turmas` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `ativa` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `turmas`
--

INSERT INTO `turmas` (`id`, `descricao`, `ativa`) VALUES
(1, '2010/2', 1),
(6, '2011/1', 1),
(7, '2011/2', 1),
(8, '2016/1', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_turma`
--

CREATE TABLE `usuario_turma` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario_turma`
--

INSERT INTO `usuario_turma` (`id`, `id_usuario`, `id_turma`) VALUES
(13, 9, 8),
(14, 5, 8),
(23, 2, 6),
(24, 4, 6),
(25, 5, 6),
(44, 3, 1),
(45, 7, 1),
(95, 2, 7),
(96, 5, 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `login` varchar(20) NOT NULL,
  `senha` text NOT NULL,
  `tentativas` int(11) NOT NULL,
  `bloqueado` int(11) NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `login`, `senha`, `tentativas`, `bloqueado`, `tipo`) VALUES
(1, 'Administrador', 'admin', '$2a$08$NDU3OTE4NTYzNTlmNzVkZOeQXKqVEHQCRoyErOnKoLq7Fj9mktPZq', 0, 0, 0),
(2, 'Rodrigo Barbosa', 'rodrigo.barbosa', '$2a$08$MTQ3NjE5NTAxNjU5ZGMwMO6OXSGA1YnhOcPml2OwUOri89u8br0aC', 0, 0, 2),
(3, 'Alesander Vaccari', 'alesander.vaccari', '$2a$08$MTUyNTE0MTkwMDU5ZTc1M.EbmEC2qx.yvZtKYrXrg1ibkoiQArovC', 0, 0, 2),
(4, 'Tiago Tonus', 'tiago.tonus', '$2a$08$NjYwMDU0MjcxNTljYmFmNumCBhhLcRzc/gblC8kGeIXHIcd938dOi', 0, 0, 2),
(5, 'Lucas', 'lucas', '$2a$08$ODExMzQ1MDY1OWYzOTg0N.Fwp.m87vxbwaF5zijJQQtGzbWjUoZ9W', 0, 0, 1),
(6, 'Willian Secco', 'willian.secco', '$2a$08$NDI2MjU4NjA1NTljYzBiM.TmtEohrKOgxYC.EEjlXWOff.Y.y9vAi', 0, 0, 2),
(7, 'Adrovane', 'adrovane', '$2a$08$MTA2MTAyMTk1NDU5ZjFlMeKj4wGhh6.HrEiYTy/HZ6aAFYkNFdiDy', 0, 0, 1),
(8, 'Rogério', 'rogerio', '$2a$08$MjA0NTkyMDA0NTU5ZjY3NedVK6BEUcBIkge29VV53GkyZKF8n873G', 0, 0, 1),
(9, 'Fulano de Tal', 'fulano.de.tal', '$2a$08$OTA1NTI2NzkzNTlmNjc2YuhK48R5UmCnmj8AbH1ykiGslaj/WLhUK', 0, 0, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exercicios`
--
ALTER TABLE `exercicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lista` (`lista`);

--
-- Indexes for table `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turma` (`turma`);

--
-- Indexes for table `respostas`
--
ALTER TABLE `respostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exercicio` (`exercicio`);

--
-- Indexes for table `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `descricao` (`descricao`);

--
-- Indexes for table `usuario_turma`
--
ALTER TABLE `usuario_turma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_turma` (`id_turma`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exercicios`
--
ALTER TABLE `exercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT for table `listas`
--
ALTER TABLE `listas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `respostas`
--
ALTER TABLE `respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `usuario_turma`
--
ALTER TABLE `usuario_turma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `exercicios`
--
ALTER TABLE `exercicios`
  ADD CONSTRAINT `exercicios_ibfk_1` FOREIGN KEY (`lista`) REFERENCES `listas` (`id`);

--
-- Limitadores para a tabela `listas`
--
ALTER TABLE `listas`
  ADD CONSTRAINT `listas_ibfk_1` FOREIGN KEY (`turma`) REFERENCES `turmas` (`id`);

--
-- Limitadores para a tabela `respostas`
--
ALTER TABLE `respostas`
  ADD CONSTRAINT `respostas_ibfk_1` FOREIGN KEY (`exercicio`) REFERENCES `exercicios` (`id`);

--
-- Limitadores para a tabela `usuario_turma`
--
ALTER TABLE `usuario_turma`
  ADD CONSTRAINT `usuario_turma_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuario_turma_ibfk_2` FOREIGN KEY (`id_turma`) REFERENCES `turmas` (`id`);
--
-- Database: `aluno_praticar_exercicios`
--
CREATE DATABASE IF NOT EXISTS `aluno_praticar_exercicios` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `aluno_praticar_exercicios`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `avioes`
--

CREATE TABLE `avioes` (
  `nome` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `avioes`
--

INSERT INTO `avioes` (`nome`) VALUES
('AÉROSPATIALE/BAC CONCORDE'),
('AIRBUS A320'),
('AIRBUS A400M GRIZZLY');

-- --------------------------------------------------------

--
-- Estrutura da tabela `carros`
--

CREATE TABLE `carros` (
  `id_carro` int(11) DEFAULT NULL,
  `marca` varchar(20) DEFAULT NULL,
  `nome` varchar(20) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `carros`
--

INSERT INTO `carros` (`id_carro`, `marca`, `nome`, `preco`) VALUES
(1, 'Fiat', 'Palio', '45050.00'),
(2, 'Volkswagen', 'Gol', '49292.00'),
(3, 'Fiat', 'Uno', '36425.00'),
(4, 'Hyundai', 'HB20', '54310.00'),
(5, 'Chevrolet', 'Onix', '50029.00'),
(6, 'Ford', 'Fiesta', '59180.00'),
(7, 'Fiat', 'Siena', '47438.00'),
(8, 'Volkswagen', 'up!', '41585.00'),
(9, 'Volkswagen', 'Voyage', '55740.00'),
(10, 'Chevrolet', 'Prisma', '52424.00'),
(11, 'Toyota', 'Corolla', '93920.00'),
(12, 'Hyundai', 'HB20S', '54003.00'),
(13, 'Renault', 'Sandero', '50990.00'),
(14, 'Honda', 'Fit', '66714.00'),
(15, 'Honda', 'Civic', '82500.00'),
(16, 'Toyota', 'Etios', '46290.00'),
(17, 'Chevrolet', 'Classic', '33855.00'),
(18, 'Chevrolet', 'Cobalt', '55017.00'),
(19, 'Renault', 'Logan', '46571.00'),
(20, 'Ford', 'Fusion', '112900.00'),
(21, 'Tesla', 'Model S', '745000.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) DEFAULT NULL,
  `nome` text,
  `idade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `idade`) VALUES
(1, 'Daniel', 24),
(2, 'João', 18),
(3, 'Mateus', 35),
(4, 'Marcos', 27),
(5, 'Alexandre', 54),
(6, 'Alice', 18),
(7, 'Juliana', 25),
(8, 'Sofia', 20),
(9, 'Helena', 27),
(10, 'Raquel', 39);

-- --------------------------------------------------------

--
-- Estrutura da tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_carro` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `compras`
--

INSERT INTO `compras` (`id`, `id_cliente`, `id_carro`) VALUES
(1, 1, 1),
(2, 2, 7),
(3, 3, 10),
(4, 4, 16),
(5, 5, 20),
(6, 5, 11),
(7, 5, 15),
(8, 6, 4),
(9, 6, 6),
(10, 7, 8),
(11, 8, 9),
(12, 9, 13),
(13, 10, 14),
(14, 10, 7),
(15, 1, 21);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pilotos`
--

CREATE TABLE `pilotos` (
  `nome` text,
  `aviao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pilotos`
--

INSERT INTO `pilotos` (`nome`, `aviao`) VALUES
('James', 'AÉROSPATIALE/BAC CONCORDE'),
('James', 'AIRBUS A320'),
('James', 'AIRBUS A400M GRIZZLY'),
('William', 'AÉROSPATIALE/BAC CONCORDE'),
('William', 'AIRBUS A320'),
('Jack', 'AÉROSPATIALE/BAC CONCORDE'),
('Jack', 'AIRBUS A320'),
('Jack', 'AIRBUS A400M GRIZZLY');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
