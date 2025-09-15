<?php
session_start();
include_once '../common/connect.php';
include_once '../common/errorLogger.php';

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['id'] != 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Clear the error log file
$logFile = '../logs/error.log';
if (file_exists($logFile)) {
    if (file_put_contents($logFile, '') !== false) {
        ErrorLogger::logError("Error logs cleared by admin", ['admin_id' => $_SESSION['id']]);
        echo json_encode(['success' => true, 'message' => 'Logs cleared successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to clear logs']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Log file not found']);
}
?>
