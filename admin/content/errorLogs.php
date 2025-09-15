<div class="error-logs-content">
    <div class="error-logs-header">
        <h1 class="section-title">Error Logs</h1>
        <p class="text-muted">Monitor and manage system error logs</p>
    </div>
        
    <div class="action-buttons">
        <button class="btn btn-primary" onclick="downloadLogs()">
            <i class="fas fa-download me-2"></i>Download Logs
        </button>
        <button class="btn btn-warning" onclick="clearLogs()">
            <i class="fas fa-trash me-2"></i>Clear Logs
        </button>
    </div>

    <div class="stats-grid">
        <?php
        $logFile = '../logs/error.log';
        $logSize = file_exists($logFile) ? filesize($logFile) : 0;
        $logSizeFormatted = $logSize > 1024 ? round($logSize / 1024, 2) . ' KB' : $logSize . ' bytes';
        
        $logContent = file_exists($logFile) ? file_get_contents($logFile) : '';
        $logLines = $logContent ? explode("\n", $logContent) : [];
        $errorCount = count(array_filter($logLines, function($line) {
            return strpos($line, '[ERROR]') !== false || strpos($line, '[DATABASE]') !== false;
        }));
        $warningCount = count(array_filter($logLines, function($line) {
            return strpos($line, '[WARNING]') !== false;
        }));
        $infoCount = count(array_filter($logLines, function($line) {
            return strpos($line, '[INFO]') !== false;
        }));
        ?>
        
        <div class="stat-card stat-danger">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $errorCount ?></h3>
                <p class="stat-label">Errors</p>
            </div>
        </div>
        
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $warningCount ?></h3>
                <p class="stat-label">Warnings</p>
            </div>
        </div>
        
        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $infoCount ?></h3>
                <p class="stat-label">Info Messages</p>
            </div>
        </div>
        
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $logSizeFormatted ?></h3>
                <p class="stat-label">Log File Size</p>
            </div>
        </div>
    </div>

    <div class="logs-table">
        <div class="card">
            <div class="card-header">
                <h3 class="section-title mb-0">
                    <i class="fas fa-list me-2"></i>Recent Log Entries
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>Level</th>
                                <th>Message</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($logContent)) {
                                $logEntries = array_reverse(array_slice($logLines, -50)); // Show last 50 entries
                                foreach ($logEntries as $entry) {
                                    if (empty(trim($entry))) continue;
                                    
                                    $parts = explode('] ', $entry, 3);
                                    if (count($parts) >= 3) {
                                        $timestamp = substr($parts[0], 1);
                                        $level = substr($parts[1], 1);
                                        $message = $parts[2];
                                        
                                        $levelClass = 'info';
                                        if (strpos($level, 'ERROR') !== false || strpos($level, 'DATABASE') !== false) {
                                            $levelClass = 'danger';
                                        } elseif (strpos($level, 'WARNING') !== false) {
                                            $levelClass = 'warning';
                                        }
                                        
                                        echo "<tr>
                                                <td>$timestamp</td>
                                                <td><span class='badge badge-$levelClass'>$level</span></td>
                                                <td>" . htmlspecialchars(substr($message, 0, 100)) . "...</td>
                                                <td>N/A</td>
                                              </tr>";
                                    }
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center text-muted py-4'>No log entries found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadLogs() {
    window.location.href = 'downloadLogs.php';
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all error logs? This action cannot be undone.')) {
        fetch('clearLogs.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error clearing logs: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error clearing logs');
        });
    }
}
</script>

<style>
.error-logs-content {
    padding: 0;
}

.error-logs-header {
    margin-bottom: 30px;
}

.error-logs-header h1 {
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

.stat-danger .stat-icon { color: #dc3545; }
.stat-warning .stat-icon { color: #ffc107; }
.stat-info .stat-icon { color: #17a2b8; }
.stat-primary .stat-icon { color: #667eea; }

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

.logs-table {
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

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
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
