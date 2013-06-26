<?php 
Class Countrymodel extends Basemodel {
 
  var $country_code = ''; // varchar(8)
  var $name = ''; // varchar(64)
  var $flag_filename = ''; // varchar(16)
 
  function __construct() {
    // Call the BaseModel constructor
    parent::__construct();
  }
 
  function getTableName() {
    return 'country';
  }
 
} 
 
