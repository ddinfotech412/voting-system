<?php
require 'connect.php';
?>

<nav class="navbar sticky-top">
  <div class="container-fluid">
    <div class="navbar-brand">
      <h1 class="mb-0">Hello <?=$_SESSION['uname']?></h1>
      <small class="text-muted">Welcome to FCRIT Voting System</small>
    </div>

    <div class="navbar-actions">
      <a href="../users/user.php" class="btn btn-light <?= ($_SESSION['id'] == 'admin') ? 'd-none' : '' ?>">
        <i class="fas fa-home me-2"></i>Home
      </a>
      <a href="../common/logout.php" class="btn btn-outline-light">
        <i class="fas fa-sign-out-alt me-2"></i>Logout
      </a>
    </div>
  </div>
</nav>

<style>
.navbar {
  background: #667eea !important;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 15px 0;
}

.navbar-brand h1 {
  color: white !important;
  font-weight: 600;
  font-size: 1.2rem;
  margin: 0;
}

.navbar-brand small {
  color: rgba(255, 255, 255, 0.8) !important;
  font-size: 0.8rem;
}

.navbar-actions {
  display: flex;
  gap: 10px;
  align-items: center;
}

.btn-light {
  background: white;
  border: 1px solid #ddd;
  color: #333;
  font-weight: 500;
  border-radius: 8px;
  padding: 8px 16px;
}

.btn-light:hover {
  background: #f8f9fa;
  color: #333;
}

.btn-outline-light {
  border: 1px solid rgba(255, 255, 255, 0.5);
  color: white;
  font-weight: 500;
  border-radius: 8px;
  padding: 8px 16px;
}

.btn-outline-light:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border-color: white;
}

@media (max-width: 768px) {
  .navbar-brand h1 {
    font-size: 1rem;
  }
  
  .navbar-brand small {
    font-size: 0.7rem;
  }
  
  .btn {
    padding: 6px 12px;
    font-size: 0.9rem;
  }
}
</style>