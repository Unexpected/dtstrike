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
    "submissions_open" => True,
    "repo_path" => $config['repo_dir'],
    "uploads_path" => $config['upload_dir'],
    "maps_path" => $config['map_dir'],
    "replay_path" => $config['replay_dir'],
    "api_create_key" => $config['api_create_key'],
    "api_log" => $config['api_log'],
    "game_result_errors" => $config['game_result_errors']
);

?>