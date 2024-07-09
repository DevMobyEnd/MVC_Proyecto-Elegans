-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_pruebita
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_usuarios`
--

DROP TABLE IF EXISTS `tb_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `Gmail` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `numero_documento` varchar(50) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Gmail_UNIQUE` (`Gmail`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuarios`
--

LOCK TABLES `tb_usuarios` WRITE;
/*!40000 ALTER TABLE `tb_usuarios` DISABLE KEYS */;
INSERT INTO `tb_usuarios` VALUES (3,'chanchitofeliz437@gmail.com','$2y$10$oBFVDrji9JjxHSC/BA8wYuvvTKTFHDbqBN9cy9M2O91qgX3dwc1cO','jhonny alexander','gonsalez torres','1116233418','@Chanchito77','2024-06-11 16:43:34','2024-06-11 16:43:34'),(6,'marioGambo@gmail.com','$2y$10$mghX8Sjdmm/HIofvdqnwruuku9VYf4KaojyQIRsax33c8qOgVqy2u','Luis Mario ','Gamboa Torres','1125484686','@Mario77','2024-06-11 16:47:13','2024-06-11 16:47:13'),(7,'sebastian123@gmail.com','$2y$10$EsT2fNvcypFLhZ.jDzGVTukVqMlK5x7fGbR3fPMTDL2KD3Nr8O0mC','sebastian','Notrabajoenclase','12345678','sebastian123','2024-06-11 16:56:15','2024-06-11 16:56:15'),(8,'lorotata@gmail.com','$2y$10$QzH0H2BgB5hfqZ5TSsWV6.fwcGQ.Tegj1AeKrREyrjUm.G3Vcokl.','jose toto','sarmiento loras','1234567891','@loro','2024-06-11 17:02:09','2024-06-11 17:02:09'),(9,'neymarjr@gmail.com','$2y$10$bkeO6EaEcnzZE4zbZX.bAeUmJhGq64kRnIn6PtJvfG/MblBj4yaOa','sapa toto','tete neymar','1234589645','@Neymarjr','2024-06-11 17:12:33','2024-06-11 17:12:33');
/*!40000 ALTER TABLE `tb_usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-11 12:40:06
