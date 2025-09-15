<div class="dashboard-content">
    <div class="dashboard-header">
        <h1 class="section-title">Admin Dashboard</h1>
        <p class="text-muted">Overview of the voting system and pending applications</p>
    </div>

    <div class="stats-grid">
        <?php
        function getTotalApplications($conn)
        {
            $sql = "SELECT COUNT(*) as total FROM candidates";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        }

        function displayCard($title, $status, $color, $icon, $conn)
        {
            if ($status === 'Total') {
                $applications = getTotalApplications($conn);
            } else {
                $sql = "SELECT * FROM candidates WHERE status='$status'";
                $result = mysqli_query($conn, $sql);
                $applications = mysqli_num_rows($result);
            }

            echo "<div class='stat-card stat-$color'>
                    <div class='stat-icon'>
                        <i class='$icon'></i>
                    </div>
                    <div class='stat-content'>
                        <h3 class='stat-number'>$applications</h3>
                        <p class='stat-label'>$title</p>
                    </div>
                </div>";
        }

        // Display cards for different application statuses
        displayCard('Accepted Applications', 'Accepted', 'success', 'fas fa-check', $conn);
        displayCard('Total Applications', 'Total', 'primary', 'fas fa-envelope-open-text', $conn);
        displayCard('Pending Applications', 'Pending', 'warning', 'far fa-clock', $conn);
        displayCard('Rejected Applications', 'Rejected', 'danger', 'fas fa-times', $conn);
        ?>
    </div>

    <div class="pending-applications">
        <div class="card">
            <div class="card-header">
                <h3 class="section-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>Pending Nominee Applications
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name of Nominee</th>
                                <th>Department</th>
                                <th>Post</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM candidates WHERE status='Pending'";
                            $query_run = mysqli_query($conn, $query);

                            $i = 1;
                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $nominee) {
                            ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$nominee['name']?></td>
                                <td><?=$nominee['dept']?></td>
                                <td><?=$nominee['post']?></td>
                                <td>
                                    <a href="./viewApplicant.php?name=<?=$nominee['name']?>" class="btn btn-primary btn-sm">
                                        <i class="far fa-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            <?php
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center text-muted py-4'>No Applications Submitted</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
