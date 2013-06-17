<?php 
Class Organizationmodel extends Model { 
 
  var $TABLE_NAME = 'organization'; 
 
  var $org_id = -1; // int(11)
  var $name = ''; // varchar(128)
 
  function Organizationmodel() {
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
 
