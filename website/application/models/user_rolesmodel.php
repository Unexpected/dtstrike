<?php 
Class User_rolesmodel extends Basemodel {
 
  var $user_id = -1; // int(11)
  var $role_name = ''; // varchar(10)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'user_roles';
  }
 
} 
 
