<?php 
Class Submissionmodel extends Model { 
 
  var $submission_id = -1; // int(11) 
  var $user_id = -1; // int(11) 
  var $version = -1; // int(11) 
  var $status = -1; // int(11) 
  var $timestamp = ''; // datetime 
  var $comments = ''; // varchar(4096) 
  var $errors = ''; // varchar(4096) 
  var $language_id = -1; // int(11) 
  var $last_game_timestamp = ''; // datetime 
  var $latest = ''; // tinyint(1) 
  var $rank = -1; // int(11) 
  var $rank_change = -1; // int(11) 
  var $mu = ''; // float 
  var $mu_change = ''; // float 
  var $sigma = ''; // float 
  var $sigma_change = ''; // float 
  var $worker_id = -1; // int(11) 
  var $min_game_id = -1; // int(11) 
  var $max_game_id = -1; // int(11) 
  var $game_count = -1; // int(11) 
 
  function Submissionmodel() {
    // Call the Model constructor
    parent::Model();
  }
 
} 
 
