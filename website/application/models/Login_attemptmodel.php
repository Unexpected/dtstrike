<?php 
Class Login_attemptmodel extends Basemodel {
 
  var $timestamp = ''; // datetime
  var $username = ''; // varchar(64)
  var $password = ''; // varchar(40)
  var $naive_ip = ''; // varchar(18)
  var $real_ip = ''; // varchar(18)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'login_attempt';
  }
 
} 
 
