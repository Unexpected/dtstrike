<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tournament extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

	public function index()
	{
		verify_user_role($this, 'tournament');

		$data['page_title'] = "Les tournois";
		render($this, 'todo', $data);
	}

	public function create()
	{
		verify_user_logged($this, 'tournament/create');
		verify_user_role($this, 'tournament');

		$data['page_title'] = "Création d'un tournois";
		render($this, 'todo', $data);
	}

	public function join()
	{
		verify_user_logged($this, 'tournament/join');
		verify_user_role($this, 'tournament');

		$data['page_title'] = "Inscription à un tournoi";
		render($this, 'todo', $data);
	}

	public function mine()
	{
		verify_user_logged($this, 'tournament/mine');
		verify_user_role($this, 'tournament');

		$data['page_title'] = "Mes tournois";
		render($this, 'todo', $data);
	}
}
