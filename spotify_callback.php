<?php
session_start();
require_once './Controller/SpotifyAuthController.php';
require_once './Config/spotify_config.php';

$controller = new SpotifyAuthController();
$userInfo = $controller->handleCallback();

require_once './Views/spotify_auth_view.php';