<?php
// Test page to verify default campaign image functionality
require 'common/connect.php';

// Test with a non-existent campaign image
$testCampaign = [
    'campaign' => '../assets/campaign/non-existent-image.jpg',
    'motto' => 'Test Campaign Motto'
];

$campaignImage = $testCampaign['campaign'];
$imagePath = '../' . $campaignImage;

// Check if the campaign image exists, otherwise use default
if (file_exists($imagePath) && !empty($campaignImage)) {
    $displayImage = $campaignImage;
} else {
    $displayImage = '../assets/default-campaign.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Campaign Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1>Campaign Image Test</h1>
                <p>This page tests the default campaign image functionality.</p>
                
                <div class="card">
                    <div class="card-header">
                        <h5>Test Campaign</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Original Image Path:</strong> <?= htmlspecialchars($testCampaign['campaign']) ?></p>
                        <p><strong>Display Image Path:</strong> <?= htmlspecialchars($displayImage) ?></p>
                        <p><strong>File Exists:</strong> <?= file_exists($imagePath) ? 'Yes' : 'No' ?></p>
                        
                        <div class="mt-3">
                            <h6>Campaign Image:</h6>
                            <img src="<?= htmlspecialchars($displayImage) ?>" 
                                 alt="<?= htmlspecialchars($testCampaign['motto']) ?>" 
                                 class="img-fluid" 
                                 style="max-width: 300px; height: 200px; object-fit: cover; border: 1px solid #ddd;"
                                 onerror="this.src='../assets/default-campaign.jpg'">
                        </div>
                        
                        <div class="mt-3">
                            <h6>Campaign Motto:</h6>
                            <p class="fs-4"><?= htmlspecialchars($testCampaign['motto']) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="users/nominee.php" class="btn btn-primary">Go to Nominee Page</a>
                    <a href="users/user.php" class="btn btn-secondary">Go to User Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
