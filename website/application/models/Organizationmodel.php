<?php 
Class Organizationmodel extends Model { 
 
  var $org_id = -1; // int(11) 
  var $name = ''; // varchar(128) 
 
  function Organizationmodel() {
    // Call the Model constructor
    parent::Model();
  }
 
} 
 
