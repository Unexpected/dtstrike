<?php 
Class Workermodel extends BaseModel {
 
  var $worker_id = -1; // int(11)
  var $ip_address = ''; // varchar(15)
  var $api_key = ''; // varchar(256)
 
  function Workermodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'worker';
  }
 
} 
 
