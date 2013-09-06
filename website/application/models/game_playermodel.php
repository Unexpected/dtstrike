<?php 
Class Game_playermodel extends Basemodel {

	var $game_id = -1; // int(11)
	var $user_id = -1; // int(11)
	var $submission_id = -1; // int(11)
	var $player_id = -1; // int(11)
	var $errors = ''; // varchar(1024)
	var $status = ''; // varchar(255)
	var $game_rank = -1; // int(11)
	var $game_score = -1; // int(11)
	var $rank_before = -1; // int(11)
	var $rank_after = -1; // int(11)
	var $mu_before = ''; // float
	var $mu_after = ''; // float
	var $sigma_before = ''; // float
	var $sigma_after = ''; // float
	var $valid = ''; // tinyint(1)

	function __construct() {
		// Call the BaseModel constructor
		parent::__construct();
	}

	function getTableName() {
		return 'game_player';
	}
	
	function getGamePlayerLogs($game_id, $user_id=NULL) {
		$query = "select gp.user_id, gp.errors, gp.status, u.username
        from game_player gp
        inner join user u on u.user_id = gp.user_id
        where gp.game_id = $game_id";
		if ($user_id !== NULL) {
			$query .= " and gp.user_id = $user_id
		        and (gp.status = 'timeout'
		            or gp.status = 'crashed'
		            or gp.status = 'invalid')";
		}

		$req = $this->db->query($query);
		$results = $req->result_array();
// 		$errors = array();
		if ($results) {
			return $results;
// 			for ($i=0; $i<count($results); $i++) {
// 				$row = $results[$i];
				
// 	            $error_msg = "<ul>";
// 	            $username = $row["username"];
// 	            $status = $row["status"];
// 	            $error_msg .= "<li><p>$username - $status</p><pre class=\"error\">";
// 	            $error_msg .= str_replace('\n', "\n", $row["errors"])."\n";
// 	            $error_msg .= "</pre></li>";
// 	            $error_msg .= "</ul>";
	            
// 	            $errors[$i] = $error_msg;
// 			}
		}
		
		return array();
	}

}

