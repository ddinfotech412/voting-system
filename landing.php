<?php
$page_title = 'FCRIT Voting System';
include 'common/header.php';
?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h1 class="display-4 fw-bold mb-4">FCRIT Voting System</h1>
                    <p class="lead mb-4">Secure, transparent, and efficient online voting platform for student council elections</p>
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <?php if (isset($_SESSION['id'])): ?>
                            <?php if ($_SESSION['id'] == 'admin'): ?>
                                <a href="admin/admin.php" class="btn btn-light btn-lg">
                                    <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                </a>
                            <?php else: ?>
                                <a href="users/user.php" class="btn btn-light btn-lg">
                                    <i class="fas fa-user me-2"></i>User Dashboard
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-light btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                            <a href="register.php" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-center gap-4">
                        <a href="about.php" class="text-white text-decoration-none">
                            <i class="fas fa-info-circle me-1"></i>About Us
                        </a>
                        <a href="contact.php" class="text-white text-decoration-none">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                        <a href="help.php" class="text-white text-decoration-none">
                            <i class="fas fa-question-circle me-1"></i>Help & FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 class="fw-bold">Why Choose Our Voting System?</h2>
                    <p class="text-muted">Modern, secure, and user-friendly election management</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Secure Voting</h5>
                            <p class="card-text">Advanced security measures ensure the integrity and confidentiality of every vote.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Real-time Analytics</h5>
                            <p class="card-text">Comprehensive analytics and reporting for transparent election monitoring.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-mobile-alt fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Mobile Friendly</h5>
                            <p class="card-text">Responsive design works perfectly on all devices and screen sizes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">
                            <?php
                            $totalUsers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login WHERE id != 'admin'"));
                            echo $totalUsers;
                            ?>
                        </div>
                        <div class="text-muted">Registered Voters</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">
                            <?php
                            $totalCandidates = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM candidates WHERE status='Accepted'"));
                            echo $totalCandidates;
                            ?>
                        </div>
                        <div class="text-muted">Candidates</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">
                            <?php
                            $totalVotes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(voteCount) as total FROM candidates WHERE status='Accepted'"))['total'] ?? 0;
                            echo $totalVotes;
                            ?>
                        </div>
                        <div class="text-muted">Votes Cast</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">
                            <?php
                            $votedUsers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login WHERE voteStatus = 1 AND id != 'admin'"));
                            $voterTurnout = $totalUsers > 0 ? round(($votedUsers / $totalUsers) * 100, 1) : 0;
                            echo $voterTurnout . '%';
                            ?>
                        </div>
                        <div class="text-muted">Voter Turnout</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="fw-bold mb-4">Ready to Participate?</h2>
                    <p class="lead mb-4">Join the democratic process and make your voice heard in the student council elections.</p>
                    <?php if (!isset($_SESSION['id'])): ?>
                        <a href="register.php" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-user-plus me-2"></i>Register Now
                        </a>
                        <a href="login.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include 'common/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
