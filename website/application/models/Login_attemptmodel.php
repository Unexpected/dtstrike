<?php 
Class Login_attemptmodel extends BaseModel {
 
  var $timestamp = ''; // datetime
  var $username = ''; // varchar(64)
  var $password = ''; // varchar(40)
  var $naive_ip = ''; // varchar(18)
  var $real_ip = ''; // varchar(18)
 
  function Login_attemptmodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'login_attempt';
  }
 
} 
 
