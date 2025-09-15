<?php
include_once '../../common/connect.php';
include_once '../../common/errorLogger.php';

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['id'] != 'admin') {
    header("Location:../../login.php");
    exit();
}

// Get admin info
$admin_query = "SELECT * FROM login WHERE id='admin'";
$admin_result = mysqli_query($conn, $admin_query);
$admin = mysqli_fetch_assoc($admin_result);

// Get total users
$totalUsers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login WHERE id != 'admin'"));

// Get voted users
$votedUsers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login WHERE voteStatus = 1 AND id != 'admin'"));

// Get total candidates
$totalCandidates = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM candidates WHERE status='Accepted'"));

// Get total votes cast
$totalVotes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(voteCount) as total FROM candidates WHERE status='Accepted'"))['total'] ?? 0;

// Calculate voter turnout
$voterTurnout = $totalUsers > 0 ? round(($votedUsers / $totalUsers) * 100, 1) : 0;

// Get position-wise data
$positions = ['General Secretary', 'Joint Secretary', 'Sports Secretary', 'Cultural Secretary'];
$positionData = [];

foreach ($positions as $position) {
    $query = "SELECT c.name, c.voteCount, c.dept, c.pfp 
              FROM candidates c 
              WHERE c.status='Accepted' AND c.post='$position' 
              ORDER BY c.voteCount DESC";
    $result = mysqli_query($conn, $query);
    $candidates = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $candidates[] = $row;
    }
    $positionData[$position] = $candidates;
}

// Get department-wise voting data
$deptQuery = "SELECT l.department, 
              COUNT(l.id) as total_users,
              SUM(CASE WHEN l.voteStatus = 1 THEN 1 ELSE 0 END) as voted_users
              FROM login l 
              WHERE l.id != 'admin' 
              GROUP BY l.department";
$deptResult = mysqli_query($conn, $deptQuery);
$deptData = [];
while ($row = mysqli_fetch_assoc($deptResult)) {
    $deptData[] = $row;
}

// Get top performers
$topPerformersQuery = "SELECT name, voteCount, post, dept 
                      FROM candidates 
                      WHERE status='Accepted' 
                      ORDER BY voteCount DESC 
                      LIMIT 5";
$topPerformers = mysqli_query($conn, $topPerformersQuery);
$topPerformersData = [];
while ($row = mysqli_fetch_assoc($topPerformers)) {
    $topPerformersData[] = $row;
}

// Get vote distribution over time (simulated - you can add timestamp to votes table later)
$voteDistribution = [];
foreach ($positionData as $position => $candidates) {
    $totalPositionVotes = array_sum(array_column($candidates, 'voteCount'));
    $voteDistribution[$position] = $totalPositionVotes;
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="section-title">Strategic Analytics</h1>
            <p class="text-muted">Comprehensive insights and data visualization</p>
        </div>
        <div>
            <span class="badge bg-primary fs-6">Election Status: 
                <?php
                $statusText = '';
                $statusClass = '';
                switch($admin['voteStatus']) {
                    case 0: $statusText = 'Not Started'; $statusClass = 'text-secondary'; break;
                    case 1: $statusText = 'In Progress'; $statusClass = 'text-success'; break;
                    case 2: $statusText = 'Ended'; $statusClass = 'text-warning'; break;
                    case 3: $statusText = 'Results Declared'; $statusClass = 'text-info'; break;
                }
                ?>
                <span class="<?= $statusClass ?>"><?= $statusText ?></span>
            </span>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Voters</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalUsers ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Votes Cast</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalVotes ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-vote-yea fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Voter Turnout</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $voterTurnout ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Candidates</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalCandidates ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Vote Distribution by Position -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Vote Distribution by Position</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="voteDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Voter Participation -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Voter Participation</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="voterParticipationChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Voted (<?= $votedUsers ?>)
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-gray-300"></i> Not Voted (<?= $totalUsers - $votedUsers ?>)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department-wise Analysis -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Department-wise Participation</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Performers</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="topPerformersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Position-wise Detailed Analysis -->
    <div class="row">
        <?php foreach ($positionData as $position => $candidates): ?>
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?= $position ?> - Vote Breakdown</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="positionChart<?= str_replace(' ', '', $position) ?>"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-xs {
    font-size: 0.7rem;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.chart-area {
    position: relative;
    height: 10rem;
}
.chart-pie {
    position: relative;
    height: 15rem;
}
</style>

<script>
// Wait for Chart.js to load
document.addEventListener('DOMContentLoaded', function() {
    // Vote Distribution by Position Chart
    const voteDistributionCtx = document.getElementById('voteDistributionChart').getContext('2d');
    new Chart(voteDistributionCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($voteDistribution)) ?>,
            datasets: [{
                label: 'Total Votes',
                data: <?= json_encode(array_values($voteDistribution)) ?>,
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(246, 194, 62, 0.8)'
                ],
                borderColor: [
                    'rgba(78, 115, 223, 1)',
                    'rgba(28, 200, 138, 1)',
                    'rgba(54, 185, 204, 1)',
                    'rgba(246, 194, 62, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Voter Participation Chart
    const voterParticipationCtx = document.getElementById('voterParticipationChart').getContext('2d');
    new Chart(voterParticipationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Voted', 'Not Voted'],
            datasets: [{
                data: [<?= $votedUsers ?>, <?= $totalUsers - $votedUsers ?>],
                backgroundColor: [
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(220, 220, 220, 0.8)'
                ],
                borderColor: [
                    'rgba(28, 200, 138, 1)',
                    'rgba(220, 220, 220, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Department-wise Chart
    const departmentCtx = document.getElementById('departmentChart').getContext('2d');
    new Chart(departmentCtx, {
        type: 'horizontalBar',
        data: {
            labels: <?= json_encode(array_column($deptData, 'department')) ?>,
            datasets: [{
                label: 'Total Users',
                data: <?= json_encode(array_column($deptData, 'total_users')) ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }, {
                label: 'Voted Users',
                data: <?= json_encode(array_column($deptData, 'voted_users')) ?>,
                backgroundColor: 'rgba(28, 200, 138, 0.8)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Top Performers Chart
    const topPerformersCtx = document.getElementById('topPerformersChart').getContext('2d');
    new Chart(topPerformersCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($topPerformersData, 'name')) ?>,
            datasets: [{
                label: 'Votes',
                data: <?= json_encode(array_column($topPerformersData, 'voteCount')) ?>,
                backgroundColor: 'rgba(246, 194, 62, 0.8)',
                borderColor: 'rgba(246, 194, 62, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Position-wise Charts
    <?php foreach ($positionData as $position => $candidates): ?>
    const positionCtx<?= str_replace(' ', '', $position) ?> = document.getElementById('positionChart<?= str_replace(' ', '', $position) ?>').getContext('2d');
    new Chart(positionCtx<?= str_replace(' ', '', $position) ?>, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($candidates, 'name')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($candidates, 'voteCount')) ?>,
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(54, 185, 204, 0.8)',
                    'rgba(246, 194, 62, 0.8)'
                ],
                borderColor: [
                    'rgba(78, 115, 223, 1)',
                    'rgba(28, 200, 138, 1)',
                    'rgba(54, 185, 204, 1)',
                    'rgba(246, 194, 62, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    <?php endforeach; ?>
});
</script>