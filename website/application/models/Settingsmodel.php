<?php 
Class Settingsmodel extends Model { 
 
  var $name = ''; // varchar(20) 
  var $number = -1; // int(11) 
  var $string = ''; // varchar(255) 
 
  function Settingsmodel() {
    // Call the Model constructor
    parent::Model();
  }
 
} 
 
