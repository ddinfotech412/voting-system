<?php
// Test page to verify image paths are working correctly
require 'common/connect.php';

// Get a sample nominee to test image paths
$query = "SELECT * FROM candidates LIMIT 1";
$result = mysqli_query($conn, $query);
$nominee = mysqli_fetch_assoc($result);

if ($nominee) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Image Path Test</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='row'>
                <div class='col-12'>
                    <h1>Image Path Test</h1>
                    <p>Testing image paths for: <strong>" . htmlspecialchars($nominee['name']) . "</strong></p>
                    
                    <div class='row'>
                        <div class='col-md-6'>
                            <h5>Profile Picture</h5>
                            <p><strong>Path:</strong> " . htmlspecialchars($nominee['pfp']) . "</p>
                            <p><strong>File Exists:</strong> " . (file_exists('../' . $nominee['pfp']) ? 'Yes' : 'No') . "</p>
                            <img src='" . htmlspecialchars($nominee['pfp']) . "' alt='Profile Picture' class='img-fluid' style='max-width: 200px; height: 200px; object-fit: cover; border: 1px solid #ddd;' onerror=\"this.src='../assets/logo.png'\">
                        </div>
                        
                        <div class='col-md-6'>
                            <h5>Certificate</h5>
                            <p><strong>Path:</strong> " . htmlspecialchars($nominee['cert']) . "</p>
                            <p><strong>File Exists:</strong> " . (file_exists('../' . $nominee['cert']) ? 'Yes' : 'No') . "</p>
                            <img src='" . htmlspecialchars($nominee['cert']) . "' alt='Certificate' class='img-fluid' style='max-width: 200px; height: 200px; object-fit: cover; border: 1px solid #ddd;' onerror=\"this.src='../assets/default-campaign.jpg'\">
                        </div>
                    </div>
                    
                    <div class='mt-4'>
                        <h5>Test Links:</h5>
                        <a href='admin/viewApplicant.php?name=" . urlencode($nominee['name']) . "' class='btn btn-primary me-2'>View in Admin Panel</a>
                        <a href='users/viewNominee.php?name=" . urlencode($nominee['name']) . "' class='btn btn-secondary'>View in User Panel</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>";
} else {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>No Data</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body>
        <div class='container mt-5'>
            <div class='alert alert-warning'>
                <h4>No Data Found</h4>
                <p>No nominees found in the database. Please add some dummy data first.</p>
                <a href='setup_dummy_data.php' class='btn btn-primary'>Setup Dummy Data</a>
            </div>
        </div>
    </body>
    </html>";
}
?>
