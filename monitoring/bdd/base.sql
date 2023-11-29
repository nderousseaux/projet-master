-- -------------------------------------------------------------
-- TablePlus 5.6.2(516)
--
-- https://tableplus.com/
--
-- Database: monitoring
-- Generation Time: 2023-11-28 15:58:52.5730
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
) ENGINE=InnoDB AUTO_INCREMENT=6206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

CREATE TABLE `metrique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(128) NOT NULL,
  `unite` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;;

INSERT INTO `composant` (`id`, `nom`) VALUES
(1, 'Machine n°1'),
(2, 'Machine n°2'),
(3, 'Machine n°3'),
(4, 'Machine n°4');

INSERT INTO `metrique` (`id`, `nom`, `unite`) VALUES
(1, 'Up', 'bool'),
(11, 'CPU Usage', 'int');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;