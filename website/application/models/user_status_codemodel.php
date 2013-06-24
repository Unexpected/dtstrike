<?php 
Class User_status_codemodel extends Basemodel {
 
  var $status_id = -1; // int(11)
  var $name = ''; // varchar(256)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'user_status_code';
  }
 
} 
 
