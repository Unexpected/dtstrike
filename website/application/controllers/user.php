<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    function __construct() {
        parent::__construct();

        $this->load->model('Usermodel');
        $this->load->library('AuthLDAP');
    }

	public function index()
	{
		verify_user_logged($this, 'user');

		$data['page_title'] = 'Mon compte';
		$data['page_icon'] = 'cogs';
		render($this, 'user/index', $data);
	}

	public function bots()
	{
		verify_user_logged($this, 'user/bots');

		$data['page_title'] = "Mes bots";
		$data['page_icon'] = 'fighter-jet';
		render($this, 'todo', $data);
	}

	public function view($user_id)
	{

		$this->Usermodel->db->select('username, email, organization.name as "org_name", country.name as "country_name", created', false);
		$this->Usermodel->db->from('user');
		$this->Usermodel->db->join('organization', 'organization.org_id = user.org_id');
		$this->Usermodel->db->join('country', 'country.country_code = user.country_code');
		$this->Usermodel->db->order_by("username", "asc"); 
		$this->Usermodel->db->order_by('user_id', $user_id); 

		$query = $this->Usermodel->db->get();
		if ($query->num_rows())  {
			$users = $query->result();
		} else {
			$users = array();
		}
		if (count($users) < 1) {
			show_error("Utilisateur avec l'ID $user_id non disponible.");
		}
		$data['user'] = $users[0];
		
		$data['page_title'] = "Fiche du joueur";
		$data['page_icon'] = 'user';
		render($this, 'user/view', $data);
	}
}
