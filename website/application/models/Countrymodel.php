<?php 
Class Countrymodel extends Model { 
 
  var $TABLE_NAME = 'country'; 
 
  var $country_id = -1; // int(11)
  var $country_code = ''; // varchar(8)
  var $name = ''; // varchar(64)
  var $flag_filename = ''; // varchar(16)
 
  function Countrymodel() {
    // Call the Model constructor
    parent::Model();
  }
 
  function getAll() {
    $query = $this->db->query($this->TABLE_NAME);
    if ($query->num_rows()  {
      return $query->result();
    }
  }
 
} 
 
