<?php

class Utilidades {
    public static function retornadorDato($des, $valor) {
        // Inicializar las variables para evitar errores de "undefined variable"
        $campo = '';
        $tabla = '';
        $busqueda = '';

        // Definir los valores de las variables según el descriptor
        if ($des == 1) {
            $campo = "Nombre";
            $tabla = "tb_Usuarios";
            $busqueda = "documento";
        }
        // Asegúrate de agregar más condiciones si necesitas manejar más casos

        // Construye la consulta SQL
        $sql = "SELECT $campo FROM $tabla WHERE $busqueda = '$valor'";

        // Retorna la consulta SQL
        return $sql;
    }
}

?>