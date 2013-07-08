<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('Gamemodel');
        $this->load->model('Submissionmodel');

        $this->load->library('table');
        $this->load->library('Bootstrap');
    }

	public function index()
	{
		// Recup des 20 dernières parties
		$limit = 20;
		$games = $this->Gamemodel->getAll($limit);
		$data['limit'] = $limit;
		$data['games'] = $games;
		
		$data['page_title'] = 'Les dernières parties';
		$data['page_icon'] = 'play-sign';
		render($this, 'game/game_list', $data);
	}

	public function view($game_id)
	{
		$data['replay_file'] = 'replays/replay_'.$game_id.'.js';
		
		$data['page_title'] = "Visualisation";
		$data['page_icon'] = 'play-circle';
		render($this, 'game/game_view', $data);
	}

	public function mine()
	{
		verify_user_logged($this, 'user');

		$data['page_title'] = "Mes parties";
		$data['page_icon'] = 'briefcase';
		render($this, 'todo', $data);
	}

	public function rank($page=0, $org_id=NULL, $country_id=NULL, $language_id=NULL)
	{
		//TODO : Gérer la pagination
    	$filtered = False;
		$where = '';
		if ($org_id !== NULL) {
			$filtered = True;
			$where .= " and u.org_id = ".$org_id;
			$page_string .= '&org='.$org_id;
		}
		if ($country_id !== NULL) {
			$filtered = True;
			$where .= " and u.country_id = ".$country_id;
			$page_string .= '&country='.$country_id;
		}
		if ($language_id !== NULL) {
			$filtered = True;
			$where .= " and s.language_id = ".$language_id;
			$page_string .= '&language='.$language_id;
		}
		$page_string .= "&page=";
		$page_string[0] = "?";
		if ($page === 0) {
			$limit = "";
		} else {
			$limit = "limit ".$page_size." offset ".($page_size * ($page-1));
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
		$limit
        order by rank
        limit 200";

		$result_id = $this->Submissionmodel->db->simple_query($query);
		
		if ($result_id === FALSE) {
			$data['rankings'] = array();
		} else {
			$data['rankings'] = mysql_fetch_assoc($result_id);
		}

		$data['page'] = $page;
		$data['page_string'] = $page_string;
		
		$data['page_title'] = "Classement actuel";
		$data['page_icon'] = 'trophy';
		render($this, 'game/game_rank', $data);
	}

	public function maps()
	{
		$data['page_title'] = "Les cartes officielles";
		$data['page_icon'] = 'sitemap';
		render($this, 'todo', $data);
	}

}
