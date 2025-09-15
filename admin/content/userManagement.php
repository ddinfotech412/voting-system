<div class="user-management-content">
    <div class="user-management-header">
        <h1 class="section-title">User Management</h1>
        <p class="text-muted">Manage user accounts and permissions</p>
    </div>
        
    <div class="action-buttons">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus me-2"></i>Add New User
        </button>
        <button class="btn btn-success" onclick="exportUsers()">
            <i class="fas fa-download me-2"></i>Export Users
        </button>
    </div>

    <div class="stats-grid">
        <?php
        $total_users_query = "SELECT COUNT(*) as total FROM login WHERE id != 'admin'";
        $total_users_result = mysqli_query($conn, $total_users_query);
        $total_users = mysqli_fetch_assoc($total_users_result)['total'];

        $voted_users_query = "SELECT COUNT(DISTINCT voter_id) as total FROM votes";
        $voted_users_result = mysqli_query($conn, $voted_users_query);
        $voted_users = mysqli_fetch_assoc($voted_users_result)['total'];

        $candidate_users_query = "SELECT COUNT(*) as total FROM candidates";
        $candidate_users_result = mysqli_query($conn, $candidate_users_query);
        $candidate_users = mysqli_fetch_assoc($candidate_users_result)['total'];
        ?>
        
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $total_users ?></h3>
                <p class="stat-label">Total Users</p>
            </div>
        </div>
        
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-vote-yea"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $voted_users ?></h3>
                <p class="stat-label">Users Who Voted</p>
            </div>
        </div>
        
        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $candidate_users ?></h3>
                <p class="stat-label">Candidates</p>
            </div>
        </div>
        
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $total_users > 0 ? round(($voted_users / $total_users) * 100, 2) : 0 ?>%</h3>
                <p class="stat-label">Voting Rate</p>
            </div>
        </div>
    </div>

    <div class="users-table">
        <div class="card">
            <div class="card-header">
                <h3 class="section-title mb-0">
                    <i class="fas fa-users me-2"></i>All Users
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Vote Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users_query = "SELECT * FROM login WHERE id != 'admin' ORDER BY id";
                            $users_result = mysqli_query($conn, $users_query);
                            
                            $i = 1;
                            if (mysqli_num_rows($users_result) > 0) {
                                while ($user = mysqli_fetch_assoc($users_result)) {
                                    $vote_status = $user['voteStatus'] == 1 ? 'Voted' : 'Not Voted';
                                    $vote_class = $user['voteStatus'] == 1 ? 'success' : 'warning';
                                    
                                    echo "<tr>
                                            <td>$i</td>
                                            <td>{$user['id']}</td>
                                            <td>{$user['uname']}</td>
                                            <td><span class='badge badge-primary'>Active</span></td>
                                            <td><span class='badge badge-$vote_class'>$vote_status</span></td>
                                            <td>
                                                <button class='btn btn-danger btn-sm' onclick='deleteUser(\"{$user['id']}\")'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>
                                          </tr>";
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../common/userAction.php" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userId" class="form-label">User ID</label>
                        <input type="text" class="form-control" id="userId" name="userId" required>
                    </div>
                    <div class="mb-3">
                        <label for="userName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="userName" name="userName" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="addUser" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function exportUsers() {
    // Simple CSV export functionality
    const table = document.querySelector('table');
    const rows = Array.from(table.querySelectorAll('tr'));
    const csvContent = rows.map(row => 
        Array.from(row.querySelectorAll('th, td')).map(cell => 
            '"' + cell.textContent.replace(/"/g, '""') + '"'
        ).join(',')
    ).join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'users_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch('../common/userAction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'deleteUser=' + userId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting user: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting user');
        });
    }
}
</script>

<style>
.user-management-content {
    padding: 0;
}

.user-management-header {
    margin-bottom: 30px;
}

.user-management-header h1 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
}

.action-buttons {
    margin-bottom: 30px;
    text-align: right;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.stat-primary .stat-icon { color: #667eea; }
.stat-success .stat-icon { color: #28a745; }
.stat-info .stat-icon { color: #17a2b8; }
.stat-warning .stat-icon { color: #ffc107; }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #333;
}

.stat-label {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

.users-table {
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

.badge-primary {
    background-color: #667eea;
    color: white;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        text-align: center;
    }
    
    .stat-card {
        padding: 15px;
    }
}
</style>
