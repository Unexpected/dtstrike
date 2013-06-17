<?php 
Class Usermodel extends Model { 
 
  var $TABLE_NAME = 'user'; 
 
  var $user_id = -1; // int(11)
  var $username = ''; // varchar(128)
  var $password = ''; // varchar(256)
  var $reset = ''; // varchar(256)
  var $email = ''; // varchar(256)
  var $status_id = -1; // int(11)
  var $activation_code = ''; // varchar(256)
  var $org_id = -1; // int(11)
  var $bio = ''; // varchar(4096)
  var $country_id = -1; // int(11)
  var $created = ''; // datetime
  var $activated = ''; // tinyint(1)
  var $admin = ''; // tinyint(1)
  var $shutdown_date = ''; // datetime
  var $max_game_id = -1; // int(11)
 
  function Usermodel() {
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
 
