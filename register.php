<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - FCRIT Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .register-header {
            margin-bottom: 30px;
        }
        
        .register-header h1 {
            color: #333;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .register-header p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9f9f9;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }
        
        .btn-register {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .btn-register:hover {
            background: #5a6fd8;
        }
        
        .btn-register:active {
            background: #4c63d2;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .alert-danger {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        
        .alert-success {
            color: #28a745;
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        
        .links-section {
            margin-top: 20px;
            text-align: center;
        }
        
        .links-section p {
            color: #666;
            font-size: 0.9rem;
            margin: 5px 0;
        }
        
        .links-section a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .links-section a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>User Registration</h1>
            <p>Create your account to access the voting system</p>
        </div>
        
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
        }
        ?>
        
        <form action="./common/registerAction.php" method="post">
            <div class="form-group">
                <label for="userid">User ID</label>
                <input type="text" 
                       class="form-control" 
                       name="userid" 
                       id="userid" 
                       placeholder="Enter unique User ID" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="username">Full Name</label>
                <input type="text" 
                       class="form-control" 
                       name="username" 
                       id="username" 
                       placeholder="Enter your full name" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" 
                       class="form-control" 
                       name="password" 
                       id="password" 
                       placeholder="Enter password" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" 
                       class="form-control" 
                       name="confirm_password" 
                       id="confirm_password" 
                       placeholder="Confirm password" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="department">Department</label>
                <select name="department" 
                        id="department" 
                        class="form-control" 
                        required>
                    <option value="">Select Department</option>
                    <option value="Computer Department">Computer Department</option>
                    <option value="IT Department">IT Department</option>
                    <option value="Mechanical Department">Mechanical Department</option>
                    <option value="Electrical Department">Electrical Department</option>
                    <option value="EXTC Department">EXTC Department</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="year">Academic Year</label>
                <select name="year" 
                        id="year" 
                        class="form-control" 
                        required>
                    <option value="">Select Year</option>
                    <option value="First Year">First Year</option>
                    <option value="Second Year">Second Year</option>
                    <option value="Third Year">Third Year</option>
                    <option value="Final Year">Final Year</option>
                </select>
            </div>
            
            <button type="submit" class="btn-register">Register</button>
        </form>
        
        <div class="links-section">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
