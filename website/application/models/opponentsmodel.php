<?php 
Class Opponentsmodel extends Basemodel {
 
  var $game_id = NULL; // int(11)
  var $user_id = NULL; // int(11)
  var $opponent_id = NULL; // int(11)
  var $timestamp = NULL; // datetime
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'opponents';
  }
 
} 
 
