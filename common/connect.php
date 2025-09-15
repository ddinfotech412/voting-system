<?php
// Include error logger
require_once 'errorLogger.php';

$sname="localhost";
$unmae="root";
$password="";

$db_name="voting_system";

$conn = mysqli_connect($sname,$unmae,$password,$db_name);

// Check connection and log errors
if (!$conn) {
    $error = "Database connection failed: " . mysqli_connect_error();
    ErrorLogger::logDatabaseError("Connection", $error, [
        'host' => $sname,
        'username' => $unmae,
        'database' => $db_name
    ]);
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
if (!mysqli_set_charset($conn, "utf8")) {
    ErrorLogger::logDatabaseError("Charset", "Error setting charset: " . mysqli_error($conn));
}

// Log successful connection
ErrorLogger::logError("Database connection established successfully", [
    'host' => $sname,
    'database' => $db_name
]);
?>


<!-- $sname="localhost";
$unmae="id21723216_root";
$password="SojitH!123";

$db_name="id21723216_voting_system";

$conn = mysqli_connect($sname,$unmae,$password,$db_name); -->