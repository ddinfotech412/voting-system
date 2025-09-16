<?php
$page_title = 'About - FCRIT Voting System';
include 'common/header.php';
?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">About FCRIT Voting System</h1>
                    <p class="lead">A modern, secure, and transparent online voting platform designed specifically for student council elections at FCRIT.</p>
                    <div class="mt-4">
                        <a href="landing.php" class="btn btn-outline-light me-2">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold mb-4">Our Mission</h2>
                    <p class="lead">To provide a secure, transparent, and efficient digital voting platform that ensures fair and democratic student council elections while maintaining the highest standards of data integrity and user experience.</p>
                    
                    <h2 class="fw-bold mb-4 mt-5">Key Features</h2>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body">
                                    <i class="fas fa-shield-alt fa-2x text-primary mb-3"></i>
                                    <h5>Security First</h5>
                                    <p>Advanced encryption and security measures protect every vote and user data.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body">
                                    <i class="fas fa-chart-line fa-2x text-success mb-3"></i>
                                    <h5>Real-time Analytics</h5>
                                    <p>Comprehensive reporting and analytics for transparent election monitoring.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body">
                                    <i class="fas fa-mobile-alt fa-2x text-info mb-3"></i>
                                    <h5>Mobile Responsive</h5>
                                    <p>Works seamlessly across all devices and screen sizes.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card feature-card h-100">
                                <div class="card-body">
                                    <i class="fas fa-users fa-2x text-warning mb-3"></i>
                                    <h5>User-Friendly</h5>
                                    <p>Intuitive interface designed for easy navigation and voting.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2 class="fw-bold mb-4 mt-5">Technology Stack</h2>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i><strong>Backend:</strong> PHP 7.4+ with MySQLi</li>
                        <li><i class="fas fa-check text-success me-2"></i><strong>Frontend:</strong> HTML5, CSS3, JavaScript, Bootstrap 5</li>
                        <li><i class="fas fa-check text-success me-2"></i><strong>Database:</strong> MySQL</li>
                        <li><i class="fas fa-check text-success me-2"></i><strong>Charts:</strong> Chart.js for data visualization</li>
                        <li><i class="fas fa-check text-success me-2"></i><strong>Icons:</strong> Font Awesome</li>
                    </ul>

                    <h2 class="fw-bold mb-4 mt-5">Contact Information</h2>
                    <p>For technical support or questions about the voting system, please contact:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i>Email: support@fcrit.ac.in</li>
                        <li><i class="fas fa-phone me-2"></i>Phone: +91-XXX-XXXXXXX</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Address: FCRIT, Vashi, Navi Mumbai</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <?php include 'common/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
