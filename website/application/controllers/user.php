<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->library('AuthLDAP');
    }

	public function index()
	{
		verify_user_logged($this, 'user');

		$data['page_title'] = 'Mon compte';
		$data['page_icon'] = 'cogs';
		render($this, 'user/index', $data);
	}

	public function register()
	{
		$data['page_title'] = "Enregistrement sur DTstrike";
		$data['page_icon'] = 'user';
		render($this, 'todo', $data);
	}

	public function bots()
	{
		verify_user_logged($this, 'user/bots');

		$data['page_title'] = "Mes bots";
		$data['page_icon'] = 'fighter-jet';
		render($this, 'todo', $data);
	}
}
