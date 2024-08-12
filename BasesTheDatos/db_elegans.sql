show databases;
create schema db_Pruebita;
use db_Pruebita;

drop database db_pruebaita3;
drop table tb_usuarios;
ALTER TABLE tb_usuarios
CHANGE COLUMN usuario Apodo VARCHAR(100) DEFAULT NULL;

ALTER TABLE tb_usuarios
ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL;

-- Tabla de usuarios (actualizada)
CREATE TABLE `tb_usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `Gmail` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `numero_documento` varchar(50) DEFAULT NULL,
  `Apodo` varchar(100) DEFAULT NULL,
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Gmail_UNIQUE` (`Gmail`),
  UNIQUE KEY `usuario_UNIQUE` (`Apodo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabla de roles (existente)
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

-- Tabla de permisos (existente)
CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255)
);

-- Tabla de relación role-permiso (existente)
CREATE TABLE role_permiso (
    role_id INT,
    permiso_id INT,
    PRIMARY KEY (role_id, permiso_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE
);

-- Tabla de relación usuario-role (existente)
CREATE TABLE tb_usuarios_role (
    usuario_id BIGINT,
    role_id INT,
    PRIMARY KEY (usuario_id, role_id),
    FOREIGN KEY (usuario_id) REFERENCES tb_usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Nueva tabla para solicitudes de música
CREATE TABLE solicitudes_musica (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT,
    spotify_track_id VARCHAR(255),
    nombre_cancion VARCHAR(255),
    imagen_url VARCHAR(512),
    estado ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES tb_usuarios(id) ON DELETE CASCADE
);

-- Nueva tabla para membresías
CREATE TABLE membresias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    duracion INT,
    precio DECIMAL(10,2)
);

-- Nueva tabla para asociar usuarios con membresías
CREATE TABLE usuario_membresia (
    usuario_id BIGINT,
    membresia_id INT,
    fecha_inicio DATE,
    fecha_fin DATE,
    FOREIGN KEY (usuario_id) REFERENCES tb_usuarios(id),
    FOREIGN KEY (membresia_id) REFERENCES membresias(id)
);

-- Nueva tabla para estadísticas de usuario
CREATE TABLE estadisticas_usuario (
    usuario_id BIGINT,
    total_solicitudes INT DEFAULT 0,
    solicitudes_aceptadas INT DEFAULT 0,
    solicitudes_rechazadas INT DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES tb_usuarios(id)
);
-- Actualizamos la tabla de estadísticas de usuario
ALTER TABLE estadisticas_usuario
ADD COLUMN mensajes_enviados_global INT DEFAULT 0,
ADD COLUMN mensajes_enviados_privado INT DEFAULT 0;


DESCRIBE estadisticas_usuario;

drop tables mensajes;
-- Tabla para mensajes (actualizamos para incluir chat global y privado)
CREATE TABLE mensajes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    emisor_id BIGINT,
    receptor_id BIGINT NULL, -- NULL para mensajes en el chat global
    contenido TEXT,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    es_global BOOLEAN DEFAULT FALSE,
    likes INT DEFAULT 0,
    dislikes INT DEFAULT 0,
    FOREIGN KEY (emisor_id) REFERENCES tb_usuarios(id),
    FOREIGN KEY (receptor_id) REFERENCES tb_usuarios(id)
);
-- Trigger para actualizar estadísticas cuando se crea una solicitud
DELIMITER //
CREATE TRIGGER after_solicitud_insert
AFTER INSERT ON solicitudes_musica
FOR EACH ROW
BEGIN
    INSERT INTO estadisticas_usuario (usuario_id, total_solicitudes)
    VALUES (NEW.usuario_id, 1)
    ON DUPLICATE KEY UPDATE total_solicitudes = total_solicitudes + 1;
END;
//
DELIMITER ;

-- Trigger para actualizar estadísticas cuando se actualiza el estado de una solicitud
DELIMITER //
CREATE TRIGGER after_solicitud_update
AFTER UPDATE ON solicitudes_musica
FOR EACH ROW
BEGIN
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
END;
//
DELIMITER ;

-- Arreglos a la base de datos 
-- Modificar la tabla de mensajes para incluir contadores de likes y dislikes
ALTER TABLE mensajes
ADD COLUMN likes INT DEFAULT 0,
ADD COLUMN dislikes INT DEFAULT 0;

-- Tabla para reacciones a los mensajes
CREATE TABLE reacciones_mensajes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    mensaje_id BIGINT,
    usuario_id BIGINT,
    tipo_reaccion ENUM('like', 'dislike'),
    fecha_reaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mensaje_id) REFERENCES mensajes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES tb_usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY (mensaje_id, usuario_id)
);
-- Trigger para actualizar los contadores de likes/dislikes en la tabla de mensajes
DELIMITER //
CREATE TRIGGER after_reaccion_insert
AFTER INSERT ON reacciones_mensajes
FOR EACH ROW
BEGIN
    IF NEW.tipo_reaccion = 'like' THEN
        UPDATE mensajes SET likes = likes + 1 WHERE id = NEW.mensaje_id;
    ELSE
        UPDATE mensajes SET dislikes = dislikes + 1 WHERE id = NEW.mensaje_id;
    END IF;
END;
//

CREATE TRIGGER after_reaccion_delete
AFTER DELETE ON reacciones_mensajes
FOR EACH ROW
BEGIN
    IF OLD.tipo_reaccion = 'like' THEN
        UPDATE mensajes SET likes = likes - 1 WHERE id = OLD.mensaje_id;
    ELSE
        UPDATE mensajes SET dislikes = dislikes - 1 WHERE id = OLD.mensaje_id;
    END IF;
END;
//

-- Trigger para actualizar las estadísticas del usuario cuando recibe un like o dislike
CREATE TRIGGER after_reaccion_insert_stats
AFTER INSERT ON reacciones_mensajes
FOR EACH ROW
BEGIN
    DECLARE autor_mensaje BIGINT;
    SELECT emisor_id INTO autor_mensaje FROM mensajes WHERE id = NEW.mensaje_id;
    
    IF NEW.tipo_reaccion = 'like' THEN
        UPDATE estadisticas_usuario 
        SET likes_recibidos = likes_recibidos + 1 
        WHERE usuario_id = autor_mensaje;
    ELSE
        UPDATE estadisticas_usuario 
        SET dislikes_recibidos = dislikes_recibidos + 1 
        WHERE usuario_id = autor_mensaje;
    END IF;
END;
//

CREATE TRIGGER after_reaccion_delete_stats
AFTER DELETE ON reacciones_mensajes
FOR EACH ROW
BEGIN
    DECLARE autor_mensaje BIGINT;
    SELECT emisor_id INTO autor_mensaje FROM mensajes WHERE id = OLD.mensaje_id;
    
    IF OLD.tipo_reaccion = 'like' THEN
        UPDATE estadisticas_usuario 
        SET likes_recibidos = likes_recibidos - 1 
        WHERE usuario_id = autor_mensaje;
    ELSE
        UPDATE estadisticas_usuario 
        SET dislikes_recibidos = dislikes_recibidos - 1 
        WHERE usuario_id = autor_mensaje;
    END IF;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_mensaje_insert
AFTER INSERT ON mensajes
FOR EACH ROW
BEGIN
    IF NEW.es_global THEN
        UPDATE estadisticas_usuario 
        SET mensajes_enviados_global = mensajes_enviados_global + 1 
        WHERE usuario_id = NEW.emisor_id;
    ELSE
        UPDATE estadisticas_usuario 
        SET mensajes_enviados_privado = mensajes_enviados_privado + 1 
        WHERE usuario_id = NEW.emisor_id;
    END IF;
END;
//

-- Trigger para actualizar likes/dislikes en mensajes y estadísticas de usuario
CREATE TRIGGER after_reaccion_insert
AFTER INSERT ON reacciones_mensajes
FOR EACH ROW
BEGIN
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
END;
//
DELIMITER ;


CREATE INDEX idx_spotify_track_id ON solicitudes_musica(spotify_track_id);

ALTER TABLE usuario_membresia
ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE estadisticas_usuario
ADD PRIMARY KEY (usuario_id);

CREATE INDEX idx_fecha_envio ON mensajes(fecha_envio);

ALTER TABLE tb_usuarios
ADD COLUMN activo BOOLEAN DEFAULT TRUE;

ALTER TABLE membresias
ADD COLUMN activo BOOLEAN DEFAULT TRUE;

ALTER TABLE membresias
ADD CONSTRAINT chk_precio_positivo CHECK (precio >= 0);

ALTER TABLE mensajes
ADD CONSTRAINT chk_likes_positivo CHECK (likes >= 0),
ADD CONSTRAINT chk_dislikes_positivo CHECK (dislikes >= 0);

-- verificar la estructura de tus tablas:
DESCRIBE solicitudes_musica;
DESCRIBE usuario_membresia;
DESCRIBE estadisticas_usuario;
DESCRIBE mensajes;
DESCRIBE tb_usuarios;
DESCRIBE membresias;

-- Y para ver los índices:
SHOW INDEX FROM solicitudes_musica;
SHOW INDEX FROM mensajes;

CREATE TABLE user_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    token VARCHAR(255) NOT NULL,
    type ENUM('login', 'password_reset') NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES tb_usuarios(id)
);

ALTER TABLE tb_usuarios
ADD COLUMN login_attempts INT DEFAULT 0,
ADD COLUMN last_login_attempt TIMESTAMP;

ALTER TABLE tb_usuarios ADD COLUMN foto_perfil VARCHAR(255);

select * from tb_usuarios;














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
