<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('Gamemodel');
        $this->load->library('table');
    }

	public function index()
	{
		$data['page_title'] = 'Les dernières parties';
		$data['page_icon'] = 'play-sign';
		
		// Recup des 20 dernières parties
		$limit = 20;
		$games = $this->Gamemodel->getAll($limit);
		$data['limit'] = $limit;
		$data['games'] = $games;

		render($this, 'game_list', $data);
	}

	public function view()
	{
		$data['page_title'] = "Visualisation";
		$data['page_icon'] = 'play-circle';
		render($this, 'todo', $data);
	}

	public function mine()
	{
		verify_user_logged($this, 'user');

		$data['page_title'] = "Mes parties";
		$data['page_icon'] = 'briefcase';
		render($this, 'todo', $data);
	}

	public function rank()
	{
		$data['page_title'] = "Classement actuel";
		$data['page_icon'] = 'trophy';
		render($this, 'todo', $data);
	}

	public function maps()
	{
		$data['page_title'] = "Les cartes officielles";
		$data['page_icon'] = 'sitemap';
		render($this, 'todo', $data);
	}

}
