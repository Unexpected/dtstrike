<?php 
Class Mapmodel extends Model { 
 
  var $TABLE_NAME = 'map'; 
 
  var $map_id = -1; // int(11)
  var $filename = ''; // varchar(256)
  var $priority = -1; // int(11)
  var $players = -1; // int(11)
  var $max_turns = -1; // int(11)
  var $timestamp = ''; // datetime
 
  function Mapmodel() {
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
 
