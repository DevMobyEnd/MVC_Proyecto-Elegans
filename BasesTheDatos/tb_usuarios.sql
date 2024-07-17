show databases;
create schema db_pruebaita;
use db_Pruebita;

drop table tb_usuarios;
ALTER TABLE tb_usuarios
CHANGE COLUMN usuario Apodo VARCHAR(100) DEFAULT NULL;

CREATE TABLE `tb_usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `Gmail` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `numero_documento` varchar(50) DEFAULT NULL, -- Campo para el número de documento
  `Apodo` varchar(100) DEFAULT NULL, -- Campo para el Apodo de usuario
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Gmail_UNIQUE` (`Gmail`), -- Asegura que el correo sea único
  UNIQUE KEY `usuario_UNIQUE` (`Apodo`) -- Opcional: si deseas que el nombre de usuario también sea único
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DELIMITER //
CREATE FUNCTION retornar_algua_cosa() RETURNS FLOAT
DETERMINISTIC
BEGIN
    RETURN 0.0;
END //
DELIMITER ;

select retornar_algua_cosa();



DELIMITER //
CREATE FUNCTION suma_algua_cosa(num1 FLOAT, num2 FLOAT) RETURNS FLOAT
DETERMINISTIC
BEGIN
    RETURN num1 + num2;
END //
DELIMITER ;

SELECT suma_algua_cosa(5.5, 3.2);

DELIMITER //
CREATE FUNCTION numero_mayor() RETURNS varchar( 200 )
DETERMINISTIC
BEGIN
    RETURN 'Jhonny Alexander Para Practicas "______"';
END //
DELIMITER ;

SELECT numero_mayor();
