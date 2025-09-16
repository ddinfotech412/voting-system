-- Create votes table for FCRIT Voting System
-- This table stores the vote history for tracking who voted for whom

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` varchar(8) NOT NULL,
  `voter_name` varchar(255) NOT NULL,
  `candidate_id` varchar(8) NOT NULL,
  `candidate_name` varchar(255) NOT NULL,
  `position` varchar(50) NOT NULL,
  `vote_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `voter_id` (`voter_id`),
  KEY `candidate_id` (`candidate_id`),
  KEY `position` (`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
