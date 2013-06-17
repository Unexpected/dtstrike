<?php 
Class Settingsmodel extends Model { 
 
  var $TABLE_NAME = 'settings'; 
 
  var $name = ''; // varchar(20)
  var $number = -1; // int(11)
  var $string = ''; // varchar(255)
 
  function Settingsmodel() {
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
 
