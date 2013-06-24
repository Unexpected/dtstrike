<?php 
Class User_cookiemodel extends Basemodel {
 
  var $user_id = -1; // int(11)
  var $cookie = ''; // varchar(256)
  var $expires = ''; // datetime
  var $forgot = ''; // tinyint(1)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'user_cookie';
  }
 
} 
 
