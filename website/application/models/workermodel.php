<?php 
Class Workermodel extends Basemodel {
 
  var $worker_id = NULL; // int(11)
  var $ip_address = NULL; // varchar(15)
  var $api_key = NULL; // varchar(256)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'worker';
  }
 
} 
 
