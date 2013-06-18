<?php 
Class Settingsmodel extends Basemodel {
 
  var $name = ''; // varchar(20)
  var $number = -1; // int(11)
  var $string = ''; // varchar(255)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'settings';
  }
 
} 
 
