<?php
// Setup Dummy Data for FCRIT Voting System
// This file can be used to populate the database with sample data for testing

require 'common/connect.php';
include 'common/errorLogger.php';

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['id'] != 'admin') {
    header("Location:login.php");
    exit();
}

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

ErrorLogger::logError("Database setup with dummy data completed", ['admin_id' => $_SESSION['id']]);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Setup Complete</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>
    <div class='container mt-5'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-body text-center'>
                        <i class='fas fa-check-circle text-success fa-4x mb-3'></i>
                        <h2 class='text-success'>Setup Complete!</h2>
                        <p class='text-muted'>Dummy data has been successfully loaded into the database.</p>
                        <div class='mt-4'>
                            <a href='admin/admin.php' class='btn btn-primary me-2'>Go to Admin Panel</a>
                            <a href='landing.php' class='btn btn-outline-primary'>View Landing Page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
?>

