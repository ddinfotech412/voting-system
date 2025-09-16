<?php
// Setup script to create the missing votes table
// Run this once to create the votes table in your database

require 'common/connect.php';

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['id'] != 'admin') {
    header("Location:login.php");
    exit();
}

// Create votes table
$createVotesTable = "CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` varchar(8) NOT NULL,
  `voter_name` varchar(255) NOT NULL,
  `candidate_id` varchar(8) NOT NULL,
  `candidate_name` varchar(255) NOT NULL,
  `position` varchar(50) NOT NULL,
  `vote_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `voter_id` (`voter_id`),
  KEY `candidate_id` (`candidate_id`),
  KEY `position` (`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if (mysqli_query($conn, $createVotesTable)) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Votes Table Created</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='row justify-content-center'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body text-center'>
                            <i class='fas fa-check-circle text-success fa-4x mb-3'></i>
                            <h2 class='text-success'>Votes Table Created!</h2>
                            <p class='text-muted'>The votes table has been successfully created in the database.</p>
                            <div class='mt-4'>
                                <a href='admin/admin.php' class='btn btn-primary me-2'>Go to Admin Panel</a>
                                <a href='landing.php' class='btn btn-outline-primary'>View Landing Page</a>
                            </div>
                        </div>
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
        <title>Error</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='row justify-content-center'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body text-center'>
                            <i class='fas fa-exclamation-triangle text-danger fa-4x mb-3'></i>
                            <h2 class='text-danger'>Error!</h2>
                            <p class='text-muted'>Failed to create votes table: " . mysqli_error($conn) . "</p>
                            <div class='mt-4'>
                                <a href='admin/admin.php' class='btn btn-primary me-2'>Go to Admin Panel</a>
                                <a href='landing.php' class='btn btn-outline-primary'>View Landing Page</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>";
}
?>
