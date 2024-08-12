<?php
class CaptchaVerifier
{
    public function verify($token)
    {
        $secretKey = CF_SECRET_KEY;
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

        $data = [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => $remoteip
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $responseKeys = json_decode($result, true);

        return isset($responseKeys["success"]) && $responseKeys["success"] === true;
    }
}