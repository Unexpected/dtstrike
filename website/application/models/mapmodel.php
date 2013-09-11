<?php 
Class Mapmodel extends Basemodel {
 
  var $map_id = NULL; // int(11)
  var $filename = NULL; // varchar(256)
  var $priority = NULL; // int(11)
  var $players = NULL; // int(11)
  var $max_turns = NULL; // int(11)
  var $timestamp = NULL; // datetime
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'map';
  }
 
} 
 
