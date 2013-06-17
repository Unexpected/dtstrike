<?php 
Class Matchupmodel extends Model { 
 
  var $TABLE_NAME = 'matchup'; 
 
  var $matchup_id = -1; // int(11)
  var $seed_id = -1; // int(11)
  var $map_id = -1; // int(11)
  var $max_turns = -1; // int(11)
  var $worker_id = -1; // int(11)
  var $error = ''; // varchar(4000)
  var $matchup_timestamp = ''; // datetime
  var $deleted = ''; // tinyint(1)
 
  function Matchupmodel() {
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
 
