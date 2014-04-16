<?php 
Class Submissionmodel extends Basemodel {

	var $submission_id = NULL; // int(11)
	var $user_id = NULL; // int(11)
	var $version = NULL; // int(11)
	var $status = NULL; // int(11)
	var $timestamp = NULL; // datetime
	var $comments = NULL; // varchar(4096)
	var $errors = NULL; // varchar(4096)
	var $language_id = NULL; // int(11)
	var $last_game_timestamp = NULL; // datetime
	var $latest = NULL; // tinyint(1)
	var $rank = NULL; // int(11)
	var $rank_change = NULL; // int(11)
	var $mu = NULL; // float
	var $mu_change = NULL; // float
	var $sigma = NULL; // float
	var $sigma_change = NULL; // float
	var $worker_id = NULL; // int(11)
	var $min_game_id = NULL; // int(11)
	var $max_game_id = NULL; // int(11)
	var $game_count = NULL; // int(11)

	function __construct() {
		// Call the BaseModel constructor
		parent::__construct();
	}

	function getTableName() {
		return 'submission';
	}
	
	/**
	 * Count number of line in ranking table
	 * 
	 * @param string $org_id
	 * @param string $country_id
	 * @param string $language_id
	 * 
	 * @return number
	 */
	function get_rank_count($org_id=NULL, $country_code=NULL, $language_id=NULL) {
		$where = '';
		if ($org_id !== NULL) {
			$where .= " and u.org_id = ".$org_id;
		}
		if ($country_code !== NULL) {
			$where .= " and u.country_code = ".$country_code;
		}
		if ($language_id !== NULL) {
			$where .= " and s.language_id = ".$language_id;
		}
		log_message('debug', "get_rank_count where=$where");
		
		$query = "select count(*) as cnt
	        from submission s
	        inner join user u
	            on u.user_id = s.user_id
	        where latest = 1 and status in (40, 100) and rank is not null
	        $where";

		$result_id = $this->db->query($query);
		if ($result_id === FALSE) {
			return 0;
		} else {
			$arr = $result_id->result_array();
			if ($arr !== NULL && isset($arr->attachments[0])) {
				return $arr[0]['cnt'];
			} else {
				return 0;
			}
		}
	}
	
	/**
	 * Get Ranking list
	 * 
	 * @param number $page
	 * @param number $limit
	 * @param string $org_id
	 * @param string $country_id
	 * @param string $language_id
	 * 
	 * @return array of db rows
	 */
	function get_rank_list($page=1, $limit=20, $org_id=NULL, $country_code=NULL, $language_id=NULL) {
		$offset = (($page - 1) * $limit);

		$where = '';
		if ($org_id !== NULL) {
			$filtered = True;
			$where .= " and u.org_id = ".$org_id;
		}
		if ($country_code !== NULL) {
			$where .= " and u.country_code = '".$country_code."'";
		}
		if ($language_id !== NULL) {
			$where .= " and s.language_id = ".$language_id;
		}
		log_message('debug', "get_rank_list where=$where");
		
		
		// Récupération du classement
		$query = "select u.user_id, u.username,
            c.name as country, c.country_code, c.flag_filename,
            l.language_id, l.name as programming_language,
            o.org_id, o.name as org_name,
            s.submission_id, s.version,
            s.rank, s.rank_change,
            s.mu, s.mu_change,
            s.sigma, s.sigma_change,
            s.mu - s.sigma * 3 as skill,
            s.mu_change - s.sigma_change * 3 as skill_change,
            s.latest,
            s.timestamp,
            s.game_count,
            (   select count(distinct game_id) as game_count
                from opponents o
                where user_id = u.user_id
            ) as game_rate
        from submission s
        inner join user u
            on s.user_id = u.user_id
        left outer join organization o
            on u.org_id = o.org_id
        left outer join language l
            on l.language_id = s.language_id
        left outer join country c
            on u.country_code = c.country_code
        where s.latest = 1 and status in (40, 100) and rank is not null
		$where
        order by rank
        limit $limit offset $offset";

		$result_id = $this->db->query($query);
		if ($result_id === FALSE) {
			return array();
		} else {
			return $result_id->result_array();
		}
	}

}

