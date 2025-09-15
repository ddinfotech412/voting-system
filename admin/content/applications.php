<div class="applications-content">
    <div class="applications-header">
        <h1 class="section-title">Nominee Applications</h1>
        <p class="text-muted">Review and manage candidate applications</p>
    </div>

    <div class="applications-table">
        <div class="card">
            <div class="card-header">
                <h3 class="section-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>All Applications
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Post</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM candidates ORDER BY id DESC";
                            $query_run = mysqli_query($conn, $query);

                            $i = 1;
                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $application) {
                                    $statusClass = '';
                                    $statusIcon = '';
                                    
                                    switch($application['status']) {
                                        case 'Accepted':
                                            $statusClass = 'success';
                                            $statusIcon = 'fas fa-check';
                                            break;
                                        case 'Rejected':
                                            $statusClass = 'danger';
                                            $statusIcon = 'fas fa-times';
                                            break;
                                        case 'Pending':
                                        default:
                                            $statusClass = 'warning';
                                            $statusIcon = 'far fa-clock';
                                            break;
                                    }
                            ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$application['name']?></td>
                                <td><?=$application['dept']?></td>
                                <td><?=$application['post']?></td>
                                <td>
                                    <span class="badge bg-<?=$statusClass?>">
                                        <i class="<?=$statusIcon?> me-1"></i><?=$application['status']?>
                                    </span>
                                </td>
                                <td>
                                    <a href="./viewApplicant.php?name=<?=$application['name']?>" class="btn btn-primary btn-sm">
                                        <i class="far fa-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            <?php
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>No Applications Found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.applications-content {
    padding: 0;
}

.applications-header {
    margin-bottom: 30px;
}

.applications-header h1 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
}

.applications-table {
    margin-bottom: 30px;
}

.table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #333;
    padding: 15px;
}

.table td {
    border: none;
    padding: 15px;
    vertical-align: middle;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: #f8f9fa;
}

.badge {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 0.375rem;
}

.bg-success {
    background-color: #28a745 !important;
    color: white;
}

.bg-danger {
    background-color: #dc3545 !important;
    color: white;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #000;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
