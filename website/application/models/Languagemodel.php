<?php 
Class Languagemodel extends BaseModel {
 
  var $language_id = -1; // int(11)
  var $name = ''; // varchar(64)
 
  function Languagemodel() {
    // Call the BaseModel constructor
    parent::BaseModel();
  }
 
  function getTableName() {
    return 'language';
  }
 
} 
 
