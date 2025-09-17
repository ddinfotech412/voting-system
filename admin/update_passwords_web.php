<?php
require '../common/connect.php';

if ($_SESSION['id'] != 'admin') {
    header("Location:../login.php");
    exit();
}

// Function to check and update password column size
function ensurePasswordColumnSize($conn) {
    // Check current column size
    $query = "SHOW COLUMNS FROM login LIKE 'pw'";
    $result = mysqli_query($conn, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $current_type = $row['Type'];
        
        // Check if it's already varchar(255) or larger
        if (strpos($current_type, 'varchar(255)') !== false || strpos($current_type, 'varchar(500)') !== false) {
            return true;
        } else {
            // Update column to VARCHAR(255)
            $alter_query = "ALTER TABLE login MODIFY COLUMN pw VARCHAR(255) NOT NULL";
            return mysqli_query($conn, $alter_query);
        }
    }
    return false;
}

// Ensure password column is large enough
$column_updated = false;
if (!ensurePasswordColumnSize($conn)) {
    $error_message = "Failed to update password column size. Please check database permissions.";
} else {
    $column_updated = true;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $column_updated) {
    $action = $_POST['action'] ?? '';
    $success_message = '';
    $error_message = '';
    
    switch ($action) {
        case 'single':
            $userid = trim($_POST['userid'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($userid) || empty($password)) {
                $error_message = "User ID and password are required!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE login SET pw = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $userid);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success_message = "Password updated successfully for user: $userid";
                } else {
                    $error_message = "Failed to update password for user: $userid";
                }
                mysqli_stmt_close($stmt);
            }
            break;
            
        case 'multiple':
            $userids = array_filter(array_map('trim', explode(',', $_POST['userids'] ?? '')));
            $password = $_POST['password'] ?? '';
            
            if (empty($userids) || empty($password)) {
                $error_message = "User IDs and password are required!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $success_count = 0;
                
                foreach ($userids as $userid) {
                    $query = "UPDATE login SET pw = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $userid);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $success_count++;
                    }
                    mysqli_stmt_close($stmt);
                }
                
                $success_message = "Updated $success_count/" . count($userids) . " users successfully";
            }
            break;
            
        case 'reset_all':
            $default_password = $_POST['default_password'] ?? 'password123';
            $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
            
            $query = "UPDATE login SET pw = ? WHERE id != 'admin'";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                $affected_rows = mysqli_stmt_affected_rows($stmt);
                $success_message = "Reset $affected_rows users to default password: $default_password";
            } else {
                $error_message = "Failed to reset user passwords";
            }
            mysqli_stmt_close($stmt);
            break;
            
        case 'admin':
            $password = $_POST['password'] ?? '';
            if (empty($password)) {
                $error_message = "Admin password is required!";
            } else {
                // Admin password stays plain text for compatibility
                $query = "UPDATE login SET pw = ? WHERE id = 'admin'";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 's', $password);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success_message = "Admin password updated successfully!";
                } else {
                    $error_message = "Failed to update admin password!";
                }
                mysqli_stmt_close($stmt);
            }
            break;
    }
}

// Get current users for display
$users_query = "SELECT id, uname, pw, department, year FROM login ORDER BY id";
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Passwords - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .password-type {
            font-size: 0.8rem;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .hashed { background-color: #d4edda; color: #155724; }
        .plain { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php?page=userManagement">
                                <i class="fas fa-users me-2"></i>User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="fas fa-key me-2"></i>Update Passwords
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Update Passwords</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <?php if ($column_updated): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Database Ready
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Database Error
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Update Forms -->
                <div class="row">
                    <!-- Single User Update -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>Update Single User
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" <?= !$column_updated ? 'onsubmit="return false;"' : '' ?>>
                                    <input type="hidden" name="action" value="single">
                                    <div class="mb-3">
                                        <label for="userid" class="form-label">User ID</label>
                                        <input type="text" class="form-control" id="userid" name="userid" required <?= !$column_updated ? 'disabled' : '' ?>>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required <?= !$column_updated ? 'disabled' : '' ?>>
                                    </div>
                                    <button type="submit" class="btn btn-primary" <?= !$column_updated ? 'disabled' : '' ?>>
                                        <i class="fas fa-save me-2"></i>Update Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Users Update -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users me-2"></i>Update Multiple Users
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="multiple">
                                    <div class="mb-3">
                                        <label for="userids" class="form-label">User IDs (comma-separated)</label>
                                        <input type="text" class="form-control" id="userids" name="userids" 
                                               placeholder="user001,user002,user003" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password2" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="password2" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Update All
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Reset All Users -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-refresh me-2"></i>Reset All Users
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" onsubmit="return confirm('Are you sure you want to reset ALL user passwords?')">
                                    <input type="hidden" name="action" value="reset_all">
                                    <div class="mb-3">
                                        <label for="default_password" class="form-label">Default Password</label>
                                        <input type="password" class="form-control" id="default_password" name="default_password" 
                                               value="password123" required>
                                    </div>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Reset All Passwords
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Update Admin -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-crown me-2"></i>Update Admin Password
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="admin">
                                    <div class="mb-3">
                                        <label for="admin_password" class="form-label">New Admin Password</label>
                                        <input type="password" class="form-control" id="admin_password" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-save me-2"></i>Update Admin
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Users Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Current Users
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Password Type</th>
                                        <th>Department</th>
                                        <th>Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= $user['uname'] ?></td>
                                        <td>
                                            <span class="password-type <?= strlen($user['pw']) > 20 ? 'hashed' : 'plain' ?>">
                                                <?= strlen($user['pw']) > 20 ? 'Hashed' : 'Plain Text' ?>
                                            </span>
                                        </td>
                                        <td><?= $user['department'] ?? 'N/A' ?></td>
                                        <td><?= $user['year'] ?? 'N/A' ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

