<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class League extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

	public function index()
	{
		$data['page_title'] = "Les ligues";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}

	public function create()
	{
		$data['page_title'] = "Création d'une ligue";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}

	public function mine()
	{
		$data['page_title'] = "Mes ligues";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}
}
