<?php
require 'connect.php';

session_start();

if ($_SESSION['id'] == 'admin') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addUser'])) {
        $userid = trim($_POST['userId']);
        $username = trim($_POST['userName']);
        $password = $_POST['password'];
        $department = isset($_POST['department']) ? $_POST['department'] : 'General';
        $year = isset($_POST['year']) ? $_POST['year'] : '2024';
        
        // Validation
        $errors = [];
        
        if (empty($userid)) {
            $errors[] = "User ID is required";
        } elseif (strlen($userid) < 3) {
            $errors[] = "User ID must be at least 3 characters";
        }
        
        if (empty($username)) {
            $errors[] = "Full name is required";
        }
        
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }
        
        if (empty($department)) {
            $errors[] = "Department is required";
        }
        
        if (empty($year)) {
            $errors[] = "Academic year is required";
        }
        
        // Check if user ID already exists
        if (empty($errors)) {
            $check_query = "SELECT id FROM login WHERE id = ?";
            $stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($stmt, 's', $userid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $errors[] = "User ID already exists. Please choose a different one.";
            }
            mysqli_stmt_close($stmt);
        }
        
        if (empty($errors)) {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $insert_query = "INSERT INTO login (id, uname, pw, voteStatus, department, year) VALUES (?, ?, ?, 0, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, 'sssss', $userid, $username, $hashed_password, $department, $year);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['successMessage'] = "User added successfully!";
                header("Location: ../admin/admin.php?page=userManagement&success=User added successfully!");
            } else {
                header("Location: ../admin/admin.php?page=userManagement&error=Failed to add user. Please try again.");
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = implode(", ", $errors);
            header("Location: ../admin/admin.php?page=userManagement&error=" . urlencode($error_message));
        }
    }
    
    // Handle delete user (AJAX)
    if (isset($_POST['deleteUser'])) {
        $user_id = $_POST['deleteUser'];
        
        if ($user_id != 'admin') {
            // Delete user votes first
            $delete_votes = "DELETE FROM votes WHERE voter_id = ?";
            $stmt1 = mysqli_prepare($conn, $delete_votes);
            mysqli_stmt_bind_param($stmt1, 's', $user_id);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);
            
            // Delete user from candidates table if exists
            $delete_candidate = "DELETE FROM candidates WHERE id = ?";
            $stmt3 = mysqli_prepare($conn, $delete_candidate);
            mysqli_stmt_bind_param($stmt3, 's', $user_id);
            mysqli_stmt_execute($stmt3);
            mysqli_stmt_close($stmt3);
            
            // Delete user
            $delete_user = "DELETE FROM login WHERE id = ?";
            $stmt2 = mysqli_prepare($conn, $delete_user);
            mysqli_stmt_bind_param($stmt2, 's', $user_id);
            
            if (mysqli_stmt_execute($stmt2)) {
                echo json_encode(['success' => true, 'message' => 'User deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
            }
            mysqli_stmt_close($stmt2);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cannot delete admin user.']);
        }
        exit();
    }
    
    // Handle delete user (GET request for backward compatibility)
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $user_id = $_GET['id'];
        
        if ($user_id != 'admin') {
            // Delete user votes first
            $delete_votes = "DELETE FROM votes WHERE voter_id = ?";
            $stmt1 = mysqli_prepare($conn, $delete_votes);
            mysqli_stmt_bind_param($stmt1, 's', $user_id);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);
            
            // Delete user
            $delete_user = "DELETE FROM login WHERE id = ?";
            $stmt2 = mysqli_prepare($conn, $delete_user);
            mysqli_stmt_bind_param($stmt2, 's', $user_id);
            
            if (mysqli_stmt_execute($stmt2)) {
                $_SESSION['successMessage'] = "User deleted successfully!";
                header("Location: ../admin/admin.php?page=userManagement&success=User deleted successfully!");
            } else {
                header("Location: ../admin/admin.php?page=userManagement&error=Failed to delete user.");
            }
            mysqli_stmt_close($stmt2);
        } else {
            header("Location: ../admin/admin.php?page=userManagement&error=Cannot delete admin user.");
        }
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>
