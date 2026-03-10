-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 06:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minha_watchlist_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `itens_audiovisuais`
--

CREATE TABLE `itens_audiovisuais` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `ano_lancamento` int(11) DEFAULT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `sinopse` text DEFAULT NULL,
  `plataforma` varchar(100) DEFAULT NULL,
  `status` enum('Quero Ver','Assistindo','Assistido') DEFAULT 'Quero Ver',
  `avaliacao_pessoal` int(11) DEFAULT NULL,
  `data_assistido` date DEFAULT NULL,
  `link_trailer` varchar(255) DEFAULT NULL,
  `favorito` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `itens_audiovisuais`
--

INSERT INTO `itens_audiovisuais` (`id`, `id_usuario`, `titulo`, `ano_lancamento`, `genero`, `sinopse`, `plataforma`, `status`, `avaliacao_pessoal`, `data_assistido`, `link_trailer`, `favorito`) VALUES
(1, 1, 'poderoso chefão', 2020, 'acão', 'afjbhbvhbas', 'Netflix', 'Assistido', 8, '0000-00-00', 'https://chatgpt.com/c/682497d5-9138-800f-b737-75b784e7adf7', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Lucas', 'lucas@gmail.com', '$2y$10$1LkoauVpqrmjWaj/QhqaJusWV73SVj.u.Zvo.3Dcpdvqj8l9jMree');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `itens_audiovisuais`
--
ALTER TABLE `itens_audiovisuais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `itens_audiovisuais`
--
ALTER TABLE `itens_audiovisuais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `itens_audiovisuais`
--
ALTER TABLE `itens_audiovisuais`
  ADD CONSTRAINT `itens_audiovisuais_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
