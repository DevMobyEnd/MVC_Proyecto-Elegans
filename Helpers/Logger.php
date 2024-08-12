<?php

class Logger {
    private $logFile;

    public function __construct($logFile = '../logs/app.log') {
        $this->logFile = $logFile;
    }

    public function log($message, $level = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    public function error($message) {
        $this->log($message, 'ERROR');
    }

    public function info($message) {
        $this->log($message, 'INFO');
    }
}