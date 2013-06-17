<?php 
Class Opponentsmodel extends Model { 
 
  var $TABLE_NAME = 'opponents'; 
 
  var $game_id = -1; // int(11)
  var $user_id = -1; // int(11)
  var $opponent_id = -1; // int(11)
  var $timestamp = ''; // datetime
 
  function Opponentsmodel() {
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
 
