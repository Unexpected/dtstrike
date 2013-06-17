<?php 
Class Gamemodel extends Model { 
 
  var $TABLE_NAME = 'game'; 
 
  var $game_id = -1; // int(11)
  var $seed_id = -1; // int(11)
  var $map_id = -1; // int(11)
  var $turns = -1; // int(11)
  var $game_length = -1; // int(11)
  var $cutoff = ''; // varchar(255)
  var $winning_turn = -1; // int(11)
  var $ranking_turn = -1; // int(11)
  var $timestamp = ''; // datetime
  var $worker_id = ''; // int(11)
  var $replay_path = ''; // varchar(255)
 
  function Gamemodel() {
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
 
