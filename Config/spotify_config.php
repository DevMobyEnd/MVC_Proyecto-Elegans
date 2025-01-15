<?php
define('SPOTIFY_CLIENT_ID', '960ffbb356cd4014a5d43dfa8995240a');
define('SPOTIFY_CLIENT_SECRET', '30ca7bd200524cd2a957bb939161fa32'); // Reemplaza con tu Client Secret
define('SPOTIFY_REDIRECT_URI', 'http://localhost:3000/spotify_callback.php');

define('SPOTIFY_AUTH_URL', 'https://accounts.spotify.com/authorize');
define('SPOTIFY_TOKEN_URL', 'https://accounts.spotify.com/api/token');
define('SPOTIFY_API_BASE_URL', 'https://api.spotify.com/v1');

$SPOTIFY_SCOPES = [
    "streaming",
    "user-read-email",
    "user-read-private",
    "user-modify-playback-state",
    "user-read-playback-state",
    "user-read-currently-playing"
];