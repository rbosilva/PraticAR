-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 03-Nov-2017 às 19:37
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

-- --------------------------------------------------------

--
-- Estrutura da tabela `respostas`
--

CREATE TABLE `respostas` (
  `id` int(11) NOT NULL,
  `exercicio` int(11) NOT NULL,
  `aluno` int(11) NOT NULL,
  `resposta` text,
  `resposta_sql` text,
  `tentativas` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `turmas`
--

CREATE TABLE `turmas` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `ativa` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_turma`
--

CREATE TABLE `usuario_turma` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 'Administrador', 'admin', '$2a$08$NDU3OTE4NTYzNTlmNzVkZOeQXKqVEHQCRoyErOnKoLq7Fj9mktPZq', 0, 0, 0);

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
  ADD KEY `exercicio` (`exercicio`),
  ADD KEY `aluno` (`aluno`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `listas`
--
ALTER TABLE `listas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `respostas`
--
ALTER TABLE `respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usuario_turma`
--
ALTER TABLE `usuario_turma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
  ADD CONSTRAINT `respostas_ibfk_1` FOREIGN KEY (`exercicio`) REFERENCES `exercicios` (`id`),
  ADD CONSTRAINT `respostas_ibfk_2` FOREIGN KEY (`aluno`) REFERENCES `usuarios` (`id`);

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
  `id_cliente` int(11) DEFAULT NULL,
  `id_carro` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `compras`
--

INSERT INTO `compras` (`id_cliente`, `id_carro`) VALUES
(1, 1),
(2, 7),
(3, 10),
(4, 16),
(5, 20),
(5, 11),
(5, 15),
(6, 4),
(6, 6),
(7, 8),
(8, 9),
(9, 13),
(10, 14),
(10, 7);

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
