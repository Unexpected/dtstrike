<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tournament extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

	public function index()
	{
		$data['page_title'] = "Les tournois";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}

	public function create()
	{
		$data['page_title'] = "Cr�ation d'un tournois";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}

	public function join()
	{
		$data['page_title'] = "Inscription � un tournoi";
      
		$this->load->view('all_header', $data);
		$this->load->view('todo');
		$this->load->view('all_footer');
	}
}
