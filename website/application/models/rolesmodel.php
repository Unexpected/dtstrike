<?php 
Class Rolesmodel extends Basemodel {
 
  var $name = NULL; // varchar(10)
  var $descr = NULL; // varchar(150)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'roles';
  }
 
} 
 
