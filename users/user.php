<?php
require '../common/connect.php';

session_start();

require '../common/links.php';

if(isset($_SESSION['id']))
{
include '../common/navbar.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FCRIT Voting System | <?=$_SESSION['uname']?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 20px;
            overflow: hidden;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            text-align: center;
        }
        
        .welcome-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 40px;
        }
        
        .role-section {
            padding: 40px;
            text-align: center;
        }
        
        .role-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }
        
        .role-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        
        .role-btn {
            padding: 20px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            min-width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .role-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .role-btn.candidate {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
        }
        
        .role-btn.voter {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            border: none;
        }
        
        .role-btn.disabled {
            background: #bdc3c7;
            color: #7f8c8d;
            cursor: not-allowed;
            transform: none;
        }
        
        .role-btn.disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        .status-message {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            padding: 20px;
            border-radius: 12px;
            font-size: 1.3rem;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        
        .campaigns-section {
            padding: 40px;
            background: #f8f9fa;
        }
        
        .campaigns-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .campaigns-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .campaign-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .campaign-item:hover {
            transform: translateY(-5px);
        }
        
        .campaign-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .campaign-image[src*="default-campaign"] {
            object-fit: contain;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .campaign-image:not([src]), 
        .campaign-image[src=""], 
        .campaign-image[src*="undefined"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        
        .campaign-image:not([src])::before, 
        .campaign-image[src=""]::before, 
        .campaign-image[src*="undefined"]::before {
            content: "📢";
            font-size: 3rem;
        }
        
        .campaign-motto {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            width: 90%;
        }
        
        .results-section {
            padding: 40px;
            background: white;
        }
        
        .results-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .winner-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        
        .winner-card:hover {
            transform: translateY(-5px);
        }
        
        .winner-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin: 0 auto 20px;
            display: block;
        }
        
        .winner-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .winner-position {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 15px;
        }
        
        .vote-count {
            font-size: 1.3rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
        }
        
        .no-campaigns {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-campaigns i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #bdc3c7;
        }
        
        .no-campaigns h4 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }
            
            .role-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .role-btn {
                width: 100%;
                max-width: 300px;
            }
            
            .campaigns-grid {
                grid-template-columns: 1fr;
            }
            
            .results-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
  <body>
    <?php include '../common/message.php'; ?>
    
    <div class="main-container">
        <div class="welcome-section">
            <h1 class="welcome-title">Welcome to FCRIT's Online Voting System</h1>
            <p class="welcome-subtitle">Cast your vote and make your voice heard</p>
        </div>

        <?php
        $query = "SELECT voteStatus FROM `login` WHERE id='admin'";
        $result = mysqli_query($conn, $query);
        while ($admin = mysqli_fetch_assoc($result)) {
            // Apply for candidate
            if($admin['voteStatus']==0){
        ?>
        <div class="role-section">
            <h2 class="role-title">What is your role? <i class="fas fa-user-tag"></i></h2>
            <div class="role-buttons">
                <a href="nominee.php" class="role-btn candidate">
                    <i class="fas fa-user-tie"></i>
                    I am a Candidate
                </a>
                <a class="role-btn voter disabled" role="button" aria-disabled="true">
                    <i class="fas fa-vote-yea"></i>
                    I am a Voter
                </a>
            </div>
            <div class="status-message">
                <i class="fas fa-info-circle me-2"></i>
                Election has not started yet. Candidates can apply now!
            </div>
        </div>
        <?php
            }
            else if($admin['voteStatus']==1){
                // Election has started
        ?>
        <div class="role-section">
            <h2 class="role-title">What is your role? <i class="fas fa-user-tag"></i></h2>
            <div class="role-buttons">
                <a class="role-btn candidate disabled" role="button" aria-disabled="true">
                    <i class="fas fa-user-tie"></i>
                    I am a Candidate
                </a>
                <a href="voter.php" class="role-btn voter">
                    <i class="fas fa-vote-yea"></i>
                    I am a Voter
                </a>
            </div>
            <div class="status-message">
                <i class="fas fa-vote-yea me-2"></i>
                Election is in progress! Cast your vote now!
            </div>
        </div>
        <?php
            }
            else if($admin['voteStatus']==2){
                // Election has stopped
        ?>
        <div class="role-section">
            <h2 class="role-title">What is your role? <i class="fas fa-user-tag"></i></h2>
            <div class="role-buttons">
                <a class="role-btn candidate disabled" role="button" aria-disabled="true">
                    <i class="fas fa-user-tie"></i>
                    I am a Candidate
                </a>
                <a class="role-btn voter disabled" role="button" aria-disabled="true">
                    <i class="fas fa-vote-yea"></i>
                    I am a Voter
                </a>
            </div>
            <div class="status-message">
                <i class="fas fa-clock me-2"></i>
                Election has ended. Results will be declared soon!
            </div>
        </div>
        <?php
            }
        ?>
        
        <?php
        // Show campaigns only when election is in progress or has ended (but not when results are declared)
        if($admin['voteStatus']==1 || $admin['voteStatus']==2){
        ?>
        <div class="campaigns-section">
            <h2 class="campaigns-title">
                <i class="fas fa-bullhorn me-2"></i>Campaigns
            </h2>
            <div class="campaigns-grid">
            <?php
            $query = "SELECT * FROM campaign";
            $query_run = mysqli_query($conn, $query);

            if (mysqli_num_rows($query_run) > 0) {
                foreach ($query_run as $campaign) {
                    // Check if campaign image exists, use default if not
                    $campaignImage = !empty($campaign['campaign']) && file_exists('../' . $campaign['campaign']) 
                        ? htmlspecialchars($campaign['campaign']) 
                        : '../assets/default-campaign.jpg';
            ?>
                <div class="campaign-item">
                    <img src="<?=$campaignImage?>" alt="Campaign" class="campaign-image" 
                         onerror="this.src='../assets/default-campaign.jpg'">
                    <div class="campaign-motto"><?=$campaign['motto']?></div>
                </div>
            <?php
                }
            } else {
            ?>
                <div class="no-campaigns">
                    <i class="fas fa-bullhorn"></i>
                    <h4>No Campaigns Available</h4>
                    <p>Campaign materials will be displayed here when available.</p>
                </div>
            <?php
            }
            ?>
            </div>
        </div>
        <?php
        }
        
        // Results are declared
        if($admin['voteStatus']==3){
        ?>

        <div class="results-section">
            <h2 class="results-title">
                <i class="fas fa-trophy me-2"></i>Election Results
            </h2>
            
            <?php
            $query = "SELECT post, id, name, voteCount, pfp FROM candidates
                      WHERE (post, voteCount) IN (
                          SELECT post, MAX(voteCount) AS max_votes FROM candidates
                          GROUP BY post
                      )         
                        ORDER BY CASE post
                            WHEN 'General Secretary' THEN 1
                            WHEN 'Joint Secretary' THEN 2
                            WHEN 'Sports Secretary' THEN 3
                            WHEN 'Cultural Secretary' THEN 4
                            ELSE 5 END";
            
            $result = mysqli_query($conn, $query);
            ?>
            
            <div class="results-grid">
            <?php
            if ($result) {
                // Fetch and display results
                while ($row = mysqli_fetch_assoc($result)) {
                    $post = $row['post'];
                    $candidateId = $row['id'];
                    $candidateImage = $row['pfp'];
                    $candidateName = $row['name'];
                    $voteCount = $row['voteCount'];
            ?>
                <div class="winner-card">
                    <img src="<?=$candidateImage?>" alt="<?=$candidateName?>" class="winner-image">
                    <h3 class="winner-name"><?=$candidateName?></h3>
                    <h5 class="winner-position"><?=$post?></h5>
                    <div class="vote-count">
                        <i class="fas fa-vote-yea me-2"></i>
                        <?=$voteCount?> votes
                    </div>
                </div>
            <?php } ?>
            </div>
            <?php
            } else {
                echo "<div class='alert alert-danger' role='alert'>
                        Error in fetching results: " . mysqli_error($conn) . "
                      </div>";
            }
            ?>
        </div>

        <?php
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

<?php
}
else{
    header("Location:../login.php");
    exit();
}
?>