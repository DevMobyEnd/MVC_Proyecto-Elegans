<?php

class Logger {
    private $logFile;

    public function __construct($logFile = '../logs/app.log') {
        $this->logFile = $logFile;
        $this->createLogFile();
    }

    private function createLogFile() {
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        if (!file_exists($this->logFile)) {
            touch($this->logFile);
        }
    }

    public function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        try {
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        } catch (Exception $e) {
            // Escribir el mensaje de registro en un archivo diferente o en una ruta diferente
            file_put_contents('logs/error.log', $logMessage, FILE_APPEND);
        }
    }

    public function error($message) {
        $this->log($message, 'ERROR');
    }

    public function info($message) {
        $this->log($message, 'INFO');
    }
}