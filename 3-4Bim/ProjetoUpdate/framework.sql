-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/08/2026 às 23:05
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `framework`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id_aluno` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `id_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`id_aluno`, `nome`, `email`, `data_nascimento`, `id_curso`) VALUES
(1, 'Gabriel Santos', 'gabriel.santos@email.com', '2001-05-14', 1),
(2, 'Camila Rodrigues', 'camila.rodrigues@email.com', '1999-12-03', 2),
(3, 'Lucas Martins', 'lucas.martins@email.com', '2002-08-21', 3),
(4, 'Beatriz Ferreira', 'beatriz.ferreira@email.com', '2000-03-10', 4),
(5, 'Thiago Barbosa', 'thiago.barbosa@email.com', '1998-11-25', 5),
(6, 'Larissa Dias', 'larissa.dias@email.com', '2003-01-07', 6),
(7, 'Mateus Carvalho', 'mateus.carvalho@email.com', '2001-09-30', 7),
(8, 'Sofia Ribeiro', 'sofia.ribeiro@email.com', '2002-04-18', 8),
(9, 'Vinicius Fernandes', 'vinicius.fernandes@email.com', '1997-07-12', 9),
(10, 'Amanda Araujo', 'amanda.araujo@email.com', '2000-10-05', 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `carga_horaria` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `id_professor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curso`
--

INSERT INTO `curso` (`id_curso`, `nome`, `carga_horaria`, `descricao`, `id_professor`) VALUES
(1, 'SQL e Banco de Dados Relacional', 60, 'Curso completo sobre bancos de dados relacionais.', 1),
(2, 'Desenvolvimento Web Front-End', 80, 'Aprenda HTML, CSS, JavaScript e React.', 2),
(3, 'Fundamentos de Machine Learning', 90, 'Introdução aos algoritmos de IA e aprendizado de máquina.', 3),
(4, 'Arquitetura e Engenharia de Software', 100, 'Boas práticas, padrões de projeto e arquitetura de software.', 4),
(5, 'Cibersegurança Prática', 70, 'Conceitos e técnicas para proteção de redes e sistemas.', 5),
(6, 'Análise de Dados com Python', 80, 'Manipulação e visualização de dados utilizando Python.', 6),
(7, 'Administração de Redes de Computadores', 60, 'Configuração e gerenciamento de infraestrutura de rede.', 7),
(8, 'Design de Interface e Experiência do Usuário (UX/UI)', 50, 'Criação de protótipos e interfaces amigáveis.', 8),
(9, 'Desenvolvimento de Apps com Flutter', 75, 'Crie aplicativos para Android e iOS.', 9),
(10, 'Sistemas Operacionais e Arquitetura', 60, 'Visão aprofundada do funcionamento de SOs.', 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id_professor` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `especialidade` varchar(50) DEFAULT NULL,
  `data_admissao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id_professor`, `nome`, `email`, `especialidade`, `data_admissao`) VALUES
(1, 'Carlos Silva', 'carlos.silva@email.com', 'Banco de Dados', '2020-03-15'),
(2, 'Mariana Souza', 'mariana.souza@email.com', 'Desenvolvimento Web', '2019-08-10'),
(3, 'Roberto Alves', 'roberto.alves@email.com', 'Inteligência Artificial', '2021-01-20'),
(4, 'Ana Paula Lima', 'ana.lima@email.com', 'Engenharia de Software', '2018-05-12'),
(5, 'Fernando Mendes', 'fernando.mendes@email.com', 'Segurança da Informação', '2022-02-01'),
(6, 'Juliana Rocha', 'juliana.rocha@email.com', 'Ciência de Dados', '2020-11-05'),
(7, 'Ricardo Gomez', 'ricardo.gomez@email.com', 'Redes de Computadores', '2017-09-18'),
(8, 'Patricia Castro', 'patricia.castro@email.com', 'Design UX/UI', '2021-06-30'),
(9, 'Lucas Oliveira', 'lucas.oliveira@email.com', 'Desenvolvimento Mobile', '2023-01-15'),
(10, 'Beatriz Costa', 'beatriz.costa@email.com', 'Sistemas Operacionais', '2019-04-22');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id_aluno`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Índices de tabela `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `id_professor` (`id_professor`);

--
-- Índices de tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`id_professor`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id_aluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `id_professor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `aluno_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id_professor`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
