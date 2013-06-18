<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$data['page_title'] = 'Bienvenue sur DTstrike!';
      
		$this->load->view('all_header', $data);
		$this->load->view('welcome_message');
		$this->load->view('all_footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */