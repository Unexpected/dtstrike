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

	public function liste($page=0, $user_id=NULL, $submission_id=NULL)
	{
		// Recup des 20 dernières parties
		$page_size = 20;
		$games = $this->Gamemodel->get_game_list($page, $page_size, $user_id, $submission_id);
		$data['list_type'] = 'game';
		$data['limit'] = $page_size;
		$data['games'] = $games;

		$row_count = $this->Gamemodel->get_game_count($user_id, $submission_id);
		
		$config['base_url'] = site_url('game/liste');
		$config['total_rows'] = ceil($row_count / $page_size);
		$config['per_page'] = $page_size;
		if ($user_id != NULL) {
			$config['suffix'] = "/$user_id";
		}
		
		$this->pagination->initialize($config);
		
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
		// Récupération des données
		$page_size = 20;
		$data['rankings'] = $this->Submissionmodel->get_rank_list($page, $page_size, $org_id, $country_id, $language_id);
		
		$row_count = $this->Submissionmodel->get_rank_count($org_id, $country_id, $language_id);

		$config['base_url'] = site_url('game/game_rank');
		$config['total_rows'] = ceil($row_count / $page_size);
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
