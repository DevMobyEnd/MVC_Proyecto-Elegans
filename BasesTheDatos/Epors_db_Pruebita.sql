-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 26-09-2024 a las 21:01:50
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_pruebita`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_usuario`
--

CREATE TABLE `estadisticas_usuario` (
  `usuario_id` bigint NOT NULL,
  `total_solicitudes` int DEFAULT '0',
  `solicitudes_aceptadas` int DEFAULT '0',
  `solicitudes_rechazadas` int DEFAULT '0',
  `likes_recibidos` int DEFAULT '0',
  `dislikes_recibidos` int DEFAULT '0',
  `mensajes_enviados_global` int DEFAULT '0',
  `mensajes_enviados_privado` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estadisticas_usuario`
--

INSERT INTO `estadisticas_usuario` (`usuario_id`, `total_solicitudes`, `solicitudes_aceptadas`, `solicitudes_rechazadas`, `likes_recibidos`, `dislikes_recibidos`, `mensajes_enviados_global`, `mensajes_enviados_privado`) VALUES
(3, 1, 0, 0, 0, 0, 0, 0),
(15, 2, 0, 0, 0, 0, 0, 0),
(16, 2, 0, 0, 0, 0, 7, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresias`
--

CREATE TABLE `membresias` (
  `id` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `duracion` int DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1'
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` bigint NOT NULL,
  `emisor_id` bigint DEFAULT NULL,
  `receptor_id` bigint DEFAULT NULL,
  `contenido` text,
  `fecha_envio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `es_global` tinyint(1) DEFAULT '0',
  `likes` int DEFAULT '0',
  `dislikes` int DEFAULT '0'
) ;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `emisor_id`, `receptor_id`, `contenido`, `fecha_envio`, `es_global`, `likes`, `dislikes`) VALUES
(2, 17, 16, 'Mensaje de prueba', '2024-09-23 04:42:26', 0, 0, 0),
(3, 17, 16, 'Mensaje de prueba', '2024-09-23 04:44:36', 0, 0, 0),
(4, 17, 16, 'Hola como estan ', '2024-09-23 05:07:37', 0, 0, 0),
(5, 17, 17, 'Hola', '2024-09-23 07:30:34', 1, 0, 1),
(6, 17, 16, 'Hola como estas Starbucks', '2024-09-23 07:32:38', 1, 0, 0),
(7, 17, 3, 'Hola como estas', '2024-09-23 07:34:39', 0, 0, 0),
(8, 17, 16, 'Hola a todos', '2024-09-23 07:43:44', 1, 0, 0),
(9, 16, 16, 'Hola como estas espero que bien mani como va todo', '2024-09-23 07:44:26', 1, 0, 0),
(10, 16, 16, 'Hola', '2024-09-23 07:57:37', 1, 0, 0),
(11, 16, NULL, 'Hola', '2024-09-23 07:58:38', 1, 0, 0),
(12, 16, NULL, 'Hola', '2024-09-23 07:58:38', 1, 0, 0),
(13, 16, NULL, 'Hola', '2024-09-23 07:59:12', 1, 0, 0),
(14, 16, 16, 'Hola', '2024-09-23 08:00:15', 1, 0, 0),
(15, 16, 16, 'Hola como estan', '2024-09-23 08:13:03', 1, 0, 0),
(16, 17, 16, 'Bien coño que estamos bien', '2024-09-23 08:14:20', 1, 0, 0),
(17, 17, NULL, 'Hola', '2024-09-23 08:28:42', 1, 1, 0),
(18, 17, 16, 'Hola a todos', '2024-09-23 08:37:59', 1, 1, 0),
(19, 17, 16, 'Hola como estan', '2024-09-23 18:57:19', 1, 1, 0),
(20, 17, 10, 'hello', '2024-09-23 19:08:35', 0, 0, 0),
(21, 17, 10, 'hola', '2024-09-23 19:08:41', 0, 0, 0),
(22, 17, NULL, 'Hola como estan', '2024-09-23 19:56:34', 1, 1, 0);

--
-- Disparadores `mensajes`
--
DELIMITER $$
CREATE TRIGGER `after_mensaje_insert` AFTER INSERT ON `mensajes` FOR EACH ROW BEGIN
    IF NEW.es_global THEN
        UPDATE estadisticas_usuario 
        SET mensajes_enviados_global = mensajes_enviados_global + 1 
        WHERE usuario_id = NEW.emisor_id;
    ELSE
        UPDATE estadisticas_usuario 
        SET mensajes_enviados_privado = mensajes_enviados_privado + 1 
        WHERE usuario_id = NEW.emisor_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Canal_de_Dialogo', 'En este permiso se podra aseder al canal de dialogo'),
(2, 'Panel_de_DJ', 'En este permiso el DJ Podra Gestionar las solicitudes de los Usuarios Naturales ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reacciones_mensajes`
--

CREATE TABLE `reacciones_mensajes` (
  `id` bigint NOT NULL,
  `mensaje_id` bigint DEFAULT NULL,
  `usuario_id` bigint DEFAULT NULL,
  `tipo_reaccion` enum('like','dislike') DEFAULT NULL,
  `fecha_reaccion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `reacciones_mensajes`
--

INSERT INTO `reacciones_mensajes` (`id`, `mensaje_id`, `usuario_id`, `tipo_reaccion`, `fecha_reaccion`) VALUES
(1, 5, 17, 'dislike', '2024-09-23 07:30:52'),
(6, 17, 17, 'like', '2024-09-23 08:29:12'),
(11, 18, 17, 'like', '2024-09-23 08:38:03'),
(18, 19, 17, 'like', '2024-09-23 18:57:28'),
(19, 22, 17, 'like', '2024-09-23 19:56:51');

--
-- Disparadores `reacciones_mensajes`
--
DELIMITER $$
CREATE TRIGGER `after_reaccion_insert` AFTER INSERT ON `reacciones_mensajes` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(3, 'usuario natural'),
(4, 'admin'),
(5, 'DJ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_permiso`
--

CREATE TABLE `role_permiso` (
  `role_id` int NOT NULL,
  `permiso_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `role_permiso`
--

INSERT INTO `role_permiso` (`role_id`, `permiso_id`) VALUES
(3, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_musica`
--

CREATE TABLE `solicitudes_musica` (
  `id` bigint NOT NULL,
  `usuario_id` bigint DEFAULT NULL,
  `spotify_track_id` varchar(255) DEFAULT NULL,
  `nombre_cancion` varchar(255) DEFAULT NULL,
  `imagen_url` varchar(512) DEFAULT NULL,
  `estado` enum('pendiente','aceptada','rechazada') DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nombre_artista` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `solicitudes_musica`
--

INSERT INTO `solicitudes_musica` (`id`, `usuario_id`, `spotify_track_id`, `nombre_cancion`, `imagen_url`, `estado`, `fecha_solicitud`, `nombre_artista`) VALUES
(1, 16, '76NtMPMpA6fuUH4euMYZoD', 'Buenos Genes', 'https://i.scdn.co/image/ab67616d0000b2730579a9f20ec52281f4447c73', 'pendiente', '2024-09-05 15:36:23', 'Rels B'),
(3, 16, '7g8YaUQABMal0zWe7a2ijz', 'Pa Mí - Remix', 'https://i.scdn.co/image/ab67616d0000b273b9242ba03ab231608f123e06', 'pendiente', '2024-09-05 20:27:49', 'Dalex'),
(4, 15, '74hxp59kFtc3Ndkb7n1IaC', 'Into It', 'https://i.scdn.co/image/ab67616d0000b273601eb33454f13f26db9084e4', 'pendiente', '2024-09-05 22:13:51', 'Chase Atlantic'),
(5, 3, '7p8JhjfckxcuOF0Weixvfa', '¿Cómo Hacer Para Olvidarte?', 'https://i.scdn.co/image/ab67616d0000b2738195fefecfd1ba1918971352', 'pendiente', '2024-09-10 02:31:58', 'Manuel Medrano'),
(6, 15, '5NR1LYf16E6K5t5AeSYP8P', 'Hagamos Lo Que Diga El Corazón', 'https://i.scdn.co/image/ab67616d0000b27351c9409f7962e3aee55c74c6', 'pendiente', '2024-09-13 03:59:17', 'Grupo Niche');

--
-- Disparadores `solicitudes_musica`
--
DELIMITER $$
CREATE TRIGGER `after_solicitud_insert` AFTER INSERT ON `solicitudes_musica` FOR EACH ROW BEGIN
    INSERT INTO estadisticas_usuario (usuario_id, total_solicitudes)
    VALUES (NEW.usuario_id, 1)
    ON DUPLICATE KEY UPDATE total_solicitudes = total_solicitudes + 1;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_solicitud_update` AFTER UPDATE ON `solicitudes_musica` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuarios`
--

CREATE TABLE `tb_usuarios` (
  `id` bigint NOT NULL,
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
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id`, `Gmail`, `password`, `nombres`, `apellidos`, `numero_documento`, `Apodo`, `fecha_creacion`, `fecha_actualizacion`, `activo`, `login_attempts`, `last_login_attempt`, `foto_perfil`) VALUES
(3, 'jhonnygonsalez7@gmail.com', '$2y$10$9FO6o1B/F3mkMmfnP9rMO.Rn.SjOyCsDIS2yv.anG6KJU5xhc9JQe', 'Jhonny Alexander', 'Gonsalez Torres', '1116233418', '@Elsarco', '2024-08-11 17:20:39', '2024-09-10 20:13:40', 1, 0, '2024-09-10 20:13:32', '66b8f2e7429fa_Will smith.jpg'),
(4, 'MarioGamboa@gmail.com', '$2y$10$Xc9cnyBFu/z6/JEBI45oteoT7ljf0vu9.rXRbjlwlmiApAvQyaPYS', 'Luis Mario ', 'Gamboa Torres', '1116233414', '@El mas ladra ', '2024-08-12 21:32:00', '2024-08-17 23:52:37', 1, 0, '2024-08-17 23:52:25', '66ba7f508811e.png'),
(5, 'mariogamboa123@gmail.com', '$2y$10$bHWJLrNp4AgT4tYEu6xjAuHfOFdp/dKtb7cvSNBjR1w4u6DAncmKC', 'Luis Mario', 'Gamboa Torres', '1116233412', '@Elmasmas', '2024-08-14 22:38:00', '2024-08-14 22:38:00', 1, 0, NULL, '66bd31c8c6b7c.png'),
(6, 'jhonnygonsalez09@gmail.com', '$2y$10$jQyZTpCzzfQdcPodF5u80upWoVNNBxdOzvc75wzQ7hUxbQc/jVdNm', 'Jhonny Garzon', 'Alexander', '1116233499', 'DevMobyEnd', '2024-08-16 15:33:01', '2024-08-16 16:15:45', 1, 0, '2024-08-16 16:15:33', '66bf712d2cc21.png'),
(7, 'norfaliat12@gmail.com', '$2y$10$xaOMNseDORxXf1/zsUwwzeqDFYOqDCeyAHJFc6w4eGXP5zvAXL8GK', 'Rosio Norfalia', 'Torres Torres', '11122236617|', 'Rosio', '2024-08-18 17:47:36', '2024-08-18 17:47:36', 1, 0, NULL, '66c233b85476a.png'),
(8, 'chanchitofeliz437@gmail.com', '$2y$10$uMPeUNT1HxWmvP0u5McBfOEIIhP33hfFQAMOoTWtYilcI1togcThe', 'Marcos Stiven ', 'Univer Amatista', '1116233423', '@Lasapa', '2024-08-20 20:36:48', '2024-09-21 08:00:23', 1, 3, '2024-09-21 08:00:23', '66c4fe60027b2.png'),
(9, 'LuizDiaz@gmail.com', '$2y$10$4Dbm.JZGKfByzB1TEmOZV.d6o3I5z6syOnCri8nQdBaI87E.WA5ce', 'Luis armando', 'Diaz Henao', '1116233418', 'Elmago', '2024-08-21 22:47:51', '2024-08-21 22:47:51', 1, 0, NULL, '66c66e97ca223.png'),
(10, 'SebastianAmaya7@gmail.com', '$2y$10$RHoJCs/oYZeHpoLui2lBIeh7HiekWgqQ8o69shkfggy9ZA7Zkj.gC', 'Sebastian Amaya', 'Univer Amatista', '1116233499', '@JhonnyElmas', '2024-08-23 19:15:23', '2024-08-23 19:15:23', 1, 0, NULL, '66c8dfcb7f73a.png'),
(11, 'Seor0@gmail.com', '$2y$10$6IkhQwhqetTDybSAVLR9nOKUIJnLQlio.jPl2cKU6f6hYpfsAXlrm', 'Señor ', 'Cero', '1116233412', '@Señor#0', '2024-08-27 21:59:33', '2024-08-27 21:59:33', 1, 0, NULL, '66ce4c45de46f.png'),
(14, 'EdgarCamilo@gmail.com', '$2y$10$nhv5acpGb8U3aWa8Ma8f7uWxtuW2/uwcZN8MazKUn5YMkFM7JrRNK', 'Edgar Camilo', 'Figueroa Acevedo', '1116233423', '@ElZzzzzz', '2024-08-28 21:31:43', '2024-09-12 20:20:22', 1, 0, '2024-09-12 20:20:11', '66cf973eed638.png'),
(15, 'LeonidasAsebedo@gmail.com', '$2y$10$k7LvWajwgut0waKhEHCKXuA6W.6cU4drOCEOy215PQjkWWCxKV8IG', 'Leonidas', 'Asebedo', '1116233489', '@LeonidasOro', '2024-08-29 20:00:15', '2024-09-09 21:31:35', 1, 0, '2024-09-09 21:31:22', '66d0d34eee3ab.png'),
(16, 'JhonnyBuenanote@gmail.com', '$2y$10$nzVN9brzpTaeDhBBw9Nvsu6Rk59Hdd4GpOMsosNpbo3JpQNgCmnhm', 'Jhonny Buenanote ', 'Asebedo Mondragon', '1116233443', '@Lasapa3', '2024-09-05 14:53:48', '2024-09-05 14:53:48', 1, 0, NULL, 'cropped_66d9c5fcbe59b2.20247358.png'),
(17, 'RosioNorfalia@gmail.com', '$2y$10$hxfFnfXkE85J9zNX2ny7seeigs8ZuzFj0OIEexLiA/udfmtK8HCr6', 'Rosio Norfalia', 'gonsalez torres', '1116233412', 'Starbucks', '2024-09-13 16:25:29', '2024-09-13 16:25:29', 1, 0, NULL, 'cropped_66e46779af1209.64464985.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuarios_role`
--

CREATE TABLE `tb_usuarios_role` (
  `usuario_id` bigint NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tb_usuarios_role`
--

INSERT INTO `tb_usuarios_role` (`usuario_id`, `role_id`) VALUES
(3, 3),
(4, 3),
(8, 3),
(9, 3),
(14, 3),
(15, 3),
(16, 3),
(17, 4),
(10, 5),
(11, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int NOT NULL,
  `user_id` bigint NOT NULL,
  `token` varchar(255) NOT NULL,
  `type` enum('login','password_reset') NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `type`, `expires_at`, `created_at`) VALUES
(15, 4, '6da4db065614b079da9ef38327d48397defec1b7dcfcffe1657d3191a00a2030', 'login', '2024-08-13 00:33:17', '2024-09-09 14:58:30'),
(19, 5, 'd31f0463e60cc66f4f0f4f96e32eb7f57df2445c46977589e4b09335b0fdf296', 'login', '2024-08-15 01:38:54', '2024-09-09 14:58:30'),
(20, 6, '31ca70734117168bc0f1056eb8a1ee2c439bbe1655d1b58c839a887e7ff46179', 'login', '2024-08-16 18:34:23', '2024-09-09 14:58:30'),
(21, 6, '201d32ed9f1400d23444288b7376cd70ca49c3efe06497cce649eface268dd1e', 'login', '2024-08-16 19:15:45', '2024-09-09 14:58:30'),
(24, 6, 'f91f5fb213d2ce8191769417509d01d8abb263449d3cdf43882f9855c3875183', 'login', '2024-08-16 21:08:20', '2024-09-09 14:58:30'),
(25, 6, '768a3b644c48043868b481d8448beffef2794f1227dfb88407d0c8ba7c998ac9', 'login', '2024-08-16 22:14:56', '2024-09-09 14:58:30'),
(26, 6, 'd3006c3c4e2b5c2570d5b0a671bc263523dbefe4ce0089ba6e76a8b2d27ab6e7', 'login', '2024-08-16 22:21:00', '2024-09-09 14:58:30'),
(27, 4, '00734fc342dfbc8b63122fe3749d0132e5494d9b1c6006ecf269ef1f3d09bda0', 'login', '2024-08-16 22:23:04', '2024-09-09 14:58:30'),
(28, 5, '6499a5e5442ec8379edb63800a003023cba64da463864c6ba6d5cd19d1405538', 'login', '2024-08-17 00:02:55', '2024-09-09 14:58:30'),
(29, 6, '030e3437096ce610df21e31f9198641389ab6dddfab901d4173d5633731501c5', 'login', '2024-08-17 01:15:10', '2024-09-09 14:58:30'),
(30, 6, '6c347fb8ea00a19b0e7153e5f432ca9d75b8f5c778b135817a833b53fe23d1b9', 'login', '2024-08-17 01:26:46', '2024-09-09 14:58:30'),
(31, 6, '723690c922539b7984ad7c2df6e00889021b5d6f13b8b7786f9ce2dd84ed3a4b', 'login', '2024-08-17 01:26:52', '2024-09-09 14:58:30'),
(32, 6, 'be4cc8f60af78f4bb8b7ea353bd9643e19e9630812161ae6937de15d668ef171', 'login', '2024-08-17 01:27:59', '2024-09-09 14:58:30'),
(33, 6, '89e4afef7a47d3fd518ceb95400b21ad382eec48b3338adc9270ebca3ff5beb2', 'login', '2024-08-17 01:29:33', '2024-09-09 14:58:30'),
(34, 6, 'a16220c5b05fd0e32e71751b0de5a7e632af6f48ff246304fcc5e3266776dfda', 'login', '2024-08-17 01:36:16', '2024-09-09 14:58:30'),
(35, 6, '1043b95eb13dfea12d5db6e3be94b46b66c22d451b7f019c937c4cb465cb580a', 'login', '2024-08-17 01:36:18', '2024-09-09 14:58:30'),
(50, 4, '3b4c958d5c94bd6a71e2625d30eb88ce1cdcaa9bad8d73eb16577d4869fcf425', 'login', '2024-08-18 02:52:37', '2024-09-09 14:58:30'),
(51, 7, 'bbb1a3a1839ae441ebe46badbf50173f198e9eb0e4b2e28349d27360800cca02', 'login', '2024-08-18 20:51:43', '2024-09-09 14:58:30'),
(55, 6, 'e253ca56aba6f18f3cd98ac943d6047470d9c9a8446a3d55a33009e793512bf7', 'login', '2024-08-21 20:00:12', '2024-09-09 14:58:30'),
(57, 9, '5f21c8e1fa3f1eec367f06fbb1a91c3a43157519a605a1b0ef2f1eba2684e0df', 'login', '2024-08-22 01:48:02', '2024-09-09 14:58:30'),
(59, 10, '1f1a0ea43923a2d644d31dcc501a453363a94356d30cedfa00be5725931dc54d', 'login', '2024-08-23 22:15:39', '2024-09-09 14:58:30'),
(140, 15, '8a55573512f3613fbe25fe9af16d4e2c', 'password_reset', '2024-09-11 02:01:05', '2024-09-09 21:01:05'),
(152, 3, '143ca8329f8a4937a146e70553e99e01', 'password_reset', '2024-09-11 19:38:30', '2024-09-10 14:38:30'),
(186, 3, 'd20c4b8c072d1f588c94ed9fcf12f501', 'login', '2024-09-14 03:39:59', '2024-09-12 22:39:59'),
(189, 15, '40d9df8da34e3fa876f787c5f88c7706', 'login', '2024-09-14 03:58:48', '2024-09-12 22:58:48'),
(195, 14, '44a0beb294fdd1584acb075bbb1c4c9e', 'login', '2024-09-18 20:56:17', '2024-09-17 15:56:17'),
(200, 11, '686221558ffc2ad45ce9c152c27216e6', 'login', '2024-09-21 21:19:52', '2024-09-20 16:19:52'),
(203, 8, '7b5b342f8bb90b0fc91e160c924800f0', 'password_reset', '2024-09-21 21:31:09', '2024-09-20 16:31:09'),
(204, 8, '2d47a01336c7ea4f503277ab7f8084c7', 'login', '2024-09-21 21:33:55', '2024-09-20 16:33:55'),
(207, 17, '1775105c3f4742d87fdf249216760525', 'login', '2024-09-22 02:10:14', '2024-09-20 21:10:14'),
(210, 16, '7d9c8378c57e5e46b74a576345eb859b', 'login', '2024-09-22 11:51:02', '2024-09-21 04:51:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_membresia`
--

CREATE TABLE `usuario_membresia` (
  `id` int NOT NULL,
  `usuario_id` bigint DEFAULT NULL,
  `membresia_id` int DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estadisticas_usuario`
--
ALTER TABLE `estadisticas_usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Indices de la tabla `membresias`
--
ALTER TABLE `membresias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emisor_id` (`emisor_id`),
  ADD KEY `receptor_id` (`receptor_id`),
  ADD KEY `idx_fecha_envio` (`fecha_envio`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reacciones_mensajes`
--
ALTER TABLE `reacciones_mensajes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mensaje_id` (`mensaje_id`,`usuario_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `role_permiso`
--
ALTER TABLE `role_permiso`
  ADD PRIMARY KEY (`role_id`,`permiso_id`),
  ADD KEY `permiso_id` (`permiso_id`);

--
-- Indices de la tabla `solicitudes_musica`
--
ALTER TABLE `solicitudes_musica`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_track_request_per_user` (`usuario_id`,`spotify_track_id`),
  ADD KEY `idx_spotify_track_id` (`spotify_track_id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Gmail_UNIQUE` (`Gmail`),
  ADD UNIQUE KEY `usuario_UNIQUE` (`Apodo`);

--
-- Indices de la tabla `tb_usuarios_role`
--
ALTER TABLE `tb_usuarios_role`
  ADD PRIMARY KEY (`usuario_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indices de la tabla `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indices de la tabla `usuario_membresia`
--
ALTER TABLE `usuario_membresia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `membresia_id` (`membresia_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `membresias`
--
ALTER TABLE `membresias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reacciones_mensajes`
--
ALTER TABLE `reacciones_mensajes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `solicitudes_musica`
--
ALTER TABLE `solicitudes_musica`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT de la tabla `usuario_membresia`
--
ALTER TABLE `usuario_membresia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estadisticas_usuario`
--
ALTER TABLE `estadisticas_usuario`
  ADD CONSTRAINT `estadisticas_usuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`emisor_id`) REFERENCES `tb_usuarios` (`id`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`receptor_id`) REFERENCES `tb_usuarios` (`id`);

--
-- Filtros para la tabla `reacciones_mensajes`
--
ALTER TABLE `reacciones_mensajes`
  ADD CONSTRAINT `reacciones_mensajes_ibfk_1` FOREIGN KEY (`mensaje_id`) REFERENCES `mensajes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reacciones_mensajes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_permiso`
--
ALTER TABLE `role_permiso`
  ADD CONSTRAINT `role_permiso_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permiso_ibfk_2` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitudes_musica`
--
ALTER TABLE `solicitudes_musica`
  ADD CONSTRAINT `solicitudes_musica_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tb_usuarios_role`
--
ALTER TABLE `tb_usuarios_role`
  ADD CONSTRAINT `tb_usuarios_role_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tb_usuarios_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_usuarios` (`id`);

--
-- Filtros para la tabla `usuario_membresia`
--
ALTER TABLE `usuario_membresia`
  ADD CONSTRAINT `usuario_membresia_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `tb_usuarios` (`id`),
  ADD CONSTRAINT `usuario_membresia_ibfk_2` FOREIGN KEY (`membresia_id`) REFERENCES `membresias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
