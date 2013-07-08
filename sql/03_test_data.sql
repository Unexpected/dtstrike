SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- Seb
INSERT INTO `user` (`user_id`, `username`, `email`, `org_id`, `bio`, `country_code`, `created`, `shutdown_date`, `max_game_id`) VALUES
(1, 'schmittse', 'sebastien.schmitt@cgi.com', 1, NULL, 'FR', '2013-06-26 08:17:26', NULL, NULL);
INSERT INTO `user_roles` (`user_id`, `role_name`) VALUES
(1, 'ADMIN');

-- Francis
INSERT INTO `user` (`user_id`, `username`, `email`, `org_id`, `bio`, `country_code`, `created`, `shutdown_date`, `max_game_id`) VALUES
(2, 'bellangerf', 'francis.bellanger@cgi.com', 1, NULL, 'FR', '2013-06-26 08:17:26', NULL, NULL);
INSERT INTO `user_roles` (`user_id`, `role_name`) VALUES
(2, 'ADMIN');


INSERT INTO `map` (`map_id`, `filename`, `priority`, `players`, `max_turns`, `timestamp`) VALUES
(1, 'map1.txt', 1, 2, 1000, '2013-06-26 08:26:55'),
(2, 'map2.txt', 1, 4, 1000, '2013-06-26 08:26:55');
