-- -------------------------------------------------------------
-- TablePlus 5.6.0(514)
--
-- https://tableplus.com/
--
-- Database: monitoring
-- Generation Time: 2023-11-23 20:46:02.0100
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `composant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valeur` decimal(10,2) NOT NULL,
  `composant_id` int NOT NULL,
  `metrique_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `composant_id` (`composant_id`),
  KEY `metrique_id` (`metrique_id`),
  CONSTRAINT `log_ibfk_1` FOREIGN KEY (`composant_id`) REFERENCES `composant` (`id`),
  CONSTRAINT `log_ibfk_2` FOREIGN KEY (`metrique_id`) REFERENCES `metrique` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `metrique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `unite` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

INSERT INTO `composant` (`id`, `nom`) VALUES
(1, 'Serveur 1'),
(2, 'Serveur 2'),
(3, 'Serveur 3'),
(4, 'Serveur 4');

INSERT INTO `metrique` (`id`, `nom`, `unite`) VALUES
(1, 'Temps de réponse', 'ms'),
(2, 'Utilisation du processeur', '%'),
(3, 'Utilisation de la mémoire', '%'),
(4, 'Usage du disque', 'Mbit/s'),
(5, 'Température', '°C'),
(6, 'Traffic', 'Mbits/s'),
(7, 'Débit', 'Mbit/s');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;