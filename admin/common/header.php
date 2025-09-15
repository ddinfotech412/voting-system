<?php
require '../../common/connect.php';

session_start();

if ($_SESSION['id'] != 'admin') {
    header("Location:../login.php");
    exit();
}

require '../../common/links.php';
include '../../common/navbar.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'Admin Panel' ?> - FCRIT Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }
        
        .navbar {
            background: #667eea !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .btn-light {
            background: white;
            border: 1px solid #ddd;
            color: #333;
            font-weight: 500;
        }
        
        .btn-light:hover {
            background: #f8f9fa;
            color: #333;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .table {
            margin-bottom: 0;
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
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
        }
        
        .btn-primary {
            background: #667eea;
            border-color: #667eea;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
            border-color: #5a6fd8;
        }
        
        .alert {
            border: none;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .section-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
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
        .stat-warning .stat-icon { color: #ffc107; }
        .stat-danger .stat-icon { color: #dc3545; }
        .stat-info .stat-icon { color: #17a2b8; }
        
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
        
        .content-wrapper {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            min-height: 500px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
  </head>
  <body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-content">
                <div class="sidebar-header">
                    <h4 class="text-center mb-4" style="color: #333; font-weight: 600;">
                        <i class="fas fa-tachometer-alt me-2"></i>Admin Panel
                    </h4>
                </div>
                
                <div class="list-group list-group-flush" id="list-tab" role="tablist">
                    <a class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="fas fa-home me-3"></i>Dashboard
                    </a>
                    <a class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'electionStatus.php' ? 'active' : '' ?>" href="electionStatus.php">
                        <i class="fas fa-vote-yea me-3"></i>Election Status
                    </a>
                    <a class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'candidates.php' ? 'active' : '' ?>" href="candidates.php">
                        <i class="fas fa-users me-3"></i>Candidate Details
                    </a>
                    <a class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'applications.php' ? 'active' : '' ?>" href="applications.php">
                        <i class="fas fa-file-alt me-3"></i>Nominee Applications
                    </a>
                    <a class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'voteHistory.php' ? 'active' : '' ?>" href="voteHistory.php">
                        <i class="fas fa-history me-3"></i>Vote History
                    </a>
                    <a class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'active' : '' ?>" href="analytics.php">
                        <i class="fas fa-chart-bar me-3"></i>Analytics
                    </a>
                    <a class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'userManagement.php' ? 'active' : '' ?>" href="userManagement.php">
                        <i class="fas fa-user-cog me-3"></i>User Management
                    </a>
                    <a class="sidebar-item <?= basename($_SERVER['PHP_SELF']) == 'errorLogs.php' ? 'active' : '' ?>" href="errorLogs.php">
                        <i class="fas fa-exclamation-triangle me-3"></i>Error Logs
                    </a>
                </div>
            </div>
        </div>
        
        <div class="main-content">
            <?php include '../../common/message.php'; ?>
            
            <div class="content-wrapper">
