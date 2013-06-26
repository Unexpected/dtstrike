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
    "repo_path" => $repo_dir,
    "uploads_path" => $upload_dir,
    "maps_path" => $map_dir,
    "replay_path" => $replay_dir,
    "api_create_key" => $api_create_key,
    "api_log" => $api_log,
    "game_result_errors" => $game_result_errors
);

?>
