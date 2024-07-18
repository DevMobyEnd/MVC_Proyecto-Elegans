show databases;
create schema db_pruebaita;
use db_Pruebita;

drop table tb_usuarios;
ALTER TABLE tb_usuarios
CHANGE COLUMN usuario Apodo VARCHAR(100) DEFAULT NULL;

ALTER TABLE tb_usuarios
ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL;

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


select * from tb_usuarios;

@Anilamejor237

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

SELECT suma_algua_cosa(1.5, 1.6);

DELIMITER //

CREATE FUNCTION promediar_notas(nota1 FLOAT, nota2 FLOAT) RETURNS FLOAT
DETERMINISTIC
BEGIN
    DECLARE suma FLOAT;
    DECLARE promedio FLOAT;
    
    -- Utilizamos la función suma_algua_cosa existente para sumar las notas
    SET suma = suma_algua_cosa(nota1, nota2);
    
    -- Calculamos el promedio dividiendo la suma entre 2
    SET promedio = suma / 2;
    
    RETURN promedio;
END //

DELIMITER ;

SELECT promediar_notas(8.5, 9.0) AS promedio;

DELIMITER //

/* ------------ Conteo usuarios       */
CREATE FUNCTION contar_usuarios() RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM tb_usuarios;
    RETURN total;
END //

DELIMITER ;

SELECT contar_usuarios();
select * from tb_usuarios;
describe tb_usuarios;
delete from tb_usuarios;

DELIMITER //

CREATE FUNCTION calcular_pago_usuarios() RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE total_usuarios INT;
    DECLARE pago_total DECIMAL(10,2);
    
    -- Contar el número de usuarios
    SELECT COUNT(*) INTO total_usuarios FROM tb_usuarios;
    
    -- Calcular el pago total (4000 pesos por usuario)
    SET pago_total = total_usuarios * 4000.00;
    
    RETURN pago_total;
END //

DELIMITER ;

SELECT calcular_pago_usuarios() AS pago_total;

DELIMITER //
CREATE FUNCTION numero_mayor() RETURNS varchar( 200 )
DETERMINISTIC
BEGIN
    RETURN 'Jhonny Alexander Para Practicas "______"';
END //
DELIMITER ;

SELECT numero_mayor();
