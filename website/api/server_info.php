<?php

$server_info = array(
    "db_host" => "localhost",
    "db_username" => "dtstrike",
    "db_password" => "dtstrike",
    "db_name" => "dtstrike",
    "mailer_address" => "donotsend",
    "aws_accesskey" => "",
    "aws_secretkey" => "",
    "submissions_open" => True,
    "repo_path" => "{repo_dir}",
    "uploads_path" => "{upload_dir}",
    "maps_path" => "{map_dir}",
    "replay_path" => "{replay_dir}",
    "api_create_key" => "",
    "api_log" => "D:/dev/workspace/dtstrike/logs/php_api.log",
    "game_result_errors" => "D:/dev/workspace/dtstrike/logs/game_result_errors.log",
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
