<?php 
Class Organizationmodel extends Basemodel {
 
  var $org_id = NULL; // int(11)
  var $name = NULL; // varchar(128)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'organization';
  }
 
} 
 
