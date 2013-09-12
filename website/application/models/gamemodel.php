<?php 
Class Gamemodel extends Basemodel {

	var $game_id = NULL; // int(11)
	var $seed_id = NULL; // int(11)
	var $map_id = NULL; // int(11)
	var $turns = NULL; // int(11)
	var $game_length = NULL; // int(11)
	var $cutoff = NULL; // varchar(255)
	var $winning_turn = NULL; // int(11)
	var $ranking_turn = NULL; // int(11)
	var $timestamp = NULL; // datetime
	var $worker_id = NULL; // int(11)
	var $replay_path = NULL; // varchar(255)

	function __construct() {
		// Call the BaseModel constructor
		parent::__construct();
	}

	function getTableName() {
		return 'game';
	}

	/**
	 * Get game count
	 * 
	 * @param string $user_id
	 * @param string $submission_id
	 * 
	 * @return count
	 */
	function get_game_count($user_id=NULL, $submission_id=NULL) {
		$list_select_field = 1;
		$list_id = 1;

		if ($user_id !== NULL) {
			$list_select_field = 'user_id';
			$list_id = $user_id;
		} else if ($submission_id !== NULL) {
			$list_select_field = 'submission_id';
			$list_id = $submission_id;
		} else {
			// Default
			$list_select_field = 1;
			$list_id = 1;
		}
		
		$query = "select count(*) as cnt
            from game g
            inner join game_player gp
                on g.game_id = gp.game_id
            where $list_select_field = $list_id";
		
		$result_id = $this->db->query($query);
		if ($result_id === FALSE) {
			return 0;
		} else {
			$arr = $result_id->result_array();
			return $arr[0]['cnt'];
		}
	}

	/**
	 * Get Game list
	 *
	 * @param number $page
	 * @param number $limit
	 * @param string $user_id
	 * @param string $submission_id
	 *
	 * @return array of db rows
	 */
	function get_game_list($page=1, $limit=20, $user_id=NULL, $submission_id=NULL) {
		$offset = (($page - 1) * $limit);
		$list_select_field = 1;
		$list_id = 1;

		if ($user_id !== NULL) {
			$list_select_field = 'user_id';
			$list_id = $user_id;
			$inner_join = 'inner join game_player gp2 on g2.game_id = gp2.game_id';
		} else if ($submission_id !== NULL) {
			$list_select_field = 'submission_id';
			$list_id = $submission_id;
			$inner_join = 'inner join game_player gp2 on g2.game_id = gp2.game_id';
		} else {
			// Default
			$inner_join = '';
			$list_select_field = 1;
			$list_id = 1;
		}
		 
		$query = "select g.game_id, g.timestamp,
		      m.players, m.map_id, m.filename as map_name,
		      g.game_length, g.winning_turn, g.ranking_turn, g.cutoff,
		      gp.user_id, gp.submission_id, u.username, s.version,
		      gp.player_id, gp.game_rank, gp.status,
		      gp.rank_before, gp.rank_after,
		      gp.mu_after - 3 * gp.sigma_after as skill,
		      gp.mu_after as mu, gp.sigma_after as sigma,
		      (gp.mu_after - 3 * gp.sigma_after) - (gp.mu_before - 3 * gp.sigma_before) as skill_change,
		      gp.mu_after - gp.mu_before as mu_change, gp.sigma_after - gp.sigma_before as sigma_change
		from (
		    select g2.*
		    from game g2
		    $inner_join
			where $list_select_field = $list_id
		    order by g2.game_id desc
		    limit $limit offset $offset
		) g
		inner join map m
		    on m.map_id = g.map_id
		inner join game_player gp
		    on g.game_id = gp.game_id
		inner join user u
		    on gp.user_id = u.user_id
		inner join submission s
		   on gp.submission_id = s.submission_id
		order by g.game_id desc, gp.game_rank";

		$user_fields = array("user_id", "submission_id", "username", "version",
				"player_id", "game_rank", "status", "skill", "mu", "sigma",
				"skill_change", "mu_change", "sigma_change", "rank_before");
		 
		$req = $this->db->query($query);
		$results = $req->result_array();
		$rows = array();
		if ($results) {
			// loop through results, turning multiple rows for the same game into arrays
			$last_game_id = -1;
			$cur_row = NULL;
			for ($i=0; $i<count($results); $i++) {
				$row = $results[$i];
				// get list type name
				//if ($list_type && !$list_name && $row[$list_select_field] == $list_id) {
				//	$list_name = $row[$list_id_field];
				//}
				if ($last_game_id !== $row['game_id']) {
					if ($cur_row !== NULL) {
						$rows[] = $cur_row;
					}
					$cur_row = $row;
					foreach ($user_fields as $user_field) {
						$cur_row[$user_field] = array($cur_row[$user_field]);
					}
				} else {
					foreach ($user_fields as $user_field) {
						$cur_row[$user_field][] = $row[$user_field];
					}
				}
				$last_game_id = $row['game_id'];
			}
			if ($cur_row !== NULL) {
				$rows[] = $cur_row;
			}
		}

		return $rows;
	}
	
	/**
	 * Get last game with length > 10.
	 * 
	 * @return Game ID
	 */
	function get_last_good_game() {
		$this->db->select('game_id');
		$this->db->from($this->getTableName());
		$this->db->where('game_length >', 50);
		$this->db->order_by("game_id", "desc");
		$this->db->limit(1);
		
		$query = $this->db->get();
		if ($query->num_rows())  {
			$rows = $query->result();
		} else {
			$rows = array();
		}
		
		if (count($rows) < 1) {
			return 1;
		}
		return $rows[0]->game_id;
	}

}

