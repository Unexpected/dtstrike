<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends CI_Controller {
    function __construct() {
        parent::__construct();

        $this->load->model('Gamemodel');
        $this->load->model('Game_playermodel');
        $this->load->model('Submissionmodel');
        $this->load->model('Usermodel');

        $this->load->library('table');
        $this->load->library('Bootstrap');
        $this->load->library('pagination');

        $this->load->helper('rank');
    }

	public function index() {
		redirect('game/liste');
	}

	public function liste($page=0, $user_id=NULL, $org_id=NULL, $country_id=NULL, $language_id=NULL)
	{
		// Recup des 20 dernières parties
		$limit = 20;
		$games = $this->Gamemodel->get_list($page, $limit);
		$data['list_type'] = 'game';
		$data['limit'] = $limit;
		$data['games'] = $games;
		
		if ($user_id != NULL) {
			$data['user'] =  $this->Usermodel->getUserData($user_id);
		}
		$data['page_title'] = "Les dernières parties";
		$data['page_icon'] = 'play-sign';
		render($this, 'game/game_list', $data);
	}

	public function rules()
	{
		$data['page_title'] = 'Les règles du concours';
		$data['page_icon'] = 'book';
		render($this, 'game/rules', $data);
	}

	public function start()
	{
		$data['page_title'] = 'Démarrage rapide';
		$data['page_icon'] = 'play-sign';
		render($this, 'game/start', $data);
	}

	public function kits()
	{
		$data['page_title'] = 'Les kits de démarrage';
		$data['page_icon'] = 'play-sign';
		render($this, 'game/kits', $data);
	}

	public function tuto()
	{
		$data['page_title'] = 'Tutoriels et Stratégies';
		$data['page_icon'] = 'play-sign';
		render($this, 'game/tuto', $data);
	}

	public function specs()
	{
		$data['page_title'] = 'Spécifications';
		$data['page_icon'] = 'play-sign';
		render($this, 'game/specs', $data);
	}

	public function view($game_id)
	{
		if (!isset($game_id)) {
			redirect('game');
		}

		// Get game data
		$user_id = current_user_id();
		if (verify_user_role($this, "admin", TRUE)) {
			$user_id = NULL;
		}
		$data['game'] = $this->Gamemodel->getOne('game_id', $game_id);
		$data['errors'] = $this->Game_playermodel->getGamePlayerLogs($game_id, $user_id);
		
		// Get replay path
		$game_id = intval($game_id);
		$data['replay_file'] =  "replays/" . strval((int) ($game_id / 1000000)) . "/" . strval((int) (($game_id / 1000) % 1000)) . "/" . $game_id . ".replaygz";
		
		$data['page_title'] = "Visualisation";
		$data['page_icon'] = 'play-circle';
		render($this, 'game/game_view', $data);
	}

	public function mine()
	{
		verify_user_logged($this, 'user');

		// Récupérer les matchs du joueur
		$user_id = current_user_id();
		$limit = 20;
		$games = $this->Gamemodel->get_list(0, $limit, $user_id);
		$data['list_type'] = 'user';
		$data['user_id'] = $user_id;
		$data['username'] = current_user_name();
		$data['limit'] = $limit;
		$data['games'] = $games;

		$data['page_title'] = "Mes dernières parties";
		$data['page_icon'] = 'briefcase';
		render($this, 'game/game_list', $data);
	}

	public function rank($page=0, $org_id=NULL, $country_id=NULL, $language_id=NULL)
	{
		//TODO : Gérer la pagination
		$page_string = '';
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
		$page_size = 20;
		if ($page === 0) {
			$limit = "limit ".$page_size;
		} else {
			$limit = "limit ".$page_size." offset ".($page_size * $page);
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
        $limit";

		$result_id = $this->Submissionmodel->db->query($query);
		if ($result_id === FALSE) {
			$data['rankings'] = array();
		} else {
			$data['rankings'] = $result_id->result_array();
		}

		//$data['page'] = $page;
		//$data['page_string'] = $page_string;
		$config['base_url'] = site_url('game/game_rank');
		$config['total_rows'] = 200;
		$config['per_page'] = $page_size;
		
		$this->pagination->initialize($config);
		
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
