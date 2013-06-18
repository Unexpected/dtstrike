<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model('Gamemodel');
    }

	public function index()
	{
		$data['page_title'] = 'Les dernières parties';
		
		// Recup des 20 dernières parties
		$games = $this->Gamemodel->getAll(20);
        log_message('debug', $games);
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}

	public function view()
	{
		$data['page_title'] = "Visualisation";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}

	public function mine()
	{
		verify_user_logged($this, 'user');
		
		$data['page_title'] = "Mes parties";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}
}
