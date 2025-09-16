<?php
session_start();
include_once '../common/connect.php';
include_once '../common/errorLogger.php';

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['id'] != 'admin') {
    header("Location:../login.php");
    exit();
}

// Get applicant name from URL
$applicantName = isset($_GET['name']) ? $_GET['name'] : '';

if (empty($applicantName)) {
    $_SESSION['errorMessage'] = "No applicant specified.";
    header("Location:admin.php?page=applications");
    exit();
}

// Get applicant details
$query = "SELECT * FROM candidates WHERE name = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $applicantName);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$applicant = mysqli_fetch_assoc($result);

if (!$applicant) {
    $_SESSION['errorMessage'] = "Applicant not found.";
    header("Location:admin.php?page=applications");
    exit();
}

// Handle application status update
if (isset($_POST['updateStatus'])) {
    $status = $_POST['status'];
    $comments = $_POST['comments'] ?? '';
    
    $updateQuery = "UPDATE candidates SET status = ?, comments = ? WHERE name = ?";
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, 'sss', $status, $comments, $applicantName);
    
    if (mysqli_stmt_execute($updateStmt)) {
        $_SESSION['successMessage'] = "Application status updated successfully.";
        header("Location:admin.php?page=applications");
        exit();
    } else {
        $_SESSION['errorMessage'] = "Failed to update application status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicant - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .applicant-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .applicant-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .applicant-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .info-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        .info-value {
            color: #666;
        }
    </style>
</head>
<body>
    <?php include '../common/navbar.php'; ?>
    <?php include '../common/message.php'; ?>

    <!-- Applicant Header -->
    <div class="applicant-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="<?= htmlspecialchars($applicant['pfp']) ?>" alt="Applicant Photo" class="applicant-photo" onerror="this.src='../assets/logo.png'">
                </div>
                <div class="col-md-9">
                    <h1 class="mb-2"><?= htmlspecialchars($applicant['name']) ?></h1>
                    <p class="mb-2">
                        <i class="fas fa-briefcase me-2"></i><?= htmlspecialchars($applicant['post']) ?>
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-building me-2"></i><?= htmlspecialchars($applicant['dept']) ?>
                    </p>
                    <span class="badge status-badge 
                        <?php 
                        switch($applicant['status']) {
                            case 'Accepted': echo 'bg-success'; break;
                            case 'Rejected': echo 'bg-danger'; break;
                            case 'Pending': echo 'bg-warning'; break;
                            default: echo 'bg-secondary';
                        }
                        ?>">
                        <?= $applicant['status'] ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Personal Information -->
            <div class="col-lg-6 mb-4">
                <div class="card applicant-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value"><?= htmlspecialchars($applicant['name']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Position Applied For</div>
                            <div class="info-value"><?= htmlspecialchars($applicant['post']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Department</div>
                            <div class="info-value"><?= htmlspecialchars($applicant['dept']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">CGPA</div>
                            <div class="info-value"><?= $applicant['cgpa'] ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Application Attempts</div>
                            <div class="info-value"><?= $applicant['attempts'] ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Details -->
            <div class="col-lg-6 mb-4">
                <div class="card applicant-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>Application Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">Reason for Applying</div>
                            <div class="info-value"><?= htmlspecialchars($applicant['reason']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Achievements</div>
                            <div class="info-value"><?= htmlspecialchars($applicant['achieve']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Club Memberships</div>
                            <div class="info-value"><?= htmlspecialchars($applicant['club']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Additional Details</div>
                            <div class="info-value"><?= htmlspecialchars($applicant['detail']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate -->
        <?php if (!empty($applicant['cert'])): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card applicant-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-certificate me-2"></i>Certificate
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="<?= htmlspecialchars($applicant['cert']) ?>" alt="Certificate" class="img-fluid" style="max-height: 400px;" onerror="this.src='../assets/default-campaign.jpg'">
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Status Update Form -->
        <div class="row">
            <div class="col-12">
                <div class="card applicant-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Update Application Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="Pending" <?= $applicant['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Accepted" <?= $applicant['status'] == 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                                        <option value="Rejected" <?= $applicant['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="comments" class="form-label">Comments</label>
                                    <textarea class="form-control" id="comments" name="comments" rows="3" placeholder="Add any comments about this application"><?= htmlspecialchars($applicant['comments']) ?></textarea>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="updateStatus" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Status
                                </button>
                                <a href="admin.php?page=applications" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Applications
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
