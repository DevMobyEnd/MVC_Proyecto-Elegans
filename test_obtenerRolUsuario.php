<?php

// Incluye el archivo que contiene la clase UsuarioModel
require_once 'Models/UsuarioModel.php';

// Ejecuta la prueba
$usuarioId = 14; // Reemplaza con el ID de usuario que deseas probar
$usuarioModel = new UsuarioModel();
$rol = $usuarioModel->obtenerRolUsuario($usuarioId);

if ($rol === null) {
    echo "Error preparando la consulta: " . $usuarioModel = new UsuarioModel();
    $rol = $usuarioModel->obtenerRolUsuario($usuarioId);
} elseif ($rol === 'rol_no_disponible') {
    echo "No se encontró el rol del usuario";
} else {
    echo "El rol del usuario es: " . $rol;
}