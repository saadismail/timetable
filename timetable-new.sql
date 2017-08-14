-- MySQL dump 10.16  Distrib 10.1.25-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: timetable
-- ------------------------------------------------------
-- Server version	10.1.25-MariaDB

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
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `batch` int(4) NOT NULL,
  `email` varchar(18) NOT NULL,
  `subjects` varchar(50) NOT NULL,
  `sections` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `short` varchar(50) NOT NULL,
  `code` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'Introduction to Computing','ITC','CS101'),(2,'Introduction to Computing Lab','ITC Lab','CL101'),(3,'Calculus - I','CAL I','MT101'),(4,'Basic Electronics','BE','EE182'),(5,'English Language','ENG','SS102'),(6,'English Language Lab','ENG','SL102'),(7,'Islamic and Religious Studies','IRS','SS111'),(8,'Ethics','Ethics','SS203'),(9,'Computer Programming','CP','CS103'),(10,'Computer Programming Lab','CP-Lab','CL103'),(11,'Digital Logic Design','DLD','EE227'),(12,'Digital Logic Design','DLD-Lab','EL227'),(13,'Calculus-II','Cal-II','MT115'),(14,'English Composition','Eng Comp.','SS122'),(15,'Pakistan Studies','Pk. Study','SS113'),(16,' Linear Algebra','LA','MT104'),(17,' Discrete Structures','Discrete','CS211'),(18,' Data Structures','DS','CS201'),(19,' Data Structures Lab','DS-Lab','CL201'),(20,' Comp. Organization & Assembly Lang.','COAL','EE213'),(21,' Comp. Organization & Assembly Lang. Lab','COAL Lab','EL213'),(22,' Marketing Management','MM','MG220'),(23,' Business Communication - I','Bcomm-I','SS223'),(24,' Psychology','Psych','SS118'),(25,' Database Systems Lab','DS-Lab','CL203'),(26,' Operating Systems Lab','OS-Lab','CL205'),(27,' Computer Networks Lab','CN-Lab','CL307'),(28,' Object Oriented Analysis & Design Lab','OOAD-Lab','CL309'),(29,' Database Systems','DS','CS203'),(30,' Operating Systems','OS','CS205'),(31,' Human Computer Interaction','HCI','CS422'),(32,' Theory of Automata','TOA','CS301'),(33,' Design & Analysis of Algorithms','Algo','CS302'),(34,' Software Engineering','SE','CS303'),(35,' Computer Networks','CN','CS307'),(36,' Object Oriented Analysis & Design','OOAD','CS309'),(37,' Artificial Intelligence','AI','CS401'),(38,' Professional Issues in IT','PIT','CS449'),(39,' Project - I','FYP-I','CS491'),(40,' Project - II','FYP-II','CS492'),(41,' Probability & Statistics','Prob','MT206'),(42,' Sociology','Socio','SS127'),(43,' Micro Economics','ME','SS135'),(44,' Business Communication - II','Bcomm-II','SS309'),(45,' Operations Research','OR','MT303'),(46,' Communication for Managers','CFM','MG335'),(47,' Entrepreneurship','EP','MG414'),(48,' Accounting and Information System','AIS','MG448'),(49,' Introduction to Cloud Computing','ICC','CS499'),(50,' Bio-Informatics','BI','CS508'),(51,' Design Patterns','DP','CS540'),(52,' Special Topics in Computer Science','STCS','CS547'),(53,' Information Retrieval & Text Mining','IRTM','CS567'),(54,' Intro. to Software Project Management','SPM','CS450'),(55,' Data Science','Dsci','CS481'),(56,' Computer Architecture','CA','EE204'),(57,' Network Programming','NP','CS404'),(58,' Web Programming','WP','CS406'),(59,' Network Security','NS','CS411'),(60,' Information Processing Techniques','IPT','CS423'),(61,' Software for Mobile Devices','SMD','CS440');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vercode`
--

DROP TABLE IF EXISTS `vercode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vercode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentID` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vercode`
--

LOCK TABLES `vercode` WRITE;
/*!40000 ALTER TABLE `vercode` DISABLE KEYS */;
/*!40000 ALTER TABLE `vercode` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-14  1:45:25
