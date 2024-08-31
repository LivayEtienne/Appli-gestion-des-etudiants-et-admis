-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 31 août 2024 à 14:19
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
-- Base de données : `gestion`
--

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
(67, 46, 10, 11, 0, 0),
(69, 45, 10, 10, 10, 0),
(82, NULL, 12, 14, 13, 10),
(84, 40, 12, 13, 14, 10),
(85, 48, 11, 10, 7, 6),
(86, 47, 10, 11, 12, 11),
(87, 42, 10, 11, 12, 9),
(88, 49, 0, 12, 1, 2),
(89, 52, 12, 12, 13, 14),
(90, 50, 11, 13, 1, 3);

--
-- Index pour les tables déchargées
--

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
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

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
