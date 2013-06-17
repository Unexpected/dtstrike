<?php 
Class User_cookiemodel extends Model { 
 
  var $TABLE_NAME = 'user_cookie'; 
 
  var $user_id = -1; // int(11)
  var $cookie = ''; // varchar(256)
  var $expires = ''; // datetime
  var $forgot = ''; // tinyint(1)
 
  function User_cookiemodel() {
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
 
