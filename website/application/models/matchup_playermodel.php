<?php 
Class Matchup_playermodel extends Basemodel {
 
  var $matchup_id = NULL; // int(11)
  var $user_id = NULL; // int(11)
  var $submission_id = NULL; // int(11)
  var $player_id = NULL; // int(11)
  var $mu = NULL; // float
  var $sigma = NULL; // float
  var $deleted = NULL; // tinyint(1)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'matchup_player';
  }
 
} 
 
