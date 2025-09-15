<?php
require '../common/connect.php';

if ($_SESSION['id'] == 'admin') {
?>

<div class="election-status-content">
    <div class="election-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="section-title">Election Status</h1>
                <p class="text-muted">Manage election phases and view results</p>
            </div>
            <div>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#emergencyNewElection">
                    <i class="fas fa-refresh me-2"></i>Reset Election
                </button>
            </div>
        </div>
    </div>

    <!-- Emergency Reset Modal -->
    <div class="modal fade" id="emergencyNewElection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="emergencyNewElectionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="emergencyNewElectionLabel">Reset Election</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reset the election? This will:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-times text-danger me-2"></i>Delete all candidates</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Delete all campaigns</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Reset all vote counts</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Reset all user vote status</li>
                        <li><i class="fas fa-check text-success me-2"></i>Start fresh election cycle</li>
                    </ul>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="../common/voteActions.php" method="post" class="d-inline">
                        <button type="submit" name="newElection" class="btn btn-danger">
                            <i class="fas fa-refresh me-2"></i>Reset Election
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    function displayChart($position, $postID, $conn)
    {
        $voteChart = array();
        $count = 0;
        $vote = "SELECT * FROM candidates WHERE status='Accepted' AND post='$position'";
        $result = mysqli_query($conn, $vote);

        while ($row = mysqli_fetch_assoc($result)) {
            $voteChart[$count]["label"] = $row["name"];
            $voteChart[$count]["y"] = $row["voteCount"];
            $count++;
        }
        
        // Always display chart container
        ?>

        <div class="chart-container">
            <div class="text-center text-muted py-4">
                <i class="fas fa-chart-bar fa-3x mb-3"></i>
                <h5>Vote Results for <?= $position ?></h5>
                <p>Vote data will be displayed here once the election starts and users begin voting.</p>
            </div>
        </div>

        <?php
        // Show additional message when no votes yet
        if ($count == 0) {
            echo "<div class='text-center text-muted mt-2'>
                    <small><i class='fas fa-info-circle me-1'></i>No votes cast yet for $position</small>
                  </div>";
        }
    }

    $query = "SELECT voteStatus FROM `login` WHERE id='admin'";
    $result = mysqli_query($conn, $query);
    while ($admin = mysqli_fetch_assoc($result)) {
        // Start Voting
        if($admin['voteStatus']==0){
    ?>
        <div class="election-controls">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">
                        <i class="fas fa-play me-2"></i>Election Not Started
                    </h3>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-4">Ready to begin the voting process</p>
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#startElection">
                        <i class="fas fa-play me-2"></i>Start Election
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirm Election Start Modal -->
        <div class="modal fade" id="startElection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="startElectionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="startElectionLabel">Confirm Election Start</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to start the election? This will allow all registered users to cast their votes.</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> Once started, users will be able to vote immediately.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="../common/voteActions.php" method="post" class="d-inline">
                            <button type="submit" name="startElection" class="btn btn-success">
                                <i class="fas fa-play me-2"></i>Start Election
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
        }
        else if($admin['voteStatus']==1){
            // Election in Progress
    ?>
        <div class="election-controls">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">
                        <i class="fas fa-vote-yea me-2"></i>Election in Progress
                    </h3>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-4">Voting is currently active</p>
                    <button type="button" class="btn btn-warning btn-lg me-3" data-bs-toggle="modal" data-bs-target="#stopElection">
                        <i class="fas fa-stop me-2"></i>Stop Election
                    </button>
                    <button type="button" class="btn btn-info btn-lg" data-bs-toggle="modal" data-bs-target="#declareResults">
                        <i class="fas fa-trophy me-2"></i>Declare Results
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirm Election Stop Modal -->
        <div class="modal fade" id="stopElection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stopElectionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="stopElectionLabel">Confirm Election Stop</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to stop the election? This will prevent users from casting new votes.</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This action cannot be undone.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="../common/voteActions.php" method="post" class="d-inline">
                            <button type="submit" name="stopElection" class="btn btn-warning">
                                <i class="fas fa-stop me-2"></i>Stop Election
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Declare Results Modal -->
        <div class="modal fade" id="declareResults" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="declareResultsLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="declareResultsLabel">Declare Election Results</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to declare the election results? This will show the winners to all users.</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Results will be visible to all users after declaration.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="../common/voteActions.php" method="post" class="d-inline">
                            <button type="submit" name="declareResults" class="btn btn-info">
                                <i class="fas fa-trophy me-2"></i>Declare Results
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
        }
        else if($admin['voteStatus']==2){
            // Election Ended
    ?>
        <div class="election-controls">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">
                        <i class="fas fa-stop me-2"></i>Election Ended
                    </h3>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-4">Voting has been completed</p>
                    <button type="button" class="btn btn-primary btn-lg me-3" data-bs-toggle="modal" data-bs-target="#declareResults">
                        <i class="fas fa-trophy me-2"></i>Declare Results
                    </button>
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#newElection">
                        <i class="fas fa-plus me-2"></i>Start New Election
                    </button>
                </div>
            </div>
        </div>

        <!-- Start New Election Modal -->
        <div class="modal fade" id="newElection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newElectionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newElectionLabel">Start New Election</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to start a new election? This will reset all vote counts and allow new applications.</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This will reset all previous election data.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="../common/voteActions.php" method="post" class="d-inline">
                            <button type="submit" name="newElection" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Start New Election
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
        }
        else if($admin['voteStatus']==3){
            // Results Declared
    ?>
        <div class="election-controls">
            <div class="card">
                <div class="card-header">
                    <h3 class="section-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Election Results Declared
                    </h3>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-4">Results have been published and are visible to all users</p>
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#newElection">
                        <i class="fas fa-plus me-2"></i>Start New Election
                    </button>
                </div>
            </div>
        </div>

        <div class="results-section">
            <div class="card">
                <div class="card-header">
                    <h2 class="section-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Election Results
                    </h2>
                </div>
                <div class="card-body">
                    <div class="results-grid">
                        <?php
                        $winner_query = "SELECT * FROM candidates WHERE status='Accepted' ORDER BY post, voteCount DESC";
                        $winner_result = mysqli_query($conn, $winner_query);
                        
                        $current_post = '';
                        while ($winner = mysqli_fetch_assoc($winner_result)) {
                            if ($current_post != $winner['post']) {
                                $current_post = $winner['post'];
                                $post = $winner['post'];
                                $voteCount = $winner['voteCount'];
                        ?>
                            <div class="winner-card">
                                <div class="winner-image">
                                    <img src="<?=$winner['pfp']?>" alt="<?=$winner['name']?>">
                                </div>
                                <div class="winner-info">
                                    <h4 class="winner-name"><?=$winner['name']?></h4>
                                    <h5 class="winner-position"><?=$post?></h5>
                                    <div class="vote-count">
                                        <i class="fas fa-vote-yea me-2"></i>
                                        <span class="count"><?=$voteCount?></span>
                                        <small class="text-muted">votes</small>
                                    </div>
                                </div>
                            </div>
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start New Election Modal -->
        <div class="modal fade" id="newElection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newElectionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newElectionLabel">Start New Election</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to start a new election? This will reset all vote counts and allow new applications.</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This will reset all previous election data.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="../common/voteActions.php" method="post" class="d-inline">
                            <button type="submit" name="newElection" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Start New Election
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
        }
        ?>
        <?php
        // Check if there are any accepted candidates
        $stats_query = "SELECT COUNT(*) as total_candidates FROM candidates WHERE status='Accepted'";
        $stats_result = mysqli_query($conn, $stats_query);
        $stats_data = mysqli_fetch_assoc($stats_result);
        
        if ($stats_data['total_candidates'] > 0) {
        ?>
        <div class="stats-section">
            <div class="card">
                <div class="card-header">
                    <h2 class="section-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Election Statistics
                    </h2>
                </div>
                <div class="card-body">
                    <?php
                    // Display overall statistics
                    $totalVotesQuery = "SELECT SUM(voteCount) as total_votes FROM candidates WHERE status='Accepted'";
                    $totalVotesResult = mysqli_query($conn, $totalVotesQuery);
                    $totalVotesData = mysqli_fetch_assoc($totalVotesResult);
                    $totalVotes = $totalVotesData['total_votes'] ?? 0;
                    
                    $totalCandidatesQuery = "SELECT COUNT(*) as total_candidates FROM candidates WHERE status='Accepted'";
                    $totalCandidatesResult = mysqli_query($conn, $totalCandidatesQuery);
                    $totalCandidatesData = mysqli_fetch_assoc($totalCandidatesResult);
                    $totalCandidates = $totalCandidatesData['total_candidates'] ?? 0;
                    ?>
                    <?php
                    // Get additional statistics
                    $totalVotersQuery = "SELECT COUNT(*) as total_voters FROM login WHERE id != 'admin'";
                    $totalVotersResult = mysqli_query($conn, $totalVotersQuery);
                    $totalVotersData = mysqli_fetch_assoc($totalVotersResult);
                    $totalVoters = $totalVotersData['total_voters'] ?? 0;
                    
                    $votedUsersQuery = "SELECT COUNT(*) as voted_users FROM login WHERE voteStatus = 1 AND id != 'admin'";
                    $votedUsersResult = mysqli_query($conn, $votedUsersQuery);
                    $votedUsersData = mysqli_fetch_assoc($votedUsersResult);
                    $votedUsers = $votedUsersData['voted_users'] ?? 0;
                    
                    $voterTurnout = $totalVoters > 0 ? round(($votedUsers / $totalVoters) * 100, 1) : 0;
                    
                    // Get position-wise statistics
                    $positionStatsQuery = "SELECT post, COUNT(*) as candidate_count, SUM(voteCount) as total_votes FROM candidates WHERE status='Accepted' GROUP BY post";
                    $positionStatsResult = mysqli_query($conn, $positionStatsQuery);
                    $positionStats = [];
                    while ($row = mysqli_fetch_assoc($positionStatsResult)) {
                        $positionStats[] = $row;
                    }
                    
                    // Get top candidate
                    $topCandidateQuery = "SELECT name, post, voteCount FROM candidates WHERE status='Accepted' ORDER BY voteCount DESC LIMIT 1";
                    $topCandidateResult = mysqli_query($conn, $topCandidateQuery);
                    $topCandidate = mysqli_fetch_assoc($topCandidateResult);
                    ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <h4><?= $totalCandidates ?></h4>
                                    <p>Total Candidates</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-vote-yea"></i>
                                </div>
                                <div class="stat-content">
                                    <h4><?= $totalVotes ?></h4>
                                    <p>Total Votes Cast</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="stat-content">
                                    <h4><?= $votedUsers ?></h4>
                                    <p>Voters Participated</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="stat-content">
                                    <h4><?= $voterTurnout ?>%</h4>
                                    <p>Voter Turnout</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detailed Reports Section -->
                    <div class="reports-section mb-4">
                        <div class="row">
                            <!-- Voter Participation Report -->
                            <div class="col-md-6 mb-4">
                                <div class="report-card">
                                    <div class="report-header">
                                        <h5><i class="fas fa-chart-pie me-2"></i>Voter Participation</h5>
                                    </div>
                                    <div class="report-body">
                                        <div class="participation-stats">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Voted: <strong><?= $votedUsers ?></strong></span>
                                                <span class="text-success"><?= $voterTurnout ?>%</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Not Voted: <strong><?= $totalVoters - $votedUsers ?></strong></span>
                                                <span class="text-muted"><?= 100 - $voterTurnout ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: <?= $voterTurnout ?>%" 
                                                     aria-valuenow="<?= $voterTurnout ?>" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    <?= $voterTurnout ?>%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Position-wise Statistics -->
                            <div class="col-md-6 mb-4">
                                <div class="report-card">
                                    <div class="report-header">
                                        <h5><i class="fas fa-list-alt me-2"></i>Position Statistics</h5>
                                    </div>
                                    <div class="report-body">
                                        <div class="position-stats">
                                            <?php foreach ($positionStats as $stat): ?>
                                            <div class="position-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="position-name"><?= $stat['post'] ?></span>
                                                    <div class="position-details">
                                                        <span class="candidate-count"><?= $stat['candidate_count'] ?> candidates</span>
                                                        <span class="vote-count"><?= $stat['total_votes'] ?> votes</span>
                                                    </div>
                                                </div>
                                                <div class="progress mt-2" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: <?= $stat['total_votes'] > 0 ? min(100, ($stat['total_votes'] / max(array_column($positionStats, 'total_votes'))) * 100) : 0 ?>%"
                                                         aria-valuenow="<?= $stat['total_votes'] ?>" aria-valuemin="0" 
                                                         aria-valuemax="<?= max(array_column($positionStats, 'total_votes')) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Top Performer Report -->
                        <?php if ($topCandidate && $topCandidate['voteCount'] > 0): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="report-card">
                                    <div class="report-header">
                                        <h5><i class="fas fa-trophy me-2"></i>Top Performer</h5>
                                    </div>
                                    <div class="report-body">
                                        <div class="top-performer">
                                            <div class="d-flex align-items-center">
                                                <div class="performer-icon">
                                                    <i class="fas fa-crown"></i>
                                                </div>
                                                <div class="performer-details">
                                                    <h6 class="performer-name"><?= $topCandidate['name'] ?></h6>
                                                    <p class="performer-position"><?= $topCandidate['post'] ?></p>
                                                    <div class="performer-votes">
                                                        <span class="vote-count"><?= $topCandidate['voteCount'] ?> votes</span>
                                                        <span class="vote-percentage">
                                                            <?= $totalVotes > 0 ? round(($topCandidate['voteCount'] / $totalVotes) * 100, 1) : 0 ?>% of total votes
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
        <?php
        displayChart("General Secretary", "General", $conn);
        displayChart("Joint Secretary", "Joint", $conn);
        displayChart("Sports Secretary", "Sports", $conn);
        displayChart("Cultural Secretary", "Cultural", $conn);
        ?>
                </div>
            </div>
        </div>
        <?php
        }
        ?>

    <?php
        }
    } else {
        header("Location:../login.php");
        exit();
    }
    ?>
</div>

<style>
.election-status-content {
    padding: 0;
}

.election-header {
    margin-bottom: 30px;
}

.election-header h1 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
}

.election-controls {
    margin-bottom: 30px;
}

.election-controls .card {
    max-width: 500px;
    margin: 0 auto;
}

.results-section {
    margin-bottom: 30px;
}

.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.winner-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.winner-image img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    margin-bottom: 15px;
}

.winner-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.winner-position {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 15px;
}

.vote-count {
    font-size: 1.2rem;
    font-weight: 600;
}

.stats-section {
    margin-top: 30px;
}

.chart-container {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
    height: 400px;
}

.chart-container canvas {
    max-height: 360px !important;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-card .stat-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
    opacity: 0.9;
}

.stat-card .stat-content h4 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-card .stat-content p {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
}

.reports-section {
    margin-top: 30px;
}

.report-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.report-card:hover {
    transform: translateY(-2px);
}

.report-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-bottom: none;
}

.report-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
}

.report-body {
    padding: 20px;
}


.position-stats {
    max-height: 300px;
    overflow-y: auto;
}

.position-item {
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.position-item:last-child {
    border-bottom: none;
}

.position-name {
    font-weight: 600;
    color: #333;
    font-size: 0.95rem;
}

.position-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.candidate-count {
    font-size: 0.8rem;
    color: #666;
}

.vote-count {
    font-size: 0.9rem;
    font-weight: 600;
    color: #667eea;
}

.progress-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 3px;
}

.top-performer {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    border-radius: 12px;
    padding: 20px;
    color: #333;
}

.performer-icon {
    font-size: 2.5rem;
    margin-right: 20px;
    color: #ff6b35;
}

.performer-name {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: #333;
}

.performer-position {
    font-size: 1rem;
    color: #666;
    margin-bottom: 10px;
}

.performer-votes {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.vote-count {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.vote-percentage {
    font-size: 0.9rem;
    color: #666;
}

.participation-stats {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.participation-stats .d-flex {
    margin-bottom: 8px;
}

.participation-stats .d-flex:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .results-grid {
        grid-template-columns: 1fr;
    }
    
    .winner-card {
        padding: 15px;
    }
    
    .winner-image img {
        width: 100px;
        height: 100px;
    }
    
    .stat-card {
        margin-bottom: 15px;
    }
    
    .report-card {
        margin-bottom: 20px;
    }
    
    .position-details {
        flex-direction: row;
        gap: 10px;
    }
    
    .top-performer {
        padding: 15px;
    }
    
    .performer-icon {
        font-size: 2rem;
        margin-right: 15px;
    }
    
    .performer-name {
        font-size: 1.1rem;
    }
}
</style>

<?php
