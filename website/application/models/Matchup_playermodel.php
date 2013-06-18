<?php 
Class Matchup_playermodel extends BaseModel {
 
  var $matchup_id = -1; // int(11)
  var $user_id = -1; // int(11)
  var $submission_id = -1; // int(11)
  var $player_id = -1; // int(11)
  var $mu = ''; // float
  var $sigma = ''; // float
  var $deleted = ''; // tinyint(1)
 
  function Matchup_playermodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'matchup_player';
  }
 
} 
 
