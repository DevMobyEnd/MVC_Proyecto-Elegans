<?php
// Config/config.php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Definición de las constantes
define('CF_SECRET_KEY', $_ENV['CF_SECRET_KEY'] ?? '');
define('SP_CLIENT_ID', $_ENV['SP_CLIENT_ID'] ?? '');
define('SP_CLIENT_SECRET', $_ENV['SP_CLIENT_SECRET'] ?? '');

// Agrega esto solo para depuración temporalmente
// if (defined('SP_CLIENT_ID') && defined('SP_CLIENT_SECRET')) {
//     echo 'SP_CLIENT_ID: ' . SP_CLIENT_ID . '<br>';
//     echo 'SP_CLIENT_SECRET: ' . SP_CLIENT_SECRET . '<br>';
// }

define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
