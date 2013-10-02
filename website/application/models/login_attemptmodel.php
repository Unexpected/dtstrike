<?php 
Class Login_attemptmodel extends Basemodel {

	var $timestamp = NULL; // datetime
	var $username = NULL; // varchar(128)
	var $naive_ip = NULL; // varchar(18)
	var $real_ip = NULL; // varchar(18)

	function __construct() {
		// Call the BaseModel constructor
		parent::__construct();
	}

	function getTableName() {
		return 'login_attempt';
	}
	
	function logAttempt($username, $naive_ip, $real_ip) {
		$this->timestamp = new DateTime(null);
		$this->username = $username;
		$this->naive_ip = $naive_ip;
		$this->real_ip = $real_ip;
		
		$this->insert();
	}

}

