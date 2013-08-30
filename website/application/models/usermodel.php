<?php 
Class Usermodel extends Basemodel {

	var $user_id = -1; // int(11)
	var $username = ''; // varchar(128)
	//var $password = null; // varchar(256)
	//var $reset = null; // varchar(256)
	var $email = ''; // varchar(256)
	//var $activation_code = ''; // varchar(256)
	var $org_id = -1; // int(11)
	var $bio = ''; // varchar(4096)
	var $country_code = ''; // varchar(8)
	var $created = ''; // datetime
	//var $activated = 0; // tinyint(1)
	var $shutdown_date = ''; // datetime
	var $max_game_id = -1; // int(11)

	function __construct() {
		// Call the BaseModel constructor
		parent::__construct();
	}

	function getTableName() {
		return 'user';
	}
	
	function getUserData($user_id) {
		if (!isset($user_id)) {
			return NULL;
		}
		
		$this->db->select('user_id, username, email, organization.name as "org_name", country.name as "country_name", created', false);
		$this->db->from('user');
		$this->db->join('organization', 'organization.org_id = user.org_id');
		$this->db->join('country', 'country.country_code = user.country_code');
		$this->db->order_by("username", "asc");
		$this->db->where('user_id', $user_id);
		
		$query = $this->db->get();
		if ($query->num_rows())  {
			$users = $query->result();
		} else {
			$users = array();
		}
		
		if (count($users) < 1) {
			return NULL;
		}
		return $users[0];
	}

}

