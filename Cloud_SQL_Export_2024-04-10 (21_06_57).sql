-- MySQL dump 10.13  Distrib 8.0.31, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: B1RD
-- ------------------------------------------------------
-- Server version	8.0.31-google

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `B1RD`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `B1RD` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `B1RD`;

--
-- Table structure for table `AdoptionApplication`
--

DROP TABLE IF EXISTS `AdoptionApplication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `AdoptionApplication` (
  `ApplicationID` int NOT NULL AUTO_INCREMENT,
  `BirdID` int DEFAULT NULL,
  `UserID` int DEFAULT NULL,
  `ApplicationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `AdoptionStatus` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ApplicationID`),
  KEY `BirdID` (`BirdID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `AdoptionApplication_ibfk_1` FOREIGN KEY (`BirdID`) REFERENCES `Bird` (`BirdID`),
  CONSTRAINT `AdoptionApplication_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AdoptionApplication`
--

LOCK TABLES `AdoptionApplication` WRITE;
/*!40000 ALTER TABLE `AdoptionApplication` DISABLE KEYS */;
/*!40000 ALTER TABLE `AdoptionApplication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Bird`
--

DROP TABLE IF EXISTS `Bird`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Bird` (
  `BirdID` int NOT NULL AUTO_INCREMENT,
  `ShelterID` int DEFAULT NULL,
  `Species` varchar(50) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Age` int DEFAULT NULL,
  `MedicalHistory` text,
  `AdoptionStatus` varchar(20) DEFAULT NULL,
  `Behavior` text,
  `Name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`BirdID`),
  KEY `ShelterID` (`ShelterID`),
  CONSTRAINT `Bird_ibfk_1` FOREIGN KEY (`ShelterID`) REFERENCES `Shelter` (`ShelterID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Bird`
--

LOCK TABLES `Bird` WRITE;
/*!40000 ALTER TABLE `Bird` DISABLE KEYS */;
INSERT INTO `Bird` VALUES (1,1,'Parrot','African Grey',5,'Healthy','Available','Friendly and talkative','Charlie'),(2,1,'Macaw','Blue and Gold',3,'Recovered from injury','Available','Playful and energetic','Rio'),(3,2,'Cockatiel','Normal Gray',2,'Healthy','Available','Shy but affectionate','Sunny'),(4,2,'Lovebird','Peach-faced',1,'Healthy','Available','Curious and playful','Pepper'),(5,1,'Conure','Sun Conure',11,'Healthy except for a foot problem','Not Available','Energetic and playful','Mithu');
/*!40000 ALTER TABLE `Bird` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Shelter`
--

DROP TABLE IF EXISTS `Shelter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Shelter` (
  `ShelterID` int NOT NULL AUTO_INCREMENT,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ShelterID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Shelter`
--

LOCK TABLES `Shelter` WRITE;
/*!40000 ALTER TABLE `Shelter` DISABLE KEYS */;
INSERT INTO `Shelter` VALUES (1,'123-456-7890','Happy Wings Aviary','123 Main St, Anytown, USA'),(2,'987-654-3210','Feathered Friends Rescue','456 Elm St, Another Town, USA');
/*!40000 ALTER TABLE `Shelter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `User` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `UserPassword` varchar(100) DEFAULT NULL,
  `UserType` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'John Smith','555-123-4567','john@example.com','password123','adopter'),(2,'Sarah Johnson','555-987-6543','sarah@example.com','abc123','shelter'),(3,'Guneet Robloxian','913-313-5369','guneet@gmail.com','76NoHomework38','adopter');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-04-11  2:10:27
