<?php
// Récupération des configs du site
define('BASEPATH', '.');
include '../application/config/database.php';
include '../application/config/contest.php';

$server_info = array(
    "db_host" 		=> $db[$active_group]['hostname'],
    "db_username" 	=> $db[$active_group]['username'],
    "db_password" 	=> $db[$active_group]['password'],
    "db_name" 		=> $db[$active_group]['database'],
    "mailer_address" => "donotsend",
    "aws_accesskey" => "",
    "aws_secretkey" => "",
    "submissions_open" => True,
    "repo_path" => $repo_dir,
    "uploads_path" => $upload_dir,
    "maps_path" => $map_dir,
    "replay_path" => $replay_dir,
    "api_create_key" => $api_create_key,
    "api_log" => $api_log,
    "game_result_errors" => $game_result_errors,
    "game_options" => array (
        "turns" => 1500,
        "loadtime" => 3000,
        "turntime" => 500,
        "viewradius2" => 77,
        "attackradius2" => 5,
        "spawnradius2" => 1,
        "location" => "{api_url}",
        "serial" => 2,
        "food_rate" => array(5,11),
        "food_turn" => array(19,37),
        "food_start" => array(75,175),
        "food_visible" => array(3,5),
        "food" => "symmetric",
        "attack" => "focus",
        "kill_points" => 2,
        "cutoff_turn" => 150,
        "cutoff_percent" => 0.85
    )
);

?>
