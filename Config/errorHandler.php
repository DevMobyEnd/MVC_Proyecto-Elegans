<?php
function manejarError($nivelError, $mensajeError, $archivo, $linea) {
    $mensaje = "Se ha producido un error: en el archivo $archivo en la línea $linea: $mensajeError";
    // Aquí podrías redirigir a una página de error o simplemente mostrar el mensaje.
    echo "<div style='background-color: #ffdddd; padding: 20px; margin: 15px; border-left: 6px solid #f44336;'>
            <strong>Error:</strong> $mensaje
          </div>";
    exit();
}

function manejarExcepcion($excepcion) {
    $mensaje = "Se ha producido una excepción: " . $excepcion->getMessage();
    // Aquí podrías redirigir a una página de error o simplemente mostrar el mensaje.
    echo "<div style='background-color: #ddffdd; padding: 20px; margin: 15px; border-left: 6px solid #4CAF50;'>
            <strong>Excepción:</strong> $mensaje
          </div>";
    exit();
}

set_error_handler("manejarError");
set_exception_handler("manejarExcepcion");
?>