<?php
class CaptchaVerifier
{
    private $logger;

    public function __construct($logger = null)
    {
        $this->logger = $logger;
    }

    public function verify($token)
    {
        if (!defined('CF_SECRET_KEY')) {
            $this->log("Error: CF_SECRET_KEY no está definida");
            return false;
        }

        $secretKey = CF_SECRET_KEY;
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

        $data = [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => $remoteip
        ];

        $this->log("Datos enviados a Cloudflare: " . print_r($data, true));

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            $this->log("Error al conectar con Cloudflare: " . error_get_last()['message']);
            return false;
        }

        $responseKeys = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->log("Error al decodificar la respuesta JSON: " . json_last_error_msg());
            return false;
        }

        $this->log("Respuesta de Cloudflare: " . print_r($responseKeys, true));

        if (!isset($responseKeys["success"])) {
            $this->log("La respuesta de Cloudflare no contiene la clave 'success'");
            return false;
        }

        return $responseKeys["success"] === true;
    }

    private function log($message)
    {
        if ($this->logger) {
            $this->logger->info($message);
        } else {
            error_log($message);
        }
    }
}