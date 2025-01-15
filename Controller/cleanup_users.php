<?php

require_once 'SoftDeleteController.php';

$controller = new SoftDeleteController();
$result = $controller->programarEliminacionPeriodica();

// Registrar resultado
error_log(date('Y-m-d H:i:s') . " - Limpieza de usuarios: " . 
          json_encode($result) . "\n", 3, "cleanup_log.txt");