<?php
// Config/config.php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Aquí puedes definir la constante CF_SECRET_KEY
define('CF_SECRET_KEY', $_ENV['CF_SECRET_KEY'] ?? '');

define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB