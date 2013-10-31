<?php 
Class Usermodel extends Basemodel {

	var $user_id = NULL; // int(11)
	var $username = NULL; // varchar(128)
	var $password = null; // varchar(256)
	//var $reset = null; // varchar(256)
	var $email = NULL; // varchar(256)
	var $activation_code = NULL; // varchar(256)
	var $org_id = NULL; // int(11)
	var $bio = NULL; // varchar(4096)
	var $country_code = NULL; // varchar(8)
	var $created = NULL; // datetime
	var $activated = 0; // tinyint(1)
	var $shutdown_date = NULL; // datetime
	var $max_game_id = NULL; // int(11)

	function __construct() {
		// Call the BaseModel constructor
		parent::__construct();
	}

	function getTableName() {
		return 'user';
	}
	
	/**
	 * Get user data
	 * 
	 * @param string $user_id
	 * @return NULL|Usermodel
	 */
	function getUserData($user_id) {
		if (!isset($user_id)) {
			return NULL;
		}
		
		$this->db->select('user_id, username, email, organization.name as "org_name", country.name as "country_name", created, bio', false);
		$this->db->from($this->getTableName());
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
	
	/**
	 * Check is username/password match an activated user
	 * 
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	function check_credentials($username, $password) {
		
		// Create query
		$this->db->select('user_id, username, password', false);
		$this->db->from($this->getTableName());
		$this->db->where('username', $username);
		$this->db->where('activated', 1);
		
		$query = $this->db->get();
		if ($query->num_rows())  {
			$users = $query->result();
			if (count($users) == 1) {
				$user = $users[0];
	
				if (crypt($password, $user->password) == $user->password) {
					// Create session data
			        $userdata = array('username' => $username,
			                          'user_id' => $user->user_id,
			                          'logged_in' => TRUE);
			        $this->session->set_userdata($userdata);
			        
					return true;
				}
			}
			return false;
		} else {
			return false;
		}
	}
	
	function get_userid_from_confirmation_code($confirmation_code) {
		// Create query
		$this->db->select('user_id');
		$this->db->from($this->getTableName());
		$this->db->where('activation_code', $confirmation_code);
		
		$query = $this->db->get();
		if ($query->num_rows())  {
			$users = $query->result();
			if (count($users) == 1) {
				$user = $users[0];
				
				return $user->user_id;
			}
			return false;
		} else {
			return false;
		}
	}
	
	/**
	 * Activate given user
	 * 
	 * @param string $user_id
	 */
	function activate_user($user_id) {
		$userdata['activated'] = 1;
		$userdata['activation_code'] = NULL;
		return $this->update('user_id', $user_id, $userdata);
	}
	

}

