SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- Create players and roles
INSERT INTO `user` (`user_id`, `username`, `email`, `org_id`, `country_code`, `created`, `shutdown_date`) 
VALUES
	(1, 'schmittse', 'sebastien.schmitt@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(2, 'bellangerf', 'francis.bellanger@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(3, 'test1', 'test1@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp)),
	(4, 'test2', 'test2@cgi.com', 1, 'FR', current_timestamp(), timestampadd(hour, 72, current_timestamp));
INSERT INTO `user_roles` (`user_id`, `role_name`) VALUES
	(1, 'ADMIN'),
	(2, 'ADMIN'),
	(3, 'USER'),
	(4, 'USER');

INSERT INTO `map` (`map_id`, `filename`, `priority`, `players`, `max_turns`, `timestamp`) VALUES
	(1, 'map1.txt', 1, 2, 1000, current_timestamp()),
	(2, 'map2.txt', 1, 4, 1000, current_timestamp());

-- Create 1 submission for each player
INSERT INTO `submission` (`submission_id`, `user_id`, `version`, `status`, `timestamp`, `language_id`) VALUES
	(1, 1, 1, 20, current_timestamp(), 0),
	(2, 2, 1, 20, current_timestamp(), 0),
	(3, 3, 1, 20, current_timestamp(), 0),
	(4, 4, 1, 20, current_timestamp(), 0);