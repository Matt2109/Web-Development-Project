-- Progettazione Web 
DROP DATABASE if exists fragassi_635287; 
CREATE DATABASE fragassi_635287; 
USE fragassi_635287; 
-- MySQL dump 10.13  Distrib 5.7.28, for Win64 (x86_64)
--
-- Host: localhost    Database: fragassi_635287
-- ------------------------------------------------------
-- Server version	5.7.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `exceptions`
--

DROP TABLE IF EXISTS `exceptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exceptions` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Restaurant` int(11) DEFAULT NULL,
  `ShiftType` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exceptions`
--

LOCK TABLES `exceptions` WRITE;
/*!40000 ALTER TABLE `exceptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `exceptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reservations` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Date` date DEFAULT NULL,
  `Hour` varchar(20) DEFAULT NULL,
  `ShiftType` int(11) DEFAULT NULL,
  `Restaurant` int(11) DEFAULT NULL,
  `Client` int(11) DEFAULT NULL,
  `Covers` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (13,'2026-01-31','13:00',0,1,2,2),(14,'2026-01-31','20:30',1,1,2,4),(15,'2026-01-31','12:30',0,1,6,5),(16,'2026-01-31','19:45',1,1,6,5),(17,'2026-01-31','13:00',0,1,7,6),(18,'2026-01-31','20:15',1,1,7,8),(19,'2026-01-31','12:45',0,1,8,3),(20,'2026-01-31','20:00',1,1,8,4),(21,'2026-01-31','12:30',0,1,9,10),(22,'2026-01-31','20:00',1,1,9,12),(23,'2026-01-31','13:15',0,1,10,6),(24,'2026-01-31','19:45',1,1,10,6);
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurants`
--

DROP TABLE IF EXISTS `restaurants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `restaurants` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  `MaxCovers` int(11) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `Type` int(11) DEFAULT NULL,
  `Owner` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurants`
--

LOCK TABLES `restaurants` WRITE;
/*!40000 ALTER TABLE `restaurants` DISABLE KEYS */;
INSERT INTO `restaurants` VALUES (1,'Locanda D\'Annunzio',150,'Pineto','Via Gabriele D\'Annunzio 1',1,1),(2,'Pizzetta',50,'Pineto','Via Gabriele D\'Annunzio 197',0,3),(3,'Ambaradam',50,'Roseto','Via Thaulero 7',3,3),(4,'Sushikaiten',100,'Montesilvano','Via Vincenzo Agostinone 1',2,4),(5,'Quebracho',100,'Pescara','Viale Regina Margherita 14',4,5),(6,'La Lanterna',150,'Silvi','Via Roma 27',5,5);
/*!40000 ALTER TABLE `restaurants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shifts` (
  `Restaurant` int(11) NOT NULL,
  `Opening` time DEFAULT NULL,
  `Closure` time DEFAULT NULL,
  `Days` varchar(7) DEFAULT NULL,
  `Type` int(11) NOT NULL,
  PRIMARY KEY (`Restaurant`,`Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
INSERT INTO `shifts` VALUES (1,'12:00:00','15:00:00','xoooooo',0),(1,'19:00:00','23:00:00','xxooooo',1),(2,'12:00:00','15:00:00','ooooooo',0),(2,'19:00:00','23:00:00','ooooooo',1),(3,'19:00:00','23:00:00','ooooooo',1),(4,'12:00:00','15:00:00','ooooooo',0),(4,'19:00:00','22:00:00','ooooooo',1),(5,'19:00:00','23:00:00','ooooooo',1),(6,'12:00:00','15:00:00','ooooooo',0),(6,'19:00:00','22:00:00','ooooooo',1);
/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `Type` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'gestore1','$2y$10$/SZLmjaYLyWelilT5blG1.839y/HcWDfy9OZcL7aD2vckX89fGZbO','Gestore','Prova',1),(2,'cliente1','$2y$10$Y13vhNYrE.2su2q1A0Pfdu6iITTnGxBus7D8DGuzV.NERKLBQAd1u','Cliente','Prova',0),(3,'gestore2','$2y$10$QH5CEwlhYyKYLWWxg.xx5O5RKNSDegKmBXMOabbM2VfmThOJaCWnW','Gestore','Prova',1),(4,'gestore3','$2y$10$8MQ4/1k8LCFDi80NUGgSjOASW3RxiDf9F5qDRgWCMC6MKFxysQyQu','Gestore','Prova',1),(5,'gestore4','$2y$10$3VdGGobrRQpJLskXEvsMbOoiYyHsNnCRFT65v12sMEKpzKxhONz3e','Gestore','Prova',1),(6,'cliente2','$2y$10$GVK5pLqjeoQUqSLdYZqrBeFVZCm8LGt0CI9KgRr7o9odkz6GNBXZy','Cliente','Prova',0),(7,'cliente3','$2y$10$3NFcMlBwJeH4G9/ngLWl6.LO/iR5XbBUbqlTSQHxiw0siU9Kl2R6e','Cliente','Prova',0),(8,'cliente4','$2y$10$MMYTr7trW7t4BHjohKhQquBkmH5otBB7Fzqd3flTrXkW9dwvgN72q','Cliente','Prova',0),(9,'cliente5','$2y$10$ckK6EASAZWmgIJka1.KDZO1q6cltmF4wHBbqVtIxKzfUj/H1EWEai','Cliente','Prova',0),(10,'cliente6','$2y$10$LNJCPt5.fjMS.veCo5gOi.rg5MGd1e9Ie9a03y5hCW.TQZsIhpgbG','Cliente','Prova',0),(11,'cliente7','$2y$10$/iFkiKs9zWiHhZ/fVkt.L.6hm3bfRQlhTjNFEOJBp9aFUHVluXE/a','Cliente','Prova',0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-05 16:16:54
