<?php 
Class Matchupmodel extends Basemodel {
 
  var $matchup_id = NULL; // int(11)
  var $seed_id = NULL; // int(11)
  var $map_id = NULL; // int(11)
  var $max_turns = NULL; // int(11)
  var $worker_id = NULL; // int(11)
  var $error = NULL; // varchar(4000)
  var $matchup_timestamp = NULL; // datetime
  var $deleted = NULL; // tinyint(1)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'matchup';
  }
 
} 
 
