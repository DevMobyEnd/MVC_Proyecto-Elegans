-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: db_elegans
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.24.04.1

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
-- Table structure for table `estadisticas_usuario`
--

DROP TABLE IF EXISTS `estadisticas_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estadisticas_usuario` (
  `usuario_id` bigint NOT NULL,
  `total_solicitudes` int DEFAULT '0',
  `solicitudes_aceptadas` int DEFAULT '0',
  `solicitudes_rechazadas` int DEFAULT '0',
  `likes_recibidos` int DEFAULT '0',
  `dislikes_recibidos` int DEFAULT '0',
  `mensajes_enviados_global` int DEFAULT '0',
  `mensajes_enviados_privado` int DEFAULT '0',
  PRIMARY KEY (`usuario_id`),
  CONSTRAINT `estadisticas_usuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadisticas_usuario`
--

LOCK TABLES `estadisticas_usuario` WRITE;
/*!40000 ALTER TABLE `estadisticas_usuario` DISABLE KEYS */;
INSERT INTO `estadisticas_usuario` VALUES (3,6,4,0,4,0,4,0),(15,2,2,0,2,0,2,0),(16,2,2,0,0,0,7,15),(28,1,1,0,0,0,0,0),(36,9,8,0,2,0,6,0),(37,2,2,0,0,0,0,0),(38,3,3,0,2,0,3,1),(39,6,0,0,0,0,0,1),(43,1,1,0,0,0,0,0);
/*!40000 ALTER TABLE `estadisticas_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membresias`
--

DROP TABLE IF EXISTS `membresias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membresias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `duracion` int DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membresias`
--

LOCK TABLES `membresias` WRITE;
/*!40000 ALTER TABLE `membresias` DISABLE KEYS */;
/*!40000 ALTER TABLE `membresias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensajes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `emisor_id` bigint DEFAULT NULL,
  `receptor_id` bigint DEFAULT NULL,
  `contenido` text,
  `fecha_envio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `es_global` tinyint(1) DEFAULT '0',
  `likes` int DEFAULT '0',
  `dislikes` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `emisor_id` (`emisor_id`),
  KEY `receptor_id` (`receptor_id`),
  KEY `idx_fecha_envio` (`fecha_envio`),
  CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`emisor_id`) REFERENCES `tb_usuarios` (`id`),
  CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`receptor_id`) REFERENCES `tb_usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes`
--

LOCK TABLES `mensajes` WRITE;
/*!40000 ALTER TABLE `mensajes` DISABLE KEYS */;
INSERT INTO `mensajes` VALUES (2,17,16,'Mensaje de prueba','2024-09-23 04:42:26',0,0,0),(3,17,16,'Mensaje de prueba','2024-09-23 04:44:36',0,0,0),(4,17,16,'Hola como estan ','2024-09-23 05:07:37',0,0,0),(5,17,17,'Hola','2024-09-23 07:30:34',1,0,1),(6,17,16,'Hola como estas Starbucks','2024-09-23 07:32:38',1,0,0),(7,17,3,'Hola como estas','2024-09-23 07:34:39',0,0,0),(8,17,16,'Hola a todos','2024-09-23 07:43:44',1,0,0),(9,16,16,'Hola como estas espero que bien mani como va todo','2024-09-23 07:44:26',1,0,0),(10,16,16,'Hola','2024-09-23 07:57:37',1,0,0),(11,16,NULL,'Hola','2024-09-23 07:58:38',1,0,0),(12,16,NULL,'Hola','2024-09-23 07:58:38',1,0,0),(13,16,NULL,'Hola','2024-09-23 07:59:12',1,0,0),(14,16,16,'Hola','2024-09-23 08:00:15',1,0,0),(15,16,16,'Hola como estan','2024-09-23 08:13:03',1,0,0),(16,17,16,'Bien coño que estamos bien','2024-09-23 08:14:20',1,0,0),(17,17,NULL,'Hola','2024-09-23 08:28:42',1,1,0),(18,17,16,'Hola a todos','2024-09-23 08:37:59',1,1,0),(19,17,16,'Hola como estan','2024-09-23 18:57:19',1,1,0),(20,17,10,'hello','2024-09-23 19:08:35',0,0,0),(21,17,10,'hola','2024-09-23 19:08:41',0,0,0),(22,17,NULL,'Hola como estan','2024-09-23 19:56:34',1,1,0),(23,17,16,'Hola Broo','2024-09-27 20:14:20',0,0,0),(24,17,15,'Hola LeonidasOro','2024-09-27 20:21:05',0,0,0),(25,15,NULL,'Hola a todos como estan Mis Razas','2024-09-27 21:35:30',1,0,0),(26,15,NULL,'Bien bien','2024-09-27 21:46:03',1,2,0),(27,17,15,'hola','2024-10-02 22:17:48',1,1,0),(28,36,NULL,'Hola gente como estan','2024-10-22 02:14:24',1,1,0),(31,36,NULL,'Hola gente','2024-10-22 02:22:36',1,0,0),(32,36,NULL,'Hola como estan','2024-10-22 02:25:26',1,0,0),(34,36,NULL,'Hola como estan','2024-10-22 02:50:30',1,0,0),(35,36,NULL,'Gente','2024-10-22 02:50:50',1,0,0),(36,36,NULL,'Hola como estamn','2024-10-22 03:05:26',1,1,0),(37,3,NULL,'Hola amigos les gusta la musica que esta sonando','2024-10-22 19:01:45',1,1,0),(38,11,NULL,'Hey mano como vamos bien o que','2024-10-22 19:08:10',1,0,0),(39,3,NULL,'Bien bien y usted Seor0@gmail.com','2024-10-22 19:08:42',1,1,0),(40,11,NULL,'Okey y que música vas a poner','2024-10-22 19:10:20',1,0,0),(41,3,NULL,'No se voy a ver cual solicito','2024-10-22 19:10:42',1,1,0),(42,3,NULL,'Hi','2024-10-22 19:40:39',1,1,0),(43,38,NULL,'Hola como estam','2024-10-23 14:17:31',1,0,0),(44,38,NULL,'Hola','2024-10-23 17:17:12',1,1,0),(45,38,NULL,'Hi','2024-11-13 13:52:12',1,1,0),(46,40,NULL,'hola como estan?','2025-01-08 05:31:47',1,0,1),(47,40,NULL,'hola como estan','2025-01-08 05:32:13',1,0,0),(48,40,NULL,'hola','2025-01-08 05:37:08',1,0,0),(49,40,NULL,'Hola putitos','2025-01-08 05:48:49',1,0,0),(50,40,NULL,'a','2025-01-08 06:07:14',1,1,0),(51,16,41,'hola como esas','2025-01-08 08:07:29',0,0,0),(52,16,15,'hola como estas','2025-01-08 08:15:07',0,0,0),(53,16,4,'d','2025-01-08 08:15:48',0,0,0),(54,16,4,'d','2025-01-08 08:15:55',0,0,0),(55,16,4,'d','2025-01-08 08:15:57',0,0,0),(56,16,20,'D','2025-01-08 08:29:37',0,0,0),(57,16,41,'dsf','2025-01-08 08:42:29',0,0,0),(58,16,23,'d','2025-01-08 08:58:49',0,0,0),(59,16,23,'d','2025-01-08 09:01:28',0,0,0),(60,16,23,'d','2025-01-08 09:01:31',0,0,0),(61,16,23,'aaaa','2025-01-08 09:01:39',0,0,0),(62,16,NULL,'sfsdf','2025-01-08 09:01:48',0,0,0),(63,16,NULL,'asds','2025-01-08 09:01:53',0,0,0),(64,16,41,'sa','2025-01-08 09:09:08',0,0,0),(65,16,NULL,'sa','2025-01-08 09:16:51',0,0,0),(66,38,NULL,'hola','2025-01-12 02:58:28',0,0,0),(67,39,40,'hola como estan','2025-01-29 12:14:25',0,0,0);
/*!40000 ALTER TABLE `mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,'Canal_de_Dialogo','En este permiso se podra aseder al canal de dialogo'),(2,'Panel_de_DJ','En este permiso el DJ Podra Gestionar las solicitudes de los Usuarios Naturales ');
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reacciones_mensajes`
--

DROP TABLE IF EXISTS `reacciones_mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reacciones_mensajes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `mensaje_id` bigint DEFAULT NULL,
  `usuario_id` bigint DEFAULT NULL,
  `tipo_reaccion` enum('like','dislike') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_reaccion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mensaje_id` (`mensaje_id`,`usuario_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `reacciones_mensajes_ibfk_1` FOREIGN KEY (`mensaje_id`) REFERENCES `mensajes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reacciones_mensajes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reacciones_mensajes`
--

LOCK TABLES `reacciones_mensajes` WRITE;
/*!40000 ALTER TABLE `reacciones_mensajes` DISABLE KEYS */;
INSERT INTO `reacciones_mensajes` VALUES (1,5,17,'dislike','2024-09-23 07:30:52'),(6,17,17,'like','2024-09-23 08:29:12'),(11,18,17,'like','2024-09-23 08:38:03'),(18,19,17,'like','2024-09-23 18:57:28'),(19,22,17,'like','2024-09-23 19:56:51'),(60,26,15,'like','2024-09-27 21:50:00'),(63,26,17,'like','2024-09-27 21:54:08'),(66,27,17,'like','2024-10-02 22:17:55'),(69,28,36,'like','2024-10-22 02:14:30'),(70,36,3,'like','2024-10-22 18:33:52'),(71,37,11,'like','2024-10-22 19:07:01'),(72,39,11,'like','2024-10-22 19:08:49'),(73,41,11,'like','2024-10-22 19:10:54'),(76,42,38,'like','2024-10-23 17:17:17'),(77,44,38,'like','2024-11-13 13:51:54'),(80,45,38,'like','2024-11-13 13:52:19'),(81,46,40,'dislike','2025-01-08 06:05:05'),(83,50,40,'like','2025-01-08 06:18:43');
/*!40000 ALTER TABLE `reacciones_mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permiso`
--

DROP TABLE IF EXISTS `role_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permiso` (
  `role_id` int NOT NULL,
  `permiso_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`permiso_id`),
  KEY `permiso_id` (`permiso_id`),
  CONSTRAINT `role_permiso_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permiso_ibfk_2` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permiso`
--

LOCK TABLES `role_permiso` WRITE;
/*!40000 ALTER TABLE `role_permiso` DISABLE KEYS */;
INSERT INTO `role_permiso` VALUES (3,1),(5,2);
/*!40000 ALTER TABLE `role_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (3,'usuario natural'),(4,'admin'),(5,'DJ');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes_musica`
--

DROP TABLE IF EXISTS `solicitudes_musica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_musica` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint DEFAULT NULL,
  `spotify_track_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nombre_cancion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `imagen_url` varchar(512) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('pendiente','aceptada','rechazada') COLLATE utf8mb4_general_ci DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nombre_artista` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_track_request_per_user` (`usuario_id`,`spotify_track_id`),
  KEY `idx_spotify_track_id` (`spotify_track_id`),
  KEY `idx_usuario_id` (`usuario_id`),
  KEY `idx_estado` (`estado`),
  CONSTRAINT `solicitudes_musica_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes_musica`
--

LOCK TABLES `solicitudes_musica` WRITE;
/*!40000 ALTER TABLE `solicitudes_musica` DISABLE KEYS */;
INSERT INTO `solicitudes_musica` VALUES (1,16,'76NtMPMpA6fuUH4euMYZoD','Buenos Genes','https://i.scdn.co/image/ab67616d0000b2730579a9f20ec52281f4447c73','aceptada','2024-09-05 15:36:23','Rels B'),(3,16,'7g8YaUQABMal0zWe7a2ijz','Pa Mí - Remix','https://i.scdn.co/image/ab67616d0000b273b9242ba03ab231608f123e06','aceptada','2024-09-05 20:27:49','Dalex'),(4,15,'74hxp59kFtc3Ndkb7n1IaC','Into It','https://i.scdn.co/image/ab67616d0000b273601eb33454f13f26db9084e4','aceptada','2024-09-05 22:13:51','Chase Atlantic'),(5,3,'7p8JhjfckxcuOF0Weixvfa','¿Cómo Hacer Para Olvidarte?','https://i.scdn.co/image/ab67616d0000b2738195fefecfd1ba1918971352','aceptada','2024-09-10 02:31:58','Manuel Medrano'),(6,15,'5NR1LYf16E6K5t5AeSYP8P','Hagamos Lo Que Diga El Corazón','https://i.scdn.co/image/ab67616d0000b27351c9409f7962e3aee55c74c6','aceptada','2024-09-13 03:59:17','Grupo Niche'),(7,3,'4HwDCXsMBC7SUdp2WT4MZP','Into It','https://i.scdn.co/image/ab67616d0000b2735a0c2870f4f309e382d1fad6','aceptada','2024-10-02 22:03:25','Chase Atlantic'),(8,36,'4OwhwvKESFtuu06dTgct7i','Tiroteo - Remix','https://i.scdn.co/image/ab67616d0000b2735a048cbaa3385de9f723c699','aceptada','2024-10-20 18:31:58','Marc Seguí'),(9,36,'5kd6nThD2Gxdoxc6WFyGdB','GOOGLE ME','https://i.scdn.co/image/ab67616d0000b2730a4d2dc72196c8b02b8b5783','aceptada','2024-10-21 01:53:59','Cochise'),(10,36,'7nc7mlSdWYeFom84zZ8Wr8','Tell Em','https://i.scdn.co/image/ab67616d0000b273e60b56aa1e58fe76240a101b','aceptada','2024-10-21 04:12:47','Cochise'),(11,36,'5VjQTikdqkiKi1XtXTVdc8','Save Me','https://i.scdn.co/image/ab67616d0000b273008ab78467a893675f88ad2a','aceptada','2024-10-21 04:26:16','Chief Keef'),(12,36,'1FKG2wgJ75wS9MFNVZFiWd','POCKET ROCKET','https://i.scdn.co/image/ab67616d0000b273efe0cfba604f12fddb637588','aceptada','2024-10-21 04:28:57','Cochise'),(13,36,'0J1kF6VdorvmLjmRJObiV1','Hummingbird','https://i.scdn.co/image/ab67616d0000b273687ed0f52df9d126a72b334d','aceptada','2024-10-21 04:33:57','Metro Boomin'),(14,36,'4EGt2Y8GqjVzjXi39UOQfg','Hummingbird (Metro Boomin & James Blake)','https://i.scdn.co/image/ab67616d0000b2734a3cdc1e547b3d275d97cff8','aceptada','2024-10-21 04:34:09','Metro Boomin'),(15,36,'3bQ6ECgoGPgT6cvjOmMPAG','AURA - Slowed','https://i.scdn.co/image/ab67616d0000b273bb7e0eb64fae8dbd5b9d2a12','aceptada','2024-10-21 04:40:06','Ogryzek'),(16,36,'1xs8bOvm3IzEYmcLJVOc34','Rush','https://i.scdn.co/image/ab67616d0000b2734828f4b04d92d6641be98cc5','pendiente','2024-10-21 04:42:39','Ayra Starr'),(17,37,'4zrKN5Sv8JS5mqnbVcsul7','Celestial','https://i.scdn.co/image/ab67616d0000b273c18194a4022ec44507f7b248','aceptada','2024-10-21 17:13:23','Ed Sheeran'),(21,28,'6HexNTb392JS071DoTGo0y','Hummingbird (Metro Boomin & James Blake)','https://i.scdn.co/image/ab67616d0000b2736ed9aef791159496b286179f','aceptada','2024-10-22 18:01:49','Metro Boomin'),(24,3,'0Dc7J9VPV4eOInoxUiZrsL','Don\'t Tell \'Em','https://i.scdn.co/image/ab67616d0000b273262c8e955ad5c60b1d0abf53','pendiente','2024-10-22 18:22:42','Jeremih'),(25,3,'7nc7mlSdWYeFom84zZ8Wr8','Tell Em','https://i.scdn.co/image/ab67616d0000b273e60b56aa1e58fe76240a101b','pendiente','2024-10-22 18:22:52','Cochise'),(26,3,'4NpDZPwSXmL0cCTaJuVrCw','Birthday Sex','https://i.scdn.co/image/ab67616d0000b27318b7e2ca5058f16a12059044','aceptada','2024-10-22 18:30:11','Jeremih'),(27,3,'3pXF1nA74528Edde4of9CC','Don\'t','https://i.scdn.co/image/ab67616d0000b273d5f3cea8affdca01a0dc754f','aceptada','2024-10-22 18:32:19','Bryson Tiller'),(28,37,'0X2bh8NVQ8svDQIn2AdCbW','Consume (feat. Goon Des Garcons)','https://i.scdn.co/image/ab67616d0000b2735a0c2870f4f309e382d1fad6','aceptada','2024-10-23 05:43:37','Chase Atlantic'),(29,38,'5UFjlIK589UVrEUEVbkw1X','Tidal Wave','https://i.scdn.co/image/ab67616d0000b273f5fd3cdb7d0b744aa26630b6','aceptada','2024-10-23 06:50:36','Chase Atlantic'),(30,38,'07fpZNFa6QQscBLF2Yewkn','MAMACITA','https://i.scdn.co/image/ab67616d0000b2730ff6d8add33883704b313ff8','aceptada','2024-10-23 14:15:38','Chase Atlantic'),(31,38,'0X2bh8NVQ8svDQIn2AdCbW','Consume (feat. Goon Des Garcons)','https://i.scdn.co/image/ab67616d0000b2735a0c2870f4f309e382d1fad6','aceptada','2024-10-23 17:15:07','Chase Atlantic'),(32,43,'4x3Kk8FBD4SFC88RZNJpGL','stellar (Slowed + Reverb)','https://i.scdn.co/image/ab67616d0000b27359c72ff3ee769168f8dcdb88','aceptada','2025-01-08 03:29:21','.diedlonely'),(33,39,'2VBLFxCUyFp5BfmsZpxcis','絆ノ奇跡','https://i.scdn.co/image/ab67616d0000b273cb6080eae6c43b7ed1bb44b4','pendiente','2025-01-15 15:55:54','MAN WITH A MISSION'),(34,39,'0WaaPFt4Qy8sVfxKz43bCD','Re:Re:','https://i.scdn.co/image/ab67616d0000b27342b08c3ee667cbba84372739','pendiente','2025-01-29 11:01:50','ASIAN KUNG-FU GENERATION'),(35,39,'28DlaPydCnrs8NxYOnUPZ8','ポラリス','https://i.scdn.co/image/ab67616d0000b273926909699c1214051c7a9937','pendiente','2025-01-29 11:02:44','BLUE ENCOUNT'),(36,39,'2Kld61w2NR7zPPXtaHeIii','CLOSER','https://i.scdn.co/image/ab67616d0000b27361c3f8580306d35a892f7440','pendiente','2025-01-29 11:04:48','Joe Inoue'),(37,39,'1BncfTJAWxrsxyT9culBrj','Experience','https://i.scdn.co/image/ab67616d0000b2736c8ef0538e04f2e28380dcc5','pendiente','2025-02-04 16:57:51','Ludovico Einaudi'),(38,39,'7j5DZTwgUTbBS39DZucDz9','HOLLYWOOD','https://i.scdn.co/image/ab67616d0000b273490db4a35f658b73b30e18fe','pendiente','2025-02-04 19:10:01','Peso Pluma');
/*!40000 ALTER TABLE `solicitudes_musica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_usuarios`
--

DROP TABLE IF EXISTS `tb_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `Gmail` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero_documento` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Apodo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1',
  `login_attempts` int DEFAULT '0',
  `last_login_attempt` timestamp NULL DEFAULT NULL,
  `foto_perfil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('activo','inactivo','eliminado') COLLATE utf8mb4_general_ci DEFAULT 'activo',
  `fecha_desactivacion` datetime DEFAULT NULL,
  `motivo_desactivacion` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `detalle_desactivacion` text COLLATE utf8mb4_general_ci,
  `datos_anonimizados` tinyint(1) DEFAULT '0',
  `fecha_anonimizacion` datetime DEFAULT NULL,
  `fecha_reactivacion_limite` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Gmail_UNIQUE` (`Gmail`),
  UNIQUE KEY `usuario_UNIQUE` (`Apodo`),
  KEY `idx_usuario_estado` (`estado`),
  KEY `idx_fecha_desactivacion` (`fecha_desactivacion`),
  KEY `idx_gmail` (`Gmail`),
  KEY `idx_apodo` (`Apodo`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuarios`
--

LOCK TABLES `tb_usuarios` WRITE;
/*!40000 ALTER TABLE `tb_usuarios` DISABLE KEYS */;
INSERT INTO `tb_usuarios` VALUES (3,'jhonnygonsalez7@gmail.com','$2y$10$vTtpFhkYCzmha74EKYSZg.o57YRz5DaYQn6kPOMIRSWEDxHmxrTo2','Jhonny Alexander','Gonsalez Torres','1116233418','@Elsarco','2024-08-11 17:20:39','2025-01-22 06:31:30',1,0,'2025-01-22 06:31:20','66b8f2e7429fa_Will smith.jpg','activo',NULL,NULL,NULL,0,NULL,NULL),(4,'MarioGamboa@gmail.com','$2y$10$Xc9cnyBFu/z6/JEBI45oteoT7ljf0vu9.rXRbjlwlmiApAvQyaPYS','Luis Mario ','Gamboa Torres','1116233414','@El mas ladra ','2024-08-12 21:32:00','2024-08-17 23:52:37',1,0,'2024-08-17 23:52:25','66ba7f508811e.png','activo',NULL,NULL,NULL,0,NULL,NULL),(5,'mariogamboa123@gmail.com','$2y$10$bHWJLrNp4AgT4tYEu6xjAuHfOFdp/dKtb7cvSNBjR1w4u6DAncmKC','Luis Mario','Gamboa Torres','1116233412','@Elmasmas','2024-08-14 22:38:00','2024-08-14 22:38:00',1,0,NULL,'66bd31c8c6b7c.png','activo',NULL,NULL,NULL,0,NULL,NULL),(6,'jhonnygonsalez09@gmail.com','$2y$10$jQyZTpCzzfQdcPodF5u80upWoVNNBxdOzvc75wzQ7hUxbQc/jVdNm','Jhonny Garzon','Alexander','1116233499','DevMobyEnd','2024-08-16 15:33:01','2024-08-16 16:15:45',1,0,'2024-08-16 16:15:33','66bf712d2cc21.png','activo',NULL,NULL,NULL,0,NULL,NULL),(7,'norfaliat12@gmail.com','$2y$10$xaOMNseDORxXf1/zsUwwzeqDFYOqDCeyAHJFc6w4eGXP5zvAXL8GK','Rosio Norfalia','Torres Torres','11122236617|','Rosio','2024-08-18 17:47:36','2024-08-18 17:47:36',1,0,NULL,'66c233b85476a.png','activo',NULL,NULL,NULL,0,NULL,NULL),(8,'chanchitofeliz437@gmail.com','$2y$10$5neOnJlmffi374Iy.ziwAeIxHav5kTs2POPIZXy.oDsYJi23iZqnS','Marcos Stiven ','Univer Amatista','1116233423','@Lasapa','2024-08-20 20:36:48','2024-10-01 19:36:27',1,0,'2024-10-01 19:20:35','66c4fe60027b2.png','activo',NULL,NULL,NULL,0,NULL,NULL),(9,'LuizDiaz@gmail.com','$2y$10$4Dbm.JZGKfByzB1TEmOZV.d6o3I5z6syOnCri8nQdBaI87E.WA5ce','Luis armando','Diaz Henao','1116233418','Elmago','2024-08-21 22:47:51','2024-08-21 22:47:51',1,0,NULL,'66c66e97ca223.png','activo',NULL,NULL,NULL,0,NULL,NULL),(10,'SebastianAmaya7@gmail.com','$2y$10$RHoJCs/oYZeHpoLui2lBIeh7HiekWgqQ8o69shkfggy9ZA7Zkj.gC','Sebastian Amaya','Univer Amatista','1116233499','@JhonnyElmas','2024-08-23 19:15:23','2025-01-15 13:48:52',1,0,'2025-01-15 13:48:41','66c8dfcb7f73a.png','activo',NULL,NULL,NULL,0,NULL,NULL),(11,'Seor0@gmail.com','$2y$10$6IkhQwhqetTDybSAVLR9nOKUIJnLQlio.jPl2cKU6f6hYpfsAXlrm','Señor ','Cero','1116233412','@Señor#0','2024-08-27 21:59:33','2024-08-27 21:59:33',1,0,NULL,'66ce4c45de46f.png','activo',NULL,NULL,NULL,0,NULL,NULL),(14,'EdgarCamilo@gmail.com','$2y$10$nhv5acpGb8U3aWa8Ma8f7uWxtuW2/uwcZN8MazKUn5YMkFM7JrRNK','Edgar Camilo','Figueroa Acevedo','1116233423','@ElZzzzzz','2024-08-28 21:31:43','2024-09-12 20:20:22',1,0,'2024-09-12 20:20:11','66cf973eed638.png','activo',NULL,NULL,NULL,0,NULL,NULL),(15,'LeonidasAsebedo@gmail.com','$2y$10$k7LvWajwgut0waKhEHCKXuA6W.6cU4drOCEOy215PQjkWWCxKV8IG','Leonidas','Asebedo','1116233489','@LeonidasOro','2024-08-29 20:00:15','2024-09-09 21:31:35',1,0,'2024-09-09 21:31:22','66d0d34eee3ab.png','activo',NULL,NULL,NULL,0,NULL,NULL),(16,'JhonnyBuenanote@gmail.com','$2y$10$nzVN9brzpTaeDhBBw9Nvsu6Rk59Hdd4GpOMsosNpbo3JpQNgCmnhm','Jhonny Buenanote ','Asebedo Mondragon','1116233443','@Lasapa3','2024-09-05 14:53:48','2024-09-05 14:53:48',1,0,NULL,'cropped_66d9c5fcbe59b2.20247358.png','activo',NULL,NULL,NULL,0,NULL,NULL),(17,'RosioNorfalia@gmail.com','$2y$10$hxfFnfXkE85J9zNX2ny7seeigs8ZuzFj0OIEexLiA/udfmtK8HCr6','Rosio Norfalia','gonsalez torres','1116233412','Starbucks','2024-09-13 16:25:29','2024-09-13 16:25:29',1,0,NULL,'cropped_66e46779af1209.64464985.png','activo',NULL,NULL,NULL,0,NULL,NULL),(18,'AdminLTE@gmail.com','$2y$10$k6QKZk2aO1k9gb2BDvxIl.4fzjmPy8WN/97KOd3X26Z3aiL.ppk.q','Admin','LTE','1116233476','@Elenvidioso','2024-10-18 21:12:17','2024-10-18 21:12:17',1,0,NULL,'cropped_6712cf3146db53.64897013.png','activo',NULL,NULL,NULL,0,NULL,NULL),(19,'StivenUnivers@gmail.com','$2y$10$aPR9XEjtAlU7iIWIF7xzt.ShgankTUk3OfDQVj9xslTsmdKu1nYwq','Stiven ','Univers','1122236617','@Elperron','2024-10-18 21:26:32','2024-10-18 21:26:32',1,0,NULL,'cropped_6712d288c65728.65930110.png','activo',NULL,NULL,NULL,0,NULL,NULL),(20,'Admin2LTE2@gmail.com','$2y$10$EsiK.Gu.Ci66f3IsXQusCulZ/Os046FAwg1qIaJ7jT5VHm87HP/Xy','Admin2','LTE2','1116233456','@Elenvidioso2','2024-10-18 21:42:05','2024-10-18 21:42:05',1,0,NULL,'cropped_6712d62d51c039.29365779.png','activo',NULL,NULL,NULL,0,NULL,NULL),(21,'LuisErnestostio@gmail.com','$2y$10$lz9FTN52d9zY5cCTffop7.o/vsxgJctrgSCCOSsy2t6U6vVKl2VUC','Luis ernestostio','pallea del piero','1116232809','XXX Tentación','2024-10-18 21:58:06','2024-10-18 21:58:06',1,0,NULL,'/uploads/img_6712d9ee0dbd66.71793462.jpg','activo',NULL,NULL,NULL,0,NULL,NULL),(22,'Jhonnier@gmail.com','$2y$10$HFUKmdUsCYe16bPF8JikNuRt9Xxa0JmfyNbkwDodKgFuFHmkdioXK','Jhonnier','Gonzalez','1116230998','@Elsalvador','2024-10-18 22:08:58','2024-10-18 22:08:58',1,0,NULL,'/uploads/img_6712dc7a063706.50810548.jpeg','activo',NULL,NULL,NULL,0,NULL,NULL),(23,'dastarache@gmail.com','$2y$10$8y2UOKMUGf4mkDgWbfDQ/.mbjum9SvTeXd/bnZfI51faZtpRXEBK.','dastarache','armando perea','2116233434','@CamiloF2','2024-10-18 22:13:14','2024-10-18 22:13:14',1,0,NULL,'/uploads/img_6712dd7ad63370.07530400.jpeg','activo',NULL,NULL,NULL,0,NULL,NULL),(24,'Jhonnyt@gmail.com','$2y$10$2OZVqnRi9Wr2rf.a/5DQtOoR65eUr.ZMqhcsMT9aIGqyNoaNCZ9Wq','Jhonnyt','LTEQ','1116233443','@JH','2024-10-19 01:41:29','2024-10-19 01:41:29',1,0,NULL,'/Public/dist/img/profile.jpg','activo',NULL,NULL,NULL,0,NULL,NULL),(25,'Admin12@gmail.com','$2y$10$45CqRJv1s2/kSzLfWv0PT.QDjhP/0LGsnCsPZGPIJxVy6sZlkIEoi','Admin12','@jh','1116233410','@ElZzzzzz2','2024-10-19 01:42:48','2024-10-19 01:42:48',1,0,NULL,'/Public/dist/img/profile.jpg','activo',NULL,NULL,NULL,0,NULL,NULL),(26,'EdgarCamilo2@gmail.com','$2y$10$4xZ73UNg5R.IZ0szgMqEAesfcayTRHKTghYPpF1o08vUMgcHUGH5i','Edgar Camilo2','LTE2','1116233454','@Lasapa1','2024-10-19 01:46:08','2024-10-19 01:46:08',1,0,NULL,'cropped_67130f60232d03.97370834.png','activo',NULL,NULL,NULL,0,NULL,NULL),(27,'LionelAndres@gmail.com','$2y$10$W8KTe9vufK5pud64EU2jk.hd4GKSClRtrTp/FHqyqwBNZOz4PwURO','Lionel Andres','Messi Cuchitini','1116233401','@GOAT','2024-10-19 03:09:49','2024-10-19 03:09:49',1,0,NULL,'','activo',NULL,NULL,NULL,0,NULL,NULL),(28,'AntonyMatheus@gmail.com','$2y$10$RvGCBfurdU9jTpn1ZfTTzewB3OIo4xa5t97z7hsSVuBTIFUzCcRfK','Antony Matheus','dos Santos','1116233412','@Nuestrosalvador','2024-10-19 03:25:41','2024-10-19 03:35:29',1,0,NULL,'antony.jpg','activo',NULL,NULL,NULL,0,NULL,NULL),(29,'AntonyMatheus2@gmail.com','$2y$10$IZ5uPjTWlyJOqxj4xawc4umoB1Pap7E0RO/LOWA85UPLqeKrusb7e','Antony Matheus','madra','1116233401','Señor71','2024-10-19 03:37:19','2024-10-19 03:37:19',1,0,NULL,'','activo',NULL,NULL,NULL,0,NULL,NULL),(30,'Luisenrique@gmail.com','$2y$10$/32xywleOeS1zS.ZnsHoXu8h.LQhypklwfYTDtt2VQvg19dzrmzL6','Luis enrique','LTE2','1116230998','@Lasapa212','2024-10-19 03:52:44','2024-10-19 03:52:44',1,0,NULL,'cropped_67132d0cd205f5.20013946.png','activo',NULL,NULL,NULL,0,NULL,NULL),(31,'Nuestrosalvador@gmail.com','$2y$10$nSkc230E6VZaRWxrzqrrpeULoclt7o/747/HccyXL4OLwaqYXHrOy','Nuestro salvador ','Garzon','1116233489','@Elmas132','2024-10-19 03:55:12','2024-10-19 03:55:12',1,0,NULL,'cropped_67132da0ce8693.78321739.png','activo',NULL,NULL,NULL,0,NULL,NULL),(32,'Luisarmando2@gmail.com','$2y$10$y.mSjNThyQJhUgq.p67.ie2GJ5Np2cB4DS4Z3YPRCaUgLIYLwV2vW','Luis armando2','WEEE','1116233489','Rosio2','2024-10-19 04:12:30','2024-10-19 04:12:30',1,0,NULL,'cropped_671331ae7aaf54.52085889.png','activo',NULL,NULL,NULL,0,NULL,NULL),(33,'Messirve@gmail.com','$2y$10$7sJdWZ1mckuYC7WQ/LLH8eV//.qDoN9diMPdeAL9BLxoJ93jzXRju','Messirve ','Cuchitini','1116233412','Messirve','2024-10-19 04:32:55','2024-10-19 04:32:55',1,0,NULL,'cropped_67133676ec05d2.73059425.png','activo',NULL,NULL,NULL,0,NULL,NULL),(34,'Seor007@gmail.com','$2y$10$BZI.5pmZW3i6ngIA3Q6XAeuJH1vrHf7FYhWL0HpO7LJ2teq8.3Iha','Jhonny Garzon','Amaranto Perea','1116230998','Señor007','2024-10-19 04:53:29','2024-10-19 04:53:29',1,0,NULL,'cropped_67133b4947bf62.21220499.png','activo',NULL,NULL,NULL,0,NULL,NULL),(35,'JhonnyGarzon@gmail.com','$2y$10$qoAJ.nD8CKiznzskv3sRzOBs7biAZJXOQ/pv1vecHJrBFaZrRO40u','Jhonny Garzon','LTE2','1116233412','@Lasapa209','2024-10-19 05:31:49','2024-10-19 05:31:49',1,0,NULL,'cropped_67134445758ce9.70496102.png','activo',NULL,NULL,NULL,0,NULL,NULL),(36,'LuisMario@gmail.com','$2y$10$6y0ozOHxA1ZvNOLo6/x57.qIU1bcv6uxws3SLJkDDnB77kyTGeyGu','LuisMario','Messi Cuchitini','1116233412','@GOAT007','2024-10-19 05:53:20','2024-10-19 05:53:20',1,0,NULL,'img_67134950d16149.72330043.jpg','activo',NULL,NULL,NULL,0,NULL,NULL),(37,'Nuestrosalvador007@gmail.com','$2y$10$pKIgYAi.7tQMmiG/Q0ZGBOjG4qJ47YeQXg4PnzXqb2v4zXsqLcDa6','Nuestrosalvador','Jesusgarde','1116233412','@Elenvidioso007','2024-10-19 05:56:11','2025-01-15 05:11:26',1,0,'2025-01-15 03:39:59','img_671349fbc45b20.59979467.jpg','activo',NULL,NULL,'mucha mierda en la pagina',0,NULL,'2025-02-14 00:07:25'),(38,'MemphisDepay@gmail.com','$2y$10$yp.AFUzitq3kFldl239TEe4xsXmsKWy28hiDDWRokNPvZiwHwZ44u','Memphis ','Depay','1116233413','@GOAT10','2024-10-23 06:48:14','2025-01-12 04:13:48',1,0,'2025-01-12 04:13:38','cropped_67189c2e69af90.06913716.png','activo',NULL,NULL,NULL,0,NULL,NULL),(39,'Rengoku@gmail.com','$2y$10$lvWITX9JAObnc/egmC4.teoFU5C2jugysNalh.QQpsDubmZF5K74m','Rengoku','Senyuro','1116230998','@ElpilardeFuego','2024-11-18 20:07:03','2025-02-04 19:18:34',1,0,'2025-02-04 19:18:23','cropped_673b9e678eddc1.34911328.png','activo',NULL,NULL,NULL,0,NULL,NULL),(40,'Soteldo10@gmail.com','$2y$10$VWUPYkH8jbqMnsSV003pJOom6FESCIKCqVIwNVKvPFuLdw88/fgdm','Elayuboki','Soteldo','1116232809','Soteldo10','2024-11-18 20:31:12','2024-11-18 20:31:12',1,0,NULL,'img_673ba410998821.73769895.jpeg','activo',NULL,NULL,NULL,0,NULL,NULL),(41,'Letuzaga@gmail.com','$2y$10$cesyklRc.Ty3RednG54fye236RZ8X3jsBgeC7W.lJ5xrZ0LkgytNG','Letuzaga','ocoro','1116230998','#ElmejorGusion','2024-11-18 20:40:29','2024-11-19 20:09:22',1,0,'2024-11-19 20:08:50','img_673ba63de0a191.36544938.jpeg','activo',NULL,NULL,NULL,0,NULL,NULL),(42,'Minicraft@gmail.com','$2y$10$kO9r6LaVOGU3WR4yxj7zxOosXS7U4STjIhHp4rsuIAR8unagSzTiS','Minicraft','Beckdropt','1116230998','@Minicraft','2024-11-18 21:09:46','2024-11-18 21:09:46',1,0,NULL,'img_673bad1a44eb96.46600895.png','activo',NULL,NULL,NULL,0,NULL,NULL),(43,'anonimo43@example.com','$2y$10$7CBAj5F9BCAxtgRYyDiaVu9FTZ01KagWzp4S2dHcKP.oInf0fD.6a','Anónimo','Anónimo','1116230906','Anónimo43','2024-11-18 21:20:33','2025-01-15 05:10:42',1,0,NULL,'default.jpg','activo',NULL,NULL,NULL,1,'2025-01-15 00:10:42',NULL);
/*!40000 ALTER TABLE `tb_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_usuarios_backup`
--

DROP TABLE IF EXISTS `tb_usuarios_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_usuarios_backup` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `Gmail` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `nombres` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero_documento` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Apodo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1',
  `login_attempts` int DEFAULT '0',
  `last_login_attempt` timestamp NULL DEFAULT NULL,
  `foto_perfil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('activo','inactivo','eliminado') COLLATE utf8mb4_general_ci DEFAULT 'activo',
  `fecha_desactivacion` datetime DEFAULT NULL,
  `motivo_desactivacion` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `detalle_desactivacion` text COLLATE utf8mb4_general_ci,
  `datos_anonimizados` tinyint(1) DEFAULT '0',
  `fecha_anonimizacion` datetime DEFAULT NULL,
  `fecha_reactivacion_limite` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Gmail_UNIQUE` (`Gmail`),
  UNIQUE KEY `usuario_UNIQUE` (`Apodo`),
  KEY `idx_usuario_estado` (`estado`),
  KEY `idx_fecha_desactivacion` (`fecha_desactivacion`),
  KEY `idx_gmail` (`Gmail`),
  KEY `idx_apodo` (`Apodo`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuarios_backup`
--

LOCK TABLES `tb_usuarios_backup` WRITE;
/*!40000 ALTER TABLE `tb_usuarios_backup` DISABLE KEYS */;
INSERT INTO `tb_usuarios_backup` VALUES (43,'xampp_actualizado@gmail.com','$2y$10$7CBAj5F9BCAxtgRYyDiaVu9FTZ01KagWzp4S2dHcKP.oInf0fD.6a','Xampp Actualizado','Root Actualizado','1116230906','Xxx Xampp Actualizado','2024-11-18 21:20:33','2025-01-12 05:17:18',1,0,NULL,'uploads/img_nuevo.png','activo',NULL,NULL,NULL,0,NULL,NULL);
/*!40000 ALTER TABLE `tb_usuarios_backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_usuarios_role`
--

DROP TABLE IF EXISTS `tb_usuarios_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_usuarios_role` (
  `usuario_id` bigint NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`usuario_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `tb_usuarios_role_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tb_usuarios_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuarios_role`
--

LOCK TABLES `tb_usuarios_role` WRITE;
/*!40000 ALTER TABLE `tb_usuarios_role` DISABLE KEYS */;
INSERT INTO `tb_usuarios_role` VALUES (4,3),(5,3),(6,3),(7,3),(8,3),(9,3),(10,3),(14,3),(15,3),(16,3),(18,3),(19,3),(20,3),(21,3),(22,3),(23,3),(24,3),(25,3),(26,3),(27,3),(28,3),(29,3),(30,3),(31,3),(32,3),(33,3),(34,3),(35,3),(36,3),(37,3),(38,3),(39,3),(40,3),(41,3),(42,3),(43,3),(17,4),(3,5),(11,5);
/*!40000 ALTER TABLE `tb_usuarios_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tokens`
--

DROP TABLE IF EXISTS `user_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('login','password_reset') COLLATE utf8mb4_general_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=348 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tokens`
--

LOCK TABLES `user_tokens` WRITE;
/*!40000 ALTER TABLE `user_tokens` DISABLE KEYS */;
INSERT INTO `user_tokens` VALUES (15,4,'6da4db065614b079da9ef38327d48397defec1b7dcfcffe1657d3191a00a2030','login','2024-08-13 00:33:17','2024-09-09 14:58:30'),(19,5,'d31f0463e60cc66f4f0f4f96e32eb7f57df2445c46977589e4b09335b0fdf296','login','2024-08-15 01:38:54','2024-09-09 14:58:30'),(20,6,'31ca70734117168bc0f1056eb8a1ee2c439bbe1655d1b58c839a887e7ff46179','login','2024-08-16 18:34:23','2024-09-09 14:58:30'),(21,6,'201d32ed9f1400d23444288b7376cd70ca49c3efe06497cce649eface268dd1e','login','2024-08-16 19:15:45','2024-09-09 14:58:30'),(24,6,'f91f5fb213d2ce8191769417509d01d8abb263449d3cdf43882f9855c3875183','login','2024-08-16 21:08:20','2024-09-09 14:58:30'),(25,6,'768a3b644c48043868b481d8448beffef2794f1227dfb88407d0c8ba7c998ac9','login','2024-08-16 22:14:56','2024-09-09 14:58:30'),(26,6,'d3006c3c4e2b5c2570d5b0a671bc263523dbefe4ce0089ba6e76a8b2d27ab6e7','login','2024-08-16 22:21:00','2024-09-09 14:58:30'),(27,4,'00734fc342dfbc8b63122fe3749d0132e5494d9b1c6006ecf269ef1f3d09bda0','login','2024-08-16 22:23:04','2024-09-09 14:58:30'),(28,5,'6499a5e5442ec8379edb63800a003023cba64da463864c6ba6d5cd19d1405538','login','2024-08-17 00:02:55','2024-09-09 14:58:30'),(29,6,'030e3437096ce610df21e31f9198641389ab6dddfab901d4173d5633731501c5','login','2024-08-17 01:15:10','2024-09-09 14:58:30'),(30,6,'6c347fb8ea00a19b0e7153e5f432ca9d75b8f5c778b135817a833b53fe23d1b9','login','2024-08-17 01:26:46','2024-09-09 14:58:30'),(31,6,'723690c922539b7984ad7c2df6e00889021b5d6f13b8b7786f9ce2dd84ed3a4b','login','2024-08-17 01:26:52','2024-09-09 14:58:30'),(32,6,'be4cc8f60af78f4bb8b7ea353bd9643e19e9630812161ae6937de15d668ef171','login','2024-08-17 01:27:59','2024-09-09 14:58:30'),(33,6,'89e4afef7a47d3fd518ceb95400b21ad382eec48b3338adc9270ebca3ff5beb2','login','2024-08-17 01:29:33','2024-09-09 14:58:30'),(34,6,'a16220c5b05fd0e32e71751b0de5a7e632af6f48ff246304fcc5e3266776dfda','login','2024-08-17 01:36:16','2024-09-09 14:58:30'),(35,6,'1043b95eb13dfea12d5db6e3be94b46b66c22d451b7f019c937c4cb465cb580a','login','2024-08-17 01:36:18','2024-09-09 14:58:30'),(50,4,'3b4c958d5c94bd6a71e2625d30eb88ce1cdcaa9bad8d73eb16577d4869fcf425','login','2024-08-18 02:52:37','2024-09-09 14:58:30'),(51,7,'bbb1a3a1839ae441ebe46badbf50173f198e9eb0e4b2e28349d27360800cca02','login','2024-08-18 20:51:43','2024-09-09 14:58:30'),(55,6,'e253ca56aba6f18f3cd98ac943d6047470d9c9a8446a3d55a33009e793512bf7','login','2024-08-21 20:00:12','2024-09-09 14:58:30'),(57,9,'5f21c8e1fa3f1eec367f06fbb1a91c3a43157519a605a1b0ef2f1eba2684e0df','login','2024-08-22 01:48:02','2024-09-09 14:58:30'),(140,15,'8a55573512f3613fbe25fe9af16d4e2c','password_reset','2024-09-11 02:01:05','2024-09-09 21:01:05'),(189,15,'40d9df8da34e3fa876f787c5f88c7706','login','2024-09-14 03:58:48','2024-09-12 22:58:48'),(195,14,'44a0beb294fdd1584acb075bbb1c4c9e','login','2024-09-18 20:56:17','2024-09-17 15:56:17'),(214,8,'448a46208271cb50891e5f548ef4a47f','password_reset','2024-10-02 21:35:20','2024-10-01 14:35:20'),(215,8,'017a3fddce2ec5027a41f3514ea8a3db','login','2024-10-02 21:36:27','2024-10-01 14:36:27'),(220,18,'832e47b80e875a8bfbc82c9d72ffc391','login','2024-10-19 23:13:26','2024-10-18 16:13:26'),(222,20,'1275f7367327733abe921dba5ffda88b','login','2024-10-19 23:58:26','2024-10-18 16:58:26'),(223,21,'b850054ff00ba48514e08b7a2a41c4f8','login','2024-10-19 23:59:03','2024-10-18 16:59:03'),(224,22,'55a2232cdcaa15b97a095bdaafa275cb','login','2024-10-20 00:09:18','2024-10-18 17:09:18'),(225,23,'8cf72aab659f0b679f6860b594d50cd6','login','2024-10-20 00:13:43','2024-10-18 17:13:43'),(226,24,'cd327b9c6ffd5c0943701ad751d0399a','login','2024-10-20 03:41:40','2024-10-18 20:41:40'),(227,25,'86a45dece48e1ff43307295932809ec8','login','2024-10-20 03:43:02','2024-10-18 20:43:02'),(228,26,'06acbb21395e2c2f139ab2839a83567d','login','2024-10-20 03:46:22','2024-10-18 20:46:22'),(229,27,'735475fff648f14fff4fa9f40cad98ea','login','2024-10-20 05:10:08','2024-10-18 22:10:08'),(231,29,'4f2bdd325e8fb8d1e5affa79c24953bd','login','2024-10-20 05:37:29','2024-10-18 22:37:29'),(232,30,'9b3c8b140b7d5743f5054150385f9870','login','2024-10-20 05:53:08','2024-10-18 22:53:08'),(233,31,'8a6044736ee6c1d59e95a4a57cb5a872','login','2024-10-20 05:55:35','2024-10-18 22:55:35'),(234,32,'42df89a05998ab350ad848a88ce258c1','login','2024-10-20 06:12:50','2024-10-18 23:12:50'),(235,33,'91cfbd163e1db56b381a0a3d3a3cd36a','login','2024-10-20 06:33:13','2024-10-18 23:33:13'),(244,36,'c3ba3f64517076d502f65bf427aac2df','login','2024-10-23 19:59:36','2024-10-22 12:59:36'),(245,28,'04ee9b2f1bb80a12dae19b07d2466ff3','login','2024-10-23 20:00:27','2024-10-22 13:00:27'),(250,11,'27ffd57cf3351fa17beb0475302d7389','login','2024-10-23 21:06:46','2024-10-22 14:06:46'),(264,3,'f0f2b8c4f49bd0abae74687a33b429d9','password_reset','2024-10-23 23:03:30','2024-10-22 16:03:30'),(307,41,'47a77fc5456680cb711f9318bed61ed1','login','2024-11-20 21:09:22','2024-11-19 15:09:22'),(319,43,'537bbb01c7f60af3a9e2c0d548e997a0','login','2025-01-09 03:28:16','2025-01-07 22:28:16'),(320,40,'9818994fd543556c389ae71d453bd327','login','2025-01-09 05:21:07','2025-01-08 00:21:07'),(322,16,'65f6a633fd5c550627aee65812870acf','login','2025-01-09 07:22:00','2025-01-08 02:22:00'),(324,38,'aa9bbf437c93fe98b84fc642175fbc10','login','2025-01-13 04:13:48','2025-01-11 23:13:48'),(326,37,'5a2955e0618a7538a0a8ade8c276ed61','login','2025-01-16 05:08:51','2025-01-15 00:08:51'),(327,10,'401a6eb8087ae66dfa31b64010c5df00','login','2025-01-16 13:48:52','2025-01-15 08:48:52'),(329,17,'a6557c120dde0931d2662c325c4df384','login','2025-01-16 14:25:31','2025-01-15 09:25:31'),(331,19,'e9d78b1535327aaa46a74b10fcb00534','login','2025-01-16 16:17:48','2025-01-15 11:17:48'),(346,3,'b1192bcec7d657748786e392668d1264','login','2025-02-05 19:10:16','2025-02-04 14:10:16'),(347,39,'8a986c6a158137aa92918a3cd509e652','login','2025-02-05 19:18:34','2025-02-04 14:18:34');
/*!40000 ALTER TABLE `user_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_membresia`
--

DROP TABLE IF EXISTS `usuario_membresia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_membresia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint DEFAULT NULL,
  `membresia_id` int DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `membresia_id` (`membresia_id`),
  CONSTRAINT `usuario_membresia_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`),
  CONSTRAINT `usuario_membresia_ibfk_2` FOREIGN KEY (`membresia_id`) REFERENCES `membresias` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_membresia`
--

LOCK TABLES `usuario_membresia` WRITE;
/*!40000 ALTER TABLE `usuario_membresia` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_membresia` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-04 14:32:04
