SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- Create players and roles
UPDATE `user` SET `shutdown_date` = timestampadd(hour, 72, current_timestamp), `max_game_id` = NULL;
INSERT INTO `user` (`user_id`, `username`, `email`, `org_id`, `country_code`, `created`, `shutdown_date`) 
VALUES
	(1, 'schmittse', 'sebastien.schmitt@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(2, 'bellangerf', 'francis.bellanger@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(3, 'test1', 'test1@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(4, 'test2', 'test2@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(5, 'test3', 'test3@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(6, 'test4', 'test4@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(7, 'test5', 'test5@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(8, 'test6', 'test6@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(9, 'test7', 'test7@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(10, 'test8', 'test8@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(11, 'test9', 'test9@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(12, 'test10', 'test10@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(13, 'test11', 'test11@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(14, 'test12', 'test12@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(15, 'test13', 'test13@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(16, 'test14', 'test14@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(17, 'test15', 'test15@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(18, 'test16', 'test16@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(19, 'test17', 'test17@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(20, 'test18', 'test18@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(21, 'test19', 'test19@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(22, 'test20', 'test20@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(23, 'test21', 'test21@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(24, 'test22', 'test22@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(25, 'test23', 'test23@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(26, 'test24', 'test24@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(27, 'test25', 'test25@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(28, 'test26', 'test26@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp))
;
INSERT INTO `user_roles` (`user_id`, `role_name`) VALUES
	(1, 'ADMIN'),
	(2, 'ADMIN'),
	(3, 'USER'),
	(4, 'USER'),
	(5, 'USER'),
	(6, 'USER'),
	(7, 'USER'),
	(8, 'USER'),
	(9, 'USER'),
	(10, 'USER'),
	(11, 'USER'),
	(12, 'USER'),
	(13, 'USER'),
	(14, 'USER'),
	(15, 'USER'),
	(16, 'USER'),
	(17, 'USER'),
	(18, 'USER'),
	(19, 'USER'),
	(20, 'USER'),
	(21, 'USER'),
	(22, 'USER'),
	(23, 'USER'),
	(24, 'USER'),
	(25, 'USER'),
	(26, 'USER'),
	(27, 'USER'),
	(28, 'USER')
;

INSERT INTO `map` (`map_id`, `filename`, `priority`, `players`, `max_turns`, `timestamp`) VALUES
	(1, 'map1.txt', 1, 2, 1000, current_timestamp()),
	(2, 'map2.txt', 1, 4, 1000, current_timestamp());

-- Create 1 submission for each player
INSERT INTO `submission` (`submission_id`, `user_id`, `version`, `status`, `timestamp`, `language_id`) VALUES
	(1, 1, 1, 20, current_timestamp(), 0),
	(2, 2, 1, 20, current_timestamp(), 0),
	(3, 3, 1, 20, current_timestamp(), 0),
	(4, 4, 1, 20, current_timestamp(), 0),
	(5, 5, 1, 20, current_timestamp(), 0),
	(6, 6, 1, 20, current_timestamp(), 0),
	(7, 7, 1, 20, current_timestamp(), 0),
	(8, 8, 1, 20, current_timestamp(), 0),
	(9, 9, 1, 20, current_timestamp(), 0),
	(10, 10, 1, 20, current_timestamp(), 0),
	(11, 11, 1, 20, current_timestamp(), 0),
	(12, 12, 1, 20, current_timestamp(), 0),
	(13, 13, 1, 20, current_timestamp(), 0),
	(14, 14, 1, 20, current_timestamp(), 0),
	(15, 15, 1, 20, current_timestamp(), 0),
	(16, 16, 1, 20, current_timestamp(), 0),
	(17, 17, 1, 20, current_timestamp(), 0),
	(18, 18, 1, 20, current_timestamp(), 0),
	(19, 19, 1, 20, current_timestamp(), 0),
	(20, 20, 1, 20, current_timestamp(), 0),
	(21, 21, 1, 20, current_timestamp(), 0),
	(22, 22, 1, 20, current_timestamp(), 0),
	(23, 23, 1, 20, current_timestamp(), 0),
	(24, 24, 1, 20, current_timestamp(), 0),
	(25, 25, 1, 20, current_timestamp(), 0),
	(26, 26, 1, 20, current_timestamp(), 0),
	(27, 27, 1, 20, current_timestamp(), 0),
	(28, 28, 1, 20, current_timestamp(), 0)
;



