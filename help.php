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
    <title>Help & FAQ - FCRIT Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .help-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .step-number {
            background: #667eea;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Help & FAQ</h1>
                    <p class="lead">Find answers to common questions and learn how to use the voting system effectively.</p>
                    <div class="mt-4">
                        <a href="landing.php" class="btn btn-outline-light me-2">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Help Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <!-- Getting Started -->
                    <div class="card help-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-play-circle me-2"></i>Getting Started</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>For Voters</h5>
                                    <div class="d-flex mb-3">
                                        <div class="step-number">1</div>
                                        <div>
                                            <strong>Register</strong><br>
                                            <small class="text-muted">Create your account with student details</small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="step-number">2</div>
                                        <div>
                                            <strong>Login</strong><br>
                                            <small class="text-muted">Access your account using your credentials</small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="step-number">3</div>
                                        <div>
                                            <strong>Vote</strong><br>
                                            <small class="text-muted">Cast your vote for your preferred candidates</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>For Candidates</h5>
                                    <div class="d-flex mb-3">
                                        <div class="step-number">1</div>
                                        <div>
                                            <strong>Register</strong><br>
                                            <small class="text-muted">Create your account first</small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="step-number">2</div>
                                        <div>
                                            <strong>Apply</strong><br>
                                            <small class="text-muted">Submit your candidate application</small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="step-number">3</div>
                                        <div>
                                            <strong>Campaign</strong><br>
                                            <small class="text-muted">Create your campaign and wait for approval</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <div class="card help-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-question-circle me-2"></i>Frequently Asked Questions</h3>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="helpAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="help1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                            How do I register for voting?
                                        </button>
                                    </h2>
                                    <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Click on the "Register" button on the homepage</li>
                                                <li>Fill in your student details (Name, Student ID, Department, Year)</li>
                                                <li>Create a secure password</li>
                                                <li>Submit the form and wait for account activation</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="help2">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                            How do I apply as a candidate?
                                        </button>
                                    </h2>
                                    <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Login to your account</li>
                                                <li>Go to "Apply as Nominee" section</li>
                                                <li>Select the position you want to contest for</li>
                                                <li>Fill in your achievements, club memberships, and reasons for applying</li>
                                                <li>Upload your certificate and profile picture</li>
                                                <li>Submit the application and wait for admin approval</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="help3">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                            When can I vote?
                                        </button>
                                    </h2>
                                    <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            You can vote only when the election is in progress (Status: "In Progress"). The admin will start the election, and you'll be able to access the voting page during that time. You can only vote once per election.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="help4">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                            Is my vote secure and anonymous?
                                        </button>
                                    </h2>
                                    <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            Yes, the system ensures complete vote security and anonymity. Your vote is encrypted and cannot be traced back to you. The system only tracks that you have voted, not who you voted for.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="help5">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                            What if I forget my password?
                                        </button>
                                    </h2>
                                    <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            Contact the system administrator or IT support team to reset your password. You can reach them through the contact information provided on the Contact page.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="help6">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                                            Can I change my vote after submitting?
                                        </button>
                                    </h2>
                                    <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            No, once you submit your vote, it cannot be changed. This ensures the integrity of the voting process. Make sure to review your choices carefully before submitting.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="help7">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7">
                                            How do I check my application status?
                                        </button>
                                    </h2>
                                    <div id="collapse7" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            After logging in, go to your dashboard. You'll see your application status (Pending, Accepted, or Rejected) along with any comments from the admin. You can also view your application details and make changes if needed.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Troubleshooting -->
                    <div class="card help-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-tools me-2"></i>Troubleshooting</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Common Issues</h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i><strong>Login Issues:</strong> Check your credentials and ensure caps lock is off</li>
                                        <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i><strong>Page Not Loading:</strong> Clear browser cache and try again</li>
                                        <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i><strong>Vote Not Submitting:</strong> Check your internet connection</li>
                                        <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i><strong>File Upload Issues:</strong> Ensure file size is under 2MB and format is correct</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Browser Compatibility</h5>
                                    <p>For best experience, use one of these browsers:</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fab fa-chrome text-primary me-2"></i>Google Chrome (Recommended)</li>
                                        <li><i class="fab fa-firefox text-warning me-2"></i>Mozilla Firefox</li>
                                        <li><i class="fab fa-safari text-info me-2"></i>Safari</li>
                                        <li><i class="fab fa-edge text-success me-2"></i>Microsoft Edge</li>
                                    </ul>
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
