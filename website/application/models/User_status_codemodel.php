<?php 
Class User_status_codemodel extends BaseModel {
 
  var $status_id = -1; // int(11)
  var $name = ''; // varchar(256)
 
  function User_status_codemodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'user_status_code';
  }
 
} 
 
