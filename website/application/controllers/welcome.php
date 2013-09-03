<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$data['page_title'] = 'Bienvenue sur Six Challenge!';
		$data['page_icon'] = 'rocket';
		
		// Get replay path
		$game_id = 31453; // FIXME - Get a pretty replay, the latest with at least 4 players.
		$data['replay_file'] =  "replays/" . strval((int) ($game_id / 1000000)) . "/" . strval((int) (($game_id / 1000) % 1000)) . "/" . $game_id . ".replaygz";

		
		render($this, 'welcome_message', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */