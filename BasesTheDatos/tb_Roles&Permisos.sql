-- Creación de la tabla 'roles'
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

-- Creación de la tabla 'permisos'
CREATE TABLE permisos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255)
);

-- Creación de la tabla 'role_permiso'
CREATE TABLE role_permiso (
    role_id INT,
    permiso_id INT,
    PRIMARY KEY (role_id, permiso_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE
);

-- Creación de la tabla 'usuario_role'
CREATE TABLE tb_usuarios_role (
    usuario_id BIGINT, -- Cambiado de INT a BIGINT para coincidir con tb_usuarios.id
    role_id INT,
    PRIMARY KEY (usuario_id, role_id),
    FOREIGN KEY (usuario_id) REFERENCES tb_usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);