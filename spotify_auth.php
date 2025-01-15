<?php

session_start();
require_once './Controller/SpotifyAuthController.php';

$controller = new SpotifyAuthController();
$controller->initiateAuth();

// Después de obtener los tokens de Spotify
$_SESSION['spotify_access_token'] = $accessToken;
$_SESSION['spotify_refresh_token'] = $refreshToken;
$_SESSION['spotify_scopes'] = $scopes;

header('Location: music_player.php');
exit();
?>