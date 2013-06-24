<?php 
Class Matchup_playermodel extends Basemodel {
 
  var $matchup_id = -1; // int(11)
  var $user_id = -1; // int(11)
  var $submission_id = -1; // int(11)
  var $player_id = -1; // int(11)
  var $mu = ''; // float
  var $sigma = ''; // float
  var $deleted = ''; // tinyint(1)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'matchup_player';
  }
 
} 
 
