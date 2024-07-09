show databases;
create schema db_pruebaita;
use db_Pruebita;

drop table tb_usuarios;

CREATE TABLE `tb_usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `Gmail` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `numero_documento` varchar(50) DEFAULT NULL, -- Campo para el número de documento
  `usuario` varchar(100) DEFAULT NULL, -- Campo para el nombre de usuario
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Gmail_UNIQUE` (`Gmail`), -- Asegura que el correo sea único
  UNIQUE KEY `usuario_UNIQUE` (`usuario`) -- Opcional: si deseas que el nombre de usuario también sea único
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
