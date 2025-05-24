/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: wecondb
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `chat_pools`
--

DROP TABLE IF EXISTS `chat_pools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_pools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pool_name` varchar(255) DEFAULT NULL,
  `pool_password` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_pools`
--

LOCK TABLES `chat_pools` WRITE;
/*!40000 ALTER TABLE `chat_pools` DISABLE KEYS */;
INSERT INTO `chat_pools` VALUES
(1,'Weconnect Official Pool','123','Tejas','2025-05-17 09:25:16'),
(2,'Heyy it\\\'s bellas pool psw: 999','999','Bella','2025-05-17 09:36:07'),
(4,'Sister pool','1234','Tejas','2025-05-17 11:10:18'),
(5,'Priyanka','priya','Priyanka','2025-05-17 11:12:59'),
(6,'Let\\\'s discuss space and stars','123','Kevin','2025-05-17 15:07:26'),
(7,'Skincare links','skin','Karmel','2025-05-17 15:18:46'),
(8,'Andro Coder YT','coder','Andro','2025-05-24 11:04:51');
/*!40000 ALTER TABLE `chat_pools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pool_id` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES
(1,1,'Tejas','Hello there','2025-05-17 15:55:29'),
(2,1,'Tejas','Welcome to Weconnect !!! lets start this project, and chat anonymously from anywhere... ','2025-05-17 15:56:27'),
(3,1,'Bella','Yess!!!... It\'s working guys','2025-05-17 15:57:58'),
(4,2,'Bella','Helllooooooo','2025-05-17 15:58:28'),
(5,1,'Bella','Hello bella','2025-05-17 16:31:12'),
(6,1,'Tejas','Hello bella','2025-05-17 16:31:37'),
(7,1,'Tejas','Hope you liked this design','2025-05-17 16:33:10'),
(8,1,'Tejas','For testing purposes I am checking messages','2025-05-17 16:33:36'),
(9,1,'Bella','Yeah it\'s working...','2025-05-17 16:34:54'),
(10,4,'Tejas','Hello','2025-05-17 16:41:08'),
(11,4,'Priyanka','Hi','2025-05-17 16:41:15'),
(12,4,'Tejas','Jai mata di','2025-05-17 16:41:21'),
(13,4,'Priyanka','He hee','2025-05-17 16:41:26'),
(14,1,'Tejas','Huehuehuehuehuehue','2025-05-17 16:42:16'),
(15,5,'Priyanka','Hi','2025-05-17 16:43:32'),
(16,5,'Tejas','Hi','2025-05-17 16:43:47'),
(17,5,'Tejas','Jai mata di','2025-05-17 20:12:44'),
(18,1,'Megha','Hello','2025-05-17 20:31:11'),
(19,1,'Megha','I\'m megha','2025-05-17 20:31:15'),
(20,1,'Karmel','Hi everyone!','2025-05-17 20:32:44'),
(21,1,'Karmel','I\'m karmel','2025-05-17 20:32:54'),
(22,1,'Kevin','Kevin here','2025-05-17 20:35:01'),
(23,4,'Tejas','hulelelelelele','2025-05-17 20:56:27'),
(24,1,'Aniket','Hiiii','2025-05-18 10:29:58'),
(25,1,'Tejas','hwllo fANAS','2025-05-18 10:30:05'),
(26,1,'Tejas','hello','2025-05-24 13:41:32'),
(27,1,'Tejas','wassup','2025-05-24 13:41:45'),
(28,1,'Andro','Hello Guys','2025-05-24 13:43:11'),
(29,1,'Andro','Hope everything is good','2025-05-24 13:51:46'),
(30,1,'Andro','😁😁','2025-05-24 13:52:38'),
(31,1,'Andro','Testing','2025-05-24 13:55:53'),
(32,1,'Andro','Hello','2025-05-24 15:17:56'),
(33,1,'Tejas','Hello','2025-05-24 15:28:46'),
(34,1,'Andro','Helo Tejas','2025-05-24 15:29:05'),
(35,1,'Andro','23','2025-05-24 15:29:21'),
(36,1,'Andro','Jejeje','2025-05-24 15:29:26'),
(37,1,'Andro','Jdjdjd','2025-05-24 15:29:28'),
(38,1,'Andro','Hhhh','2025-05-24 15:29:32'),
(39,1,'Tejas','ggg','2025-05-24 15:41:25'),
(40,1,'Andro','Hello','2025-05-24 16:29:52'),
(41,8,'Andro','Hello guys','2025-05-24 16:35:23'),
(42,8,'Andro','Wywyywywy','2025-05-24 16:42:26'),
(43,8,'Andro','Hello','2025-05-24 16:42:29'),
(44,8,'Andro','Syysys','2025-05-24 16:42:53'),
(45,8,'Andro','Gyt','2025-05-24 16:46:01'),
(46,2,'Tejas','Hi bella','2025-05-24 17:06:51'),
(47,2,'Tejas','How are you','2025-05-24 17:07:00'),
(48,2,'Andro','I am fine tejas:)','2025-05-24 17:07:21');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Demmy','$2y$10$.NBLmsUY.19isiRmAhXcN.dlJ4Dh7phOXfHOXV6CQfFaveA7bQrSO'),
(2,'Tejas','Tkapp@90'),
(3,'Priya','Priya@90'),
(4,'Kevin','Kevin@90'),
(5,'Judo','Judo@90'),
(6,'Tweek','Tweek@999'),
(7,'Karmel','9090'),
(8,'Kivy','0000'),
(9,'admin','9090'),
(10,'Meta','meta@00'),
(11,'Bella','bella@123'),
(12,'Priyanka','priya'),
(13,'Phillip','Philip@99'),
(14,'Merkin','Merkin@99'),
(15,'Merkin','Merkin@99'),
(16,'Mikai','Merkin@99'),
(17,'Mikoo','Merkin@99'),
(18,'Twili','ttl00'),
(19,'Megha','immeg'),
(20,'Aniket','1234'),
(21,'Andro','coder');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-05-24 22:31:16
