-- MySQL dump 10.13  Distrib 5.1.54, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: schooladmit
-- ------------------------------------------------------
-- Server version	5.1.54-1ubuntu4

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
-- Table structure for table `state`
--

DROP TABLE IF EXISTS `state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state` (
  `State_Id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `State` varchar(100) NOT NULL,
  `country` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`State_Id`),
  UNIQUE KEY `State_Id` (`State_Id`),
  KEY `country` (`country`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `state`
--

LOCK TABLES `state` WRITE;
/*!40000 ALTER TABLE `state` DISABLE KEYS */;
INSERT INTO `state` VALUES (1,'Andaman Nicobar Islands',1),(2,'Andhra Pradesh',1),(3,'Arunachal Pradesh',1),(4,'Assam',1),(5,'Bihar',1),(6,'Chandigarh',1),(7,'Chhattisgarh',1),(8,'Dadra & Nagar Haveli',1),(9,'Daman & Diu',1),(10,'Delhi',1),(11,'Goa',1),(12,'Gujarat',1),(13,'Haryana',1),(14,'Himachal Pradesh',1),(15,'Jammu & Kashmir',1),(16,'Jharkhand',1),(17,'Karnataka',1),(18,'Kerala',1),(19,'Lakshadweep',1),(20,'Madhya Pradesh',1),(21,'Maharashtra',1),(22,'Manipur',1),(23,'Meghalaya',1),(24,'Mizoram',1),(25,'Nagaland',1),(26,'Odisha',1),(27,'Puducherry',1),(28,'Punjab',1),(29,'Rajasthan',1),(30,'Sikkim',1),(31,'Tamil Nadu',1),(32,'Tripura',1),(33,'Uttar Pradesh',1),(34,'Uttarakhand',1),(35,'West Bengal',1),(36,'Orissa',1);
/*!40000 ALTER TABLE `state` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-05-11  2:45:53
