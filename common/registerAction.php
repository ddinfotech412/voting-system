<?php
require 'connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userid = trim($_POST['userid']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    
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
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
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
            $_SESSION['successMessage'] = "Registration successful! You can now login.";
            header("Location: ../login.php?success=Registration successful! You can now login.");
        } else {
            header("Location: ../register.php?error=Registration failed. Please try again.");
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = implode(", ", $errors);
        header("Location: ../register.php?error=" . urlencode($error_message));
    }
} else {
    header("Location: ../register.php");
}
exit();
?>
