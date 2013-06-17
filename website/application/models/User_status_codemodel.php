<?php 
Class User_status_codemodel extends Model { 
 
  var $TABLE_NAME = 'user_status_code'; 
 
  var $status_id = -1; // int(11)
  var $name = ''; // varchar(256)
 
  function User_status_codemodel() {
    // Call the Model constructor
    parent::Model();
  }
 
  function getAll() {
    $query = $this->db->query($this->TABLE_NAME);
    if ($query->num_rows())  {
      return $query->result();
    }
  }
 
} 
 
