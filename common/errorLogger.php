<?php
/**
 * Error Logger for Voting System
 * Centralized error logging functionality
 */

class ErrorLogger {
    private static $logFile = '../logs/error.log';
    private static $maxLogSize = 10485760; // 10MB
    private static $maxLogFiles = 5;
    
    /**
     * Initialize error logging
     */
    public static function init() {
        // Create logs directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Set error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', self::$logFile);
        
        // Set custom error handler
        set_error_handler([self::class, 'customErrorHandler']);
        set_exception_handler([self::class, 'customExceptionHandler']);
        register_shutdown_function([self::class, 'handleFatalError']);
    }
    
    /**
     * Custom error handler
     */
    public static function customErrorHandler($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        
        $errorType = self::getErrorType($severity);
        $logMessage = self::formatLogMessage($errorType, $message, $file, $line);
        self::writeToLog($logMessage);
        
        // Don't execute PHP internal error handler
        return true;
    }
    
    /**
     * Custom exception handler
     */
    public static function customExceptionHandler($exception) {
        $logMessage = self::formatLogMessage(
            'EXCEPTION',
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
        self::writeToLog($logMessage);
    }
    
    /**
     * Handle fatal errors
     */
    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
            $logMessage = self::formatLogMessage(
                'FATAL',
                $error['message'],
                $error['file'],
                $error['line']
            );
            self::writeToLog($logMessage);
        }
    }
    
    /**
     * Log custom error
     */
    public static function logError($message, $context = []) {
        $logMessage = self::formatLogMessage('CUSTOM', $message, '', 0, '', $context);
        self::writeToLog($logMessage);
    }
    
    /**
     * Log database error
     */
    public static function logDatabaseError($query, $error, $params = []) {
        $context = [
            'query' => $query,
            'params' => $params,
            'error' => $error
        ];
        $logMessage = self::formatLogMessage('DATABASE', 'Database error occurred', '', 0, '', $context);
        self::writeToLog($logMessage);
    }
    
    /**
     * Log authentication error
     */
    public static function logAuthError($message, $userId = '', $ip = '') {
        $context = [
            'user_id' => $userId,
            'ip_address' => $ip ?: $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        $logMessage = self::formatLogMessage('AUTH', $message, '', 0, '', $context);
        self::writeToLog($logMessage);
    }
    
    /**
     * Log voting error
     */
    public static function logVotingError($message, $userId = '', $candidateId = '') {
        $context = [
            'user_id' => $userId,
            'candidate_id' => $candidateId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        $logMessage = self::formatLogMessage('VOTING', $message, '', 0, '', $context);
        self::writeToLog($logMessage);
    }
    
    /**
     * Get error type from severity
     */
    private static function getErrorType($severity) {
        switch ($severity) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                return 'ERROR';
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                return 'WARNING';
            case E_PARSE:
                return 'PARSE';
            case E_NOTICE:
            case E_USER_NOTICE:
                return 'NOTICE';
            case E_STRICT:
                return 'STRICT';
            case E_RECOVERABLE_ERROR:
                return 'RECOVERABLE';
            default:
                return 'UNKNOWN';
        }
    }
    
    /**
     * Format log message
     */
    private static function formatLogMessage($type, $message, $file = '', $line = 0, $trace = '', $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $requestUri = $_SERVER['REQUEST_URI'] ?? 'unknown';
        
        $logEntry = "[$timestamp] [$type] [$ip] ";
        
        if ($file) {
            $logEntry .= basename($file);
            if ($line) {
                $logEntry .= ":$line";
            }
            $logEntry .= " - ";
        }
        
        $logEntry .= $message;
        
        if ($trace) {
            $logEntry .= "\nStack Trace:\n$trace";
        }
        
        if (!empty($context)) {
            $logEntry .= "\nContext: " . json_encode($context, JSON_PRETTY_PRINT);
        }
        
        $logEntry .= "\nRequest URI: $requestUri";
        $logEntry .= "\nUser Agent: $userAgent";
        $logEntry .= "\n" . str_repeat('-', 80) . "\n";
        
        return $logEntry;
    }
    
    /**
     * Write to log file
     */
    private static function writeToLog($message) {
        $logFile = self::$logFile;
        
        // Check if log rotation is needed
        if (file_exists($logFile) && filesize($logFile) > self::$maxLogSize) {
            self::rotateLogs();
        }
        
        // Write to log file
        file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Rotate log files
     */
    private static function rotateLogs() {
        $logFile = self::$logFile;
        
        // Rotate existing logs
        for ($i = self::$maxLogFiles - 1; $i > 0; $i--) {
            $oldFile = $logFile . '.' . $i;
            $newFile = $logFile . '.' . ($i + 1);
            if (file_exists($oldFile)) {
                rename($oldFile, $newFile);
            }
        }
        
        // Move current log to .1
        if (file_exists($logFile)) {
            rename($logFile, $logFile . '.1');
        }
    }
    
    /**
     * Get recent errors
     */
    public static function getRecentErrors($limit = 50) {
        $logFile = self::$logFile;
        if (!file_exists($logFile)) {
            return [];
        }
        
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $errors = [];
        $currentError = '';
        
        foreach (array_reverse($lines) as $line) {
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \[(\w+)\] \[([^\]]+)\] (.+)/', $line, $matches)) {
                if ($currentError) {
                    $errors[] = $currentError;
                    if (count($errors) >= $limit) break;
                }
                $currentError = [
                    'timestamp' => $matches[1],
                    'type' => $matches[2],
                    'ip' => $matches[3],
                    'message' => $matches[4]
                ];
            } else {
                $currentError['details'] = ($currentError['details'] ?? '') . $line . "\n";
            }
        }
        
        if ($currentError) {
            $errors[] = $currentError;
        }
        
        return $errors;
    }
    
    /**
     * Clear log file
     */
    public static function clearLogs() {
        $logFile = self::$logFile;
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
    }
    
    /**
     * Get log file size
     */
    public static function getLogSize() {
        $logFile = self::$logFile;
        if (file_exists($logFile)) {
            return filesize($logFile);
        }
        return 0;
    }
}

// Initialize error logging when this file is included
ErrorLogger::init();
?>
