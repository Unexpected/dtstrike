<?php 
Class Countrymodel extends Basemodel {
 
  var $country_code = NULL; // varchar(8)
  var $name = NULL; // varchar(64)
  var $flag_filename = NULL; // varchar(16)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'country';
  }
 
} 
 
