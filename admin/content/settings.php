<?php
include_once '../../common/connect.php';
include_once '../../common/errorLogger.php';

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['id'] != 'admin') {
    header("Location:../../login.php");
    exit();
}

// Get admin info
$admin_query = "SELECT * FROM login WHERE id='admin'";
$admin_result = mysqli_query($conn, $admin_query);
$admin = mysqli_fetch_assoc($admin_result);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="section-title">System Settings</h1>
            <p class="text-muted">Manage system configuration and data</p>
        </div>
    </div>

    <div class="row">
        <!-- Database Management -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-database me-2"></i>Database Management
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Reset Database with Dummy Data</h6>
                            <p class="text-muted mb-3">
                                This will completely reset the entire database and populate it with comprehensive dummy data for testing and demonstration purposes.
                            </p>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>What will be created:</strong>
                                <ul class="mb-0 mt-2">
                                    <li><strong>18 Users:</strong> 1 Admin + 17 Regular users across different departments</li>
                                    <li><strong>8 Candidates:</strong> 2 for each position (General Secretary, Joint Secretary, Sports Secretary, Cultural Secretary)</li>
                                    <li><strong>6 Campaigns:</strong> With mottos and campaign images</li>
                                    <li><strong>Vote Data:</strong> Realistic vote counts and some users marked as voted</li>
                                    <li><strong>Election Status:</strong> Reset to "Not Started" (Status 0)</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="d-grid">
                                <button type="button" class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#resetDatabaseModal">
                                    <i class="fas fa-database me-2"></i>Reset with Dummy Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>System Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Current Election Status</h6>
                            <p class="text-muted">
                                <?php
                                $statusText = '';
                                $statusClass = '';
                                switch($admin['voteStatus']) {
                                    case 0: $statusText = 'Not Started'; $statusClass = 'text-secondary'; break;
                                    case 1: $statusText = 'In Progress'; $statusClass = 'text-success'; break;
                                    case 2: $statusText = 'Ended'; $statusClass = 'text-warning'; break;
                                    case 3: $statusText = 'Results Declared'; $statusClass = 'text-info'; break;
                                }
                                ?>
                                <span class="<?= $statusClass ?>"><strong><?= $statusText ?></strong></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Total Users</h6>
                            <p class="text-muted">
                                <?php
                                $userCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login"));
                                echo "<strong>$userCount</strong> users registered";
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Database Modal -->
<div class="modal fade" id="resetDatabaseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="resetDatabaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="resetDatabaseModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Reset Database with Dummy Data
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action will completely replace all existing data in the database!
                </div>
                
                <p>This will:</p>
                <ul>
                    <li><strong>Delete all existing users</strong> (except admin)</li>
                    <li><strong>Delete all candidates and applications</strong></li>
                    <li><strong>Delete all campaigns and votes</strong></li>
                    <li><strong>Reset election status</strong> to "Not Started"</li>
                    <li><strong>Populate with fresh dummy data</strong> for testing</li>
                </ul>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Perfect for:</strong> Testing, demonstrations, development, or starting fresh with sample data.
                </div>
                
                <p class="text-muted">Are you sure you want to proceed? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <form action="../common/voteActions.php" method="post" class="d-inline">
                    <button type="submit" name="resetWithDummyData" class="btn btn-warning">
                        <i class="fas fa-database me-2"></i>Yes, Reset Database
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
