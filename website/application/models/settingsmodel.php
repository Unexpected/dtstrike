<?php 
Class Settingsmodel extends Basemodel {
 
  var $name = NULL; // varchar(20)
  var $number = NULL; // int(11)
  var $string = NULL; // varchar(255)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'settings';
  }
 
} 
 
