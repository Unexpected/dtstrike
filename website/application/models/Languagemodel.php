<?php 
Class Languagemodel extends Basemodel {
 
  var $language_id = -1; // int(11)
  var $name = ''; // varchar(64)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'language';
  }
 
} 
 
