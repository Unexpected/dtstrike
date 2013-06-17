<?php 
Class Languagemodel extends Model { 
 
  var $language_id = -1; // int(11) 
  var $name = ''; // varchar(64) 
 
  function Languagemodel() {
    // Call the Model constructor
    parent::Model();
  }
 
} 
 
