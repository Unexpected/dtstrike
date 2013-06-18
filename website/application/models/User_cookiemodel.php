<?php 
Class User_cookiemodel extends BaseModel {
 
  var $user_id = -1; // int(11)
  var $cookie = ''; // varchar(256)
  var $expires = ''; // datetime
  var $forgot = ''; // tinyint(1)
 
  function User_cookiemodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'user_cookie';
  }
 
} 
 
