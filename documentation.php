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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 120px 0 100px 0;
            text-align: center;
            margin-top: 0;
        }
        .doc-section {
            padding: 60px 0;
        }
        .doc-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }
        .doc-card:hover {
            transform: translateY(-5px);
        }
        .code-block {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list i {
            color: #28a745;
            margin-right: 0.5rem;
        }
        .download-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .download-btn:hover {
            transform: translateY(-2px);
            color: white;
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
            color: #667eea !important;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 1rem 0;
        }
        .tech-item {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            border: 1px solid #e9ecef;
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
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">System Documentation</h1>
                    <p class="lead">Comprehensive documentation for the FCRIT Voting System including technical specifications, user guides, and system requirements.</p>
                    <div class="mt-4">
                        <a href="landing.php" class="btn btn-outline-light me-2">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                        <a href="#downloads" class="btn btn-light">
                            <i class="fas fa-download me-2"></i>Download SRS
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Documentation Content -->
    <div class="container">
        <!-- System Overview -->
        <section class="doc-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Overview</h2>
                        </div>
                        <div class="card-body">
                            <p class="lead">The FCRIT Voting System is a comprehensive web-based application designed for conducting secure and transparent student council elections at FCRIT (Fr. Conceicao Rodrigues Institute of Technology).</p>
                            
                            <h4>Key Objectives:</h4>
                            <ul class="feature-list">
                                <li><i class="fas fa-check"></i>Provide a secure and transparent voting platform</li>
                                <li><i class="fas fa-check"></i>Enable efficient candidate registration and management</li>
                                <li><i class="fas fa-check"></i>Ensure voter privacy and vote integrity</li>
                                <li><i class="fas fa-check"></i>Provide real-time analytics and reporting</li>
                                <li><i class="fas fa-check"></i>Support multiple election phases and statuses</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Technical Specifications -->
        <section class="doc-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-cogs me-2"></i>Technical Specifications</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Backend Technologies</h4>
                                    <div class="tech-stack">
                                        <span class="tech-item">PHP 7.4+</span>
                                        <span class="tech-item">MySQL 8.0+</span>
                                        <span class="tech-item">MySQLi</span>
                                        <span class="tech-item">Apache/Nginx</span>
                                    </div>
                                    
                                    <h4 class="mt-4">Frontend Technologies</h4>
                                    <div class="tech-stack">
                                        <span class="tech-item">HTML5</span>
                                        <span class="tech-item">CSS3</span>
                                        <span class="tech-item">JavaScript</span>
                                        <span class="tech-item">Bootstrap 5</span>
                                        <span class="tech-item">Chart.js</span>
                                        <span class="tech-item">Font Awesome</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>System Requirements</h4>
                                    <ul class="feature-list">
                                        <li><i class="fas fa-server"></i>Web Server: Apache 2.4+ or Nginx</li>
                                        <li><i class="fas fa-database"></i>Database: MySQL 8.0+ or MariaDB 10.3+</li>
                                        <li><i class="fas fa-code"></i>PHP: Version 7.4 or higher</li>
                                        <li><i class="fas fa-memory"></i>RAM: Minimum 2GB, Recommended 4GB+</li>
                                        <li><i class="fas fa-hdd"></i>Storage: 500MB for application + database</li>
                                        <li><i class="fas fa-globe"></i>Browser: Modern browsers (Chrome, Firefox, Safari, Edge)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Database Schema -->
        <section class="doc-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-database me-2"></i>Database Schema</h2>
                        </div>
                        <div class="card-body">
                            <p>The system uses a well-structured MySQL database with the following tables:</p>
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Table Name</th>
                                            <th>Purpose</th>
                                            <th>Key Fields</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>login</code></td>
                                            <td>User authentication and voting status</td>
                                            <td>id, uname, pw, voteStatus, department, year</td>
                                        </tr>
                                        <tr>
                                            <td><code>candidates</code></td>
                                            <td>Candidate information and applications</td>
                                            <td>id, name, post, dept, status, voteCount</td>
                                        </tr>
                                        <tr>
                                            <td><code>campaign</code></td>
                                            <td>Campaign materials and mottos</td>
                                            <td>id, motto, size, campaign</td>
                                        </tr>
                                        <tr>
                                            <td><code>votes</code></td>
                                            <td>Individual vote records</td>
                                            <td>voter_id, candidate_id, position, voted_at</td>
                                        </tr>
                                        <tr>
                                            <td><code>analytics</code></td>
                                            <td>Election statistics and metrics</td>
                                            <td>total_voters, total_votes_cast, voter_turnout</td>
                                        </tr>
                                        <tr>
                                            <td><code>announcements</code></td>
                                            <td>System announcements</td>
                                            <td>title, content, created_by, is_active</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Features -->
        <section class="doc-section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-users me-2"></i>User Features</h3>
                        </div>
                        <div class="card-body">
                            <ul class="feature-list">
                                <li><i class="fas fa-user-plus"></i>User Registration and Authentication</li>
                                <li><i class="fas fa-vote-yea"></i>Secure Voting Interface</li>
                                <li><i class="fas fa-user-tie"></i>Candidate Application System</li>
                                <li><i class="fas fa-bullhorn"></i>Campaign Management</li>
                                <li><i class="fas fa-history"></i>Vote History Tracking</li>
                                <li><i class="fas fa-mobile-alt"></i>Mobile-Responsive Design</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Admin Features</h3>
                        </div>
                        <div class="card-body">
                            <ul class="feature-list">
                                <li><i class="fas fa-cogs"></i>Election Status Management</li>
                                <li><i class="fas fa-user-check"></i>Candidate Application Review</li>
                                <li><i class="fas fa-chart-bar"></i>Real-time Analytics Dashboard</li>
                                <li><i class="fas fa-users-cog"></i>User Management</li>
                                <li><i class="fas fa-exclamation-triangle"></i>Error Logging and Monitoring</li>
                                <li><i class="fas fa-database"></i>Database Management Tools</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Installation Guide -->
        <section class="doc-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-download me-2"></i>Installation Guide</h2>
                        </div>
                        <div class="card-body">
                            <h4>Step 1: Prerequisites</h4>
                            <div class="code-block">
                                <pre><code># Install required software
- Web Server (Apache/Nginx)
- PHP 7.4+ with MySQLi extension
- MySQL 8.0+ or MariaDB 10.3+
- Modern web browser</code></pre>
                            </div>

                            <h4>Step 2: Database Setup</h4>
                            <div class="code-block">
                                <pre><code># Create database
CREATE DATABASE voting_system;

# Import schema
mysql -u username -p voting_system < voting_system.sql</code></pre>
                            </div>

                            <h4>Step 3: Configuration</h4>
                            <div class="code-block">
                                <pre><code># Update database connection in common/connect.php
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'voting_system';</code></pre>
                            </div>

                            <h4>Step 4: File Permissions</h4>
                            <div class="code-block">
                                <pre><code># Set proper permissions
chmod 755 logs/
chmod 644 logs/error.log
chmod 755 assets/</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Downloads Section -->
        <section id="downloads" class="doc-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-file-download me-2"></i>Downloads & Resources</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Documentation Files</h4>
                                    <div class="d-grid gap-2">
                                        <a href="docs/MINI-PROJECT REPORT FINAL.pdf" class="download-btn" target="_blank">
                                            <i class="fas fa-file-pdf me-2"></i>Download Project Report (PDF)
                                        </a>
                                        <a href="docs/Technical Paper.pdf" class="download-btn" target="_blank">
                                            <i class="fas fa-file-pdf me-2"></i>Download Technical Paper (PDF)
                                        </a>
                                        <a href="voting_system.sql" class="download-btn" download>
                                            <i class="fas fa-database me-2"></i>Download Database Schema (SQL)
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>System Requirements Specification (SRS)</h4>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Comprehensive SRS Document</strong><br>
                                        Download the complete Software Requirements Specification document containing detailed system requirements, functional specifications, and technical documentation.
                                    </div>
                                    <a href="docs/SRS_FCRIT_Voting_System.pdf" class="download-btn" target="_blank">
                                        <i class="fas fa-file-alt me-2"></i>Download SRS Document (PDF)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- API Documentation -->
        <section class="doc-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-code me-2"></i>API & Integration</h2>
                        </div>
                        <div class="card-body">
                            <h4>Key Endpoints</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Endpoint</th>
                                            <th>Method</th>
                                            <th>Purpose</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>/common/postLogin.php</code></td>
                                            <td>POST</td>
                                            <td>User authentication</td>
                                        </tr>
                                        <tr>
                                            <td><code>/common/voteActions.php</code></td>
                                            <td>POST</td>
                                            <td>Vote submission and election management</td>
                                        </tr>
                                        <tr>
                                            <td><code>/common/formActions.php</code></td>
                                            <td>POST</td>
                                            <td>Candidate application processing</td>
                                        </tr>
                                        <tr>
                                            <td><code>/common/campaignActions.php</code></td>
                                            <td>POST</td>
                                            <td>Campaign management</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Security Features -->
        <section class="doc-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Features</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Authentication & Authorization</h4>
                                    <ul class="feature-list">
                                        <li><i class="fas fa-lock"></i>Session-based authentication</li>
                                        <li><i class="fas fa-user-shield"></i>Role-based access control</li>
                                        <li><i class="fas fa-key"></i>Password hashing and validation</li>
                                        <li><i class="fas fa-sign-out-alt"></i>Secure logout functionality</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h4>Data Protection</h4>
                                    <ul class="feature-list">
                                        <li><i class="fas fa-database"></i>SQL injection prevention</li>
                                        <li><i class="fas fa-eye-slash"></i>Vote anonymity protection</li>
                                        <li><i class="fas fa-file-shield"></i>Input validation and sanitization</li>
                                        <li><i class="fas fa-history"></i>Audit trail logging</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Support & Contact -->
        <section class="doc-section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card doc-card">
                        <div class="card-header">
                            <h2 class="mb-0"><i class="fas fa-life-ring me-2"></i>Support & Contact</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Technical Support</h4>
                                    <p>For technical issues, bugs, or feature requests:</p>
                                    <ul class="feature-list">
                                        <li><i class="fas fa-envelope"></i>Email: support@fcrit.ac.in</li>
                                        <li><i class="fas fa-phone"></i>Phone: +91-XXX-XXXXXXX</li>
                                        <li><i class="fas fa-clock"></i>Hours: Monday-Friday, 9 AM - 5 PM</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h4>Documentation Updates</h4>
                                    <p>This documentation is regularly updated. For the latest version:</p>
                                    <ul class="feature-list">
                                        <li><i class="fas fa-globe"></i>Visit: <a href="landing.php">FCRIT Voting System</a></li>
                                        <li><i class="fas fa-download"></i>Check downloads section for updates</li>
                                        <li><i class="fas fa-bell"></i>Subscribe to announcements for updates</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'common/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
</body>
</html>
