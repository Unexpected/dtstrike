<?php 
Class Organizationmodel extends Basemodel {
 
  var $org_id = -1; // int(11)
  var $name = ''; // varchar(128)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'organization';
  }
 
} 
 
