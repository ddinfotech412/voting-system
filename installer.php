<?php
/**
 * FCRIT Voting System - Installation Script
 * This script helps set up the voting system project
 */

// Prevent direct access after installation
if (file_exists('common/connect.php') && !isset($_GET['reinstall'])) {
    $config_content = file_get_contents('common/connect.php');
    if (strpos($config_content, 'localhost') !== false && strpos($config_content, 'root') !== false) {
        // Configuration exists, show warning
        $show_warning = true;
    }
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 1:
            // Database configuration
            $db_host = $_POST['db_host'] ?? 'localhost';
            $db_user = $_POST['db_user'] ?? 'root';
            $db_pass = $_POST['db_pass'] ?? '';
            $db_name = $_POST['db_name'] ?? 'voting_system';
            
            // Test database connection
            $test_conn = @mysqli_connect($db_host, $db_user, $db_pass);
            if (!$test_conn) {
                $error = "Database connection failed: " . mysqli_connect_error();
            } else {
                // Check if database exists, create if not
                $create_db = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
                if (mysqli_query($test_conn, $create_db)) {
                    mysqli_close($test_conn);
                    // Store config and proceed to next step
                    $_SESSION['db_config'] = compact('db_host', 'db_user', 'db_pass', 'db_name');
                    header('Location: ?step=2');
                    exit;
                } else {
                    $error = "Failed to create database: " . mysqli_error($test_conn);
                    mysqli_close($test_conn);
                }
            }
            break;
            
        case 2:
            // Create tables
            $config = $_SESSION['db_config'];
            $conn = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
            
            if (!$conn) {
                $error = "Database connection failed: " . mysqli_connect_error();
            } else {
                // Read and execute SQL file
                $sql_file = 'voting_system.sql';
                if (file_exists($sql_file)) {
                    $sql = file_get_contents($sql_file);
                    $queries = explode(';', $sql);
                    
                    $success_count = 0;
                    foreach ($queries as $query) {
                        $query = trim($query);
                        if (!empty($query)) {
                            if (mysqli_query($conn, $query)) {
                                $success_count++;
                            }
                        }
                    }
                    
                    if ($success_count > 0) {
                        // Generate config file
                        $config_content = generateConfigFile($config);
                        if (file_put_contents('common/connect.php', $config_content)) {
                            $success = "Database tables created successfully! Configuration file generated.";
                            header('Location: ?step=3');
                            exit;
                        } else {
                            $error = "Failed to create configuration file. Please check file permissions.";
                        }
                    } else {
                        $error = "Failed to create database tables.";
                    }
                } else {
                    $error = "SQL file not found. Please ensure voting_system.sql is in the root directory.";
                }
                mysqli_close($conn);
            }
            break;
            
        case 3:
            // Create directories and set permissions
            $directories = [
                'logs',
                'assets/campaign',
                'assets/pfp',
                'assets/certificate'
            ];
            
            $created_dirs = 0;
            foreach ($directories as $dir) {
                if (!is_dir($dir)) {
                    if (mkdir($dir, 0755, true)) {
                        $created_dirs++;
                    }
                } else {
                    $created_dirs++;
                }
            }
            
            // Create .htaccess for security
            $htaccess_content = "# Security\nOptions -Indexes\n\n# Prevent access to sensitive files\n<Files \"*.sql\">\n    Order allow,deny\n    Deny from all\n</Files>\n\n<Files \"installer.php\">\n    Order allow,deny\n    Deny from all\n</Files>\n\n# Error pages\nErrorDocument 404 /index.php";
            
            if (file_put_contents('.htaccess', $htaccess_content)) {
                $success = "Installation completed successfully! Directories created and security configured.";
                header('Location: ?step=4');
                exit;
            } else {
                $error = "Installation completed but failed to create .htaccess file.";
            }
            break;
    }
}

function generateConfigFile($config) {
    return "<?php
// Include error logger
require_once 'errorLogger.php';

\$sname=\"{$config['db_host']}\";
\$unmae=\"{$config['db_user']}\";
\$password=\"{$config['db_pass']}\";

\$db_name=\"{$config['db_name']}\";

\$conn = mysqli_connect(\$sname,\$unmae,\$password,\$db_name);

// Check connection and log errors
if (!\$conn) {
    \$error = \"Database connection failed: \" . mysqli_connect_error();
    ErrorLogger::logDatabaseError(\"Connection\", \$error, [
        'host' => \$sname,
        'username' => \$unmae,
        'database' => \$db_name
    ]);
    die(\"Connection failed: \" . mysqli_connect_error());
}

// Set charset to UTF-8
if (!mysqli_set_charset(\$conn, \"utf8\")) {
    ErrorLogger::logDatabaseError(\"Charset\", \"Error setting charset: \" . mysqli_error(\$conn));
}

// Log successful connection
ErrorLogger::logError(\"Database connection established successfully\", [
    'host' => \$sname,
    'database' => \$db_name
]);
?>";
}

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCRIT Voting System - Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .installer-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .step.active {
            background: #007bff;
            color: white;
        }
        .step.completed {
            background: #28a745;
            color: white;
        }
        .step.pending {
            background: #e9ecef;
            color: #6c757d;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list i {
            color: #28a745;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="installer-container p-5">
                    <div class="text-center mb-4">
                        <h1 class="display-4 text-primary mb-3">
                            <i class="fas fa-vote-yea"></i> FCRIT Voting System
                        </h1>
                        <p class="lead text-muted">Installation Wizard</p>
                    </div>

                    <?php if (isset($show_warning) && $show_warning): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> The system appears to be already installed. 
                        <a href="?reinstall=1" class="alert-link">Click here to reinstall</a> or 
                        <a href="index.php" class="alert-link">go to the application</a>.
                    </div>
                    <?php endif; ?>

                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : 'pending'; ?>">1</div>
                        <div class="step <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : 'pending'; ?>">2</div>
                        <div class="step <?php echo $step >= 3 ? ($step > 3 ? 'completed' : 'active') : 'pending'; ?>">3</div>
                        <div class="step <?php echo $step >= 4 ? 'active' : 'pending'; ?>">4</div>
                    </div>

                    <!-- Error/Success Messages -->
                    <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Step 1: Database Configuration -->
                    <?php if ($step == 1): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-database"></i> Database Configuration</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="db_host" class="form-label">Database Host</label>
                                        <input type="text" class="form-control" id="db_host" name="db_host" 
                                               value="localhost" required>
                                        <div class="form-text">Usually 'localhost' for local installations</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="db_user" class="form-label">Database Username</label>
                                        <input type="text" class="form-control" id="db_user" name="db_user" 
                                               value="root" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="db_pass" class="form-label">Database Password</label>
                                        <input type="password" class="form-control" id="db_pass" name="db_pass">
                                        <div class="form-text">Leave empty if no password is set</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="db_name" class="form-label">Database Name</label>
                                        <input type="text" class="form-control" id="db_name" name="db_name" 
                                               value="voting_system" required>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-arrow-right"></i> Test Connection & Continue
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Step 2: Database Setup -->
                    <?php if ($step == 2): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-cogs"></i> Database Setup</h5>
                        </div>
                        <div class="card-body">
                            <p class="lead">Creating database tables and initializing the system...</p>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>This will create the following tables:</strong>
                                <ul class="mb-0 mt-2">
                                    <li><code>analytics</code> - Voting analytics and statistics</li>
                                    <li><code>announcements</code> - System announcements</li>
                                    <li><code>campaign</code> - Candidate campaign information</li>
                                    <li><code>candidates</code> - Candidate profiles and applications</li>
                                    <li><code>login</code> - User accounts and authentication</li>
                                    <li><code>votes</code> - Voting records and history</li>
                                </ul>
                            </div>

                            <form method="POST">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-database"></i> Create Tables & Continue
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Step 3: File Permissions & Directories -->
                    <?php if ($step == 3): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-folder-plus"></i> File System Setup</h5>
                        </div>
                        <div class="card-body">
                            <p class="lead">Creating necessary directories and setting up security...</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Directories to be created:</h6>
                                    <ul class="feature-list">
                                        <li><i class="fas fa-folder"></i> logs/</li>
                                        <li><i class="fas fa-folder"></i> assets/campaign/</li>
                                        <li><i class="fas fa-folder"></i> assets/pfp/</li>
                                        <li><i class="fas fa-folder"></i> assets/certificate/</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Security features:</h6>
                                    <ul class="feature-list">
                                        <li><i class="fas fa-shield-alt"></i> .htaccess protection</li>
                                        <li><i class="fas fa-lock"></i> Directory indexing disabled</li>
                                        <li><i class="fas fa-ban"></i> SQL file access blocked</li>
                                    </ul>
                                </div>
                            </div>

                            <form method="POST">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-tools"></i> Setup File System
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Step 4: Installation Complete -->
                    <?php if ($step == 4): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle"></i> Installation Complete!</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-trophy text-success" style="font-size: 4rem;"></i>
                            </div>
                            
                            <h3 class="text-success mb-3">Congratulations!</h3>
                            <p class="lead">Your FCRIT Voting System has been successfully installed and configured.</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <h6 class="card-title text-success">
                                                <i class="fas fa-user-shield"></i> Admin Access
                                            </h6>
                                            <p class="card-text">
                                                <strong>Username:</strong> admin<br>
                                                <strong>Password:</strong> admin
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-info">
                                        <div class="card-body">
                                            <h6 class="card-title text-info">
                                                <i class="fas fa-users"></i> Test Users
                                            </h6>
                                            <p class="card-text">
                                                Use the setup_dummy_data.php script to add test users and candidates for testing.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="index.php" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-home"></i> Go to Application
                                </a>
                                <a href="admin/admin.php" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-cog"></i> Admin Panel
                                </a>
                            </div>

                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Important:</strong> For security reasons, please delete the installer.php file after installation is complete.
                                <br>
                                <button class="btn btn-sm btn-outline-danger mt-2" onclick="deleteInstaller()">
                                    <i class="fas fa-trash"></i> Delete Installer
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Footer -->
                    <div class="text-center mt-4 text-muted">
                        <small>
                            <i class="fas fa-code"></i> FCRIT Voting System v1.0 | 
                            <i class="fas fa-graduation-cap"></i> Developed for FCRIT
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteInstaller() {
            if (confirm('Are you sure you want to delete the installer? This action cannot be undone.')) {
                fetch('?action=delete_installer', {method: 'POST'})
                    .then(response => response.text())
                    .then(data => {
                        alert('Installer deleted successfully!');
                        window.location.href = 'index.php';
                    })
                    .catch(error => {
                        alert('Error deleting installer. Please delete it manually.');
                    });
            }
        }

        // Auto-delete installer if requested
        <?php if (isset($_GET['action']) && $_GET['action'] === 'delete_installer' && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php
        if (unlink(__FILE__)) {
            echo "alert('Installer deleted successfully!'); window.location.href = 'index.php';";
        } else {
            echo "alert('Error deleting installer. Please delete it manually.');";
        }
        ?>
        <?php endif; ?>
    </script>
</body>
</html>


