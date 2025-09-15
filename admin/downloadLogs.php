<?php
session_start();
include_once '../common/connect.php';
include_once '../common/errorLogger.php';

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['id'] != 'admin') {
    header("Location:../login.php");
    exit();
}

$logFile = '../logs/error.log';

if (file_exists($logFile)) {
    // Log the download action
    ErrorLogger::logError("Error logs downloaded by admin", ['admin_id' => $_SESSION['id']]);
    
    // Set headers for file download
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="error_log_' . date('Y-m-d_H-i-s') . '.txt"');
    header('Content-Length: ' . filesize($logFile));
    
    // Output the file content
    readfile($logFile);
    exit();
} else {
    $_SESSION['errorMessage'] = "Log file not found.";
    header("Location:admin.php?page=errorLogs");
    exit();
}
?>
