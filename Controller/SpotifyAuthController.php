<?php
require_once './Config/spotify_config.php';

class SpotifyAuthController {
    public function initiateAuth() {
        global $SPOTIFY_SCOPES;
        $scope = implode(' ', $SPOTIFY_SCOPES);
        
        $params = [
            'client_id' => SPOTIFY_CLIENT_ID,
            'response_type' => 'code',
            'redirect_uri' => SPOTIFY_REDIRECT_URI,
            'scope' => $scope,
        ];
        
        $authUrl = SPOTIFY_AUTH_URL . '?' . http_build_query($params);
        header("Location: $authUrl");
        exit;
    }

    public function handleCallback() {
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            $token = $this->getAccessToken($code);
            
            if ($token) {
                $_SESSION['spotify_access_token'] = $token->access_token;
                $_SESSION['spotify_refresh_token'] = $token->refresh_token;
                $_SESSION['spotify_token_expires'] = time() + $token->expires_in;
                
                $userInfo = $this->getUserInfo($token->access_token);
                return $userInfo;
            }else {
                // Manejar el error
                error_log('Error al obtener el token de acceso: ' . json_encode($token));
                return false;
            }
        }
        return null;
    }

    private function getAccessToken($code) {
        $postData = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => SPOTIFY_REDIRECT_URI,
            'client_id' => SPOTIFY_CLIENT_ID,
            'client_secret' => SPOTIFY_CLIENT_SECRET
        ];

        $ch = curl_init(SPOTIFY_TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    private function getUserInfo($accessToken) {
        $ch = curl_init(SPOTIFY_API_BASE_URL . '/me');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}