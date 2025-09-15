<?php
require '../common/connect.php';

session_start();

if(isset($_SESSION['id']))
{
?>

<!-- Start Voting -->
<?php
if (isset($_POST['startElection'])) {
    $startElection = "UPDATE login SET voteStatus=1 WHERE id=?";
    $stmt = mysqli_prepare($conn, $startElection);
    if (!$stmt) {
        ErrorLogger::logDatabaseError($startElection, mysqli_error($conn), ['admin_id' => $_SESSION['id']]);
        $_SESSION['errorMessage']="Database error occurred while starting election.";
        header("Location:../admin/admin.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        ErrorLogger::logDatabaseError($startElection, mysqli_stmt_error($stmt), ['admin_id' => $_SESSION['id']]);
        $_SESSION['errorMessage']="Failed to start election.";
        header("Location:../admin/admin.php");
        exit();
    }
    ErrorLogger::logError("Election started by admin", ['admin_id' => $_SESSION['id']]);
    $_SESSION['successMessage']="The Election has been started.";
    header("Location:../admin/admin.php");
    exit();
}
?>

<!-- Stop Voting -->
<?php
if (isset($_POST['stopElection'])) {
    $stopElection = "UPDATE login SET voteStatus=2 WHERE id=?";
    $stmt = mysqli_prepare($conn, $stopElection);
    if (!$stmt) {
        ErrorLogger::logDatabaseError($stopElection, mysqli_error($conn), ['admin_id' => $_SESSION['id']]);
        $_SESSION['errorMessage']="Database error occurred while stopping election.";
        header("Location:../admin/admin.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        ErrorLogger::logDatabaseError($stopElection, mysqli_stmt_error($stmt), ['admin_id' => $_SESSION['id']]);
        $_SESSION['errorMessage']="Failed to stop election.";
        header("Location:../admin/admin.php");
        exit();
    }
    ErrorLogger::logError("Election stopped by admin", ['admin_id' => $_SESSION['id']]);
    $_SESSION['successMessage']="The Election has ended.";
    header("Location:../admin/admin.php");
    exit();
}
?>

<!-- Declare Voting Results -->
<?php
if (isset($_POST['declareResults'])) {
    $declareResults = "UPDATE login SET voteStatus=3 WHERE id=?";
    $stmt = mysqli_prepare($conn, $declareResults);
    if (!$stmt) {
        ErrorLogger::logDatabaseError($declareResults, mysqli_error($conn), ['admin_id' => $_SESSION['id']]);
        $_SESSION['errorMessage']="Database error occurred while declaring results.";
        header("Location:../admin/admin.php");
        exit();
    }
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        ErrorLogger::logDatabaseError($declareResults, mysqli_stmt_error($stmt), ['admin_id' => $_SESSION['id']]);
        $_SESSION['errorMessage']="Failed to declare results.";
        header("Location:../admin/admin.php");
        exit();
    }
    ErrorLogger::logError("Election results declared by admin", ['admin_id' => $_SESSION['id']]);
    $_SESSION['successMessage']="The Election Results have been declared.";
    header("Location:../admin/admin.php");
    exit();
}
?>

<!-- Restarting Elections -->
<?php
if (isset($_POST['newElection'])) {
    // Delete candidates
    $deleteCandidates = "DELETE FROM candidates";
    $resultCandidates = mysqli_query($conn, $deleteCandidates);
    // Delete campaigns
    $deleteCampaigns = "DELETE FROM campaign";
    $resultCampaigns = mysqli_query($conn, $deleteCampaigns);
    // Delete Votes
    $resetVoteStatus = "UPDATE login SET voteStatus=0";
    $resultReset = mysqli_query($conn, $resetVoteStatus);
    $_SESSION['successMessage']="The Election will be restarted.";
    header("Location:../admin/admin.php");
    exit();
}
?>

<!-- Reset with Dummy Data -->
<?php
if (isset($_POST['resetWithDummyData'])) {
    // Clear existing data
    $deleteCandidates = "DELETE FROM candidates";
    $deleteCampaigns = "DELETE FROM campaign";
    $deleteVotes = "DELETE FROM votes";
    $deleteUsers = "DELETE FROM login WHERE id != 'admin'";
    
    mysqli_query($conn, $deleteCandidates);
    mysqli_query($conn, $deleteCampaigns);
    mysqli_query($conn, $deleteVotes);
    mysqli_query($conn, $deleteUsers);
    
    // Reset admin vote status
    $resetAdminStatus = "UPDATE login SET voteStatus=0 WHERE id='admin'";
    mysqli_query($conn, $resetAdminStatus);
    
    // Insert dummy users
    $dummyUsers = [
        ['user001', 'John Smith', 'pass123', 0, 'Computer Department', '2024'],
        ['user002', 'Sarah Johnson', 'pass456', 0, 'IT Department', '2024'],
        ['user003', 'Michael Brown', 'pass789', 0, 'Mechanical Department', '2024'],
        ['user004', 'Emily Davis', 'pass101', 0, 'Electrical Department', '2024'],
        ['user005', 'David Wilson', 'pass202', 0, 'EXTC Department', '2024'],
        ['user006', 'Lisa Anderson', 'pass303', 0, 'Computer Department', '2024'],
        ['user007', 'Robert Taylor', 'pass404', 0, 'Mechanical Department', '2024'],
        ['user008', 'Jennifer Martinez', 'pass505', 0, 'IT Department', '2024'],
        ['user009', 'Christopher Lee', 'pass606', 0, 'Electrical Department', '2024'],
        ['user010', 'Amanda Garcia', 'pass707', 0, 'EXTC Department', '2024'],
        ['user011', 'Daniel Rodriguez', 'pass808', 0, 'Computer Department', '2024'],
        ['user012', 'Jessica White', 'pass909', 0, 'IT Department', '2024'],
        ['user013', 'Matthew Harris', 'pass010', 0, 'Mechanical Department', '2024'],
        ['user014', 'Ashley Clark', 'pass111', 0, 'Electrical Department', '2024'],
        ['user015', 'Andrew Lewis', 'pass222', 0, 'EXTC Department', '2024'],
        ['user016', 'Samantha Walker', 'pass333', 0, 'Computer Department', '2024'],
        ['user017', 'James Hall', 'pass444', 0, 'IT Department', '2024']
    ];
    
    foreach ($dummyUsers as $user) {
        $insertUser = "INSERT INTO login (id, uname, pw, voteStatus, department, year) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertUser);
        mysqli_stmt_bind_param($stmt, 'sssiss', $user[0], $user[1], $user[2], $user[3], $user[4], $user[5]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Insert dummy candidates
    $dummyCandidates = [
        ['user001', 'John Smith', '../assets/pfp/p1.png', 'Computer Department', 'General Secretary', 'I believe I can bring positive change to our student community through effective leadership and innovative ideas.', 8.750, 'Won 1st place in National Coding Competition 2023, Organized TechFest 2023 with 500+ participants', 'Computer Science Society (President), Photography Club (Member), Debate Society (Vice-President)', '../assets/certificate/c1.png', 'My vision is to create a more inclusive and engaging campus environment.', 'Accepted', '', 0, 15],
        ['user002', 'Sarah Johnson', '../assets/pfp/p2.png', 'IT Department', 'Joint Secretary', 'I am passionate about student welfare and have the organizational skills needed for this role.', 8.920, 'Best Student Award 2023, Organized Cultural Week 2023, Led Women in Tech initiative', 'Cultural Committee (Secretary), IT Society (Treasurer), Women in Tech (Founder)', '../assets/certificate/c2.png', 'I believe in the power of teamwork and effective communication.', 'Accepted', '', 0, 12],
        ['user003', 'Michael Brown', '../assets/pfp/p5.png', 'Mechanical Department', 'Sports Secretary', 'Sports have always been my passion, and I want to promote a healthy and active lifestyle among students.', 8.450, 'Captain of College Cricket Team, Won State Level Badminton Championship', 'Sports Committee (Captain), Cricket Club (President), Badminton Club (Vice-Captain)', '../assets/certificate/c3.png', 'My goal is to make sports accessible to everyone.', 'Accepted', '', 0, 18],
        ['user004', 'Emily Davis', '../assets/pfp/p6.png', 'Electrical Department', 'Cultural Secretary', 'I love organizing cultural events and believe in the power of arts to bring people together.', 8.680, 'Won College Dance Competition 2023, Organized Diwali Celebration 2023', 'Drama Society (President), Dance Club (Vice-President), Music Society (Member)', '../assets/certificate/c4.png', 'Culture is the soul of our college community.', 'Accepted', '', 0, 14],
        ['user005', 'David Wilson', '../assets/pfp/p7.png', 'EXTC Department', 'General Secretary', 'I have strong leadership qualities and a clear vision for improving student life.', 8.850, 'Student Council Member 2022-23, Organized Tech Symposium 2023', 'Robotics Club (President), EXTC Society (Vice-President)', '../assets/certificate/c5.png', 'I believe in transparent governance and student-centric policies.', 'Accepted', '', 0, 10],
        ['user007', 'Robert Taylor', '../assets/pfp/pg12.png', 'Mechanical Department', 'Sports Secretary', 'I have been actively involved in sports throughout my college life.', 8.350, 'Football Team Captain, Won Inter-University Volleyball Championship', 'Football Club (Captain), Volleyball Club (Vice-Captain)', '../assets/certificate/c1.png', 'Sports teach us discipline, teamwork, and perseverance.', 'Accepted', '', 0, 16],
        ['user008', 'Jennifer Martinez', '../assets/pfp/pg13.png', 'IT Department', 'Cultural Secretary', 'I am passionate about cultural activities and have experience in organizing various events.', 8.580, 'Won College Singing Competition, Organized Cultural Fest 2023', 'Art Society (President), Music Club (Vice-President)', '../assets/certificate/c2.png', 'Culture brings people together and creates lasting memories.', 'Accepted', '', 0, 13],
        ['user006', 'Lisa Anderson', '../assets/pfp/pg10.png', 'Computer Department', 'Joint Secretary', 'I am detail-oriented and have excellent communication skills.', 8.720, 'Academic Excellence Award 2023, Organized Hackathon 2023', 'Coding Club (Secretary), Quiz Society (President)', '../assets/certificate/c6.png', 'I am committed to creating an environment where every student can thrive.', 'Rejected', 'Incomplete application form. Please provide more details about your leadership experience.', 1, 0]
    ];
    
    foreach ($dummyCandidates as $candidate) {
        $insertCandidate = "INSERT INTO candidates (id, name, pfp, dept, post, reason, cgpa, achieve, club, cert, detail, status, comments, attempts, voteCount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertCandidate);
        mysqli_stmt_bind_param($stmt, 'ssssssssssssiis', $candidate[0], $candidate[1], $candidate[2], $candidate[3], $candidate[4], $candidate[5], $candidate[6], $candidate[7], $candidate[8], $candidate[9], $candidate[10], $candidate[11], $candidate[12], $candidate[13], $candidate[14]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Insert dummy campaigns
    $dummyCampaigns = [
        ['user001', 'Building Tomorrow Together', 'col-6 col-md-3', '../assets/campaign/john_campaign.jpg'],
        ['user002', 'Your Voice, Our Future', 'col-4 col-md-2', '../assets/campaign/sarah_campaign.jpg'],
        ['user003', 'Champions On and Off Field', 'col-4 col-md-2', '../assets/campaign/michael_campaign.jpg'],
        ['user004', 'Celebrating Diversity', 'col-6 col-md-3', '../assets/campaign/emily_campaign.jpg'],
        ['user005', 'Innovation in Action', 'col-4 col-md-2', '../assets/campaign/david_campaign.jpg'],
        ['user007', 'Sports for All', 'col-4 col-md-2', '../assets/campaign/robert_campaign.jpg']
    ];
    
    foreach ($dummyCampaigns as $campaign) {
        $insertCampaign = "INSERT INTO campaign (id, motto, size, campaign) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertCampaign);
        mysqli_stmt_bind_param($stmt, 'ssss', $campaign[0], $campaign[1], $campaign[2], $campaign[3]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    // Mark some users as voted
    $votedUsers = ['user002', 'user003', 'user004', 'user005', 'user006', 'user007', 'user008', 'user009', 'user010', 'user011'];
    foreach ($votedUsers as $userId) {
        $updateVoteStatus = "UPDATE login SET voteStatus=1 WHERE id=?";
        $stmt = mysqli_prepare($conn, $updateVoteStatus);
        mysqli_stmt_bind_param($stmt, 's', $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    ErrorLogger::logError("Database reset with dummy data by admin", ['admin_id' => $_SESSION['id']]);
    $_SESSION['successMessage'] = "Database has been reset with dummy data successfully!";
    header("Location:../admin/admin.php");
    exit();
}
?>



<?php
if (isset($_POST['submitVote'])) {
    $updateVoteFlag = "UPDATE login SET voteStatus=1 WHERE id=?";
    $stmt = mysqli_prepare($conn, $updateVoteFlag);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);

    // Add Vote count and track vote history
    foreach ($_POST as $key => $value) {
        if ($key != 'submitVote') {
            // Update candidate vote count
            $query = "UPDATE candidates SET voteCount = voteCount + 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $value);
            $result = mysqli_stmt_execute($stmt);
            
            if ($result) {
                // Get candidate details for vote history
                $candidate_query = "SELECT name, post FROM candidates WHERE id = ?";
                $candidate_stmt = mysqli_prepare($conn, $candidate_query);
                mysqli_stmt_bind_param($candidate_stmt, 's', $value);
                mysqli_stmt_execute($candidate_stmt);
                $candidate_result = mysqli_stmt_get_result($candidate_stmt);
                $candidate_data = mysqli_fetch_assoc($candidate_result);
                
                // Insert vote history
                $vote_history_query = "INSERT INTO votes (voter_id, voter_name, candidate_id, candidate_name, position) VALUES (?, ?, ?, ?, ?)";
                $vote_history_stmt = mysqli_prepare($conn, $vote_history_query);
                mysqli_stmt_bind_param($vote_history_stmt, 'sssss', $_SESSION['id'], $_SESSION['uname'], $value, $candidate_data['name'], $candidate_data['post']);
                mysqli_stmt_execute($vote_history_stmt);
                mysqli_stmt_close($vote_history_stmt);
                mysqli_stmt_close($candidate_stmt);
            }
            mysqli_stmt_close($stmt);
        }
    }

    
    header("Location:../users/successVote.php");
    exit();
}
?>

<?php
}
else{
    header("Location:../login.php");
    exit();
}
?>