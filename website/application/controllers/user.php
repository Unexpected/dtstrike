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
		$this->load->view('all_header', $data);
		$this->load->view('user/index');
		$this->load->view('all_footer');
	}

	public function register()
	{
		$data['page_title'] = "Enregistrement sur DTstrike";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}

	public function bots()
	{
		verify_user_logged($this, 'user/bots');
		
		$data['page_title'] = "Mes bots";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}
}
