# FCRIT Voting System - Technical Implementation Guide

## Table of Contents
1. [Code Architecture](#code-architecture)
2. [Database Implementation](#database-implementation)
3. [Security Implementation](#security-implementation)
4. [User Interface Implementation](#user-interface-implementation)
5. [Admin Panel Implementation](#admin-panel-implementation)
6. [Error Handling Implementation](#error-handling-implementation)
7. [Voting Logic Implementation](#voting-logic-implementation)
8. [Analytics Implementation](#analytics-implementation)
9. [File Upload Implementation](#file-upload-implementation)
10. [Session Management](#session-management)

---

## Code Architecture

### File Structure Overview
```
voting-system/
├── admin/
│   ├── admin.php                 # Main admin interface
│   ├── sidebar.php              # Admin navigation
│   ├── content/                 # Admin page content
│   │   ├── dashboard.php        # Admin dashboard
│   │   ├── analytics.php        # Analytics and charts
│   │   ├── candidates.php       # Candidate management
│   │   ├── applications.php     # Application review
│   │   ├── electionStatus.php   # Election control
│   │   ├── userManagement.php   # User administration
│   │   ├── voteHistory.php      # Vote records
│   │   └── errorLogs.php        # Error monitoring
│   └── common/                  # Admin shared components
├── common/
│   ├── connect.php              # Database connection
│   ├── errorLogger.php          # Error logging system
│   ├── formActions.php          # Form processing
│   ├── voteActions.php          # Voting logic
│   ├── userAction.php           # User management
│   ├── registerAction.php       # Registration logic
│   ├── postLogin.php            # Login processing
│   ├── style.css                # Global styles
│   └── links.php                # Common includes
├── users/
│   ├── user.php                 # User dashboard
│   ├── voter.php                # Voting interface
│   ├── newNominee.php           # Candidate application
│   ├── nominee.php              # Nominee dashboard
│   ├── viewNominee.php          # View candidate details
│   └── successVote.php          # Vote confirmation
├── assets/                      # Static assets
├── logs/                        # Error logs
└── screenshots/                 # System screenshots
```

### Core PHP Classes and Functions

#### Database Connection (`common/connect.php`)
```php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_system";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
?>
```

#### Error Logger Class (`common/errorLogger.php`)
```php
class ErrorLogger {
    private static $logFile = '../logs/error.log';
    private static $maxLogSize = 10485760; // 10MB
    private static $maxLogFiles = 5;
    
    public static function init() {
        // Initialize error logging
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', self::$logFile);
        
        set_error_handler([self::class, 'customErrorHandler']);
        set_exception_handler([self::class, 'customExceptionHandler']);
        register_shutdown_function([self::class, 'handleFatalError']);
    }
    
    public static function logError($message, $context = []) {
        $logMessage = self::formatLogMessage('CUSTOM', $message, '', 0, '', $context);
        self::writeToLog($logMessage);
    }
    
    public static function logDatabaseError($query, $error, $params = []) {
        $context = [
            'query' => $query,
            'params' => $params,
            'error' => $error
        ];
        $logMessage = self::formatLogMessage('DATABASE', 'Database error occurred', '', 0, '', $context);
        self::writeToLog($logMessage);
    }
}
```

---

## Database Implementation

### Table Creation Scripts

#### Login Table
```sql
CREATE TABLE `login` (
  `sr` int(5) NOT NULL AUTO_INCREMENT,
  `id` varchar(8) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `voteStatus` int(1) NOT NULL DEFAULT 0,
  `department` varchar(255) NOT NULL,
  `year` varchar(10) NOT NULL,
  PRIMARY KEY (`sr`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Candidates Table
```sql
CREATE TABLE `candidates` (
  `id` varchar(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pfp` varchar(255) NOT NULL,
  `dept` enum('Computer Department','IT Department','Mechanical Department','Electrical Department','EXTC Department') NOT NULL,
  `post` enum('General Secretary','Joint Secretary','Sports Secretary','Cultural Secretary') NOT NULL,
  `reason` text NOT NULL,
  `cgpa` decimal(5,3) NOT NULL,
  `achieve` text NOT NULL,
  `club` text NOT NULL,
  `cert` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `comments` text NOT NULL,
  `attempts` int(1) NOT NULL,
  `voteCount` int(5) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Votes Table
```sql
CREATE TABLE `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` varchar(8) NOT NULL,
  `voter_name` varchar(255) NOT NULL,
  `candidate_id` varchar(8) NOT NULL,
  `candidate_name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `voter_id` (`voter_id`),
  KEY `candidate_id` (`candidate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Database Operations

#### User Registration
```php
// Registration validation and insertion
if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $insert_query = "INSERT INTO login (id, uname, pw, voteStatus, department, year) VALUES (?, ?, ?, 0, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 'sssss', $userid, $username, $hashed_password, $department, $year);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['successMessage'] = "Registration successful!";
        header("Location: ../login.php?success=Registration successful!");
    }
}
```

#### Vote Processing
```php
// Vote submission and counting
foreach ($_POST as $key => $value) {
    if ($key != 'submitVote') {
        // Update candidate vote count
        $query = "UPDATE candidates SET voteCount = voteCount + 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $value);
        $result = mysqli_stmt_execute($stmt);
        
        // Insert vote history
        $vote_history_query = "INSERT INTO votes (voter_id, voter_name, candidate_id, candidate_name, position) VALUES (?, ?, ?, ?, ?)";
        $vote_history_stmt = mysqli_prepare($conn, $vote_history_query);
        mysqli_stmt_bind_param($vote_history_stmt, 'sssss', $_SESSION['id'], $_SESSION['uname'], $value, $candidate_data['name'], $candidate_data['post']);
        mysqli_stmt_execute($vote_history_stmt);
    }
}
```

---

## Security Implementation

### Password Security
```php
// Password hashing during registration
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Password verification during login
if (password_verify($password, $stored_hash)) {
    // Login successful
    $_SESSION['id'] = $user_id;
    $_SESSION['uname'] = $username;
}
```

### SQL Injection Prevention
```php
// Using prepared statements for all database queries
$check_query = "SELECT id FROM login WHERE id = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, 's', $userid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
```

### Input Validation
```php
// Comprehensive input validation
$errors = [];

if (empty($userid)) {
    $errors[] = "User ID is required";
} elseif (strlen($userid) < 3) {
    $errors[] = "User ID must be at least 3 characters";
}

if (empty($password)) {
    $errors[] = "Password is required";
} elseif (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}

if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match";
}
```

### Session Security
```php
// Session validation on protected pages
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Admin-only access control
if ($_SESSION['id'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
```

---

## User Interface Implementation

### Responsive Design with Bootstrap
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FCRIT Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
```

### Dynamic Content Loading
```php
// Admin panel with dynamic content loading
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$validPages = ['dashboard', 'electionStatus', 'candidates', 'applications', 'voteHistory', 'analytics', 'userManagement', 'errorLogs', 'settings'];

if (!in_array($page, $validPages)) {
    $page = 'dashboard';
}

// Include appropriate content file
include "content/{$page}.php";
```

### Form Handling
```php
// Candidate application form processing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $post = $_POST['post'];
    $reason = $_POST['nomiReason'];
    $cgpa = $_POST['cgpa'];
    $achieve = $_POST['achieve'];
    $club = $_POST['club'];
    $detail = $_POST['detail'];
    $status = $_POST['status'];
    
    // File upload handling
    $pfp_path = handleFileUpload($_FILES['pfp'], 'assets/pfp/');
    $cert_path = handleFileUpload($_FILES['cert'], 'assets/certificate/');
    
    // Database insertion
    $insert_query = "INSERT INTO candidates (id, name, pfp, dept, post, reason, cgpa, achieve, club, cert, detail, status, comments, attempts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', 0)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 'ssssssssssss', $id, $name, $pfp_path, $dept, $post, $reason, $cgpa, $achieve, $club, $cert_path, $detail, $status);
    mysqli_stmt_execute($stmt);
}
```

---

## Admin Panel Implementation

### Dashboard Analytics
```php
// Real-time statistics calculation
function getTotalApplications($conn) {
    $sql = "SELECT COUNT(*) as total FROM candidates";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function displayCard($title, $status, $color, $icon, $conn) {
    if ($status === 'Total') {
        $applications = getTotalApplications($conn);
    } else {
        $sql = "SELECT * FROM candidates WHERE status='$status'";
        $result = mysqli_query($conn, $sql);
        $applications = mysqli_num_rows($result);
    }
    
    echo "<div class='stat-card stat-$color'>
            <div class='stat-icon'><i class='$icon'></i></div>
            <div class='stat-content'>
                <h3 class='stat-number'>$applications</h3>
                <p class='stat-label'>$title</p>
            </div>
          </div>";
}
```

### Election Status Control
```php
// Election state management
if (isset($_POST['startElection'])) {
    $startElection = "UPDATE login SET voteStatus=1 WHERE id=?";
    $stmt = mysqli_prepare($conn, $startElection);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        ErrorLogger::logError("Election started by admin", ['admin_id' => $_SESSION['id']]);
        $_SESSION['successMessage'] = "The Election has been started.";
    }
}

if (isset($_POST['stopElection'])) {
    $stopElection = "UPDATE login SET voteStatus=2 WHERE id=?";
    $stmt = mysqli_prepare($conn, $stopElection);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        ErrorLogger::logError("Election stopped by admin", ['admin_id' => $_SESSION['id']]);
        $_SESSION['successMessage'] = "The Election has been stopped.";
    }
}
```

---

## Error Handling Implementation

### Custom Error Handler
```php
public static function customErrorHandler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    
    $errorType = self::getErrorType($severity);
    $logMessage = self::formatLogMessage($errorType, $message, $file, $line);
    self::writeToLog($logMessage);
    
    return true;
}

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
```

### Database Error Logging
```php
// Database error handling with logging
$query = "SELECT * FROM candidates WHERE status='Accepted'";
$result = mysqli_query($conn, $query);

if (!$result) {
    ErrorLogger::logDatabaseError($query, mysqli_error($conn), ['admin_id' => $_SESSION['id']]);
    $_SESSION['errorMessage'] = "Database error occurred while fetching candidates.";
    header("Location: ../admin/admin.php");
    exit();
}
```

---

## Voting Logic Implementation

### Vote Validation
```php
// Check if user has already voted
$checkVoteStatus = "SELECT voteStatus FROM `login` WHERE id=?";
$run = mysqli_prepare($conn, $checkVoteStatus);
mysqli_stmt_bind_param($run, 's', $_SESSION['id']);
mysqli_stmt_execute($run);
mysqli_stmt_bind_result($run, $voteStatus);
mysqli_stmt_fetch($run);

// Check admin's election status
$checkElectionStatus = "SELECT voteStatus FROM `login` WHERE id='admin'";
$electionResult = mysqli_query($conn, $checkElectionStatus);
$electionData = mysqli_fetch_assoc($electionResult);
$electionStatus = $electionData['voteStatus'];

// Allow voting only if user hasn't voted AND election is in progress
if($voteStatus == 0 && $electionStatus == 1) {
    // Show voting interface
} else {
    // Show appropriate message
}
```

### Vote Processing
```php
// Process vote submission
if (isset($_POST['submitVote'])) {
    // Update user's vote status
    $updateVoteFlag = "UPDATE login SET voteStatus=1 WHERE id=?";
    $stmt = mysqli_prepare($conn, $updateVoteFlag);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    mysqli_stmt_execute($stmt);

    // Process each vote
    foreach ($_POST as $key => $value) {
        if ($key != 'submitVote') {
            // Update candidate vote count
            $query = "UPDATE candidates SET voteCount = voteCount + 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $value);
            mysqli_stmt_execute($stmt);
            
            // Record vote history
            $vote_history_query = "INSERT INTO votes (voter_id, voter_name, candidate_id, candidate_name, position) VALUES (?, ?, ?, ?, ?)";
            $vote_history_stmt = mysqli_prepare($conn, $vote_history_query);
            mysqli_stmt_bind_param($vote_history_stmt, 'sssss', $_SESSION['id'], $_SESSION['uname'], $value, $candidate_data['name'], $candidate_data['post']);
            mysqli_stmt_execute($vote_history_stmt);
        }
    }
    
    header("Location: ../users/successVote.php");
    exit();
}
```

---

## Analytics Implementation

### Chart.js Integration
```javascript
// Vote distribution chart
const voteDistributionCtx = document.getElementById('voteDistributionChart').getContext('2d');
new Chart(voteDistributionCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($voteDistribution)) ?>,
        datasets: [{
            label: 'Total Votes',
            data: <?= json_encode(array_values($voteDistribution)) ?>,
            backgroundColor: [
                'rgba(78, 115, 223, 0.8)',
                'rgba(28, 200, 138, 0.8)',
                'rgba(54, 185, 204, 0.8)',
                'rgba(246, 194, 62, 0.8)'
            ],
            borderColor: [
                'rgba(78, 115, 223, 1)',
                'rgba(28, 200, 138, 1)',
                'rgba(54, 185, 204, 1)',
                'rgba(246, 194, 62, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
```

### Data Aggregation
```php
// Calculate analytics data
$totalUsers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login WHERE id != 'admin'"));
$votedUsers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login WHERE voteStatus = 1 AND id != 'admin'"));
$totalCandidates = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM candidates WHERE status='Accepted'"));
$totalVotes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(voteCount) as total FROM candidates WHERE status='Accepted'"))['total'] ?? 0;
$voterTurnout = $totalUsers > 0 ? round(($votedUsers / $totalUsers) * 100, 1) : 0;

// Position-wise data
$positions = ['General Secretary', 'Joint Secretary', 'Sports Secretary', 'Cultural Secretary'];
$positionData = [];

foreach ($positions as $position) {
    $query = "SELECT c.name, c.voteCount, c.dept, c.pfp 
              FROM candidates c 
              WHERE c.status='Accepted' AND c.post='$position' 
              ORDER BY c.voteCount DESC";
    $result = mysqli_query($conn, $query);
    $candidates = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $candidates[] = $row;
    }
    $positionData[$position] = $candidates;
}
```

---

## File Upload Implementation

### File Upload Handler
```php
function handleFileUpload($file, $uploadDir) {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPEG, PNG, and GIF are allowed.');
        }
        
        if ($file['size'] > $maxSize) {
            throw new Exception('File size too large. Maximum size is 5MB.');
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        } else {
            throw new Exception('Failed to upload file.');
        }
    }
    
    return '';
}
```

### File Validation
```php
// Profile picture upload validation
if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] === UPLOAD_ERR_OK) {
    $pfp_path = handleFileUpload($_FILES['pfp'], 'assets/pfp/');
} else {
    $pfp_path = 'assets/pfp/default.png'; // Default image
}

// Certificate upload validation
if (isset($_FILES['cert']) && $_FILES['cert']['error'] === UPLOAD_ERR_OK) {
    $cert_path = handleFileUpload($_FILES['cert'], 'assets/certificate/');
} else {
    $cert_path = ''; // Optional field
}
```

---

## Session Management

### Session Initialization
```php
// Start session and set security parameters
session_start();

// Regenerate session ID for security
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Set session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}
$_SESSION['last_activity'] = time();
```

### Session Validation
```php
// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['id']) && !empty($_SESSION['id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['id']) && $_SESSION['id'] === 'admin';
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Redirect if not admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: login.php");
        exit();
    }
}
```

---

This technical implementation guide provides detailed insights into the code structure, security measures, and implementation patterns used in the FCRIT Voting System. The system demonstrates best practices in web development, security, and user experience design.
