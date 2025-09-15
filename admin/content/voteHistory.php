<div class="vote-history-content">
    <div class="vote-history-header">
        <h1 class="section-title">Vote History</h1>
        <p class="text-muted">Track who voted for whom in the current election</p>
    </div>

    <div class="stats-grid">
        <?php
        // Get total voters
        $total_voters_query = "SELECT COUNT(*) as total FROM login WHERE id != 'admin'";
        $total_voters_result = mysqli_query($conn, $total_voters_query);
        $total_voters = mysqli_fetch_assoc($total_voters_result)['total'];

        // Get total votes cast
        $total_votes_query = "SELECT COUNT(*) as total FROM votes";
        $total_votes_result = mysqli_query($conn, $total_votes_query);
        $total_votes = mysqli_fetch_assoc($total_votes_result)['total'];

        // Get voters who have voted
        $voted_users_query = "SELECT COUNT(DISTINCT voter_id) as total FROM votes";
        $voted_users_result = mysqli_query($conn, $voted_users_query);
        $voted_users = mysqli_fetch_assoc($voted_users_result)['total'];

        $turnout = $total_voters > 0 ? round(($voted_users / $total_voters) * 100, 2) : 0;
        ?>
        
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $total_voters ?></h3>
                <p class="stat-label">Total Registered Voters</p>
            </div>
        </div>
        
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-vote-yea"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $total_votes ?></h3>
                <p class="stat-label">Votes Cast</p>
            </div>
        </div>
        
        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $voted_users ?></h3>
                <p class="stat-label">Voters Who Voted</p>
            </div>
        </div>
        
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $turnout ?>%</h3>
                <p class="stat-label">Voter Turnout</p>
            </div>
        </div>
    </div>

    <div class="position-stats">
        <div class="card">
            <div class="card-header">
                <h3 class="section-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Votes by Position
                </h3>
            </div>
            <div class="card-body">
                <?php
                $position_query = "SELECT post, COUNT(*) as vote_count FROM votes GROUP BY post";
                $position_result = mysqli_query($conn, $position_query);
                
                if (mysqli_num_rows($position_result) > 0) {
                    while ($position = mysqli_fetch_assoc($position_result)) {
                        echo "<div class='position-stat'>
                                <span class='position-name'>{$position['post']}</span>
                                <span class='position-count'>{$position['vote_count']} votes</span>
                              </div>";
                    }
                } else {
                    echo "<p class='text-muted text-center'>No votes cast yet</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="vote-history-table">
        <div class="card">
            <div class="card-header">
                <h3 class="section-title mb-0">
                    <i class="fas fa-history me-2"></i>Detailed Vote History
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Voter ID</th>
                                <th>Voter Name</th>
                                <th>Candidate Name</th>
                                <th>Position</th>
                                <th>Vote Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $history_query = "SELECT * FROM votes ORDER BY vote_time DESC";
                            $history_result = mysqli_query($conn, $history_query);
                            
                            $i = 1;
                            if (mysqli_num_rows($history_result) > 0) {
                                while ($vote = mysqli_fetch_assoc($history_result)) {
                                    echo "<tr>
                                            <td>$i</td>
                                            <td>{$vote['voter_id']}</td>
                                            <td>{$vote['voter_name']}</td>
                                            <td>{$vote['candidate_name']}</td>
                                            <td>{$vote['position']}</td>
                                            <td>" . date('Y-m-d H:i:s', strtotime($vote['vote_time'])) . "</td>
                                          </tr>";
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted py-4'>No votes cast yet</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn btn-primary" onclick="exportVoteHistory()">
            <i class="fas fa-download me-2"></i>Export to CSV
        </button>
    </div>
</div>

<script>
function exportVoteHistory() {
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
    a.download = 'vote_history_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>

<style>
.vote-history-content {
    padding: 0;
}

.vote-history-header {
    margin-bottom: 30px;
}

.vote-history-header h1 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
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

.position-stats {
    margin-bottom: 30px;
}

.position-stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.position-stat:last-child {
    border-bottom: none;
}

.position-name {
    color: #333;
    font-weight: 500;
}

.position-count {
    color: #667eea;
    font-weight: 600;
}

.vote-history-table {
    margin-bottom: 30px;
}

.action-buttons {
    text-align: center;
    margin-top: 20px;
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

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 15px;
    }
}
</style>
