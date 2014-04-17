<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
    function __construct() {
        parent::__construct();

        $this->load->model('Gamemodel');
    }

	public function index() {
		$data['page_title'] = 'Bienvenue sur cgi Challenge!';
		$data['page_icon'] = 'rocket';
		
		// Get replay path
		$game_id = $this->Gamemodel->get_last_good_game();
		$data['replay_file'] =  "replays/" . strval((int) ($game_id / 1000000)) . "/" . strval((int) (($game_id / 1000) % 1000)) . "/" . $game_id . ".replaygz";

		$data['error'] = $this->session->flashdata('error');
		$data['message'] = $this->session->flashdata('message');
		render($this, 'welcome_message', $data);
	}

	public function news() {
		$data['page_title'] = 'Les dernières news';
		$data['page_icon'] = 'exclamation';
		
		render($this, 'news', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */