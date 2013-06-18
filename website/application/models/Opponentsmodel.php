<?php 
Class Opponentsmodel extends Basemodel {
 
  var $game_id = -1; // int(11)
  var $user_id = -1; // int(11)
  var $opponent_id = -1; // int(11)
  var $timestamp = ''; // datetime
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'opponents';
  }
 
} 
 
