-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: eou_website
-- ------------------------------------------------------
-- Server version	5.7.11-log

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
-- Dumping data for table `Categories`
--

LOCK TABLES `Categories` WRITE;
/*!40000 ALTER TABLE `Categories` DISABLE KEYS */;
INSERT INTO `Categories` VALUES (1,'2016-04-27 01:57:31','2016-05-06 18:45:48',0,0,NULL,'General Discussion','Discuss EOU related topics that do not fit into a more specific category.','\0','\0'),(2,'2016-04-27 03:56:21','2016-05-06 20:04:55',0,0,NULL,'Help and Support','Need help? Ask for it here!','\0','\0'),(3,'2016-05-06 20:07:23','2016-05-06 20:07:23',0,0,NULL,'Academic Programs','A list of categories for academic programs.','','\0'),(4,'2016-05-06 20:08:21','2016-05-06 20:09:45',0,0,3,'Computer Science (CS)','Category for the Computer Science Program.','','\0'),(5,'2016-05-06 20:09:32','2016-05-06 20:09:59',0,0,3,'Multimedia (MM)','Category for the Multimedia Program.','','\0');
/*!40000 ALTER TABLE `Categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Posts`
--

-- REMOVED

--
-- Dumping data for table `Topics`
--

-- REMOVED

--
-- Dumping data for table `UserLevels`
--

LOCK TABLES `UserLevels` WRITE;
/*!40000 ALTER TABLE `UserLevels` DISABLE KEYS */;
INSERT INTO `UserLevels` VALUES (0,'2016-04-19 00:03:23','2016-04-19 00:03:23',0,0,'User','Can create topics and posts.  Can edit topics and posts that they created.'),(1,'2016-04-19 00:05:12','2016-04-27 03:43:00',0,0,'Moderator','Can lock, sticky, and move posts.  Can edit all topics and posts.  Can access user moderation menu.'),(2,'2016-04-19 00:06:48','2016-04-19 00:06:48',0,0,'Administrator','Can do everything Operators can do, as well as create and edit categories.');
/*!40000 ALTER TABLE `UserLevels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Users`
--

-- REMOVED
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-08 17:24:41
