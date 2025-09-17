-- FCRIT Voting System - Database with Dummy Data
-- Generated: 2025
-- Description: Complete database schema with realistic dummy data for testing

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `id` varchar(8) NOT NULL,
  `motto` text NOT NULL,
  `size` varchar(48) NOT NULL DEFAULT 'col-4 col-md-2',
  `campaign` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` varchar(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pfp` varchar(255) NOT NULL,
  `dept` enum('Computer Department','IT Department','Mechanical Department','Electrical Department','EXTC Department') NOT NULL,
  `post` enum('General Secretary','Joint Secretary','Sports Secretary','Cultural Secretary') NOT NULL,
  `reason` text NOT NULL,
  `cgpa` decimal(5,3) NOT NULL,
  `achieve` text NOT NULL,
  `club` text NOT NULL,
  `cert` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `comments` text NOT NULL,
  `attempts` int(1) NOT NULL,
  `voteCount` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `sr` int(5) NOT NULL,
  `id` varchar(8) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `voteStatus` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`sr`, `id`, `uname`, `pw`, `voteStatus`) VALUES
(1, 'admin', 'Administrator', '$2y$10$BbRf7SdYsLeEsuMq0epRIeTWbMGBANMhlKxMXM5LQSQLD7GbHe2cW', 0),
(2, 'user001', 'John Smith', '$2y$10$SqGYH8d7sGimLtQ6ca8eIeuOBLer.1EtzRwGDY8mGEwfh8YoP66si', 0),
(3, 'user002', 'Sarah Johnson', '$2y$10$UG/kNcWomcHF6XA2eCydMuH4/WPZC.W.5mXgAETkpTs3oCM8IpiLu', 0),
(4, 'user003', 'Michael Brown', '$2y$10$li5uiFID0t5f87vI.q3u8uEp38M47Vo4SpHJaA5EJB0H9EtIEVCD2', 0),
(5, 'user004', 'Emily Davis', '$2y$10$jeL6z3FLmFYW.WaDO.XPOejrtTb4B/JZ79kfjRf9BJW7PCY7591OK', 0),
(6, 'user005', 'David Wilson', '$2y$10$vSiGTb8lmKF6r0kKb2p7yeM2oSHKo5EC1xgHR.gsymNcLyqp6GyqC', 0),
(7, 'user006', 'Lisa Anderson', '$2y$10$z8yyGjjQ1Y7fpIB1CRP/yOIbu9bwg0wutPMdVB6FSU9jmjx2Vw5yy', 0),
(8, 'user007', 'Robert Taylor', '$2y$10$Vj36/tmukcEcBpEA4XGJh.8AtXvT3Mdd5AQ0YUeL6cozwm/xXrZ76', 0),
(9, 'user008', 'Jennifer Martinez', '$2y$10$5V.JfLM1fR1geO.yDZay5.zgW0XTRLHuopaoMArX25M4h7ESl82H2', 0),
(10, 'user009', 'Christopher Lee', '$2y$10$IG7g.Zv9qTt6dxLUEzkFbuHqw7MYIcLD3YXlxn8C5gzHNpv7mvcUO', 0),
(11, 'user010', 'Amanda Garcia', '$2y$10$fDMCeLuKqjJUtrcgpbCg6OL0Oxzarfj7QMWK/ms9A..YkjsfpafIq', 0),
(12, 'user011', 'Daniel Rodriguez', '$2y$10$QE648RnX1Wa9ZkdpQ0cPfebu0joa4EbNAyY9CsDLP8Ai1x37lyX2q', 0),
(13, 'user012', 'Jessica White', '$2y$10$PrDGpogkvywOKWzV9ylWken./GJTUeSdmBLWCIQA2.dISxt2NOxje', 0),
(14, 'user013', 'Matthew Harris', '$2y$10$mA7vimhewQHIEcNU3BJpXO9R//MbSeoa5aw8W0YYPyGOsg8RXbWCu', 0),
(15, 'user014', 'Ashley Clark', '$2y$10$.nkL5rSM6A00m42pO2KHye8.4jO9.KtYt81I/YpeYZ0kTGArVm/Ne', 0),
(16, 'user015', 'Andrew Lewis', '$2y$10$DIJ1zEhCJeuGdaPGGkLnEudFB.SOVBoenznU4QvlO4UPWN36sIR72', 0),
(17, 'user016', 'Samantha Walker', '$2y$10$XVrI.wUj4fAuTCcPWCXlB.y/fMNi7Gdj9L6WOsLOqoxi4GiaddTnK', 0),
(18, 'user017', 'James Hall', '$2y$10$wxbQi2YlAPSO13CjGMxaoO.nF8AFvHGZC2HI3oG49qqUtxp0.kze2', 0);

-- --------------------------------------------------------

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `pfp`, `dept`, `post`, `reason`, `cgpa`, `achieve`, `club`, `cert`, `detail`, `status`, `comments`, `attempts`, `voteCount`) VALUES
('user001', 'John Smith', '../assets/pfp/p1.png', 'Computer Department', 'General Secretary', 'I believe I can bring positive change to our student community through effective leadership and innovative ideas. My experience in organizing events and managing teams makes me a suitable candidate for this position.', 8.750, 'Won 1st place in National Coding Competition 2023, Organized TechFest 2023 with 500+ participants, Led the Computer Science Society for 2 years, Published research paper in IEEE conference', 'Computer Science Society (President), Photography Club (Member), Debate Society (Vice-President), Sports Committee (Member)', '../assets/certificate/c1.png', 'My vision is to create a more inclusive and engaging campus environment. I want to bridge the gap between students and administration, ensuring every voice is heard. My leadership style focuses on collaboration, transparency, and student welfare.', 'Accepted', '', 0, 0),
('user002', 'Sarah Johnson', '../assets/pfp/p2.png', 'IT Department', 'Joint Secretary', 'I am passionate about student welfare and have the organizational skills needed for this role. I want to work closely with the General Secretary to implement student-friendly policies and improve campus life.', 8.920, 'Best Student Award 2023, Organized Cultural Week 2023, Led Women in Tech initiative, Won inter-college debate competition', 'Cultural Committee (Secretary), IT Society (Treasurer), Women in Tech (Founder), Literary Society (Member)', '../assets/certificate/c2.png', 'I believe in the power of teamwork and effective communication. As Joint Secretary, I will ensure smooth coordination between different departments and work towards creating a more vibrant campus culture.', 'Accepted', '', 0, 0),
('user003', 'Michael Brown', '../assets/pfp/p5.png', 'Mechanical Department', 'Sports Secretary', 'Sports have always been my passion, and I want to promote a healthy and active lifestyle among students. I have experience in organizing sports events and managing teams.', 8.450, 'Captain of College Cricket Team, Won State Level Badminton Championship, Organized Inter-Department Sports Meet, Best Athlete Award 2023', 'Sports Committee (Captain), Cricket Club (President), Badminton Club (Vice-Captain), Fitness Club (Member)', '../assets/certificate/c3.png', 'My goal is to make sports accessible to everyone and create opportunities for students to excel in their chosen sports. I will work towards improving sports infrastructure and organizing regular tournaments.', 'Accepted', '', 0, 0),
('user004', 'Emily Davis', '../assets/pfp/p6.png', 'Electrical Department', 'Cultural Secretary', 'I love organizing cultural events and believe in the power of arts to bring people together. I want to create a platform where students can showcase their talents and celebrate diversity.', 8.680, 'Won College Dance Competition 2023, Organized Diwali Celebration 2023, Led Drama Society for 2 years, Best Cultural Event Organizer Award', 'Drama Society (President), Dance Club (Vice-President), Music Society (Member), Cultural Committee (Secretary)', '../assets/certificate/c4.png', 'Culture is the soul of our college community. I want to organize diverse cultural events that celebrate our rich heritage while embracing modern trends. My focus will be on inclusivity and student participation.', 'Accepted', '', 0, 0),
('user005', 'David Wilson', '../assets/pfp/p7.png', 'EXTC Department', 'General Secretary', 'I have strong leadership qualities and a clear vision for improving student life. My technical background combined with management skills makes me an ideal candidate for this position.', 8.850, 'Student Council Member 2022-23, Organized Tech Symposium 2023, Led Robotics Club, Won National Project Competition', 'Robotics Club (President), EXTC Society (Vice-President), Innovation Cell (Member), Student Council (Representative)', '../assets/certificate/c5.png', 'I believe in transparent governance and student-centric policies. My approach focuses on digital transformation of student services and creating more opportunities for skill development and career growth.', 'Pending', '', 0, 0),
('user006', 'Lisa Anderson', '../assets/pfp/pg10.png', 'Computer Department', 'Joint Secretary', 'I am detail-oriented and have excellent communication skills. I want to support the General Secretary in implementing policies that benefit all students and improve campus facilities.', 8.720, 'Academic Excellence Award 2023, Organized Hackathon 2023, Led Coding Bootcamp, Won Inter-College Quiz Competition', 'Coding Club (Secretary), Quiz Society (President), Academic Committee (Member), Women in Computing (Treasurer)', '../assets/certificate/c6.png', 'I am committed to creating an environment where every student can thrive. My focus will be on academic support, career guidance, and ensuring equal opportunities for all students.', 'Rejected', 'Incomplete application form. Please provide more details about your leadership experience.', 1, 0),
('user007', 'Robert Taylor', '../assets/pfp/pg12.png', 'Mechanical Department', 'Sports Secretary', 'I have been actively involved in sports throughout my college life and want to take this passion to the next level by organizing better sports events and facilities.', 8.350, 'Football Team Captain, Won Inter-University Volleyball Championship, Organized Sports Day 2023, Best Sportsman Award', 'Football Club (Captain), Volleyball Club (Vice-Captain), Sports Committee (Member), Fitness Club (President)', '../assets/certificate/c1.png', 'Sports teach us discipline, teamwork, and perseverance. I want to create a sports culture where every student feels motivated to participate and excel in their chosen sport.', 'Accepted', '', 0, 0),
('user008', 'Jennifer Martinez', '../assets/pfp/pg13.png', 'IT Department', 'Cultural Secretary', 'I am passionate about cultural activities and have experience in organizing various events. I want to create a vibrant cultural atmosphere in our college.', 8.580, 'Won College Singing Competition, Organized Cultural Fest 2023, Led Art Society, Best Cultural Performer Award', 'Art Society (President), Music Club (Vice-President), Cultural Committee (Member), Photography Club (Secretary)', '../assets/certificate/c2.png', 'Culture brings people together and creates lasting memories. I want to organize events that showcase our diverse talents and create a sense of belonging among all students.', 'Pending', '', 0, 0);

-- --------------------------------------------------------

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` (`id`, `motto`, `size`, `campaign`) VALUES
('user001', 'Building Tomorrow Together', 'col-6 col-md-3', '../assets/campaign/john_campaign.jpg'),
('user002', 'Your Voice, Our Future', 'col-4 col-md-2', '../assets/campaign/sarah_campaign.jpg'),
('user003', 'Champions On and Off Field', 'col-4 col-md-2', '../assets/campaign/michael_campaign.jpg'),
('user004', 'Celebrating Diversity', 'col-6 col-md-3', '../assets/campaign/emily_campaign.jpg'),
('user005', 'Innovation in Action', 'col-4 col-md-2', '../assets/campaign/david_campaign.jpg'),
('user007', 'Sports for All', 'col-4 col-md-2', '../assets/campaign/robert_campaign.jpg');

-- --------------------------------------------------------

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`sr`);

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `sr` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- Additional Test Data for Different Scenarios
--

-- Add some users who have already voted
UPDATE `login` SET `voteStatus` = 1 WHERE `id` IN ('user002', 'user003', 'user004');

-- Add some vote counts to candidates
UPDATE `candidates` SET `voteCount` = 45 WHERE `id` = 'user001';
UPDATE `candidates` SET `voteCount` = 38 WHERE `id` = 'user002';
UPDATE `candidates` SET `voteCount` = 52 WHERE `id` = 'user003';
UPDATE `candidates` SET `voteCount` = 41 WHERE `id` = 'user004';
UPDATE `candidates` SET `voteCount` = 29 WHERE `id` = 'user007';

-- Add some rejected candidates with comments
UPDATE `candidates` SET `status` = 'Rejected', `comments` = 'Please provide more details about your previous leadership roles and specific achievements.', `attempts` = 2 WHERE `id` = 'user008';

-- Add some pending applications
UPDATE `candidates` SET `status` = 'Pending' WHERE `id` = 'user005';

-- --------------------------------------------------------

--
-- Sample Election Status Scenarios
--

-- Scenario 1: Application Phase (voteStatus = 0)
-- UPDATE `login` SET `voteStatus` = 0 WHERE `id` = 'admin';

-- Scenario 2: Voting Phase (voteStatus = 1) 
-- UPDATE `login` SET `voteStatus` = 1 WHERE `id` = 'admin';

-- Scenario 3: Election Ended (voteStatus = 2)
-- UPDATE `login` SET `voteStatus` = 2 WHERE `id` = 'admin';

-- Scenario 4: Results Declared (voteStatus = 3)
-- UPDATE `login` SET `voteStatus` = 3 WHERE `id` = 'admin';

-- --------------------------------------------------------

--
-- Notes for Testing
--

/*
TESTING SCENARIOS:

1. ADMIN LOGIN:
   - Username: admin
   - Password: admin

2. REGULAR USER LOGINS:
   - user001/pass123 (John Smith - General Secretary candidate)
   - user002/pass456 (Sarah Johnson - Joint Secretary candidate)
   - user003/pass789 (Michael Brown - Sports Secretary candidate)
   - user004/pass101 (Emily Davis - Cultural Secretary candidate)
   - user005/pass202 (David Wilson - General Secretary candidate, Pending)
   - user006/pass303 (Lisa Anderson - Joint Secretary candidate, Rejected)
   - user007/pass404 (Robert Taylor - Sports Secretary candidate)
   - user008/pass505 (Jennifer Martinez - Cultural Secretary candidate, Pending)

3. CANDIDATE STATUSES:
   - Accepted: user001, user002, user003, user004, user007
   - Pending: user005, user008
   - Rejected: user006

4. VOTING STATUS:
   - Already Voted: user002, user003, user004
   - Not Voted: All others

5. VOTE COUNTS:
   - user001 (John): 45 votes
   - user002 (Sarah): 38 votes
   - user003 (Michael): 52 votes
   - user004 (Emily): 41 votes
   - user007 (Robert): 29 votes

6. CAMPAIGNS:
   - Each accepted candidate has a campaign with motto and image

7. TESTING WORKFLOW:
   - Login as admin to manage applications
   - Login as regular users to apply as candidates
   - Test voting functionality
   - Test different election phases
   - Test results declaration
*/
