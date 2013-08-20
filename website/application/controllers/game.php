<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('Gamemodel');
        $this->load->model('Submissionmodel');

        $this->load->library('table');
        $this->load->library('Bootstrap');

        $this->load->helper('rank');
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
		// FIXME : Get replay directory from submission_id
		$data['replay_file'] = '0/0/'.$game_id.'.replaygz';
		
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
		if ($page === 0) {
			$limit = "";
		} else {
			$limit = "limit ".$page_size." offset ".($page_size * ($page-1));
		}
		
		// Récupération du classement
		$query = get_rank_query($where, $limit);

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
