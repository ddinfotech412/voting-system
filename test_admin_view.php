<?php
// Test page to verify admin view applicant functionality
session_start();

// Simulate admin login for testing
$_SESSION['id'] = 'admin';
$_SESSION['uname'] = 'Administrator';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test Admin View</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
    <div class='container mt-5'>
        <div class='row'>
            <div class='col-12'>
                <h1>Admin View Test</h1>
                <p>This page tests the admin view applicant functionality.</p>
                
                <div class='alert alert-info'>
                    <h5>Session Status:</h5>
                    <p><strong>Session ID:</strong> " . (isset($_SESSION['id']) ? $_SESSION['id'] : 'Not set') . "</p>
                    <p><strong>Session Username:</strong> " . (isset($_SESSION['uname']) ? $_SESSION['uname'] : 'Not set') . "</p>
                </div>
                
                <div class='mt-4'>
                    <h5>Test Links:</h5>
                    <a href='admin/viewApplicant.php?name=John%20Smith' class='btn btn-primary me-2'>View John Smith</a>
                    <a href='admin/viewApplicant.php?name=Sarah%20Johnson' class='btn btn-primary me-2'>View Sarah Johnson</a>
                    <a href='admin/admin.php?page=applications' class='btn btn-secondary'>Go to Applications</a>
                </div>
                
                <div class='mt-4'>
                    <h5>Instructions:</h5>
                    <ol>
                        <li>Click on one of the 'View' buttons above</li>
                        <li>You should see the nominee details instead of being redirected to login</li>
                        <li>If you still get redirected to login, there might be another issue</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
?>
