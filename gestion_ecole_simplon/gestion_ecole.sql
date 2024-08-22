-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 22 août 2024 à 13:45
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_ecole`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_suppression` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_inscription`, `date_modification`, `date_suppression`) VALUES
(5, 'Gallas', 'Fallou', 'gallasfallou@gmail.com', '$2y$10$FAhDOsiyUeqrxTCprtTprOv0uim5FZu9U5YmyI4Xquv3uSWojIrYW', 'superadmin', '2024-08-19 08:57:01', '2024-08-19 08:57:01', NULL),
(6, 'Sow', 'Abou', 'abou@gmail.com', '$2y$10$FAcrHXcRsZT.wzmVNq6ssOQY3ZSI4rmAT7pCVeNyjDeIxlXSnkESq', 'admin', '2024-08-19 09:49:34', '2024-08-19 09:49:34', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `niveau` enum('L1','L2','L3','M1','M2') NOT NULL,
  `matricule` varchar(20) NOT NULL,
  `statut_admission` enum('en cours','admis','recalé') DEFAULT 'en cours',
  `archive` tinyint(1) DEFAULT 0,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modification` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_suppression` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `nom`, `prenom`, `date_naissance`, `email`, `telephone`, `niveau`, `matricule`, `statut_admission`, `archive`, `date_inscription`, `date_modification`, `date_suppression`) VALUES
(36, 'Fall', 'Diounkoury', '2002-08-14', 'diounkary@gmail.com', '774463329', 'L1', 'ET2002FD', 'en cours', 0, '2024-08-18 19:27:22', '2024-08-18 19:27:22', NULL),
(37, 'Fall', 'Demba', '2000-08-08', 'falll@gmail.com', '78333223', 'L1', 'ET2000FD', 'en cours', 1, '2024-08-18 19:28:41', '2024-08-19 18:15:22', NULL),
(38, 'Dieme', 'Louis', '2001-06-06', 'diemelouis@gmail.com', '7622320203', 'L1', 'ET2001DL', 'en cours', 0, '2024-08-18 19:30:13', '2024-08-19 10:12:56', NULL),
(39, 'fall', 'Bambas', '2006-06-07', 'fallbambas@gmail.com', '784465523', 'L1', 'ET2006FB', 'en cours', 0, '2024-08-18 19:35:44', '2024-08-18 19:35:44', NULL),
(40, 'DIOUNKOU', 'OUSMANE', '2000-05-09', 'diounkou@gmail.com', '785465575', 'L2', 'ET2000DO', 'admis', 0, '2024-08-18 19:39:28', '2024-08-19 17:57:16', NULL),
(41, 'DIALLO', 'HABIB', '2002-05-01', 'diallobib@gmail.com', '769935633', 'L2', 'ET2002DH', 'admis', 0, '2024-08-18 19:50:30', '2024-08-19 18:15:08', NULL),
(42, 'DIATTA', 'DAFESFSE', '2001-06-05', 'diatta@gmail.com', '783332232', 'L3', 'ET2001DD', 'admis', 0, '2024-08-18 19:51:59', '2024-08-22 10:55:23', NULL),
(45, 'MAY', 'FALLY', '2004-08-03', 'fally@gmail.com', '753332232', 'M1', 'ET2004MF', 'admis', 0, '2024-08-18 19:54:44', '2024-08-19 10:20:03', NULL),
(46, 'AEER FFTT', 'EERE EERRF', '2007-08-02', 'baetienne20@gmail.com', '784463342', 'L1', 'ET2007AE', 'admis', 1, '2024-08-19 09:24:31', '2024-08-22 10:37:22', NULL),
(47, 'FALL   ', '   DIOP', '2003-02-12', 'modou@gmail.com', '777775246', 'L2', 'ET2003FD', 'admis', 0, '2024-08-19 10:10:16', '2024-08-20 14:38:35', NULL),
(48, 'Diop ', ' Demba', '1999-12-12', 'diopdemba@gmail.com', '784465575', 'L3', 'ET1999DD', 'recalé', 0, '2024-08-20 14:30:15', '2024-08-20 14:31:01', NULL),
(49, 'ba', 'damso', '2000-11-10', 'livayglound@gmail.com', '783220303', 'M1', 'ET2000BD', 'recalé', 0, '2024-08-20 23:08:57', '2024-08-22 10:55:48', NULL),
(50, 'Guaye', 'Dembous', '2000-09-12', 'Guayedembous@gmail.com', '784465578', 'M2', 'ET2000GD', 'en cours', 0, '2024-08-22 11:23:24', '2024-08-22 11:23:24', NULL),
(51, 'Ndiaye', 'Gallas', '2003-11-10', 'ndiaye@gmail.com', '784473245', 'M2', 'ET2003NG', 'en cours', 0, '2024-08-22 11:30:50', '2024-08-22 11:30:50', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `module1` float DEFAULT NULL,
  `module2` float DEFAULT NULL,
  `module3` float DEFAULT NULL,
  `module4` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`id`, `etudiant_id`, `module1`, `module2`, `module3`, `module4`) VALUES
(25, 36, 10, 20, 0, 0),
(26, 37, 10, 12, 1, 0),
(27, 38, 10, 12, 15, 15),
(28, 39, 10, 12, 15, 10),
(30, 36, 10, 20, 0, 0),
(31, 37, 10, 12, 1, 0),
(32, 38, 10, 12, 15, 15),
(33, 39, 10, 12, 15, 10),
(48, 37, 10, 12, 1, 0),
(49, 38, 10, 12, 15, 15),
(50, 39, 10, 12, 15, 10),
(52, 36, 10, 20, 0, 0),
(53, 37, 10, 12, 1, 0),
(54, 38, 10, 12, 15, 15),
(55, 39, 10, 12, 15, 10),
(62, 36, 10, 20, 0, 0),
(63, 37, 10, 12, 1, 0),
(64, 38, 10, 12, 15, 15),
(65, 39, 10, 12, 15, 10),
(66, 41, 12, 14, 0, 0),
(67, 46, 10, 11, 12, 13),
(69, 45, 10, 10, 10, 13),
(82, NULL, 12, 14, 13, 10),
(84, 40, 12, 13, 14, 10),
(85, 48, 11, 10, 7, 6),
(86, 47, 10, 11, 12, 11),
(87, 42, 10, 11, 12, 9),
(88, 49, 0, 12, 1, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD UNIQUE KEY `unique_telephone` (`telephone`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_etudiant` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
