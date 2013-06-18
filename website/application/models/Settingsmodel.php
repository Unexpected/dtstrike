<?php 
Class Settingsmodel extends BaseModel {
 
  var $name = ''; // varchar(20)
  var $number = -1; // int(11)
  var $string = ''; // varchar(255)
 
  function Settingsmodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'settings';
  }
 
} 
 
