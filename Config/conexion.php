<?php
require_once "global.php";

$conexion = mysqli_connect("DB_HOST", "DB_NAME", "DB_USERNAME", "DB_PASSWORD");

mysqli_query($conexion, 'SET NAMES"' . DB_ENCODE . '"');

if (mysqli_connect_errno()) {
    print "Error al conectar con la base de datos: " . mysqli_connect_error();
    exit();
}

function ejecutarConsulta($sql)
{
    global $conexion;
    $query = mysqli_query($conexion, $sql);
    return $query;
}

function ejecutarConsultaUnica($sql){
    global $conexion;
    $query = mysqli_query($conexion, $sql);
    $row = $query->fetch_assoc(); // Corregido: se agregó el signo de dólar a 'row'
    return $row;
}

//funcion para ejecutar consultas

function ejecutarConsulta_retornarID($sql){
    global $conexion;
    $query = mysqli_query($conexion, $sql);
    return $conexion->insert_id;
}


//funcion para limpiar cadena

function limpiarCadena($str){
    global $conexion;
    $str = mysqli_real_escape_string($conexion, trim($str));
    return htmlspecialchars($str);
}