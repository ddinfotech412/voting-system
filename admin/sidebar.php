<div class="sidebar-content">
    <div class="sidebar-header">
        <h4 class="text-center mb-4" style="color: #333; font-weight: 600;">
            <i class="fas fa-tachometer-alt me-2"></i>Admin Panel
        </h4>
    </div>
    
    <div class="list-group list-group-flush" id="list-tab" role="tablist">
        <a class="sidebar-item active" id="list-dashboard-list" data-bs-toggle="list" href="#dashboard" role="tab" aria-controls="dashboard">
            <i class="fas fa-home me-3"></i>Dashboard
        </a>
        <a class="sidebar-item" id="list-electionStatus-list" data-bs-toggle="list" href="#electionStatus" role="tab" aria-controls="electionStatus">
            <i class="fas fa-vote-yea me-3"></i>Election Status
        </a>
        <a class="sidebar-item" id="list-candDetail-list" data-bs-toggle="list" href="#candDetail" role="tab" aria-controls="candDetail">
            <i class="fas fa-users me-3"></i>Candidate Details
        </a>
        <a class="sidebar-item" id="list-applications-list" data-bs-toggle="list" href="#applications" role="tab" aria-controls="applications">
            <i class="fas fa-file-alt me-3"></i>Nominee Applications
        </a>
        <a class="sidebar-item" id="list-voteHistory-list" data-bs-toggle="list" href="#voteHistory" role="tab" aria-controls="voteHistory">
            <i class="fas fa-history me-3"></i>Vote History
        </a>
        <a class="sidebar-item" id="list-analytics-list" data-bs-toggle="list" href="#analytics" role="tab" aria-controls="analytics">
            <i class="fas fa-chart-bar me-3"></i>Analytics
        </a>
        <a class="sidebar-item" id="list-userManagement-list" data-bs-toggle="list" href="#userManagement" role="tab" aria-controls="userManagement">
            <i class="fas fa-user-cog me-3"></i>User Management
        </a>
        <a class="sidebar-item" id="list-errorLogs-list" data-bs-toggle="list" href="#errorLogs" role="tab" aria-controls="errorLogs">
            <i class="fas fa-exclamation-triangle me-3"></i>Error Logs
        </a>
        <a class="sidebar-item" id="list-settings-list" data-bs-toggle="list" href="#settings" role="tab" aria-controls="settings">
            <i class="fas fa-cog me-3"></i>Settings
        </a>
    </div>
</div>

<style>
.sidebar-content {
    padding: 20px 0;
    height: 100%;
}

.sidebar-header {
    padding: 0 20px 20px;
    border-bottom: 1px solid #eee;
}

.sidebar-menu {
    padding: 20px 0;
}

.sidebar-item {
    display: block;
    padding: 15px 20px;
    color: #666;
    text-decoration: none;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.sidebar-item:hover {
    background: #f8f9fa;
    color: #333;
    text-decoration: none;
}

.sidebar-item.active {
    background: #667eea;
    color: white;
    border-left-color: #4c63d2;
}

.sidebar-item i {
    width: 20px;
    text-align: center;
}

@media (max-width: 768px) {
    .sidebar-content {
        padding: 10px 0;
    }
    
    .sidebar-item {
        padding: 12px 15px;
        font-size: 0.9rem;
    }
}
</style>