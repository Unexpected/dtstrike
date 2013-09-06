<?php 
Class Submissionmodel extends Basemodel {

	var $submission_id = -1; // int(11)
	var $user_id = -1; // int(11)
	var $version = -1; // int(11)
	var $status = -1; // int(11)
	var $timestamp = ''; // datetime
	var $comments = ''; // varchar(4096)
	var $errors = ''; // varchar(4096)
	var $language_id = -1; // int(11)
	var $last_game_timestamp = ''; // datetime
	var $latest = ''; // tinyint(1)
	var $rank = -1; // int(11)
	var $rank_change = -1; // int(11)
	var $mu = ''; // float
	var $mu_change = ''; // float
	var $sigma = ''; // float
	var $sigma_change = ''; // float
	var $worker_id = -1; // int(11)
	var $min_game_id = -1; // int(11)
	var $max_game_id = -1; // int(11)
	var $game_count = -1; // int(11)

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
	function get_rank_count($org_id=NULL, $country_id=NULL, $language_id=NULL) {
		$where = '';
		if ($org_id !== NULL) {
			$where .= " and u.org_id = ".$org_id;
		}
		if ($country_id !== NULL) {
			$where .= " and u.country_id = ".$country_id;
		}
		if ($language_id !== NULL) {
			$where .= " and s.language_id = ".$language_id;
		}
		
		$query = "select count(*) as cnt
	        from submission s
	        inner join user u
	            on u.user_id = s.user_id
	        where latest = 1
	        $where";

		$result_id = $this->db->query($query);
		if ($result_id === FALSE) {
			return 0;
		} else {
			$arr = $result_id->result_array();
			return $arr[0]['cnt'];
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
	function get_rank_list($page=0, $limit=20, $org_id=NULL, $country_id=NULL, $language_id=NULL) {
		$offset = 0;
		if ($page > 0)
			$offset = (($page - 1) * $limit);

		$where = '';
		if ($org_id !== NULL) {
			$filtered = True;
			$where .= " and u.org_id = ".$org_id;
		}
		if ($country_id !== NULL) {
			$where .= " and u.country_id = ".$country_id;
		}
		if ($language_id !== NULL) {
			$where .= " and s.language_id = ".$language_id;
		}
		
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

