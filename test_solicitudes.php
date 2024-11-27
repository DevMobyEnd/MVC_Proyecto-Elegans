<?php
// Código para iniciar sesión y cargar el modelo
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "Error: Usuario no autenticado.";
    exit();
}

require_once __DIR__ . '/Models/UsuarioModel.php';
$usuarioModel = new UsuarioModel();

$usuarioId = $_SESSION['usuario_id'];
$solicitudesMusica = $usuarioModel->obtenerInformacionSolicitudesMusica($usuarioId);

// Incluye la vista, pasando las solicitudes de música como una variable
include 'Views/layout/Myperfil/head.php'; // Cambia la ruta a la ubicación de tu vista

