<?php 
Class Rolesmodel extends BaseModel {
 
  var $name = ''; // varchar(10)
  var $descr = ''; // varchar(150)
 
  function Rolesmodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'roles';
  }
 
} 
 
