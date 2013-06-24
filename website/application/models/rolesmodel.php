<?php 
Class Rolesmodel extends Basemodel {
 
  var $name = ''; // varchar(10)
  var $descr = ''; // varchar(150)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'roles';
  }
 
} 
 
