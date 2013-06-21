<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class League extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

	public function index()
	{
		verify_user_role($this, 'league');

		$data['page_title'] = "Les ligues";
		render($this, 'todo', $data);
	}

	public function create()
	{
		verify_user_logged($this, 'league/create');
		verify_user_role($this, 'league');

		$data['page_title'] = "Création d'une ligue";
		render($this, 'todo', $data);
	}

	public function mine()
	{
		verify_user_logged($this, 'league/mine');
		verify_user_role($this, 'league');

		$data['page_title'] = "Mes ligues";
		render($this, 'todo', $data);
	}
}
