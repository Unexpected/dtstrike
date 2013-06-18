<?php 
Class User_rolesmodel extends BaseModel {
 
  var $user_id = -1; // int(11)
  var $role_name = ''; // varchar(10)
 
  function User_rolesmodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'user_roles';
  }
 
} 
 
