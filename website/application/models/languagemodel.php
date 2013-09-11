<?php 
Class Languagemodel extends Basemodel {
 
  var $language_id = NULL; // int(11)
  var $name = NULL; // varchar(64)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'language';
  }
 
} 
 
