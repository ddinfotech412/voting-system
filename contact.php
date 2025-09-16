<?php
session_start();
require 'common/connect.php';
require 'common/links.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - FCRIT Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 120px 0 80px 0;
            margin-top: 0;
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
        .contact-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-5px);
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
                        <a class="nav-link active" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="help.php">Help</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="documentation.php">Documentation</a>
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
                    <h1 class="display-4 fw-bold mb-4">Contact Us</h1>
                    <p class="lead">Get in touch with our support team for any questions or assistance with the voting system.</p>
                    <div class="mt-4">
                        <a href="landing.php" class="btn btn-outline-light me-2">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card contact-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                    <h5>Email Support</h5>
                                    <p class="text-muted">For technical issues and general inquiries</p>
                                    <a href="mailto:support@fcrit.ac.in" class="btn btn-primary">support@fcrit.ac.in</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card contact-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-phone fa-3x text-success mb-3"></i>
                                    <h5>Phone Support</h5>
                                    <p class="text-muted">Available during college hours</p>
                                    <a href="tel:+91-XXX-XXXXXXX" class="btn btn-success">+91-XXX-XXXXXXX</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card contact-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-map-marker-alt fa-3x text-info mb-3"></i>
                                    <h5>Visit Us</h5>
                                    <p class="text-muted">FCRIT Campus, Vashi</p>
                                    <address class="mb-0">
                                        FCRIT<br>
                                        Vashi, Navi Mumbai<br>
                                        Maharashtra, India
                                    </address>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card contact-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                                    <h5>Support Hours</h5>
                                    <p class="text-muted">Monday to Friday</p>
                                    <p class="mb-0">9:00 AM - 5:00 PM IST</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h3 class="fw-bold mb-4">Frequently Asked Questions</h3>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                        How do I register to vote?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can register by clicking the "Register" button on the homepage and filling out the registration form with your student details.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                        How do I apply as a candidate?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        After logging in, go to the "Apply as Nominee" section and fill out the candidate application form with your details, achievements, and campaign information.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                        Is my vote secure and anonymous?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes, the system ensures vote security and anonymity. Your vote is encrypted and cannot be traced back to you personally.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                        What if I forget my password?
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Contact the system administrator or IT support team to reset your password. You can reach them through the contact information provided above.
                                    </div>
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
