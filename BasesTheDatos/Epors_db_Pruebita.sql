-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: db_Pruebita
-- ------------------------------------------------------
-- Server version	8.0.30

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadisticas_usuario`
--

LOCK TABLES `estadisticas_usuario` WRITE;
/*!40000 ALTER TABLE `estadisticas_usuario` DISABLE KEYS */;
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
  PRIMARY KEY (`id`),
  CONSTRAINT `chk_precio_positivo` CHECK ((`precio` >= 0))
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
  CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`receptor_id`) REFERENCES `tb_usuarios` (`id`),
  CONSTRAINT `chk_dislikes_positivo` CHECK ((`dislikes` >= 0)),
  CONSTRAINT `chk_likes_positivo` CHECK ((`likes` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes`
--

LOCK TABLES `mensajes` WRITE;
/*!40000 ALTER TABLE `mensajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `mensajes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_mensaje_insert` AFTER INSERT ON `mensajes` FOR EACH ROW BEGIN
    IF NEW.es_global THEN
        UPDATE estadisticas_usuario 
        SET mensajes_enviados_global = mensajes_enviados_global + 1 
        WHERE usuario_id = NEW.emisor_id;
    ELSE
        UPDATE estadisticas_usuario 
        SET mensajes_enviados_privado = mensajes_enviados_privado + 1 
        WHERE usuario_id = NEW.emisor_id;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
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
  `tipo_reaccion` enum('like','dislike') DEFAULT NULL,
  `fecha_reaccion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mensaje_id` (`mensaje_id`,`usuario_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `reacciones_mensajes_ibfk_1` FOREIGN KEY (`mensaje_id`) REFERENCES `mensajes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reacciones_mensajes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reacciones_mensajes`
--

LOCK TABLES `reacciones_mensajes` WRITE;
/*!40000 ALTER TABLE `reacciones_mensajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `reacciones_mensajes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_reaccion_insert` AFTER INSERT ON `reacciones_mensajes` FOR EACH ROW BEGIN
    DECLARE autor_mensaje BIGINT;
    
    -- Actualizar contadores en la tabla de mensajes
    IF NEW.tipo_reaccion = 'like' THEN
        UPDATE mensajes SET likes = likes + 1 WHERE id = NEW.mensaje_id;
    ELSE
        UPDATE mensajes SET dislikes = dislikes + 1 WHERE id = NEW.mensaje_id;
    END IF;
    
    -- Obtener el autor del mensaje
    SELECT emisor_id INTO autor_mensaje FROM mensajes WHERE id = NEW.mensaje_id;
    
    -- Actualizar estadísticas del usuario
    IF NEW.tipo_reaccion = 'like' THEN
        UPDATE estadisticas_usuario 
        SET likes_recibidos = likes_recibidos + 1 
        WHERE usuario_id = autor_mensaje;
    ELSE
        UPDATE estadisticas_usuario 
        SET dislikes_recibidos = dislikes_recibidos + 1 
        WHERE usuario_id = autor_mensaje;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permiso`
--

LOCK TABLES `role_permiso` WRITE;
/*!40000 ALTER TABLE `role_permiso` DISABLE KEYS */;
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
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
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
  `spotify_track_id` varchar(255) DEFAULT NULL,
  `nombre_cancion` varchar(255) DEFAULT NULL,
  `imagen_url` varchar(512) DEFAULT NULL,
  `estado` enum('pendiente','aceptada','rechazada') DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `idx_spotify_track_id` (`spotify_track_id`),
  CONSTRAINT `solicitudes_musica_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes_musica`
--

LOCK TABLES `solicitudes_musica` WRITE;
/*!40000 ALTER TABLE `solicitudes_musica` DISABLE KEYS */;
/*!40000 ALTER TABLE `solicitudes_musica` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_solicitud_insert` AFTER INSERT ON `solicitudes_musica` FOR EACH ROW BEGIN
    INSERT INTO estadisticas_usuario (usuario_id, total_solicitudes)
    VALUES (NEW.usuario_id, 1)
    ON DUPLICATE KEY UPDATE total_solicitudes = total_solicitudes + 1;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `after_solicitud_update` AFTER UPDATE ON `solicitudes_musica` FOR EACH ROW BEGIN
    IF NEW.estado != OLD.estado THEN
        IF NEW.estado = 'aceptada' THEN
            UPDATE estadisticas_usuario
            SET solicitudes_aceptadas = solicitudes_aceptadas + 1
            WHERE usuario_id = NEW.usuario_id;
        ELSEIF NEW.estado = 'rechazada' THEN
            UPDATE estadisticas_usuario
            SET solicitudes_rechazadas = solicitudes_rechazadas + 1
            WHERE usuario_id = NEW.usuario_id;
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `Apodo` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1',
  `login_attempts` int DEFAULT '0',
  `last_login_attempt` timestamp NULL DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Gmail_UNIQUE` (`Gmail`),
  UNIQUE KEY `usuario_UNIQUE` (`Apodo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuarios`
--

LOCK TABLES `tb_usuarios` WRITE;
/*!40000 ALTER TABLE `tb_usuarios` DISABLE KEYS */;
INSERT INTO `tb_usuarios` VALUES (3,'jhonnygonsalez7@gmail.com','$2y$10$9VVk1IA7WOuji/tm7eoBJ.icxEJBYlea3jPSQwJI.aBVuiNK/BJsi','Jhonny Alexander','Gonsalez Torres','1116233418','@Elsarco','2024-08-11 17:20:39','2024-08-11 17:20:39',1,0,NULL,'66b8f2e7429fa_Will smith.jpg');
/*!40000 ALTER TABLE `tb_usuarios` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_usuarios_role`
--

LOCK TABLES `tb_usuarios_role` WRITE;
/*!40000 ALTER TABLE `tb_usuarios_role` DISABLE KEYS */;
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
  `token` varchar(255) NOT NULL,
  `type` enum('login','password_reset') NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tokens`
--

LOCK TABLES `user_tokens` WRITE;
/*!40000 ALTER TABLE `user_tokens` DISABLE KEYS */;
INSERT INTO `user_tokens` VALUES (1,3,'2e986e5727e0f82380ed8aad4e234bc5bce1fcbad03ade5324fbdc1bc47e4d22','login','2024-08-11 20:22:24'),(2,3,'4623cf0c750a674ee4a09f29eda856b9113bdd6e583b1ed23a232fa09809f9f4','login','2024-08-11 23:59:14'),(3,3,'99374c8afdfd986a99c0b7830fe00444b1bf7a83936598a77c5b309df20bb498','login','2024-08-12 00:15:43'),(4,3,'440ad46c815d8724b1bccf60dbcd1dcff42d77a6791c371579fc9d101f6d0bb4','login','2024-08-12 01:42:12'),(5,3,'b6ab75ec4d5d4d861cb347e5d59fc31e92662c5efc576bbf32c6b75fbbd1ea20','login','2024-08-12 04:23:29'),(6,3,'c58979ed91ba82aa4d3dccabbde6225335f9b69d9e146cfeeacc2d3c30e65fe6','login','2024-08-12 04:42:38'),(7,3,'0524d996fae5efa0d1755797af6af90adb14cfe57cc2a14da0bba6769425f36b','login','2024-08-12 04:44:11'),(8,3,'152709a0ede2f1971f13a9677bd6c2bd04b980386705567a6bd902b23595b773','login','2024-08-12 04:46:28'),(9,3,'e6bf74153d21b4d9db46c6b4f409b55ab4bac4e00aee9461c2cd13e95d73f344','login','2024-08-12 04:49:05'),(10,3,'a5a43b36a64bf458e80cdff8081726792616975e62b248fc9cf9989c66ec3601','login','2024-08-12 04:55:55'),(11,3,'42d7d4f37584a6be34166611753423d29f6fbeae79a13768ff8dcf012c8df77f','login','2024-08-12 05:03:26'),(12,3,'a12b07733a58c610e6e71171aa939fcf096ea01e07eaf31cd872920a439165e8','login','2024-08-12 05:28:20'),(13,3,'60a408aa8486559e29eb6185387f94150f37c67d14310cc4ac5eba4d11d8df3a','login','2024-08-12 08:18:15');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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

-- Dump completed on 2024-08-12  0:30:02
