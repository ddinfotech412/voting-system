<?php
session_start();
require 'common/connect.php';
require 'common/links.php';

// Set page title
$page_title = 'Documentation - FCRIT Voting System';

// Simple markdown parser function
function parseMarkdown($text) {
    // Headers
    $text = preg_replace('/^### (.*$)/m', '<h3 class="fw-bold mt-4 mb-3">$1</h3>', $text);
    $text = preg_replace('/^## (.*$)/m', '<h2 class="fw-bold mt-5 mb-4 text-primary">$1</h2>', $text);
    $text = preg_replace('/^# (.*$)/m', '<h1 class="fw-bold mb-4 text-primary">$1</h1>', $text);
    
    // Bold text
    $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
    
    // Italic text
    $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
    
    // Code blocks
    $text = preg_replace('/```(.*?)```/s', '<pre class="bg-light p-3 rounded"><code>$1</code></pre>', $text);
    
    // Inline code
    $text = preg_replace('/`(.*?)`/', '<code class="bg-light px-2 py-1 rounded">$1</code>', $text);
    
    // Lists
    $text = preg_replace('/^\- (.*$)/m', '<li class="mb-2">$1</li>', $text);
    $text = preg_replace('/^(\d+)\. (.*$)/m', '<li class="mb-2">$2</li>', $text);
    
    // Wrap lists in ul/ol tags
    $text = preg_replace('/(<li class="mb-2">.*<\/li>)/s', '<ul class="list-unstyled">$1</ul>', $text);
    
    // Links
    $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" class="text-primary" target="_blank">$1</a>', $text);
    
    // Horizontal rules
    $text = preg_replace('/^---$/m', '<hr class="my-4">', $text);
    
    // Paragraphs
    $text = preg_replace('/^(?!<[h|u|p|d|h])(.*)$/m', '<p class="mb-3">$1</p>', $text);
    
    return $text;
}

// Read the markdown file
$markdown_content = file_get_contents('docs/FCRIT_Voting_System_Documentation.md');
$html_content = parseMarkdown($markdown_content);
?>

<?php include 'common/header.php'; ?>

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

    <!-- Documentation Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="documentation-content">
                                <?php echo $html_content; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Documentation Links -->
            <div class="row mt-5">
                <div class="col-lg-12">
                    <h3 class="fw-bold text-center mb-4">Additional Resources</h3>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                                    <h6 class="card-title">System Requirements Specification</h6>
                                    <p class="card-text small">Original SRS document</p>
                                    <a href="docs/SRS_FCRIT_Voting_System.pdf" class="btn btn-outline-danger btn-sm" target="_blank">
                                        <i class="fas fa-download me-1"></i>Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-file-pdf fa-2x text-warning mb-2"></i>
                                    <h6 class="card-title">Technical Paper</h6>
                                    <p class="card-text small">Original technical paper</p>
                                    <a href="docs/Technical Paper.pdf" class="btn btn-outline-warning btn-sm" target="_blank">
                                        <i class="fas fa-download me-1"></i>Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="card feature-card h-100">
                                <div class="card-body text-center p-3">
                                    <i class="fas fa-file-pdf fa-2x text-info mb-2"></i>
                                    <h6 class="card-title">Project Report</h6>
                                    <p class="card-text small">Complete project report</p>
                                    <a href="docs/MINI-PROJECT REPORT FINAL.pdf" class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="fas fa-download me-1"></i>Download PDF
                                    </a>
                                </div>
                            </div>
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
