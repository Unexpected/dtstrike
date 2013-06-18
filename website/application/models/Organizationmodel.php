<?php 
Class Organizationmodel extends BaseModel {
 
  var $org_id = -1; // int(11)
  var $name = ''; // varchar(128)
 
  function Organizationmodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'organization';
  }
 
} 
 
