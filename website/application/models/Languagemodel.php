<?php 
Class Languagemodel extends Model { 
 
  var $TABLE_NAME = 'language'; 
 
  var $language_id = -1; // int(11)
  var $name = ''; // varchar(64)
 
  function Languagemodel() {
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
 
