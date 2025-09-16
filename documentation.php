<?php
session_start();
require 'common/connect.php';
require 'common/links.php';
include 'common/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation - FCRIT Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 120px 0 80px 0;
            text-align: center;
        }
        .document-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .document-card:hover {
            transform: translateY(-5px);
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            color: #667eea !important;
        }
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #ff6b6b !important;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="landing.php">
                <i class="fas fa-vote-yea me-2"></i>FCRIT Voting System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="landing.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="help.php">Help</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="documentation.php">Documentation</a>
                    </li>
                    <?php if (isset($_SESSION['id'])): ?>
                        <?php if ($_SESSION['id'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/admin.php">Admin Panel</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="users/user.php">Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="common/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h1 class="display-4 fw-bold mb-4">Documentation</h1>
                    <p class="lead mb-4">Comprehensive documentation for the FCRIT Voting System</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Documentation Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 class="fw-bold">Available Documents</h2>
                    <p class="text-muted">Download and review our comprehensive documentation</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card document-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">System Requirements Specification</h5>
                            <p class="card-text">Complete SRS document detailing system requirements, features, and specifications.</p>
                            <a href="docs/SRS_FCRIT_Voting_System.pdf" class="btn btn-primary" target="_blank">
                                <i class="fas fa-download me-2"></i>Download SRS
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card document-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-pdf fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Technical Paper</h5>
                            <p class="card-text">Technical documentation covering implementation details and system architecture.</p>
                            <a href="docs/Technical Paper.pdf" class="btn btn-primary" target="_blank">
                                <i class="fas fa-download me-2"></i>Download Technical Paper
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card document-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-pdf fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Project Report</h5>
                            <p class="card-text">Complete project report with analysis, design, and implementation details.</p>
                            <a href="docs/MINI-PROJECT REPORT FINAL.pdf" class="btn btn-primary" target="_blank">
                                <i class="fas fa-download me-2"></i>Download Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Start Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h3 class="fw-bold text-center mb-4">Quick Start Guide</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-plus text-primary fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5>1. Register</h5>
                                    <p class="text-muted">Create your account to participate in the voting process.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-sign-in-alt text-success fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5>2. Login</h5>
                                    <p class="text-muted">Access your dashboard and voting interface.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-vote-yea text-info fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5>3. Vote</h5>
                                    <p class="text-muted">Cast your vote for your preferred candidates.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chart-bar text-warning fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5>4. View Results</h5>
                                    <p class="text-muted">Monitor real-time results and analytics.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'common/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
