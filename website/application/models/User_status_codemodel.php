<?php 
Class User_status_codemodel extends Model { 
 
  var $status_id = -1; // int(11) 
  var $name = ''; // varchar(256) 
 
  function User_status_codemodel() {
    // Call the Model constructor
    parent::Model();
  }
 
} 
 
